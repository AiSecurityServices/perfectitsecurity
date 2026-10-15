<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Services\SlaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('customer_id', auth()->id())->with('category', 'assignee');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = addcslashes(mb_substr(trim((string) $request->search), 0, 100), '%_\\');
            $query->where(fn ($w) => $w->where('ticket_number', 'like', "%{$s}%")->orWhere('subject', 'like', "%{$s}%"));
        }
        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('customer.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = TicketCategory::where('is_active', true)->get();

        return view('customer.tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:ticket_categories,id',
            'priority' => 'required|in:low,medium,high,urgent,critical',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,txt,doc,docx,xls,xlsx|max:10240',
        ]);

        // Duplicate-submission guard: double-clicks, back-button resubmits
        // and retried requests replay the original ticket, never a copy.
        $user = auth()->user();
        $key = \App\Support\Idempotency::keyForRequest($request, 'portal.ticket.store', $user->id, [
            'subject' => $validated['subject'],
            'description' => mb_substr($validated['description'], 0, 500),
            'category_id' => $validated['category_id'] ?? null,
            'priority' => $validated['priority'],
        ]);
        $outcome = \App\Support\Idempotency::run($key, function () use ($validated, $request, $user) {
            $ticket = Ticket::create([
                'customer_id' => $user->id,
                'company_id' => $user->company_id,
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'priority' => $validated['priority'],
                'severity' => 'moderate',
                'impact' => 'medium',
                'urgency' => 'medium',
                'status' => 'new',
            ]);

            app(SlaService::class)->applySla($ticket);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    // Ticket evidence is private customer data; it must never be served by /storage.
                    $path = $file->store('ticket-attachments', 'local');
                    $ticket->attachments()->create([
                        'uploaded_by' => $user->id,
                        'filename' => basename($path),
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'path' => $path,
                    ]);
                }
            }

            return ['ticket_id' => $ticket->id];
        });

        $ticket = Ticket::findOrFail($outcome['result']['ticket_id']);

        return redirect()->route('portal.tickets.show', $ticket)
            ->with('success', $outcome['replayed'] ? 'This request was already submitted — showing your original ticket.' : 'Ticket created successfully!');
    }

    public function show($id)
    {
        $ticket = $this->findVisibleTicket($id)->load('messages.user', 'attachments', 'category', 'assignee');
        $slaStatus = app(SlaService::class)->getSlaStatus($ticket);

        return view('customer.tickets.show', compact('ticket', 'slaStatus'));
    }

    /**
     * Organisation tickets: colleagues in the same company can follow and
     * reply on shared tickets. Ownership rules stay strict — only the ticket
     * owner or staff can reopen/close; cross-company access is impossible.
     */
    public function organisation(Request $request)
    {
        $user = auth()->user();
        abort_unless($user->company_id, 404);

        $query = Ticket::where('company_id', $user->company_id)
            ->where('customer_id', '!=', $user->id)
            ->with('category', 'assignee', 'customer');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('customer.tickets.organisation', compact('tickets'));
    }

    public function reopen(Request $request, $id)
    {
        // Only the ticket OWNER may reopen, only from resolved/closed, only
        // within 7 days of resolution — then staff triage again (reopened).
        $ticket = Ticket::where('customer_id', auth()->id())->findOrFail($id);
        abort_unless(in_array($ticket->status, ['resolved', 'closed'], true), 422, 'Only resolved or closed tickets can be reopened.');
        abort_unless($ticket->resolved_at && $ticket->resolved_at->gte(now()->subDays(7)), 422, 'Reopening is available within 7 days of resolution. Please open a new ticket.');

        $request->validate(['reason' => 'required|string|max:500']);

        $ticket->update([
            'status' => 'reopened',
            'resolved_at' => null,
            'closed_at' => null,
            'sla_warning_sent_at' => null,
            'sla_breached_at' => null,
            'sla_paused_at' => null,
        ]);
        app(SlaService::class)->applySla($ticket->fresh());

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => "Customer reopened the ticket. Reason: {$request->reason}",
        ]);

        if ($ticket->assigned_to) {
            \App\Services\ServiceTrackingService::notify(
                (int) $ticket->assigned_to, 'ticket_reply',
                "Customer reopened {$ticket->ticket_number}",
                mb_substr((string) $request->reason, 0, 140)
            );
        }

        return redirect()->route('portal.tickets.show', $ticket)->with('success', 'Ticket reopened. Our team will follow up.');
    }

    public function reply(Request $request, $id)
    {
        $ticket = $this->findVisibleTicket($id);
        abort_if($ticket->isTerminal(), 422, 'This ticket is closed. Please reopen it or open a new one.');

        $validated = $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,txt,doc,docx,xls,xlsx|max:10240',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Ticket evidence is private customer data; it must never be served by /storage.
                $path = $file->store('ticket-attachments', 'local');
                $ticket->attachments()->create([
                    'uploaded_by' => auth()->id(),
                    'filename' => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path,
                ]);
            }
        }

        if (in_array($ticket->status, ['waiting_customer', 'waiting_third_party', 'escalated'], true)) {
            $ticket->update(['status' => 'in_progress']);
        }

        // Customer reply → notify the assigned agent (preference-gated).
        if ($ticket->assigned_to) {
            \App\Services\ServiceTrackingService::notify(
                (int) $ticket->assigned_to, 'ticket_reply',
                "Customer reply on {$ticket->ticket_number}",
                mb_substr((string) $validated['message'], 0, 140)
            );
        }

        return redirect()->back()->with('success', 'Reply sent!');
    }

    public function downloadAttachment(Ticket $ticket, TicketAttachment $attachment)
    {
        $visible = $this->findVisibleTicket($ticket->id);
        abort_unless($attachment->ticket_id === $visible->id, 404);
        abort_unless(Storage::disk('local')->exists($attachment->path), 404);

        return Storage::disk('local')->download($attachment->path, $attachment->original_name);
    }

    /**
     * Owner ticket, or a colleague's ticket in the same company.
     * Anything else is indistinguishable from nonexistent (404, never 403).
     */
    private function findVisibleTicket($id): Ticket
    {
        $user = auth()->user();
        $ticket = Ticket::findOrFail($id);
        if ((int) $ticket->customer_id === (int) $user->id) {
            return $ticket;
        }
        if ($user->company_id && (int) $ticket->company_id === (int) $user->company_id) {
            return $ticket;
        }
        abort(404);
    }
}
