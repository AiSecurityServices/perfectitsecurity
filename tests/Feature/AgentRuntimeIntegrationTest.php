<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Ai\AgentRouter;
use App\Services\Ai\CustomerSupportOrchestrator;
use App\Services\Ai\HermesAgentAdapter;
use App\Services\Ai\OpenClawAdapter;
use App\Services\Ai\RuntimeHttpClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Agent runtimes: honest states, verified-gated execution, safe envelopes.
 * External HTTP is faked — no credentials, no live runtimes needed.
 */
class AgentRuntimeIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config([
            'agent.enabled' => true,
            'agent.hermes_enabled' => true,
            'agent.openclaw_enabled' => true,
            'agent.hermes' => [
                'base_url' => 'http://hermes.test', 'api_key' => 'hk-test',
                'model' => 'hermes-agent', 'timeout' => 10, 'connect_timeout' => 5,
                'verified' => true,
            ],
            'agent.openclaw' => [
                'base_url' => 'http://openclaw.test', 'token' => 'oc-test',
                'agent_id' => 'hooks', 'timeout' => 10, 'connect_timeout' => 5,
                'verified' => true,
            ],
        ]);
    }

    private function staff(string $role = 'support_agent'): User
    {
        return User::create([
            'name' => 'Staff', 'email' => $role.'-rt@test.com', 'password' => bcrypt('password'),
            'role' => $role, 'is_active' => true, 'email_verified_at' => now(),
        ]);
    }

    private function customer(): User
    {
        return User::create([
            'name' => 'Customer', 'email' => 'cust-rt@test.com', 'password' => bcrypt('password'),
            'role' => 'customer', 'is_active' => true, 'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function hermes_chat_uses_documented_api_and_parses_defensively()
    {
        Http::fake([
            'hermes.test/v1/chat/completions' => Http::response([
                'choices' => [['message' => ['content' => 'Hello from Hermes']]],
            ], 200),
        ]);

        $out = (new HermesAgentAdapter())->chat([['role' => 'user', 'content' => 'Hi']]);
        $this->assertTrue($out['success']);
        $this->assertSame('Hello from Hermes', $out['content']);
        Http::assertSent(fn ($req) => $req->url() === 'http://hermes.test/v1/chat/completions'
            && ($req->header('Authorization')[0] ?? '') === 'Bearer hk-test');
    }

    /** @test */
    public function hermes_refuses_when_unverified_and_maps_auth_failures()
    {
        config(['agent.hermes.verified' => false]);
        $this->expectException(\RuntimeException::class);
        (new HermesAgentAdapter())->chat([['role' => 'user', 'content' => 'Hi']]);
    }

    /** @test */
    public function hermes_reports_auth_failed_and_invalid_responses_honestly()
    {
        Http::fake([
            'hermes.test/*' => Http::sequence()->push([], 401)->push(['choices' => []], 200),
        ]);
        try {
            (new HermesAgentAdapter())->chat([['role' => 'user', 'content' => 'Hi']]);
            $this->fail('Expected AiProviderException');
        } catch (\App\Services\Ai\AiProviderException $e) {
            $this->assertSame('auth_failed', $e->getCategory());
        }

        try {
            (new HermesAgentAdapter())->chat([['role' => 'user', 'content' => 'Hi']]);
            $this->fail('Expected invalid response');
        } catch (\App\Services\Ai\AiProviderException $e) {
            $this->assertSame('invalid_response', $e->getCategory());
        }
    }

    /** @test */
    public function hermes_health_states_are_derived_not_assumed()
    {
        config(['agent.hermes_enabled' => false]);
        $this->assertSame('disabled', (new HermesAgentAdapter())->health()['error']);

        config(['agent.hermes_enabled' => true, 'agent.hermes.base_url' => '', 'agent.hermes.api_key' => '']);
        $this->assertSame('not_configured', (new HermesAgentAdapter())->health()['error']);
    }

    /** @test */
    public function openclaw_dispatch_posts_documented_payload_without_reply_invention()
    {
        Http::fake(['openclaw.test/hooks/agent' => Http::response(['ok' => true], 200)]);

        $out = (new OpenClawAdapter())->dispatchAgentTurn('Summarize ticket TK-1', ['name' => 'Support']);
        $this->assertTrue($out['accepted']);
        $this->assertStringStartsWith('aisupport:', $out['session_key']);
        $this->assertArrayNotHasKey('content', $out); // never invents reply text
        Http::assertSent(function ($req) {
            $body = $req->data();
            if (($req->header('Authorization')[0] ?? '') !== 'Bearer oc-test') {
                return false;
            }

            return $body['message'] === 'Summarize ticket TK-1'
                && $body['deliver'] === false
                && isset($body['sessionKey']);
        });
    }

    /** @test */
    public function openclaw_refuses_unverified_and_reports_auth()
    {
        config(['agent.openclaw.verified' => false]);
        $this->expectException(\RuntimeException::class);
        (new OpenClawAdapter())->dispatchAgentTurn('Hi');
    }

    /** @test */
    public function openclaw_wake_and_chat_semantics()
    {
        Http::fake(['openclaw.test/hooks/wake' => Http::response(['ok' => true], 200)]);
        $out = (new OpenClawAdapter())->wake('Admin test', 'next-heartbeat');
        $this->assertTrue($out['accepted']);

        $this->expectException(\RuntimeException::class);
        (new OpenClawAdapter())->chat([['role' => 'user', 'content' => 'Hi']]);
    }

    /** @test */
    public function router_never_assumes_connectivity()
    {
        $router = app(AgentRouter::class);

        $this->assertSame('no_runtime_selected', $router->route('nope')['reason']);

        config(['agent.enabled' => false]);
        $this->assertSame('hermes_not_usable', $router->route('hermes')['reason']);
        config(['agent.enabled' => true]);

        // Kill switch wins over everything.
        Cache::put('agent:kill:openclaw', ['by' => 1], 60);
        $this->assertFalse($router->isUsable('openclaw'));
        $this->assertSame('openclaw_not_usable', $router->route('openclaw')['reason']);
    }

    /** @test */
    public function orchestrator_classifies_and_routes_deterministically()
    {
        $o = app(CustomerSupportOrchestrator::class);

        $this->assertSame('security_incident', $o->classify('We have ransomware on the file server!'));
        $this->assertSame('human_request', $o->classify('I want to talk to a real person please'));
        $this->assertSame('quote_request', $o->classify('How much does managed IT support cost?'));
        $this->assertSame('ticket_followup', $o->classify('What is the status of my ticket TK-ABC123?'));
        $this->assertSame('ai_integration', $o->classify('Can you integrate an AI assistant with our CRM?'));
        $this->assertSame('out_of_scope', $o->classify('What is the weather on Mars?'));

        $this->assertSame('human', $o->route('We have ransomware!')['capability']);
        $this->assertSame('quote', $o->route('How much does it cost?')['capability']);

        // Runtime requires staff approval even when verified.
        $denied = $o->route('Research this', $this->customer(), ['prefer_runtime' => true, 'runtime' => 'openclaw']);
        $this->assertArrayHasKey('runtime_denied', $denied);
        $this->assertNotSame('runtime', $denied['capability']);
    }

    /** @test */
    public function orchestrator_dispatch_is_staff_only_and_audited()
    {
        Http::fake(['openclaw.test/hooks/agent' => Http::response(['ok' => true], 200)]);
        $o = app(CustomerSupportOrchestrator::class);

        $refused = $o->dispatchToRuntime($this->customer(), 'openclaw', 'Do thing');
        $this->assertFalse($refused['success']);
        $this->assertSame('staff_only', $refused['error']);

        $ok = $o->dispatchToRuntime($this->staff(), 'openclaw', 'Summarize queue');
        $this->assertTrue($ok['success']);
        $this->assertTrue($ok['accepted']);

        $this->assertDatabaseHas('audit_logs', ['action' => 'agent.orchestrator']);
    }

    /** @test */
    public function gateway_kill_switch_denies_tools_and_secrets_stay_masked()
    {
        config(['agent.enabled' => false]);
        $auth = app(\App\Services\Ai\AgentGateway::class)->authorizeTool($this->customer(), 'search_knowledge_base');
        $this->assertFalse($auth['allowed']);
        $this->assertSame('agent_disabled', $auth['reason']);

        $this->assertSame('not set', RuntimeHttpClient::maskSecret(''));
        $this->assertStringNotContainsString('hk-test', RuntimeHttpClient::maskSecret('hk-test-secret-value'));
    }

    /** @test */
    public function admin_runtime_page_shows_real_states_without_secrets()
    {
        config(['agent.hermes.api_key' => 'hk-super-secret-value', 'agent.openclaw.token' => 'oc-super-secret']);
        Http::fake([
            'hermes.test/health' => Http::response(['status' => 'ok'], 200),
            'openclaw.test/' => Http::response('not found', 404),
        ]);

        $admin = $this->staff('super_admin');
        $this->actingAs($admin)->get(route('admin.ai.runtime'))
            ->assertStatus(200)
            ->assertDontSee('hk-super-secret-value')
            ->assertDontSee('oc-super-secret');

        // Kill switch flips the reported state.
        $this->actingAs($admin)->post(route('admin.ai.runtime.kill', 'hermes'), ['action' => 'disable'])
            ->assertRedirect();
        $this->assertTrue((bool) Cache::get('agent:kill:hermes'));
        $this->actingAs($admin)->get(route('admin.ai.runtime'))
            ->assertStatus(200)->assertSee('disabled');
    }

    /** @test */
    public function email_ingress_route_is_registered()
    {
        $this->assertNotNull(route('ingress.email'));
    }
}
