<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Anthropic provider (Messages API).
 *
 * Backend-only: the key never leaves the server; failures surface as
 * categorized exceptions with no prompt content, keys, or raw bodies.
 * System prompts are extracted from a leading system message, per the
 * Anthropic Messages API contract.
 */
class AnthropicProvider implements AiProviderInterface
{
    private string $apiKey;

    private string $model;

    private string $baseUrl;

    private int $timeout;

    private const PRICING = [
        'claude-sonnet-4-20250514' => ['input' => 0.003, 'output' => 0.015],
        'claude-3-5-sonnet-20241022' => ['input' => 0.003, 'output' => 0.015],
        'claude-3-5-haiku-20241022' => ['input' => 0.0008, 'output' => 0.004],
    ];

    private const FALLBACK_PRICING = ['input' => 0.003, 'output' => 0.015];

    private const API_VERSION = '2023-06-01';

    public function __construct()
    {
        $this->apiKey = (string) config('services.ai.anthropic.api_key', env('ANTHROPIC_API_KEY', ''));
        $this->model = (string) config('services.ai.anthropic.model', env('ANTHROPIC_MODEL', 'claude-sonnet-4-20250514'));
        $this->baseUrl = rtrim((string) config('services.ai.anthropic.base_url', 'https://api.anthropic.com/v1'), '/');
        $this->timeout = (int) config('services.ai.anthropic.timeout', 60);
    }

    public function chat(array $messages, array $options = []): array
    {
        $model = $options['model'] ?? $this->model;
        $maxTokens = $options['max_tokens'] ?? 1024;
        $temperature = $options['temperature'] ?? 0.7;

        // Anthropic takes system separately; roles are user/assistant only.
        $system = null;
        $converted = [];
        foreach ($messages as $m) {
            $role = $m['role'] ?? 'user';
            $content = (string) ($m['content'] ?? '');
            if ($role === 'system' && $system === null) {
                $system = $content;
                continue;
            }
            $converted[] = ['role' => $role === 'assistant' ? 'assistant' : 'user', 'content' => $content];
        }

        $payload = [
            'model' => $model,
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
            'messages' => $converted,
        ];
        if ($system !== null && $system !== '') {
            $payload['system'] = $system;
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => self::API_VERSION,
                'Content-Type' => 'application/json',
            ])->connectTimeout(10)->timeout($this->timeout)->post("{$this->baseUrl}/messages", $payload);

            if ($response->failed()) {
                Log::warning('Anthropic chat API error', ['status' => $response->status()]);
                throw AiProviderException::fromStatus($response->status());
            }

            $data = $response->json();
            $blocks = $data['content'] ?? [];
            $content = '';
            foreach ($blocks as $block) {
                if (($block['type'] ?? '') === 'text') {
                    $content .= $block['text'] ?? '';
                }
            }
            $usage = $data['usage'] ?? [];
            $inputTokens = (int) ($usage['input_tokens'] ?? 0);
            $outputTokens = (int) ($usage['output_tokens'] ?? 0);

            return [
                'content' => $content,
                'tokens_used' => $inputTokens + $outputTokens,
                'input_tokens' => $inputTokens,
                'output_tokens' => $outputTokens,
                'model' => $model,
                'cost' => $this->calculateCost($model, $inputTokens, $outputTokens),
            ];
        } catch (AiProviderException $e) {
            throw $e;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('Anthropic connection failed', ['category' => AiProviderException::CONNECTION_ERROR]);
            throw AiProviderException::connectionError();
        } catch (\Throwable $e) {
            $category = stripos($e->getMessage(), 'timed out') !== false || stripos($e->getMessage(), 'timeout') !== false
                ? AiProviderException::TIMEOUT
                : AiProviderException::UNKNOWN;
            Log::warning('Anthropic request failed', ['category' => $category]);
            throw new AiProviderException($category);
        }
    }

    public function getName(): string
    {
        return 'anthropic';
    }

    public function isAvailable(): bool
    {
        return ! empty($this->apiKey);
    }

    public function healthCheck(): array
    {
        // No network call: must not burn customer budget on a status page.
        $configured = ! empty($this->apiKey);

        return [
            'provider' => 'anthropic',
            'reachable' => $configured,
            'model' => $this->model,
            'model_available' => $configured,
            'models' => [],
            'latency_ms' => null,
            'checked_at' => now()->toDateTimeString(),
            'error' => $configured ? null : 'missing_api_key',
        ];
    }

    private function calculateCost(string $model, int $inputTokens, int $outputTokens): float
    {
        $pricing = self::PRICING[$model] ?? self::FALLBACK_PRICING;

        return (($inputTokens * $pricing['input']) + ($outputTokens * $pricing['output'])) / 1000;
    }
}
