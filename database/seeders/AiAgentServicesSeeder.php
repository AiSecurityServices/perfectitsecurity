<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCountryPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * AI Agent Services division catalogue.
 *
 * Idempotent: safe to re-run (firstOrCreate / updateOrCreate on slugs).
 * All offerings are scoped as custom proposals — no invented fixed prices.
 * Copy rules: no 24/7 monitoring claims, no unverified platform claims,
 * authorization-required language for security work, human-approval gates
 * for consequential automation, honest local-AI hardware caveats.
 */
class AiAgentServicesSeeder extends Seeder
{
    public function run(): void
    {
        $sort = (int) (ServiceCategory::max('sort_order') ?? 0);

        $categories = [
            [
                'name' => 'AI Agent Development',
                'slug' => 'ai-agent-development',
                'description' => 'Custom AI agents designed, built, and deployed for your business operations — assistants, tool-using agents, and coordinated multi-agent workflows.',
                'icon' => 'AI',
                'color' => '#55E6FF',
                'sort_order' => ++$sort,
                'is_active' => true,
            ],
            [
                'name' => 'AI Setup & Integration',
                'slug' => 'ai-setup-integration',
                'description' => 'Practical installation, configuration, and integration of AI agent frameworks, coding assistants, and model providers — including Claude Code workflows and local model environments.',
                'icon' => 'AI',
                'color' => '#9275FF',
                'sort_order' => ++$sort,
                'is_active' => true,
            ],
            [
                'name' => 'Local & Private AI',
                'slug' => 'local-private-ai',
                'description' => 'Self-hosted model deployments, private knowledge assistants, and retrieval-augmented generation for organizations that want control over their AI infrastructure.',
                'icon' => 'AI',
                'color' => '#4DA3FF',
                'sort_order' => ++$sort,
                'is_active' => true,
            ],
            [
                'name' => 'AI Automation & Managed AI',
                'slug' => 'ai-automation-managed',
                'description' => 'AI-powered business process automation with human approval gates, plus security hardening and ongoing maintenance for deployed AI environments.',
                'icon' => 'AI',
                'color' => '#00E67A',
                'sort_order' => ++$sort,
                'is_active' => true,
            ],
        ];

        $cats = [];
        foreach ($categories as $cd) {
            $cats[$cd['slug']] = ServiceCategory::firstOrCreate(['slug' => $cd['slug']], $cd);
        }

        $services = [
            // ── CATEGORY: AI Agent Development ──────────────────────────
            [
                'category' => 'ai-agent-development',
                'name' => 'Custom AI Agents for Business Operations',
                'slug' => 'custom-ai-agents',
                'short_description' => 'Purpose-built AI agents that use your approved tools and data to carry out defined business tasks under human supervision.',
                'description' => 'Many teams lose hours to repetitive operational work: triaging inboxes, copying data between systems, drafting routine documents. A custom AI agent combines a language model with your approved tools and data sources to carry out a defined task — and stops where its permissions stop. We design the agent around one job, connect only the integrations it needs, and put consequential actions behind human approval.',
                'scope' => 'Single-purpose or multi-step agents built on approved commercial or open-source models, integrated with your existing tools via official APIs.',
                'exclusions' => 'Unrestricted autonomous access to production systems; fully autonomous financial transactions; scraping or messaging that violates provider terms.',
                'complexity_level' => 'advanced',
                'estimated_completion' => '2-6 weeks depending on scope',
                'price_type' => 'custom_quote',
                'starting_price' => 0,
                'allows_custom_quote' => true,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
                'deliverables' => ['Scoped agent specification document', 'Configured agent with approved tool connections', 'Permission and approval-gate configuration', 'Test evidence and acceptance walkthrough', 'Operator documentation and handover training'],
                'features' => ['Standalone assistants, tool-using agents, or multi-agent workflows', 'Official API integrations only', 'Human approval gates for consequential actions', 'Full audit logging of agent actions'],
                'process_steps' => [
                    ['t' => 'Discovery', 'd' => 'Map the task, inputs, systems, and decision points with your team.'],
                    ['t' => 'Technical Assessment', 'd' => 'Verify model, tool, and data access requirements and constraints.'],
                    ['t' => 'Solution Design', 'd' => 'Define agent responsibilities, permissions, and approval gates.'],
                    ['t' => 'Configuration & Development', 'd' => 'Build and connect the agent in a non-production environment.'],
                    ['t' => 'Testing & Security Review', 'd' => 'Adversarial checks, permission review, and acceptance testing.'],
                    ['t' => 'Deployment & Handover', 'd' => 'Production rollout with documentation and operator training.'],
                    ['t' => 'Ongoing Support', 'd' => 'Monitoring guidance and optional maintenance agreement.'],
                ],
                'faq' => ['What is the difference between an assistant, an agent, and a multi-agent workflow? — An assistant answers questions; a tool-using agent can also act through approved integrations; a multi-agent workflow coordinates several agents with distinct responsibilities under one supervisor.', 'Will the agent work with our existing software? — If the software offers an official API or export, usually yes. We verify each integration during technical assessment before committing.', 'Can the agent act without human review? — Only for low-risk, reversible actions you explicitly approve. Anything consequential requires a human gate.'],
                'tags' => ['ai agents', 'automation', 'custom ai', 'integration', 'workflow'],
                'seo_title' => 'Custom AI Agents for Business Operations | AISecurity',
                'seo_description' => 'Purpose-built AI agents integrated with your approved tools, with human approval gates and full audit logging.',
            ],
            [
                'category' => 'ai-agent-development',
                'name' => 'AI Customer Service Agents',
                'slug' => 'ai-customer-service-agents',
                'short_description' => 'Support assistants that answer from your documentation, draft replies, and escalate uncertain cases to your human team.',
                'description' => 'Support queues grow faster than support teams. An AI customer service agent trained on your approved documentation can resolve common questions instantly, draft replies for staff review, categorize incoming enquiries, and escalate anything uncertain to a human — with every action logged. You choose the autonomy level: read-only answers, draft generation, or authorized ticket creation inside your ticketing system.',
                'scope' => 'Knowledge-grounded support agents for web chat, email triage, or internal helpdesk assistance, integrated with your approved ticketing platform.',
                'exclusions' => 'Agents impersonating humans without disclosure; unsupervised changes to customer records; access to systems outside the agreed scope.',
                'complexity_level' => 'standard',
                'estimated_completion' => '2-4 weeks depending on scope',
                'price_type' => 'custom_quote',
                'starting_price' => 0,
                'allows_custom_quote' => true,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
                'deliverables' => ['Curated knowledge base connection', 'Configured support agent with escalation rules', 'Ticketing integration (draft or authorized create)', 'Response quality test report', 'Staff training and escalation handbook'],
                'features' => ['Answers grounded in your documentation', 'Read-only, draft, or authorized-write modes', 'Automatic escalation of uncertain cases', 'Conversation logging for quality review'],
                'process_steps' => [
                    ['t' => 'Discovery', 'd' => 'Analyze enquiry types, volumes, and documentation quality.'],
                    ['t' => 'Technical Assessment', 'd' => 'Verify ticketing and knowledge-base integration options.'],
                    ['t' => 'Solution Design', 'd' => 'Define autonomy level, escalation paths, and tone.'],
                    ['t' => 'Configuration & Development', 'd' => 'Build the agent against a staging knowledge copy.'],
                    ['t' => 'Testing & Security Review', 'd' => 'Accuracy testing, data-exposure review, red-teaming.'],
                    ['t' => 'Deployment & Handover', 'd' => 'Phased rollout starting in draft mode.'],
                    ['t' => 'Ongoing Support', 'd' => 'Quality reviews and knowledge-base refresh guidance.'],
                ],
                'faq' => ['Will customers know they are talking to AI? — Yes. We recommend transparent disclosure, and configure the agent to identify itself.', 'What happens when the agent does not know the answer? — It says so and escalates to your team with full context, rather than guessing.', 'Can it update our ticketing system directly? — Only if you authorize write access. Most deployments start in draft mode and graduate after review.'],
                'tags' => ['ai agents', 'customer support', 'chatbot', 'helpdesk', 'triage'],
                'seo_title' => 'AI Customer Service Agents | AISecurity',
                'seo_description' => 'Knowledge-grounded AI support agents with escalation to humans and draft or authorized ticketing modes.',
            ],
            [
                'category' => 'ai-agent-development',
                'name' => 'AI Sales & Research Agents',
                'slug' => 'ai-sales-research-agents',
                'short_description' => 'Lead research, qualification, and sales drafting assistants that work from permitted sources with human review.',
                'description' => 'Sales teams spend much of their week on research and drafting. A sales support agent can research accounts from permitted sources, score leads against your criteria, draft outreach for human approval, and keep CRM records current. Research agents apply the same pattern to market and document analysis. Every output is a draft until your team approves it — no unsolicited bulk messaging, no deceptive impersonation, no unauthorized scraping.',
                'scope' => 'Lead research, qualification scoring, outreach drafting, proposal assistance, and research summarization using permitted data sources and your CRM.',
                'exclusions' => 'Spam or unsolicited bulk messaging; scraping in violation of terms of service; impersonation of real people; fully autonomous outreach.',
                'complexity_level' => 'standard',
                'estimated_completion' => '2-4 weeks depending on scope',
                'price_type' => 'custom_quote',
                'starting_price' => 0,
                'allows_custom_quote' => true,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
                'deliverables' => ['Lead qualification criteria configuration', 'Research and drafting agent setup', 'CRM integration (read or authorized write)', 'Consent and compliance checklist', 'Team training on review workflows'],
                'features' => ['Permitted-source research only', 'Human approval before any outreach', 'CRM record assistance', 'Activity logging for audit'],
                'process_steps' => [
                    ['t' => 'Discovery', 'd' => 'Define ideal customer profile and sales workflow.'],
                    ['t' => 'Technical Assessment', 'd' => 'Verify data sources, CRM access, and consent basis.'],
                    ['t' => 'Solution Design', 'd' => 'Design research criteria and review checkpoints.'],
                    ['t' => 'Configuration & Development', 'd' => 'Build prompts, scoring, and CRM connections.'],
                    ['t' => 'Testing & Security Review', 'd' => 'Accuracy checks and data-handling review.'],
                    ['t' => 'Deployment & Handover', 'd' => 'Pilot with one sales workflow, then expand.'],
                    ['t' => 'Ongoing Support', 'd' => 'Criteria tuning and performance reviews.'],
                ],
                'faq' => ['Will this send emails automatically? — No. All outreach stays in draft until a team member reviews and sends it.', 'Which data sources can it use? — Only sources you have the right to use: your CRM, licensed data, and public sources within their terms.', 'How is consent handled? — We document the lawful basis and consent requirements for your region during design.'],
                'tags' => ['ai agents', 'sales', 'lead qualification', 'research', 'crm'],
                'seo_title' => 'AI Sales & Research Agents | AISecurity',
                'seo_description' => 'Lead research and sales drafting agents with human approval and permitted-source compliance.',
            ],
            // ── CATEGORY: AI Setup & Integration ────────────────────────
            [
                'category' => 'ai-setup-integration',
                'name' => 'AI Agent Platform Setup',
                'slug' => 'ai-agent-platform-setup',
                'short_description' => 'Expert installation and configuration of AI agent frameworks, coding assistants, and model providers — verified against current docs.',
                'description' => 'The AI tooling landscape changes monthly, and setup guides go stale fast. We install and configure the platforms you have chosen — open-source agent frameworks, AI coding assistants, Claude Code development workflows, workflow orchestration tools, or approved cloud AI platforms — verifying each step against current documentation. You receive a working environment, secure credential handling, and documentation your team can actually follow.',
                'scope' => 'Environment assessment, installation, model/provider configuration, secure credential setup, tool and permission configuration, testing, documentation, and handover training.',
                'exclusions' => 'Features a platform does not actually support; license costs for commercial platforms; hardware procurement (assessed separately).',
                'complexity_level' => 'standard',
                'estimated_completion' => '3-10 business days per platform',
                'price_type' => 'custom_quote',
                'starting_price' => 0,
                'allows_custom_quote' => true,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
                'deliverables' => ['Environment compatibility assessment', 'Installed and configured platform', 'Secure credential and permission setup', 'Connectivity and smoke-test evidence', 'Setup documentation and handover session'],
                'features' => ['Verified against current documentation', 'Claude Code workflow configuration', 'Open-source framework setup', 'Cloud and local deployment options'],
                'process_steps' => [
                    ['t' => 'Discovery', 'd' => 'Confirm goals, platforms, and team skill levels.'],
                    ['t' => 'Technical Assessment', 'd' => 'Check OS, hardware, network, and licensing prerequisites.'],
                    ['t' => 'Solution Design', 'd' => 'Select versions, models, and configuration approach.'],
                    ['t' => 'Configuration & Development', 'd' => 'Install, configure, and connect tools and APIs.'],
                    ['t' => 'Testing & Security Review', 'd' => 'Verify functionality and credential handling.'],
                    ['t' => 'Deployment & Handover', 'd' => 'Handover with documentation and training.'],
                    ['t' => 'Ongoing Support', 'd' => 'Troubleshooting and update assistance.'],
                ],
                'faq' => ['Which platforms do you support? — Open-source agent frameworks, Claude Code workflows, major coding assistants, Ollama-compatible local models, and approved cloud AI platforms — verified per engagement.', 'Do you guarantee a specific feature works? — Only after verification. If a platform cannot do what you need, we tell you during assessment, not after installation.', 'Will our API keys be safe? — Credentials are stored in your systems using secure practices, never committed to source control or left in chat logs.'],
                'tags' => ['ai setup', 'installation', 'claude code', 'coding assistants', 'configuration'],
                'seo_title' => 'AI Agent Platform Setup & Configuration | AISecurity',
                'seo_description' => 'Verified installation and configuration of AI agent frameworks, coding assistants, and model providers.',
            ],
            [
                'category' => 'ai-setup-integration',
                'name' => 'AI Coding Assistant Configuration',
                'slug' => 'ai-coding-assistant-configuration',
                'short_description' => 'Repository-aware AI development workflows: coding standards, project instructions, testing, and secure CI integration.',
                'description' => 'An AI coding assistant is only as good as its context. We configure repository-aware workflows — project instructions, coding standards, test commands, and review checkpoints — so the assistant works the way your team works. Includes model provider configuration, secure key management, and CI/CD integration where appropriate, plus training so developers review AI output critically rather than accepting it blindly.',
                'scope' => 'Coding assistant setup, provider and routing configuration, project instruction files, testing and review workflows, documentation generation, and team training.',
                'exclusions' => 'Guarantees of defect-free generated code; exposure of secrets in prompts or repositories; bypassing your existing code review process.',
                'complexity_level' => 'standard',
                'estimated_completion' => '3-7 business days',
                'price_type' => 'custom_quote',
                'starting_price' => 0,
                'allows_custom_quote' => true,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 2,
                'deliverables' => ['Configured coding assistant environment', 'Project instruction and standards files', 'Secure provider and key configuration', 'Test and review workflow documentation', 'Developer training session'],
                'features' => ['Repository-aware context setup', 'Model provider and routing guidance', 'Automated testing workflows', 'Secure development practices enforced'],
                'process_steps' => [
                    ['t' => 'Discovery', 'd' => 'Review stack, repositories, and team workflow.'],
                    ['t' => 'Technical Assessment', 'd' => 'Check IDE, provider, and CI compatibility.'],
                    ['t' => 'Solution Design', 'd' => 'Design instruction files and review gates.'],
                    ['t' => 'Configuration & Development', 'd' => 'Configure assistant, providers, and hooks.'],
                    ['t' => 'Testing & Security Review', 'd' => 'Verify secret handling and output quality.'],
                    ['t' => 'Deployment & Handover', 'd' => 'Roll out with team training.'],
                    ['t' => 'Ongoing Support', 'd' => 'Tune instructions as the codebase evolves.'],
                ],
                'faq' => ['What is the difference between an assistant, a provider, and routing? — The assistant is the tool your developers use; the provider supplies the model; routing selects models per task where supported.', 'Will AI-generated code be secure? — It still needs human review and automated checks. We configure both into the workflow.', 'Do you store our code anywhere? — No. Configuration happens in your repositories and accounts under your control.'],
                'tags' => ['ai setup', 'coding assistants', 'claude code', 'devops', 'ci-cd'],
                'seo_title' => 'AI Coding Assistant Configuration | AISecurity',
                'seo_description' => 'Repository-aware AI development workflows with secure provider setup and review checkpoints.',
            ],
            // ── CATEGORY: Local & Private AI ────────────────────────────
            [
                'category' => 'local-private-ai',
                'name' => 'Local & Private AI Deployment',
                'slug' => 'local-private-ai-deployment',
                'short_description' => 'Self-hosted models, private knowledge assistants, and RAG pipelines on your own infrastructure — with honest hardware guidance.',
                'description' => 'When data control matters, running models on your own infrastructure is the answer — but local inference improves control, not automatic privacy or security. We assess your hardware honestly (GPU and CPU paths), select suitably licensed models, deploy private chat interfaces and retrieval-augmented generation over your documents, and configure access control, logging, backups, and updates. If your hardware cannot run what you need, we say so before you spend anything.',
                'scope' => 'Hardware assessment, model selection and tuning, local deployment (Ollama-compatible and similar), private RAG assistants, access control, logging, backup planning, and maintenance.',
                'exclusions' => 'Guarantees that local deployment equals compliance; running models beyond hardware capacity; unlicensed model redistribution.',
                'complexity_level' => 'advanced',
                'estimated_completion' => '1-4 weeks depending on hardware',
                'price_type' => 'custom_quote',
                'starting_price' => 0,
                'allows_custom_quote' => true,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
                'deliverables' => ['Hardware compatibility assessment report', 'Deployed local model environment', 'Private document search and chat interface', 'Access control and logging configuration', 'Backup, update, and maintenance plan'],
                'features' => ['GPU and CPU deployment paths', 'RAG over your private documents', 'Internal-only access controls', 'No data leaves your infrastructure by design'],
                'process_steps' => [
                    ['t' => 'Discovery', 'd' => 'Define use cases and data-control requirements.'],
                    ['t' => 'Technical Assessment', 'd' => 'Benchmark hardware; verify model licenses and fit.'],
                    ['t' => 'Solution Design', 'd' => 'Select models, architecture, and access model.'],
                    ['t' => 'Configuration & Development', 'd' => 'Deploy models, RAG pipeline, and interfaces.'],
                    ['t' => 'Testing & Security Review', 'd' => 'Quality, isolation, and access-control testing.'],
                    ['t' => 'Deployment & Handover', 'd' => 'Production handover with admin training.'],
                    ['t' => 'Ongoing Support', 'd' => 'Model updates and performance tuning.'],
                ],
                'faq' => ['Does local AI guarantee privacy? — It improves data control, but privacy still depends on access control, logging hygiene, and network isolation — all of which we configure.', 'What hardware do we need? — It depends on the model. We assess your GPUs/CPUs and RAM honestly, including CPU-only options where suitable.', 'Which model licenses apply? — We verify each model license for commercial use before deployment and document the obligations.'],
                'tags' => ['local ai', 'private ai', 'ollama', 'rag', 'self-hosted'],
                'seo_title' => 'Local & Private AI Deployment | AISecurity',
                'seo_description' => 'Self-hosted models and private RAG assistants on your infrastructure, with honest hardware assessment.',
            ],
            // ── CATEGORY: AI Automation & Managed AI ────────────────────
            [
                'category' => 'ai-automation-managed',
                'name' => 'Business Process Automation with AI',
                'slug' => 'business-process-automation-ai',
                'short_description' => 'Documented AI workflows for email, CRM, scheduling, and reporting — every consequential step behind human approval.',
                'description' => 'Email classification, enquiry processing, CRM updates, appointment scheduling, invoice assistance, report generation — these workflows follow a pattern we document explicitly: trigger, inputs, AI processing, tools used, permissions, human approval, final action, and logging. High-impact or irreversible actions always require a person. The result is automation you can audit, not a black box you have to trust.',
                'scope' => 'Workflow analysis, AI-assisted automation design, approved API integrations, approval-gate configuration, logging, testing, and staff training.',
                'exclusions' => 'Unsupervised irreversible actions; integrations without official APIs; automation of decisions requiring regulated human judgment.',
                'complexity_level' => 'standard',
                'estimated_completion' => '2-5 weeks per workflow set',
                'price_type' => 'custom_quote',
                'starting_price' => 0,
                'allows_custom_quote' => true,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
                'deliverables' => ['Documented workflow specifications (trigger to log)', 'Configured automation with approval gates', 'Integration and permission setup', 'Error-handling and rollback procedures', 'Staff training and runbooks'],
                'features' => ['Explicit trigger-to-log documentation', 'Human approval for consequential steps', 'Approved API integrations only', 'Full error and exception logging'],
                'process_steps' => [
                    ['t' => 'Discovery', 'd' => 'Select and prioritize candidate workflows.'],
                    ['t' => 'Technical Assessment', 'd' => 'Verify data access, APIs, and edge cases.'],
                    ['t' => 'Solution Design', 'd' => 'Specify each workflow step and approval gate.'],
                    ['t' => 'Configuration & Development', 'd' => 'Build automations in staging first.'],
                    ['t' => 'Testing & Security Review', 'd' => 'Failure-mode testing and permission review.'],
                    ['t' => 'Deployment & Handover', 'd' => 'Phased activation with monitoring.'],
                    ['t' => 'Ongoing Support', 'd' => 'Refinement as volumes and exceptions evolve.'],
                ],
                'faq' => ['Which workflows are good candidates? — High-volume, rules-based work with clear inputs: triage, extraction, drafting, scheduling, and reporting.', 'What happens when the automation fails? — Every workflow has defined error handling: retry, queue for human review, and alerting. Nothing fails silently.', 'Can it write directly to our systems? — Only steps you authorize. Consequential actions pause for human approval by design.'],
                'tags' => ['automation', 'ai agents', 'crm', 'workflow', 'integration'],
                'seo_title' => 'Business Process Automation with AI | AISecurity',
                'seo_description' => 'Auditable AI automation with human approval gates, documented from trigger to log.',
            ],
            [
                'category' => 'ai-automation-managed',
                'name' => 'AI Security Hardening & Review',
                'slug' => 'ai-security-hardening',
                'short_description' => 'Permission reviews, prompt-injection assessment, secret management, and audit logging for your AI deployments.',
                'description' => 'AI systems introduce new attack surface: over-permissioned tools, prompt injection, sensitive data in prompts and logs, and unreviewed third-party integrations. We review your AI deployment the way we review any infrastructure — tool permissions, data flows, secret handling, logging, and human gates — and test AI-enabled applications within an explicitly authorized scope. Model guardrails alone are not authorization, and we make sure your application does not treat them as such.',
                'scope' => 'AI security configuration review, tool permission audit, prompt-injection risk assessment, data-exposure review, logging configuration, and authorized testing of AI applications.',
                'exclusions' => 'Testing systems outside written authorization; guarantees of invulnerability; replacement of application-level authorization with model guardrails.',
                'complexity_level' => 'advanced',
                'estimated_completion' => '1-3 weeks depending on scope',
                'price_type' => 'custom_quote',
                'starting_price' => 0,
                'allows_custom_quote' => true,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 2,
                'deliverables' => ['AI threat-model document', 'Permission and data-flow findings report', 'Remediation roadmap with severity ratings', 'Logging and approval-gate configuration', 'Retest and verification evidence'],
                'features' => ['Tool permission least-privilege review', 'Prompt-injection risk assessment', 'Secret and credential handling review', 'Authorized-scope testing only'],
                'process_steps' => [
                    ['t' => 'Discovery', 'd' => 'Inventory AI tools, models, and data flows.'],
                    ['t' => 'Technical Assessment', 'd' => 'Agree written authorization and test scope.'],
                    ['t' => 'Solution Design', 'd' => 'Plan review depth and test scenarios.'],
                    ['t' => 'Configuration & Development', 'd' => 'Apply hardening configurations.'],
                    ['t' => 'Testing & Security Review', 'd' => 'Authorized testing and finding validation.'],
                    ['t' => 'Deployment & Handover', 'd' => 'Findings report with remediation guidance.'],
                    ['t' => 'Ongoing Support', 'd' => 'Retests and periodic reviews.'],
                ],
                'faq' => ['Are model guardrails not enough? — Correct. Guardrails help, but authorization, validation, and isolation must live in your application — we verify they do.', 'Will you attack our production AI? — Only within explicitly authorized scope, typically starting in staging. No testing begins without written authorization.', 'What standards do you align with? — OWASP guidance for LLM applications, plus our standard infrastructure review practices.'],
                'tags' => ['ai security', 'hardening', 'prompt injection', 'audit', 'penetration testing'],
                'seo_title' => 'AI Security Hardening & Review | AISecurity',
                'seo_description' => 'Permission reviews, prompt-injection assessment, and authorized testing for AI deployments.',
            ],
            [
                'category' => 'ai-automation-managed',
                'name' => 'Managed AI Support & Maintenance',
                'slug' => 'managed-ai-support',
                'short_description' => 'Health checks, updates, cost optimization, and troubleshooting for your deployed AI environments under a clear scope.',
                'description' => 'Deployed AI needs care: dependencies change, model versions retire, costs drift, integrations break, and prompts decay as data evolves. Our managed AI support covers health checks, updates, troubleshooting, usage and cost optimization, and security reviews under a clearly written scope — what is monitored, what is excluded, response expectations, and escalation paths. We do not claim 24/7 monitoring; we commit to what is in the agreement and report against it.',
                'scope' => 'Scheduled health checks, dependency and model updates, integration troubleshooting, performance and cost reviews, backup verification, documentation, and defined support response.',
                'exclusions' => 'Unclaimed 24/7 monitoring; out-of-scope rebuilds; third-party outages outside our control (we coordinate, not absorb).',
                'complexity_level' => 'standard',
                'estimated_completion' => 'Ongoing agreement',
                'price_type' => 'custom_quote',
                'starting_price' => 0,
                'allows_custom_quote' => true,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
                'deliverables' => ['Written support scope and response expectations', 'Scheduled health-check reports', 'Update and change log', 'Cost and usage optimization reviews', 'Quarterly security review summary'],
                'features' => ['Defined scope — monitored vs excluded', 'Model migration assistance', 'Usage and cost optimization', 'Documented change management'],
                'process_steps' => [
                    ['t' => 'Discovery', 'd' => 'Inventory deployed AI and pain points.'],
                    ['t' => 'Technical Assessment', 'd' => 'Baseline health, versions, costs, and risks.'],
                    ['t' => 'Solution Design', 'd' => 'Draft support scope and schedules.'],
                    ['t' => 'Configuration & Development', 'd' => 'Set up monitoring and access.'],
                    ['t' => 'Testing & Security Review', 'd' => 'Verify alerting and backup recovery.'],
                    ['t' => 'Deployment & Handover', 'd' => 'Agreement start with reporting cadence.'],
                    ['t' => 'Ongoing Support', 'd' => 'Operate, report, and improve quarterly.'],
                ],
                'faq' => ['Do you monitor our AI 24/7? — We commit only to what the agreement states: scheduled checks and defined response times. Anything claiming more without staffing is marketing.', 'What is excluded? — Rebuilds, new features, and third-party outages are out of scope but can be quoted separately.', 'Can we leave the agreement? — Yes, with notice as stated, and you keep all documentation and configurations.'],
                'tags' => ['managed ai', 'maintenance', 'monitoring', 'support', 'optimization'],
                'seo_title' => 'Managed AI Support & Maintenance | AISecurity',
                'seo_description' => 'Maintained AI environments with defined scope, health checks, and cost optimization.',
            ],
        ];

        $countries = Country::whereIn('code', ['UK', 'US', 'BD'])->get();
        if ($countries->isEmpty()) {
            $countries = Country::take(3)->get();
        }

        foreach ($services as $sd) {
            $cat = $cats[$sd['category']];
            unset($sd['category']);
            $sd['category_id'] = $cat->id;
            if (! isset($sd['slug'])) {
                $sd['slug'] = Str::slug($sd['name']);
            }
            if (! isset($sd['full_description'])) {
                $sd['full_description'] = $sd['description'];
            }

            $service = Service::updateOrCreate(['slug' => $sd['slug']], $sd);

            foreach ($countries as $country) {
                ServiceCountryPrice::firstOrCreate(
                    ['service_id' => $service->id, 'country_id' => $country->id],
                    ['pricing_type' => 'custom_quote', 'price' => 0, 'is_active' => true]
                );
            }
        }
    }
}
