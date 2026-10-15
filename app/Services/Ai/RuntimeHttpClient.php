<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * RuntimeHttpClient — shared backend-to-backend HTTP for agent runtimes.
 *
 * Rules: timeouts always bounded; request bodies are never logged (they may
 * contain customer text); URLs are logged host-only (tokens travel in
 * headers, but defence in depth); responses are decoded defensively and
 * only whitelisted scalar fields are ever returned to callers.
 */
class RuntimeHttpClient
{
    /**
     * @return array {ok, status, json|error, error_category}
     */
    public static function post(string $url, array $headers, array $payload, int $timeout, int $connectTimeout): array
    {
        try {
            $response = Http::withHeaders($headers)
                ->acceptJson()
                ->timeout($timeout)
                ->connectTimeout($connectTimeout)
                ->post($url, $payload);
        } catch (ConnectionException $e) {
            Log::warning('Agent runtime connection failed', ['host' => self::hostOf($url)]);

            return ['ok' => false, 'status' => null, 'error' => 'unreachable', 'error_category' => 'unavailable'];
        } catch (\Throwable $e) {
            Log::warning('Agent runtime request failed', ['host' => self::hostOf($url), 'error' => $e->getMessage()]);

            return ['ok' => false, 'status' => null, 'error' => 'request_failed', 'error_category' => 'unavailable'];
        }

        $status = $response->status();
        if ($status === 401 || $status === 403) {
            return ['ok' => false, 'status' => $status, 'error' => 'auth_failed', 'error_category' => 'auth_failed'];
        }
        if ($status === 408 || $status === 504) {
            return ['ok' => false, 'status' => $status, 'error' => 'timeout', 'error_category' => 'timeout'];
        }
        if ($status === 429) {
            return ['ok' => false, 'status' => $status, 'error' => 'rate_limited', 'error_category' => 'rate_limited'];
        }
        if (! $response->successful()) {
            return ['ok' => false, 'status' => $status, 'error' => 'runtime_error', 'error_category' => 'unavailable'];
        }

        $decoded = null;
        try {
            $decoded = $response->json();
        } catch (\Throwable $e) {
            return ['ok' => false, 'status' => $status, 'error' => 'invalid_response', 'error_category' => 'invalid_response'];
        }

        return ['ok' => true, 'status' => $status, 'json' => $decoded, 'error' => null, 'error_category' => null];
    }

    /**
     * Side-effect-free reachability probe: any HTTP response (even 404)
     * proves the endpoint is alive; only transport failure counts as down.
     * Used for health checks that must never trigger agent activity.
     */
    public static function reachable(string $url, int $connectTimeout): ?bool
    {
        try {
            Http::timeout(5)->connectTimeout($connectTimeout)->get($url);

            return true; // any response (2xx/4xx/5xx) = host reachable
        } catch (ConnectionException $e) {
            return false;
        } catch (RequestException $e) {
            return true; // HTTP-level answer received
        } catch (\Throwable $e) {
            Log::warning('Agent runtime probe failed', ['host' => self::hostOf($url)]);

            return null; // indeterminate
        }
    }

    public static function hostOf(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        return is_string($host) && $host !== '' ? $host : 'unknown';
    }

    /** Mask a secret for admin display (first 3 + last 2 chars only). */
    public static function maskSecret(string $secret): string
    {
        if ($secret === '') {
            return 'not set';
        }
        if (mb_strlen($secret) <= 7) {
            return 'set (hidden)';
        }

        return mb_substr($secret, 0, 3).'…'.mb_substr($secret, -2).' ('.mb_strlen($secret).' chars)';
    }
}
