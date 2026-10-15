# Agent Runtime Setup — Hermes Agent & OpenClaw

External agent runtimes are **optional automation engines**. The website,
helpdesk, AI chat, and ticketing work fully without them. Nothing here is
required for normal operation.

## 1. Architecture (what is real)

- `App\Services\Ai\AgentRuntimeInterface` — the only contract business code uses.
- `HermesAgentAdapter` — real implementation against the documented Hermes
  Agent API server (OpenAI-compatible):
  `POST {HERMES_BASE_URL}/v1/chat/completions` (Bearer `HERMES_API_KEY`,
  `{model, messages}`), `GET /health`, `GET /v1/capabilities`.
- `OpenClawAdapter` — real implementation against the documented OpenClaw
  Gateway webhooks: `POST {OPENCLAW_BASE_URL}/hooks/agent`
  (`{message*, name?, agentId?, sessionKey?, wakeMode?, deliver?, model?,
  thinking?, timeoutSeconds?}`, `Authorization: Bearer` header —
  query-string tokens are rejected upstream) and `POST /hooks/wake`
  (`{text*, mode?}`).
- `AgentRouter` — selects a runtime only when enabled + configured +
  staging-verified; otherwise returns an explicit reason for fallback.
- `CustomerSupportOrchestrator` — deterministic classification and explicit
  routing (knowledge / quote / helpdesk / human / runtime). Runtime
  dispatch is staff-only and audited.
- Admin UI: **AI → Runtimes** (`admin.ai.runtime`) shows live states,
  masked config, explicit connection tests, and kill switches.

Hermes `chat()` refuses unless enabled + configured + `HERMES_VERIFIED=true`.
OpenClaw `chat()` always refuses (the conversational reply path is
undocumented); only event dispatch (`dispatchAgentTurn`, `deliver=false`)
and `wake()` are offered, both verified-gated. Health checks are
side-effect-free (no agent activity): Hermes `GET /health`; OpenClaw a
plain reachability probe that never touches `/hooks/*`.

## 2. Hermes Agent setup (staging first)

1. On a **private** host (loopback or VPC — never expose the port publicly):
   install Hermes Agent, run `hermes setup`, then enable the API server:
   `API_SERVER_ENABLED=true`, `API_SERVER_KEY=<strong random>`,
   start with `hermes gateway` (listens on `127.0.0.1:8642` by default).
2. From the Laravel host, verify reachability to that private address.
3. Set in the Laravel environment:
   `HERMES_ENABLED=true`, `HERMES_BASE_URL=http://<private-host>:8642`,
   `HERMES_API_KEY=<same key>`, `HERMES_MODEL=hermes-agent`,
   then prove it: `curl $HERMES_BASE_URL/health` (2xx),
   plus one `POST /v1/chat/completions` from the staging Laravel box.
4. Only then set `HERMES_VERIFIED=true` and `AI_AGENT_ENABLED=true`
   (+ `AI_AGENT_RUNTIME=hermes` if the orchestrator should prefer it).
5. Confirm **AI → Runtimes** shows `ready` (not `not_verified`).

## 3. OpenClaw setup (staging first)

1. Run the OpenClaw Gateway on a **private** host with webhooks enabled and
   a strong `hooks.token` (`allowedAgentIds` narrowed, e.g. `["hooks"]`).
2. Set `OPENCLAW_ENABLED=true`,
   `OPENCLAW_BASE_URL=http://<private-host>:<port>`,
   `OPENCLAW_TOKEN=<hooks token>`, `OPENCLAW_AGENT_ID=hooks`.
3. Click **Run connection test** in **AI → Runtimes** (this sends one
   `next-heartbeat` wake event — gateway activity by design — then reports
   the real outcome). On 2xx set `OPENCLAW_VERIFIED=true`.
4. Never put the Gateway port on the public internet; if remote access is
   needed, front it with TLS + client auth (reverse proxy / VPN / Tailscale).

## 4. Health states (all derived, never assumed)

`disabled` (switch off or kill switch) · `not_configured` (URL/token
missing) · `not_verified` (reachable but staging proof flag unset) ·
`unreachable` (transport failure) · `auth_failed` (a real call got
401/403) · `ready` (enabled + configured + verified + reachable).

## 5. Kill switch & recovery when a runtime is down

- **AI → Runtimes → Kill switch** (per runtime or global) disables routing
  instantly without config changes. `AgentGateway` chat, orchestrator
  dispatch, and the router all fail over to provider chat / knowledge /
  human support — the helpdesk never depends on a runtime.
- Recovery: fix the runtime host, re-run the connection test, release the
  kill switch. Audit entries (`agent.*`) record every check, test,
  dispatch, and kill for review.

## 6. Inbound email → tickets (optional)

Point the provider (Mailgun / SendGrid / Postmark / SES) at
`POST /api/ingress/email` with headers `X-Ingress-Timestamp` (unix) and
`X-Ingress-Signature` = `hex(hmac_sha256(ts . "." . raw_body, secret))`.
Set `INBOUND_EMAIL_ENABLED=true`, `INBOUND_EMAIL_WEBHOOK_SECRET=<random>`,
`INBOUND_EMAIL_DEFAULT_CATEGORY_ID=<id>`. Tickets are created only for
known customer sender addresses; replies match `[TK-XXXXXXXX]` in
subject/headers with sender-ownership checks; provider `message_id`
deduplicates retries; unknown senders get 202 + log, never a ticket.

## 7. Production checklist (Laravel backend)

- PHP 8.0+, Composer install, `php artisan migrate --force`, `vite build`
  assets served from `public/build`.
- Queue: set `QUEUE_CONNECTION=database` (or redis) and run a supervised
  `queue:work`; `sync` is dev-only. Scheduler: one cron line
  `* * * * * php artisan schedule:run`.
- `APP_DEBUG=false`, HTTPS, `MAIL_*` for outbound, log mailer only in dev.
- Backups run on schedule (`backup:run`); restores are admin-gated with
  confirmation tokens — see `docs/BACKUP_DISASTER_RECOVERY.md`.
- GitHub Pages / static hosting **cannot** run this backend (PHP, sessions,
  DB, queues, webhooks). Deploy to a PHP-capable host (Railway config in
  repo) — the static exporter is marketing-snapshot only.
