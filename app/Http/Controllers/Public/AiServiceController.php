<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;

class AiServiceController extends Controller
{
    /**
     * Slugs that constitute the first-class AI Agent Services division.
     * Detail pages are scoped to these categories; everything else stays
     * on the canonical services.show route.
     */
    public const AI_CATEGORY_SLUGS = [
        'ai-agent-development',
        'ai-setup-integration',
        'local-private-ai',
        'ai-automation-managed',
    ];

    public function index()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->whereIn('slug', self::AI_CATEGORY_SLUGS)
            ->with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $featured = Service::where('is_active', true)
            ->where('is_featured', true)
            ->whereHas('category', fn ($q) => $q->whereIn('slug', self::AI_CATEGORY_SLUGS))
            ->with('category')
            ->orderBy('sort_order')
            ->get();

        $allServices = Service::where('is_active', true)
            ->whereHas('category', fn ($q) => $q->whereIn('slug', self::AI_CATEGORY_SLUGS))
            ->with('category')
            ->orderBy('sort_order')
            ->get();

        // Real filter facets derived from service tags (no decorative buttons).
        $facets = $allServices->flatMap(fn ($s) => $s->tags ?? [])
            ->map(fn ($t) => trim((string) $t))
            ->reject(fn ($t) => $t === '')
            ->unique()
            ->sort()
            ->values();

        return view('public.ai.index', compact('categories', 'featured', 'allServices', 'facets'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->whereHas('category', fn ($q) => $q->whereIn('slug', self::AI_CATEGORY_SLUGS))
            ->with('category', 'countryPrices.country')
            ->firstOrFail();

        $related = Service::where('is_active', true)
            ->where('id', '!=', $service->id)
            ->whereHas('category', fn ($q) => $q->whereIn('slug', self::AI_CATEGORY_SLUGS))
            ->with('category')
            ->orderByRaw("CASE WHEN category_id = ? THEN 0 ELSE 1 END", [$service->category_id])
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('public.ai.show', compact('service', 'related'));
    }
}
