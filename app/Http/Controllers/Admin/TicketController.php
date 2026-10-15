<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTeam;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\User;
use App\Services\SlaService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('customer', 'assignee', 'category', 'team');

        // Only the explicit support-agent role is scoped to its own queue.
        if ($request->user()->role === 'support_agent') {
            $query->where('assigned_to', $request->user()->id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->priority) {
            $query->where('priority', $request->priority);
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->assigned_to && $request->user()->isSupportManager()) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_number', 'like', "%{$request->search}%")
                  ->orWhere('subject', 'like', "%{$request->search}%");
            });
        }

        $tickets = $query->latest()->paginate(20);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Request $request, $id)
    {
        $ticket = Ticket::with('customer', 'assignee', 'category', 'subcategory', 'team', 'messages.user', 'attachments', 'timeEntries.user')->findOrFail($id);
        $this->ensureTicketAccess($ticket, false);
        $slaStatus = app(SlaService::class)->getSlaStatus($ticket);
        // Additive ITSM: grounded suggestions (related tickets + permission-aware
        // KB articles). Read-only and failure-isolated — never breaks the view.
        try {
            $suggestions = app(\App\Services\ItsmService::class)->suggestForTicket($ticket, $request->user());
        } catch (\Throwable $e) {
            report($e);
            $suggestions = ['tickets' => collect(), 'articles' => []];
        }
        $agents = User::where('role', 'support_agent')->get();
        $teams = SupportTeam::where('is_active', true)->get();
        $categories = TicketCategory::where('is_active', true)->get();

        return view('admin.tickets.show', compact('ticket', 'slaStatus', 'suggestions', 'agents', 'teams', 'categories'));
    }

    public function assign(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ensureTicketAccess($ticket, true);
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'team_id' => 'nullable|exists:support_teams,id',
        ]);

        // Assignees must be active staff — customers and contractors can
        // never own a support queue ticket.
        $assignee = User::findOrFail($request->assigned_to);
        abort_unless($assignee->is_active && $assignee->isStaff(), 422, 'Assignee must be an active staff member.');
        abort_if($ticket->isTerminal(), 422, 'Terminal tickets cannot be reassigned. Reopen first.');

        $updates = [
            'assigned_to' => $assignee->id,
            'team_id' => $request->team_id ?? $ticket->team_id,
        ];
        // Re-assignment must not regress an in-flight ticket back to
        // "assigned"; only fresh tickets advance to it (FSM-guarded).
        if ($ticket->canTransitionTo('assigned')) {
            $updates['status'] = 'assigned';
        }
        $ticket->update($updates);

        $assignee->notify(new \App\Notifications\TicketAssignedNotification($ticket->fresh(), auth()->user()->name ?? 'System'));

        return redirect()->back()->with('success', 'Ticket assigned successfully!');
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ensureTicketAccess($ticket, false);

        // Handle time entry addition
        if ($request->boolean('add_time_entry')) {
            $request->validate([
                'minutes' => 'required|integer|min:1|max:480',
                'time_description' => 'nullable|string|max:500',
            ]);

            \App\Models\TicketTimeEntry::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'minutes' => $request->minutes,
                'description' => $request->time_description ?? 'Time logged',
                'date' => now()->toDateString(),
            ]);

            return redirect()->back()->with('success', 'Time entry added!');
        }

        $request->validate(['message' => 'required|string']);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Staff evidence uploads follow the same private-storage rules as
        // customer attachments (never served from /storage).
        $request->validate(['attachments' => 'nullable|array|max:5', 'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,txt,doc,docx,xls,xlsx|max:10240']);
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'local');
                \App\Models\TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'uploaded_by' => auth()->id(),
                    'filename' => basename($path),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path,
                ]);
            }
        }

        app(SlaService::class)->recordFirstResponse($ticket);

        if ($ticket->status === 'new' || $ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        // Customer-visible reply → notify the customer (preference-gated).
        // Internal notes use addNote() and deliberately notify nobody.
        if ($ticket->customer) {
            \App\Services\ServiceTrackingService::notify(
                (int) $ticket->customer_id, 'ticket_reply',
                "Reply on ticket {$ticket->ticket_number}",
                mb_substr((string) $request->message, 0, 140)
            );
        }

        return redirect()->back()->with('success', 'Reply sent!');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ensureTicketAccess($ticket, false);
        $request->validate(['status' => 'required|in:new,open,assigned,in_progress,waiting_customer,waiting_third_party,escalated,resolved,reopened,closed,cancelled']);

        // Server-enforced lifecycle: invalid transitions are rejected,
        // never silently applied.
        abort_unless($ticket->canTransitionTo($request->status), 422, "Cannot move ticket from {$ticket->status} to {$request->status}.");

        $from = $ticket->status;
        $ticket->update(['status' => $request->status]);

        // SLA clock: freeze while waiting on customer/third party, resume
        // (deadlines shifted by the waited duration) when work restarts.
        $sla = app(SlaService::class);
        if (in_array($request->status, Ticket::WAITING_STATUSES, true)) {
            $sla->pauseClock($ticket->fresh());
        } elseif (in_array($from, Ticket::WAITING_STATUSES, true)) {
            $sla->resumeClock($ticket->fresh());
        }

        if (in_array($request->status, ['resolved'])) {
            $sla->recordResolution($ticket);
        }
        if ($request->status === 'closed') {
            $ticket->update(['closed_at' => now()]);
        }

        // Customer-visible status changes notify the customer
        // (preference-gated; internal-only transitions stay silent).
        if ($ticket->customer && in_array($request->status, ['resolved', 'closed', 'reopened', 'escalated', 'waiting_customer'], true)) {
            \App\Services\ServiceTrackingService::notify(
                (int) $ticket->customer_id, 'ticket_status',
                "Ticket {$ticket->ticket_number} is now {$request->status}",
                "Your support ticket status changed from {$from} to {$request->status}."
            );
        }

        return redirect()->back()->with('success', 'Ticket status updated!');
    }

    public function addNote(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ensureTicketAccess($ticket, false);
        $request->validate(['note' => 'required|string']);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->note,
            'is_internal_note' => true,
        ]);

        return redirect()->back()->with('success', 'Internal note added!');
    }

    public function updateTags(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ensureTicketAccess($ticket, false);

        $request->validate([
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        $ticket->update(['tags' => $request->tags ?? []]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'tags' => $ticket->tags]);
        }

        return redirect()->back()->with('success', 'Tags updated!');
    }

    public function merge(Request $request, $id)
    {
        $primaryTicket = Ticket::findOrFail($id);
        $this->ensureTicketAccess($primaryTicket, true);

        $request->validate([
            'merge_ticket_id' => 'required|exists:tickets,id|not_in:'.$id,
            'reason' => 'nullable|string|max:500',
        ]);

        $secondaryTicket = Ticket::findOrFail($request->merge_ticket_id);

        // Cross-customer merges would expose one customer's internal notes
        // and history to another — refuse them outright.
        abort_unless((int) $secondaryTicket->customer_id === (int) $primaryTicket->customer_id, 422, 'Only tickets of the same customer can be merged.');

        \Illuminate\Support\Facades\DB::transaction(function () use ($primaryTicket, $secondaryTicket, $request) {
            // Move all messages from secondary to primary
            TicketMessage::where('ticket_id', $secondaryTicket->id)
                ->update(['ticket_id' => $primaryTicket->id]);

            // Move attachments
            \App\Models\TicketAttachment::where('ticket_id', $secondaryTicket->id)
                ->update(['ticket_id' => $primaryTicket->id]);

            // Move time entries
            \App\Models\TicketTimeEntry::where('ticket_id', $secondaryTicket->id)
                ->update(['ticket_id' => $primaryTicket->id]);

            // Add merge note
            $reason = $request->reason ? " (Reason: {$request->reason})" : '';
            TicketMessage::create([
                'ticket_id' => $primaryTicket->id,
                'user_id' => auth()->id(),
                'message' => "Merged ticket {$secondaryTicket->ticket_number} into this ticket{$reason}",
                'is_internal_note' => true,
            ]);

            // Soft delete secondary ticket
            $secondaryTicket->update([
                'status' => 'cancelled',
                'description' => $secondaryTicket->description."\n\n--- MERGED INTO: {$primaryTicket->ticket_number} ---",
            ]);
            $secondaryTicket->delete();
        });

        return redirect()->route('admin.tickets.show', $primaryTicket)
            ->with('success', "Ticket {$secondaryTicket->ticket_number} has been merged into {$primaryTicket->ticket_number}.");
    }

    public function reopen(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ensureTicketAccess($ticket, false);

        $request->validate(['reason' => 'required|string|max:500']);

        // Resolved tickets reopen through the FSM; closed tickets need an
        // explicit staff override (audited via the reopen message).
        abort_unless(
            $ticket->canTransitionTo('reopened') || $ticket->status === 'closed',
            422, "Cannot reopen a ticket with status {$ticket->status}."
        );

        $ticket->update([
            'status' => 'reopened',
            'resolved_at' => null,
            'closed_at' => null,
            'sla_warning_sent_at' => null,
            'sla_breached_at' => null,
            'sla_paused_at' => null,
        ]);
        // Fresh SLA clock for the reopened work.
        app(SlaService::class)->applySla($ticket->fresh());

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => "Ticket reopened. Reason: {$request->reason}",
        ]);

        if ($ticket->customer) {
            \App\Services\ServiceTrackingService::notify(
                (int) $ticket->customer_id, 'ticket_status',
                "Ticket {$ticket->ticket_number} reopened",
                "Your ticket was reopened. Reason: {$request->reason}"
            );
        }

        return redirect()->back()->with('success', 'Ticket reopened!');
    }

    private function ensureTicketAccess(Ticket $ticket, bool $managerOnly): void
    {
        $user = request()->user();
        if ($user->isSupportManager() || $user->isAdmin()) {
            return;
        }

        abort_if($managerOnly || ! $user->isSupportAgent() || (int) $ticket->assigned_to !== (int) $user->id, 403);
    }
}
