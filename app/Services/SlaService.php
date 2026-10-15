<?php

namespace App\Services;

use App\Models\Country;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use App\Models\User;

class SlaService
{
    public function applySla(Ticket $ticket, ?Country $country = null): Ticket
    {
        $policy = $ticket->slaPolicy ?? $ticket->category?->slaPolicy;
        if (! $policy) {
            $policy = SlaPolicy::where('priority', $ticket->priority)->first();
        }
        if (! $policy) {
            return $ticket;
        }

        // NOTE: users.country is a plain string code (e.g. "GB"), not a
        // relation — resolve it to a Country model. addBusinessMinutes()
        // requires ?Country, so a raw string here used to fatal.
        $countryCode = $country?->code ?? $ticket->customer?->country;
        $country = $country
            ?? ($countryCode ? Country::where('code', $countryCode)->first() : null)
            ?? Country::where('code', 'UK')->first();

        $hours = app(BusinessHoursService::class);
        $responseDeadline = $hours->addBusinessMinutes($country, (int) $policy->response_time_minutes);
        $resolutionDeadline = $hours->addBusinessMinutes($country, (int) $policy->resolution_time_minutes);

        $ticket->update([
            'sla_policy_id' => $policy->id,
            'sla_response_deadline' => $responseDeadline,
            'sla_resolution_deadline' => $resolutionDeadline,
        ]);

        return $ticket;
    }

    public function recordFirstResponse(Ticket $ticket): Ticket
    {
        if (! $ticket->first_response_at) {
            $ticket->update(['first_response_at' => now()]);
        }

        return $ticket;
    }

    public function recordResolution(Ticket $ticket): Ticket
    {
        $ticket->update([
            'resolved_at' => now(),
            'status' => 'resolved',
        ]);

        return $ticket;
    }

    /**
     * Freeze the SLA clock while work waits on the customer or a third
     * party. Idempotent — calling twice is a no-op.
     */
    public function pauseClock(Ticket $ticket): Ticket
    {
        if (! $ticket->sla_paused_at && ! $ticket->isTerminal() && $ticket->sla_resolution_deadline) {
            $ticket->update(['sla_paused_at' => now()]);
        }

        return $ticket;
    }

    /**
     * Resume a frozen clock: both deadlines shift forward by the exact
     * waited duration, so neither side is penalised for waiting time.
     */
    public function resumeClock(Ticket $ticket): Ticket
    {
        if (! $ticket->sla_paused_at) {
            return $ticket;
        }
        $waitedMinutes = max(0, (int) $ticket->sla_paused_at->diffInMinutes(now()));
        $updates = ['sla_paused_at' => null];
        if ($ticket->sla_response_deadline) {
            $updates['sla_response_deadline'] = $ticket->sla_response_deadline->copy()->addMinutes($waitedMinutes);
        }
        if ($ticket->sla_resolution_deadline) {
            $updates['sla_resolution_deadline'] = $ticket->sla_resolution_deadline->copy()->addMinutes($waitedMinutes);
        }
        $ticket->update($updates);

        return $ticket;
    }

    public function getSlaStatus(Ticket $ticket): string
    {
        if ($ticket->sla_paused_at) {
            return 'paused';
        }
        if (! $ticket->sla_resolution_deadline) {
            return 'no_sla';
        }
        if (in_array($ticket->status, ['resolved', 'closed'])) {
            return $ticket->resolved_at && $ticket->resolved_at->lte($ticket->sla_resolution_deadline) ? 'met' : 'breached';
        }
        if (now()->gt($ticket->sla_resolution_deadline)) {
            return 'breached';
        }
        if (now()->diffInHours($ticket->sla_resolution_deadline) < 2) {
            return 'warning';
        }

        return 'ok';
    }

    public function getBreachedTickets()
    {
        return Ticket::where('status', '!=', 'closed')
            ->where('sla_resolution_deadline', '<', now())
            ->whereNull('resolved_at')
            ->get();
    }

    /**
     * Generate one warning and one breach alert per ticket. This is safe for
     * scheduler retries because timestamps make the operation idempotent.
     */
    public function processDeadlines(): array
    {
        $warningCount = 0;
        $breachCount = 0;
        // Paused clocks (waiting on customer/third party) are skipped —
        // their deadlines shift on resume instead.
        $tickets = Ticket::with('assignee')->open()->whereNotNull('sla_resolution_deadline')->whereNull('sla_paused_at')->get();

        foreach ($tickets as $ticket) {
            $deadline = $ticket->sla_resolution_deadline;
            if ($deadline->isPast() && ! $ticket->sla_breached_at) {
                $ticket->update(['sla_breached_at' => now(), 'status' => 'escalated']);
                $this->notifySlaOwners($ticket, 'sla_breached', 'SLA breach', "Ticket {$ticket->ticket_number} has exceeded its resolution deadline.");
                // Additive ITSM ledger: persistent breach record (idempotent).
                try {
                    app(ItsmService::class)->recordBreach($ticket->fresh(), 'resolution');
                } catch (\Throwable $e) {
                    report($e);
                }
                $breachCount++;
            } elseif (! $ticket->sla_warning_sent_at && now()->diffInMinutes($deadline, false) <= 120) {
                $ticket->update(['sla_warning_sent_at' => now()]);
                $this->notifySlaOwners($ticket, 'sla_warning', 'SLA warning', "Ticket {$ticket->ticket_number} is within two hours of its resolution deadline.");
                $warningCount++;
            }
            // Additive: response-breach ledger entry (no status change — existing behaviour preserved).
            if ($ticket->sla_response_deadline && ! $ticket->first_response_at && $ticket->sla_response_deadline->isPast()) {
                try {
                    app(ItsmService::class)->recordBreach($ticket, 'response');
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        return compact('warningCount', 'breachCount');
    }

    private function notifySlaOwners(Ticket $ticket, string $type, string $title, string $message): void
    {
        $recipients = User::query()
            ->where('is_active', true)
            ->whereIn('role', ['super_admin', 'admin', 'support_manager'])
            ->get();
        if ($ticket->assignee && ! $recipients->contains('id', $ticket->assignee->id)) {
            $recipients->push($ticket->assignee);
        }

        foreach ($recipients->unique('id') as $user) {
            // Preference-gated like every other notification (no direct
            // Notification::create bypass).
            ServiceTrackingService::notify((int) $user->id, $type, $title, $message);
        }
    }

    public function getSlaComplianceStats(): array
    {
        $total = Ticket::whereNotNull('sla_resolution_deadline')->count();
        $met = Ticket::whereNotNull('resolved_at')
            ->whereColumn('resolved_at', '<=', 'sla_resolution_deadline')
            ->count();

        return [
            'total' => $total,
            'met' => $met,
            'breached' => $total - $met,
            'compliance_rate' => $total > 0 ? round(($met / $total) * 100, 1) : 100,
        ];
    }
}
