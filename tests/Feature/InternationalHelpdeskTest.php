<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\User;
use App\Services\SlaService;
use App\Support\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * International helpdesk: lifecycle enforcement, assignment safety,
 * customer reopen, organisation isolation, SLA pause, idempotent creates,
 * email ingress, and cent-exact money math.
 */
class InternationalHelpdeskTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    private User $colleague;

    private User $outsider;

    private User $agent;

    private User $manager;

    private TicketCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        $coA = \App\Models\Company::create(['name' => 'Intl Co A', 'slug' => 'intl-co-a']);
        $coB = \App\Models\Company::create(['name' => 'Intl Co B', 'slug' => 'intl-co-b']);
        $mk = fn (string $role, string $email, ?int $company) => User::create([
            'name' => $role.' user', 'email' => $email, 'password' => bcrypt('password'),
            'role' => $role, 'is_active' => true, 'company_id' => $company,
            'email_verified_at' => now(),
        ]);
        $this->customer = $mk('customer', 'cust-intl@test.com', $coA->id);
        $this->colleague = $mk('customer', 'colleague-intl@test.com', $coA->id);
        $this->outsider = $mk('customer', 'outsider-intl@test.com', $coB->id);
        $this->agent = $mk('support_agent', 'agent-intl@test.com', null);
        $this->manager = $mk('support_manager', 'mgr-intl@test.com', null);
        $this->category = TicketCategory::create(['name' => 'Intl', 'slug' => 'intl']);
    }

    private function ticket(User $owner, array $over = []): Ticket
    {
        return Ticket::create(array_merge([
            'ticket_number' => 'TK-'.strtoupper(uniqid()),
            'customer_id' => $owner->id,
            'company_id' => $owner->company_id,
            'subject' => 'Intl probe', 'description' => 'Body.',
            'category_id' => $this->category->id,
            'priority' => 'medium', 'status' => 'new',
        ], $over));
    }

    /** @test */
    public function lifecycle_transitions_are_server_enforced()
    {
        $t = $this->ticket($this->customer);

        // Illegal jump new -> resolved is rejected, state unchanged.
        $this->actingAs($this->manager)->post("/admin/tickets/{$t->id}/status", ['status' => 'resolved'])->assertStatus(422);
        $this->assertSame('new', $t->fresh()->status);

        // Legal path works end to end.
        foreach (['open', 'in_progress', 'resolved', 'closed'] as $s) {
            $this->actingAs($this->manager)->post("/admin/tickets/{$t->id}/status", ['status' => $s])->assertSessionHas('success');
        }
        $this->assertSame('closed', $t->fresh()->status);

        // Terminal states accept nothing further.
        $this->actingAs($this->manager)->post("/admin/tickets/{$t->id}/status", ['status' => 'open'])->assertStatus(422);
    }

    /** @test */
    public function assignee_must_be_active_staff()
    {
        $t = $this->ticket($this->customer);
        $this->actingAs($this->manager)->post("/admin/tickets/{$t->id}/assign", ['assigned_to' => $this->customer->id])
            ->assertStatus(422);
        $this->actingAs($this->manager)->post("/admin/tickets/{$t->id}/assign", ['assigned_to' => $this->agent->id])
            ->assertSessionHas('success');
        $this->assertSame('assigned', $t->fresh()->status);
    }

    /** @test */
    public function admin_show_renders_for_authorized_agent()
    {
        $t = $this->ticket($this->customer, ['assigned_to' => $this->agent->id]);
        $this->actingAs($this->agent)->get("/admin/tickets/{$t->id}")->assertStatus(200);
    }

    /** @test */
    public function merge_refuses_cross_customer_tickets()
    {
        $a = $this->ticket($this->customer);
        $b = $this->ticket($this->outsider);
        $this->actingAs($this->manager)->post("/admin/tickets/{$a->id}/merge", ['merge_ticket_id' => $b->id])
            ->assertStatus(422);
        $this->assertNotNull($b->fresh());
    }

    /** @test */
    public function customer_can_reopen_within_policy_window()
    {
        $t = $this->ticket($this->customer, ['status' => 'resolved', 'resolved_at' => now()->subDay()]);
        $this->actingAs($this->customer)->post(route('portal.tickets.reopen', $t->id), ['reason' => 'Still broken here.'])
            ->assertRedirect();
        $this->assertSame('reopened', $t->fresh()->status);

        // Another customer's resolved ticket is invisible (404, not 403).
        $other = $this->ticket($this->outsider, ['status' => 'resolved', 'resolved_at' => now()->subDay()]);
        $this->actingAs($this->customer)->post(route('portal.tickets.reopen', $other->id), ['reason' => 'x'])->assertStatus(404);

        // Outside the 7-day window: refused.
        $old = $this->ticket($this->customer, ['status' => 'resolved', 'resolved_at' => now()->subDays(9)]);
        $this->actingAs($this->customer)->post(route('portal.tickets.reopen', $old->id), ['reason' => 'Late.'])->assertStatus(422);
    }

    /** @test */
    public function organisation_tickets_are_company_isolated()
    {
        $t = $this->ticket($this->customer);

        $this->actingAs($this->colleague)->get(route('portal.tickets.show', $t->id))->assertStatus(200);
        $this->actingAs($this->outsider)->get(route('portal.tickets.show', $t->id))->assertStatus(404);

        $this->actingAs($this->colleague)->get(route('portal.tickets.organisation'))->assertStatus(200)->assertSee($t->ticket_number);
        $this->actingAs($this->outsider)->get(route('portal.tickets.organisation'))->assertDontSee($t->ticket_number);

        // Colleague can reply; outsider cannot.
        $this->actingAs($this->colleague)->post(route('portal.tickets.reply', $t->id), ['message' => 'Same issue on my machine.'])->assertRedirect();
        $this->actingAs($this->outsider)->post(route('portal.tickets.reply', $t->id), ['message' => 'Snoop.'])->assertStatus(404);
    }

    /** @test */
    public function sla_clock_pauses_while_waiting_and_scheduler_skips_paused()
    {
        $t = $this->ticket($this->customer);
        $t->update([
            'status' => 'in_progress',
            'sla_response_deadline' => now()->addHours(4),
            'sla_resolution_deadline' => $resolution = now()->addHours(8),
        ]);

        $this->actingAs($this->manager)->post("/admin/tickets/{$t->id}/status", ['status' => 'waiting_customer'])->assertSessionHas('success');
        $this->assertNotNull($t->fresh()->sla_paused_at);
        $this->assertSame('paused', app(SlaService::class)->getSlaStatus($t->fresh()));

        // Simulate 90 waited minutes, then resume work.
        $t->fresh()->update(['sla_paused_at' => now()->subMinutes(90)]);
        $this->actingAs($this->manager)->post("/admin/tickets/{$t->id}/status", ['status' => 'in_progress'])->assertSessionHas('success');
        $resumed = $t->fresh();
        $this->assertNull($resumed->sla_paused_at);
        // Deadlines shifted forward by ~90 waited minutes.
        $this->assertTrue($resumed->sla_resolution_deadline->gte($resolution->copy()->addMinutes(85)));

        // A paused, past-deadline ticket is never breached by the scheduler.
        $t->update(['status' => 'waiting_customer', 'sla_paused_at' => now(), 'sla_resolution_deadline' => now()->subHour()]);
        $counts = app(SlaService::class)->processDeadlines();
        $this->assertSame(0, $counts['breachCount']);
        $this->assertSame('waiting_customer', $t->fresh()->status);
    }

    /** @test */
    public function customer_sees_sla_status_on_ticket_page()
    {
        $t = $this->ticket($this->customer, ['sla_resolution_deadline' => now()->addDay()]);
        $this->actingAs($this->customer)->get(route('portal.tickets.show', $t->id))
            ->assertStatus(200)->assertSee('SLA:');
    }

    /** @test */
    public function quote_submission_is_idempotent_with_stable_reference()
    {
        $payload = ['name' => 'Intl Buyer', 'email' => 'buyer-intl@test.com', 'message' => 'Need managed IT support across three offices, please quote.'];
        $this->post(route('get-quote.submit'), $payload)->assertRedirect();
        $this->post(route('get-quote.submit'), $payload)->assertRedirect()->assertSessionHas('success');
        $this->assertStringContainsString('already received', (string) session('success'));

        $this->assertSame(1, \App\Models\ServiceRequest::where('email', 'buyer-intl@test.com')->count());
        $this->assertSame(1, \App\Models\Lead::where('email', 'buyer-intl@test.com')->count());
        $ref = \App\Models\ServiceRequest::where('email', 'buyer-intl@test.com')->first()->request_number;
        $this->assertNotEmpty($ref);
    }

    /** @test */
    public function ticket_creation_is_idempotent()
    {
        $payload = ['subject' => 'Double click probe', 'description' => 'Submitted twice by accident.', 'category_id' => $this->category->id, 'priority' => 'medium'];
        $this->actingAs($this->customer)->post(route('portal.tickets.store'), $payload)->assertRedirect();
        $this->actingAs($this->customer)->post(route('portal.tickets.store'), $payload)->assertRedirect();
        $this->assertSame(1, Ticket::where('customer_id', $this->customer->id)->where('subject', 'Double click probe')->count());
        $this->assertStringContainsString('already submitted', (string) session('success'));
    }

    private function ingressPayload(array $over = []): array
    {
        return array_merge([
            'message_id' => '<msg-'.uniqid().'@mail.test>',
            'from' => 'cust-intl@test.com',
            'subject' => 'Printer offline on floor 3',
            'text_body' => 'The office printer stopped responding this morning.',
        ], $over);
    }

    private function ingressPost(array $payload, string $secret = 'testsecret')
    {
        $raw = json_encode($payload);
        $ts = (string) time();
        $sig = hash_hmac('sha256', $ts.'.'.$raw, $secret);

        return $this->call('POST', '/api/ingress/email', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_INGRESS_TIMESTAMP' => $ts,
            'HTTP_X_INGRESS_SIGNATURE' => $sig,
        ], $raw);
    }

    /** @test */
    public function email_ingress_is_disabled_without_config_and_auth_guarded()
    {
        config(['inbound_email.enabled' => false]);
        $this->ingressPost($this->ingressPayload())->assertStatus(503);

        config(['inbound_email.enabled' => true, 'inbound_email.webhook_secret' => 'testsecret']);
        $this->ingressPost($this->ingressPayload(), 'wrong')->assertStatus(401);
    }

    /** @test */
    public function email_ingress_creates_and_threads_tickets_idempotently()
    {
        config(['inbound_email.enabled' => true, 'inbound_email.webhook_secret' => 'testsecret']);

        // Unknown sender: acknowledged, never ticketed.
        $this->ingressPost($this->ingressPayload(['message_id' => '<u1@mail.test>', 'from' => 'stranger@test.com']))
            ->assertStatus(202)->assertJsonPath('result', 'unknown_sender');
        $this->assertSame(0, Ticket::count());

        // New ticket for a known customer.
        $r = $this->ingressPost($this->ingressPayload(['message_id' => '<n1@mail.test>']))->assertStatus(202);
        $number = $r->json('ticket_number');
        $this->assertNotEmpty($number);
        $this->assertSame(1, Ticket::where('ticket_number', $number)->count());

        // Provider retry: same message_id is a duplicate, not a second ticket.
        $this->ingressPost($this->ingressPayload(['message_id' => '<n1@mail.test>']))
            ->assertStatus(202)->assertJsonPath('result', 'duplicate');
        $this->assertSame(1, Ticket::count());

        // Reply threading via [TK-...] with ownership check.
        $this->ingressPost($this->ingressPayload([
            'message_id' => '<r1@mail.test>',
            'subject' => "Re: [{$number}] Printer offline",
            'text_body' => 'Additional info: error light blinking.',
        ]))->assertStatus(202)->assertJsonPath('result', 'reply_added');
        $this->assertSame(2, TicketMessage::where('ticket_id', Ticket::where('ticket_number', $number)->first()->id)->count());

        // Third party quoting the number is refused (no leak, no write).
        $this->ingressPost($this->ingressPayload([
            'message_id' => '<r2@mail.test>', 'from' => 'outsider-intl@test.com',
            'subject' => "Re: [{$number}]", 'text_body' => 'Let me in.',
        ]))->assertStatus(202)->assertJsonPath('result', 'sender_mismatch');
    }

    /** @test */
    public function money_math_is_cent_exact()
    {
        $this->assertSame(12345, Money::toMinor('123.45'));
        $this->assertSame(12345, Money::toMinor(123.45));
        $this->assertSame('123.45', Money::toMajor(12345));
        // Classic float trap: 0.1 + 0.2 must be exactly 0.30.
        $this->assertSame(30, Money::add(Money::toMinor(0.1), Money::toMinor(0.2)));
        $this->assertSame('0.30', Money::toMajor(Money::add(Money::toMinor(0.1), Money::toMinor(0.2))));
        $this->assertSame('749.70', Money::toMajor(Money::sub(Money::toMinor('750.00'), Money::toMinor('0.30'))));
    }
}
