<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Generic OpenAI-compatible provider for managed endpoints that speak the
 * OpenAI chat-completions wire format: Azure OpenAI, AWS Bedrock via an
 * OpenAI-compatible gateway, or self-hosted OpenAI-compatible servers
 * (vLLM, TGI, LM Studio server mode).
 *
 * Backend-only: the key never leaves the server; failures surface as
 * categorized exceptions with no prompt content, keys, or raw bodies.
 */
class OpenAiCompatibleProvider implements AiProviderInterface
{
    private string $apiKey;

    private string $model;

    private string $baseUrl;

    private int $timeout;

    private const FALLBACK_PRICING = ['input' => 0.001, 'output' => 0.003];

    public function __construct()
    {
        $this->apiKey = (string) config('services.ai.azure.api_key', env('AZURE_OPENAI_API_KEY', ''));
        $this->model = (string) config('services.ai.azure.model', env('AZURE_OPENAI_MODEL', ''));
        $this->baseUrl = rtrim((string) config('services.ai.azure.base_url', env('AZURE_OPENAI_BASE_URL', '')), '/');
        $this->timeout = (int) config('services.ai.azure.timeout', 60);
    }

    public function chat(array $messages, array $options = []): array
    {
        $model = $options['model'] ?? $this->model;
        $temperature = $options['temperature'] ?? 0.7;
        $maxTokens = $options['max_tokens'] ?? 1024;

        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->connectTimeout(10)->timeout($this->timeout)->post("{$this->baseUrl}/chat/completions", [
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if ($response->failed()) {
                Log::warning('OpenAI-compatible chat API error', ['status' => $response->status()]);
                throw AiProviderException::fromStatus($response->status());
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '';
            $usage = $data['usage'] ?? [];
            $inputTokens = (int) ($usage['prompt_tokens'] ?? 0);
            $outputTokens = (int) ($usage['completion_tokens'] ?? 0);

            return [
                'content' => $content,
                'tokens_used' => $inputTokens + $outputTokens,
                'input_tokens' => $inputTokens,
                'output_tokens' => $outputTokens,
                'model' => $model,
                'cost' => $this->calculateCost($inputTokens, $outputTokens),
            ];
        } catch (AiProviderException $e) {
            throw $e;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('OpenAI-compatible connection failed', ['category' => AiProviderException::CONNECTION_ERROR]);
            throw AiProviderException::connectionError();
        } catch (\Throwable $e) {
            $category = stripos($e->getMessage(), 'timed out') !== false || stripos($e->getMessage(), 'timeout') !== false
                ? AiProviderException::TIMEOUT
                : AiProviderException::UNKNOWN;
            Log::warning('OpenAI-compatible request failed', ['category' => $category]);
            throw new AiProviderException($category);
        }
    }

    public function getName(): string
    {
        return 'azure';
    }

    public function isAvailable(): bool
    {
        return ! empty($this->apiKey) && ! empty($this->baseUrl);
    }

    public function healthCheck(): array
    {
        // No network call: must not burn customer budget on a status page.
        $configured = ! empty($this->apiKey) && ! empty($this->baseUrl);

        return [
            'provider' => 'azure',
            'reachable' => $configured,
            'model' => $this->model,
            'model_available' => $configured,
            'models' => [],
            'latency_ms' => null,
            'checked_at' => now()->toDateTimeString(),
            'error' => $configured ? null : 'missing_configuration',
        ];
    }

    private function calculateCost(int $inputTokens, int $outputTokens): float
    {
        return (($inputTokens * self::FALLBACK_PRICING['input']) + ($outputTokens * self::FALLBACK_PRICING['output'])) / 1000;
    }
}
