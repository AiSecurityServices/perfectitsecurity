<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Google Gemini provider (generateContent REST API).
 *
 * Backend-only: the key stays server-side (sent as a query param over TLS
 * per Google's REST contract, never logged); failures surface as
 * categorized exceptions with no prompt content or raw bodies.
 */
class GeminiProvider implements AiProviderInterface
{
    private string $apiKey;

    private string $model;

    private string $baseUrl;

    private int $timeout;

    private const PRICING = [
        'gemini-2.0-flash' => ['input' => 0.0001, 'output' => 0.0004],
        'gemini-2.5-flash' => ['input' => 0.0003, 'output' => 0.0025],
        'gemini-2.5-pro' => ['input' => 0.00125, 'output' => 0.01],
    ];

    private const FALLBACK_PRICING = ['input' => 0.0003, 'output' => 0.0025];

    public function __construct()
    {
        $this->apiKey = (string) config('services.ai.gemini.api_key', env('GEMINI_API_KEY', ''));
        $this->model = (string) config('services.ai.gemini.model', env('GEMINI_MODEL', 'gemini-2.0-flash'));
        $this->baseUrl = rtrim((string) config('services.ai.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');
        $this->timeout = (int) config('services.ai.gemini.timeout', 60);
    }

    public function chat(array $messages, array $options = []): array
    {
        $model = $options['model'] ?? $this->model;

        // Gemini roles: user/model; system messages fold into the first turn.
        $contents = [];
        $systemPrefix = null;
        foreach ($messages as $m) {
            $role = $m['role'] ?? 'user';
            $text = (string) ($m['content'] ?? '');
            if ($role === 'system') {
                $systemPrefix = $systemPrefix === null ? $text : $systemPrefix."\n".$text;
                continue;
            }
            if ($systemPrefix !== null) {
                $text = $systemPrefix."\n\n".$text;
                $systemPrefix = null;
            }
            $contents[] = [
                'role' => $role === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $text]],
            ];
        }

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->connectTimeout(10)->timeout($this->timeout)
                ->post($this->requestUrl($model), [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => $options['temperature'] ?? 0.7,
                        'maxOutputTokens' => $options['max_tokens'] ?? 1024,
                    ],
                ]);

            // Key travels as a query parameter per Google's REST contract
            // (attached inside requestUrl so it never passes through logs).
            if ($response->failed()) {
                Log::warning('Gemini chat API error', ['status' => $response->status()]);
                throw AiProviderException::fromStatus($response->status());
            }

            $data = $response->json();
            $parts = $data['candidates'][0]['content']['parts'] ?? [];
            $content = '';
            foreach ($parts as $part) {
                $content .= $part['text'] ?? '';
            }
            $usage = $data['usageMetadata'] ?? [];
            $inputTokens = (int) ($usage['promptTokenCount'] ?? 0);
            $outputTokens = (int) ($usage['candidatesTokenCount'] ?? 0);

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
            Log::warning('Gemini connection failed', ['category' => AiProviderException::CONNECTION_ERROR]);
            throw AiProviderException::connectionError();
        } catch (\Throwable $e) {
            $category = stripos($e->getMessage(), 'timed out') !== false || stripos($e->getMessage(), 'timeout') !== false
                ? AiProviderException::TIMEOUT
                : AiProviderException::UNKNOWN;
            Log::warning('Gemini request failed', ['category' => $category]);
            throw new AiProviderException($category);
        }
    }

    public function getName(): string
    {
        return 'gemini';
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
            'provider' => 'gemini',
            'reachable' => $configured,
            'model' => $this->model,
            'model_available' => $configured,
            'models' => [],
            'latency_ms' => null,
            'checked_at' => now()->toDateTimeString(),
            'error' => $configured ? null : 'missing_api_key',
        ];
    }

    /**
     * Build the request URL with the API key attached as a query parameter,
     * per Google's REST contract. Kept in one place so the key is never
     * interpolated into logs or error messages.
     */
    public function requestUrl(string $model): string
    {
        return "{$this->baseUrl}/models/{$model}:generateContent?key=".urlencode($this->apiKey);
    }

    private function calculateCost(string $model, int $inputTokens, int $outputTokens): float
    {
        $pricing = self::PRICING[$model] ?? self::FALLBACK_PRICING;

        return (($inputTokens * $pricing['input']) + ($outputTokens * $pricing['output'])) / 1000;
    }
}
