<?php

namespace Tests\Feature;

use App\Services\Ai\AiProviderFactory;
use App\Services\Ai\AnthropicProvider;
use App\Services\Ai\GeminiProvider;
use App\Services\Ai\OpenAiCompatibleProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * New AI providers (Anthropic, Gemini, Azure/OpenAI-compatible):
 * wire-format mapping, cost accounting, sanitized errors, factory wiring.
 * All HTTP faked — no real keys used.
 */
class AiAdditionalProvidersTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        AiProviderFactory::reset();
        parent::tearDown();
    }

    /** @test */
    public function anthropic_maps_message_response_with_cost()
    {
        Http::fake(['https://api.anthropic.com/v1/messages' => Http::response([
            'content' => [['type' => 'text', 'text' => 'We can help with that.']],
            'usage' => ['input_tokens' => 100, 'output_tokens' => 10],
        ], 200)]);

        $result = (new AnthropicProvider())->chat([
            ['role' => 'system', 'content' => 'You are helpful.'],
            ['role' => 'user', 'content' => 'Hi'],
        ]);

        $this->assertEquals('We can help with that.', $result['content']);
        $this->assertEquals(110, $result['tokens_used']);
        $this->assertGreaterThan(0, $result['cost']);
        $this->assertEquals('anthropic', (new AnthropicProvider())->getName());

        // System prompt must travel as `system`, not as a message turn.
        $sent = Http::recorded()[0][0] ?? null;
        $this->assertNotNull($sent);
        $this->assertEquals('You are helpful.', $sent['system'] ?? null);
        $this->assertCount(1, $sent['messages'] ?? []);
    }

    /** @test */
    public function anthropic_failure_throws_without_leaking_server_body()
    {
        Http::fake(['https://api.anthropic.com/v1/messages' => Http::response(
            '{"error":{"message":"bad key secret-xyz-123"}}', 401
        )]);

        try {
            (new AnthropicProvider())->chat([['role' => 'user', 'content' => 'Hi']]);
            $this->fail('expected RuntimeException');
        } catch (\RuntimeException $e) {
            $this->assertStringNotContainsString('secret-xyz-123', $e->getMessage());
            $this->assertEquals('auth_error', $e->getCategory());
        }
    }

    /** @test */
    public function gemini_maps_generate_content_response_with_cost()
    {
        Http::fake(['https://generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => ['parts' => [['text' => 'Hello from Gemini.']]],
            ]],
            'usageMetadata' => ['promptTokenCount' => 50, 'candidatesTokenCount' => 5],
        ], 200)]);

        config(['services.ai.gemini.api_key' => 'test-key']);
        $result = (new GeminiProvider())->chat([['role' => 'user', 'content' => 'Hi']]);

        $this->assertEquals('Hello from Gemini.', $result['content']);
        $this->assertEquals(55, $result['tokens_used']);
        $this->assertGreaterThan(0, $result['cost']);
        $this->assertEquals('gemini', (new GeminiProvider())->getName());
    }

    /** @test */
    public function gemini_rate_limit_maps_to_category()
    {
        Http::fake(['https://generativelanguage.googleapis.com/*' => Http::response('{}', 429)]);

        try {
            (new GeminiProvider())->chat([['role' => 'user', 'content' => 'Hi']]);
            $this->fail('expected RuntimeException');
        } catch (\RuntimeException $e) {
            $this->assertEquals('rate_limited', $e->getCategory());
        }
    }

    /** @test */
    public function azure_compatible_requires_key_and_endpoint()
    {
        config(['services.ai.azure.api_key' => 'test-key', 'services.ai.azure.base_url' => '']);
        $this->assertFalse((new OpenAiCompatibleProvider())->isAvailable());

        config(['services.ai.azure.base_url' => 'https://example.openai.azure.com/openai/deployments/x']);
        $this->assertTrue((new OpenAiCompatibleProvider())->isAvailable());

        config(['services.ai.azure.api_key' => '']);
        $this->assertFalse((new OpenAiCompatibleProvider())->isAvailable());
    }

    /** @test */
    public function factory_maps_new_providers_and_health_shape()
    {
        foreach (['anthropic', 'gemini', 'azure'] as $name) {
            $provider = AiProviderFactory::makeNamed($name);
            $this->assertEquals($name, $provider->getName());

            $health = $provider->healthCheck();
            foreach (['provider', 'reachable', 'model', 'model_available', 'latency_ms', 'checked_at', 'error'] as $key) {
                $this->assertArrayHasKey($key, $health);
            }
            // Health payloads must never carry secrets.
            $this->assertStringNotContainsStringIgnoringCase('sk-', json_encode($health));
        }

        $caps = AiProviderFactory::capabilities();
        foreach (['ollama', 'openai', 'openrouter', 'anthropic', 'gemini', 'azure'] as $name) {
            $this->assertArrayHasKey($name, $caps);
        }
    }

    /** @test */
    public function availability_tracks_key_presence()
    {
        config(['services.ai.anthropic.api_key' => 'test-key']);
        $this->assertTrue((new AnthropicProvider())->isAvailable());
        config(['services.ai.anthropic.api_key' => '']);
        $this->assertFalse((new AnthropicProvider())->isAvailable());

        config(['services.ai.gemini.api_key' => 'test-key']);
        $this->assertTrue((new GeminiProvider())->isAvailable());
        config(['services.ai.gemini.api_key' => '']);
        $this->assertFalse((new GeminiProvider())->isAvailable());
    }
}
