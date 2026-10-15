<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Lead;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Services\BusinessHoursService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function create(Request $request)
    {
        $services = Service::where('is_active', true)->orderBy('name')->get(['id', 'name', 'slug', 'category_id']);
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->get();
        $countries = Country::active()->orderBy('sort_order')->get();

        // Pre-select service when arriving from a service page (?service_id=).
        $selectedServiceId = null;
        if ($request->filled('service_id')) {
            $preselected = Service::where('is_active', true)->find($request->input('service_id'));
            $selectedServiceId = $preselected?->id;
        }

        return view('public.get-quote', compact('services', 'categories', 'countries', 'selectedServiceId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'service_id' => 'nullable|exists:services,id',
            'service_interest' => 'nullable|string|max:100',
            'country_id' => 'nullable|exists:countries,id',
            'budget' => 'nullable|numeric|min:0',
            'budget_range' => 'nullable|string|max:50',
            'timeline' => 'nullable|string|max:50',
            'message' => 'required|string|max:10000',
        ]);

        $country = ! empty($validated['country_id'])
            ? Country::find($validated['country_id'])
            : null;

        $taxRate = app(BusinessHoursService::class)->taxRateForCountry($country);

        // Duplicate-submission guard: identical replays return the original
        // request reference instead of creating a second lead + request.
        $actor = auth()->id() ?? 'guest:'.(string) $request->ip();
        $key = \App\Support\Idempotency::keyForRequest($request, 'public.quote.store', $actor, [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'service_id' => $validated['service_id'] ?? null,
            'message' => mb_substr($validated['message'], 0, 500),
        ]);
        $outcome = \App\Support\Idempotency::run($key, function () use ($validated, $country, $taxRate) {
            $serviceRequest = DB::transaction(function () use ($validated, $country, $taxRate) {
            $serviceRequest = ServiceRequest::create([
                'user_id' => auth()->id(),
                'service_id' => $validated['service_id'] ?? null,
                'country_id' => $country?->id,
                'currency' => $country?->currency_code ?? 'USD',
                'currency_symbol' => $country?->currency_symbol ?? '$',
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company' => $validated['company'] ?? null,
                'subject' => 'Quote Request',
                'service_interest' => $validated['service_interest'] ?? null,
                'requirements' => $validated['message'],
                'budget' => $validated['budget'] ?? null,
                'budget_range' => $validated['budget_range'] ?? null,
                'timeline' => $validated['timeline'] ?? null,
                'lead_source' => 'get-quote',
                'status' => 'new',
                'review_status' => 'new',
                'priority' => 'medium',
            ]);

            Lead::create([
                'service_request_id' => $serviceRequest->id,
                'customer_id' => auth()->id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company_name' => $validated['company'] ?? null,
                'source' => 'get-quote',
                'status' => 'new',
                'priority' => 'medium',
                'estimated_value' => $validated['budget'] ?? null,
                'currency' => $country?->currency_code ?? 'USD',
                'country_id' => $country?->id,
                'notes' => "Tax preset: {$taxRate}%\n\n".$validated['message'],
            ]);

            return $serviceRequest;
        });

            // Staff alert: quote requests must reach sales (in-app notification).
            $staff = \App\Models\User::whereIn('role', ['super_admin', 'admin', 'support_manager', 'sales_agent'])
                ->where('is_active', true)->limit(10)->get();
            foreach ($staff as $member) {
                \App\Services\ServiceTrackingService::notify(
                    (int) $member->id, 'quote_request',
                    'New quote request',
                    "{$validated['name']} ({$validated['email']}) requested a quote."
                );
            }

            return ['request_id' => $serviceRequest->id, 'reference' => $serviceRequest->request_number];
        });

        $reference = $outcome['result']['reference'] ?? null;

        return redirect()->route('get-quote')->with(
            'success',
            $outcome['replayed']
                ? "This request was already received. Your reference is {$reference}."
                : "Your quote request has been submitted. Reference {$reference} — we will respond within 24 hours."
        );
    }
}
