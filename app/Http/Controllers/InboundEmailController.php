<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Models\User;
use App\Services\ServiceTrackingService;
use App\Services\SlaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Inbound email ingestion via provider webhook (HMAC-signed JSON).
 *
 * Contract: POST /api/ingress/email
 *   headers: X-Ingress-Timestamp (unix), X-Ingress-Signature
 *            hex(hmac_sha256(timestamp . "." . raw_body, secret))
 *   body: {message_id*, from*, subject, text_body, in_reply_to,
 *          references[], attachments[]?{filename,mime,size,content_base64}}
 *
 * Always responds 202 (accepted) or 4xx on auth/shape failure — sender
 * addresses are never enumerated. Unknown senders never create tickets.
 */
class InboundEmailController extends Controller
{
    public function store(Request $request)
    {
        if (! config('inbound_email.enabled') || ! config('inbound_email.webhook_secret')) {
            return response()->json(['accepted' => false, 'reason' => 'disabled'], 503);
        }

        $raw = $request->getContent();
        $ts = $request->header('X-Ingress-Timestamp');
        $sig = $request->header('X-Ingress-Signature');
        if (! $ts || ! $sig || abs(time() - (int) $ts) > (int) config('inbound_email.timestamp_tolerance', 300)) {
            return response()->json(['accepted' => false, 'reason' => 'unauthorized'], 401);
        }
        $expected = hash_hmac('sha256', $ts.'.'.$raw, (string) config('inbound_email.webhook_secret'));
        if (! hash_equals($expected, (string) $sig)) {
            return response()->json(['accepted' => false, 'reason' => 'unauthorized'], 401);
        }

        $data = $request->validate([
            'message_id' => 'required|string|max:191',
            'from' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'text_body' => 'nullable|string|max:20000',
            'in_reply_to' => 'nullable|string|max:500',
            'references' => 'nullable|array|max:20',
            'references.*' => 'string|max:500',
            'attachments' => 'nullable|array|max:5',
            'attachments.*.filename' => 'required_with:attachments|string|max:255',
            'attachments.*.mime' => 'required_with:attachments|string|max:100',
            'attachments.*.size' => 'required_with:attachments|integer|min:1|max:10485760',
            'attachments.*.content_base64' => 'required_with:attachments|string|max:15000000',
        ]);

        // Idempotency: provider retries replay the same message_id.
        if (TicketMessage::where('external_id', $data['message_id'])->exists()) {
            return response()->json(['accepted' => true, 'result' => 'duplicate'], 202);
        }

        $sender = User::where('email', $data['from'])->where('is_active', true)->first();
        if (! $sender) {
            Log::info('Inbound email from unknown sender ignored', ['message_id' => $data['message_id']]);

            return response()->json(['accepted' => true, 'result' => 'unknown_sender'], 202);
        }

        $ticketNumber = $this->extractTicketNumber(
            (string) ($data['subject'] ?? ''), (string) ($data['in_reply_to'] ?? ''), (array) ($data['references'] ?? [])
        );

        if ($ticketNumber) {
            $ticket = Ticket::where('ticket_number', $ticketNumber)->first();
            if (! $ticket) {
                return response()->json(['accepted' => true, 'result' => 'unknown_ticket'], 202);
            }
            // Sender must own the ticket or be staff — never a third party.
            $owns = (int) $ticket->customer_id === (int) $sender->id;
            if (! $owns && ! $sender->isStaff()) {
                Log::warning('Inbound email sender mismatch', ['message_id' => $data['message_id']]);

                return response()->json(['accepted' => true, 'result' => 'sender_mismatch'], 202);
            }
            $body = trim((string) ($data['text_body'] ?? ''));
            if ($body === '') {
                return response()->json(['accepted' => true, 'result' => 'empty_body'], 202);
            }
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $sender->id,
                'message' => $body,
                'is_internal_note' => $sender->isStaff() && ! $owns,
                'external_id' => $data['message_id'],
            ]);
            if (in_array($ticket->status, ['waiting_customer', 'waiting_third_party', 'escalated'], true)) {
                $ticket->update(['status' => 'in_progress']);
            }
            $this->storeAttachments($ticket, $data['attachments'] ?? [], $sender->id);

            return response()->json(['accepted' => true, 'result' => 'reply_added', 'ticket_number' => $ticket->ticket_number], 202);
        }

        // New ticket — customers only (staff use the dashboard).
        if (! $sender->isCustomer()) {
            return response()->json(['accepted' => true, 'result' => 'staff_new_ticket_ignored'], 202);
        }
        $body = trim((string) ($data['text_body'] ?? ''));
        if ($body === '') {
            return response()->json(['accepted' => true, 'result' => 'empty_body'], 202);
        }
        $categoryId = config('inbound_email.default_category_id')
            ?: TicketCategory::where('is_active', true)->orderBy('id')->value('id');

        $ticket = Ticket::create([
            'customer_id' => $sender->id,
            'company_id' => $sender->company_id,
            'subject' => mb_substr((string) ($data['subject'] ?? '(no subject)'), 0, 255),
            'description' => $body,
            'category_id' => $categoryId,
            'priority' => 'medium',
            'severity' => 'moderate',
            'impact' => 'medium',
            'urgency' => 'medium',
            'status' => 'new',
        ]);
        app(SlaService::class)->applySla($ticket);
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $sender->id,
            'message' => $body,
            'is_internal_note' => false,
            'external_id' => $data['message_id'],
        ]);
        $this->storeAttachments($ticket, $data['attachments'] ?? [], $sender->id);

        ServiceTrackingService::notify(
            $sender->id, 'ticket_created',
            "Ticket {$ticket->ticket_number} created from your email",
            'We received your email and created a support ticket. Track it in your portal.'
        );

        return response()->json(['accepted' => true, 'result' => 'ticket_created', 'ticket_number' => $ticket->ticket_number], 202);
    }

    private function extractTicketNumber(string $subject, string $inReplyTo, array $references): ?string
    {
        foreach (array_merge([$subject, $inReplyTo], $references) as $text) {
            if (preg_match('/TK-[A-Z0-9]{4,16}/', strtoupper($text), $m)) {
                return $m[0];
            }
        }

        return null;
    }

    private function storeAttachments(Ticket $ticket, array $attachments, int $uploaderId): void
    {
        $allowed = (array) config('inbound_email.allowed_mimes', []);
        foreach (array_slice($attachments, 0, (int) config('inbound_email.max_attachments', 5)) as $a) {
            $ext = strtolower(pathinfo((string) ($a['filename'] ?? ''), PATHINFO_EXTENSION));
            if (! in_array($ext, $allowed, true)) {
                continue;
            }
            $raw = base64_decode((string) ($a['content_base64'] ?? ''), true);
            if ($raw === false || strlen($raw) > (int) config('inbound_email.max_attachment_bytes', 10485760)) {
                continue;
            }
            $path = 'ticket-attachments/'.uniqid('eml_', true).'.'.$ext;
            \Illuminate\Support\Facades\Storage::disk('local')->put($path, $raw);
            \App\Models\TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'uploaded_by' => $uploaderId,
                'filename' => basename($path),
                'original_name' => mb_substr((string) $a['filename'], 0, 255),
                'mime_type' => mb_substr((string) ($a['mime'] ?? 'application/octet-stream'), 0, 100),
                'size' => strlen($raw),
                'path' => $path,
            ]);
        }
    }
}
