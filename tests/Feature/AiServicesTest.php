<?php

namespace Tests\Feature;

use App\Http\Controllers\Public\AiServiceController;
use Database\Seeders\AiAgentServicesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * AI Agent Services division: catalogue seeding, landing page,
 * scoped detail pages, catalogue category filter, quote integration.
 */
class AiServicesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AiAgentServicesSeeder::class);
    }

    /** @test */
    public function seeder_creates_four_categories_and_nine_services()
    {
        $this->assertEquals(4, \App\Models\ServiceCategory::whereIn('slug', AiServiceController::AI_CATEGORY_SLUGS)->count());
        $this->assertEquals(9, \App\Models\Service::whereHas('category', fn ($q) => $q->whereIn('slug', AiServiceController::AI_CATEGORY_SLUGS))->count());
    }

    /** @test */
    public function seeder_is_idempotent()
    {
        $this->seed(AiAgentServicesSeeder::class);
        $this->assertEquals(4, \App\Models\ServiceCategory::whereIn('slug', AiServiceController::AI_CATEGORY_SLUGS)->count());
        $this->assertEquals(9, \App\Models\Service::whereHas('category', fn ($q) => $q->whereIn('slug', AiServiceController::AI_CATEGORY_SLUGS))->count());
    }

    /** @test */
    public function ai_services_are_custom_proposal_only()
    {
        $priced = \App\Models\Service::whereHas('category', fn ($q) => $q->whereIn('slug', AiServiceController::AI_CATEGORY_SLUGS))
            ->where('price_type', '!=', 'custom_quote')
            ->count();
        $this->assertEquals(0, $priced);
    }

    /** @test */
    public function landing_page_renders_with_catalogue()
    {
        $response = $this->get(route('ai.index'));
        $response->assertOk();
        $response->assertSee('AI Agents, Engineered for Business.', false);
        $response->assertSee(route('ai.show', 'custom-ai-agents'), false);
    }

    /** @test */
    public function ai_detail_page_renders_with_workflow_and_cta()
    {
        $service = \App\Models\Service::where('slug', 'custom-ai-agents')->firstOrFail();
        $response = $this->get(route('ai.show', 'custom-ai-agents'));
        $response->assertOk();
        $response->assertSee($service->name, false);
        $response->assertSee(route('get-quote', ['service_id' => $service->id]), false);
    }

    /** @test */
    public function ai_detail_rejects_non_ai_slugs()
    {
        $cat = \App\Models\ServiceCategory::create([
            'name' => 'Generic IT', 'slug' => 'generic-it-test',
            'description' => 'Test', 'is_active' => true, 'sort_order' => 999,
        ]);
        $service = \App\Models\Service::create([
            'category_id' => $cat->id, 'name' => 'Generic Service', 'slug' => 'generic-service-test',
            'short_description' => 'Test service', 'description' => 'Test',
            'price_type' => 'custom_quote', 'is_active' => true,
        ]);
        $this->get(route('ai.show', $service->slug))->assertNotFound();
        // …but the canonical route still serves it.
        $this->get(route('services.show', $service->slug))->assertOk();
    }

    /** @test */
    public function services_index_supports_category_deep_link()
    {
        $response = $this->get(route('services.index', ['category' => 'ai-agent-development']));
        $response->assertOk();
        $response->assertSee('Custom AI Agents for Business Operations', false);
    }

    /** @test */
    public function quote_form_preselects_ai_service()
    {
        $service = \App\Models\Service::where('slug', 'local-private-ai-deployment')->firstOrFail();
        $response = $this->get(route('get-quote', ['service_id' => $service->id]));
        $response->assertOk();
    }
}
