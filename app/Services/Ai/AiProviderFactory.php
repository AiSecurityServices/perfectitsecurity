<?php

namespace App\Services\Ai;

use App\Models\AiSetting;

class AiProviderFactory
{
    private static ?AiProviderInterface $instance = null;

    public static function make(): AiProviderInterface
    {
        if (self::$instance) {
            return self::$instance;
        }

        $provider = AiSetting::get('ai_provider', config('services.ai.default_provider', 'ollama'));

        self::$instance = self::makeNamed($provider);

        return self::$instance;
    }

    /**
     * Build a provider by explicit name (used for the fallback path).
     * Never cached: the fallback must not silently replace the primary.
     *
     * Supported: ollama (local), openai, openrouter, anthropic, gemini,
     * azure (any OpenAI-compatible endpoint: Azure OpenAI, Bedrock via an
     * OpenAI-compatible gateway, self-hosted vLLM/TGI/LM Studio).
     */
    public static function makeNamed(string $provider): AiProviderInterface
    {
        return match ($provider) {
            'ollama' => new OllamaProvider(),
            'openai' => new OpenAiProvider(),
            'openrouter' => new OpenRouterProvider(),
            'anthropic' => new AnthropicProvider(),
            'gemini' => new GeminiProvider(),
            'azure' => new OpenAiCompatibleProvider(),
            default => new OpenAiProvider(),
        };
    }

    /**
     * Provider capability matrix for admin status screens and docs.
     * No secrets — static capability facts only.
     */
    public static function capabilities(): array
    {
        return [
            'ollama' => ['chat' => true, 'streaming' => false, 'tools' => false, 'images' => false, 'local' => true, 'auth' => 'none (localhost)'],
            'openai' => ['chat' => true, 'streaming' => false, 'tools' => true, 'images' => true, 'local' => false, 'auth' => 'bearer API key'],
            'openrouter' => ['chat' => true, 'streaming' => false, 'tools' => true, 'images' => true, 'local' => false, 'auth' => 'bearer API key'],
            'anthropic' => ['chat' => true, 'streaming' => false, 'tools' => true, 'images' => true, 'local' => false, 'auth' => 'x-api-key header'],
            'gemini' => ['chat' => true, 'streaming' => false, 'tools' => true, 'images' => true, 'local' => false, 'auth' => 'API key (TLS query param)'],
            'azure' => ['chat' => true, 'streaming' => false, 'tools' => true, 'images' => false, 'local' => false, 'auth' => 'api-key / bearer (endpoint-specific)'],
        ];
    }

    public static function reset(): void
    {
        self::$instance = null;
    }
}
