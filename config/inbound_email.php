<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Inbound email → ticket ingestion (provider webhook, HMAC-signed)
    |--------------------------------------------------------------------------
    | Mail providers (Mailgun / SendGrid / Postmark / SES) forward parsed
    | mail to POST /api/ingress/email with an HMAC signature. The shared
    | secret below is the ONLY credential; it never appears in responses.
    | Tickets are created ONLY for known customer/staff sender addresses —
    | unknown senders are acknowledged (202) and logged, never ticketed.
    */
    'enabled' => env('INBOUND_EMAIL_ENABLED', false),
    'webhook_secret' => env('INBOUND_EMAIL_WEBHOOK_SECRET', ''),
    'timestamp_tolerance' => 300, // seconds
    'default_category_id' => env('INBOUND_EMAIL_DEFAULT_CATEGORY_ID'),
    'max_attachments' => 5,
    'max_attachment_bytes' => 10 * 1024 * 1024,
    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png', 'txt', 'doc', 'docx', 'xls', 'xlsx'],
];
