<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->with(['services' => function ($q) {
                $q->where('is_active', true)
                  ->with('countryPrices.country')
                  ->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // Support ?category=slug deep-links from navigation dropdowns.
        $activeCategory = null;
        if (request()->filled('category')) {
            $activeCategory = ServiceCategory::where('slug', request()->input('category'))
                ->where('is_active', true)
                ->first();
            if ($activeCategory) {
                $categories = $categories->filter(fn ($c) => $c->id === $activeCategory->id)->values();
            }
        }

        $featured = Service::where('is_active', true)
            ->where('is_featured', true)
            ->with('category', 'countryPrices.country')
            ->orderBy('sort_order')
            ->get();

        $countries = Country::where('is_active', true)->orderBy('sort_order')->get();

        return view('public.services.index', compact('categories', 'featured', 'countries', 'activeCategory'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->with('category', 'countryPrices.country')
            ->firstOrFail();

        $related = Service::where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->with('countryPrices.country')
            ->limit(3)
            ->get();

        $countries = Country::where('is_active', true)->orderBy('sort_order')->get();

        return view('public.services.show', compact('service', 'related', 'countries'));
    }
}
