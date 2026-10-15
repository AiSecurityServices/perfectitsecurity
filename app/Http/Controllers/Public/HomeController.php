<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CaseStudy;
use App\Models\KbArticle;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\ServiceCategory;

class HomeController extends Controller
{
    public function index()
    {
        $featuredServices = Service::where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->limit(8)
            ->get();

        $latestPosts = BlogPost::published()->with('author', 'category')->latest('published_at')->limit(3)->get();

        // Real catalogue data only — no invented statistics anywhere on the page.
        $serviceCategories = ServiceCategory::where('is_active', true)
            ->with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
            ->orderBy('sort_order')
            ->get();

        $portfolioItems = PortfolioItem::published()
            ->orderBy('sort_order')->latest('published_at')->limit(6)->get();

        $caseStudies = CaseStudy::published()
            ->orderBy('sort_order')->latest('published_at')->limit(3)->get();

        $kbArticles = KbArticle::published()->where('visibility', 'public')
            ->orderByDesc('views_count')->limit(6)->get();

        $kbCategories = \App\Models\KbCategory::where('is_active', true)
            ->orderBy('sort_order')->limit(8)->get();

        // AISecurity spotlight: eight AI agent cards in reference order.
        // Slugs map to the real catalogue (AiAgentServicesSeeder); missing
        // rows are simply skipped so cards never link to dead pages.
        $aiSpotlightSlugs = [
            'ai-customer-service-agents',
            'ai-sales-research-agents',
            'ai-coding-assistant-configuration',
            'business-process-automation-ai',
            'local-private-ai-deployment',
            'ai-security-hardening',
            'ai-agent-platform-setup',
            'managed-ai-support',
        ];
        $aiSpotlight = Service::where('is_active', true)
            ->whereIn('slug', $aiSpotlightSlugs)
            ->with('category')
            ->get()
            ->sortBy(fn ($s) => array_search($s->slug, $aiSpotlightSlugs, true))
            ->values();

        return view('public.home', compact(
            'featuredServices', 'latestPosts', 'serviceCategories',
            'portfolioItems', 'caseStudies', 'kbArticles', 'kbCategories',
            'aiSpotlight'
        ));
    }
}
