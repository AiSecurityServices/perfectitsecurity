<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Idempotency — duplicate-submission guard for customer creates.
 *
 * Key = client-supplied `Idempotency-Key` header when present, else a hash
 * of actor + route + payload. First call executes the callback and stores a
 * small result envelope for 24h; replays return the stored envelope without
 * re-executing (no duplicate tickets/orders/requests on double-click,
 * back-button, or retried requests).
 */
class Idempotency
{
    public const TTL_SECONDS = 86400;

    public static function key(?string $clientKey, string $scope, int|string $actor, array $payload): string
    {
        if ($clientKey && preg_match('/^[A-Za-z0-9\-_]{8,128}$/', $clientKey)) {
            return 'idem:client:'.sha1($scope.'|'.$clientKey);
        }
        ksort($payload);

        return 'idem:auto:'.$scope.':'.sha1($actor.'|'.json_encode($payload));
    }

    /**
     * @return array {replayed:bool, result:mixed}
     */
    public static function run(string $key, callable $callback): array
    {
        $hit = Cache::get($key);
        if (is_array($hit) && array_key_exists('result', $hit)) {
            return ['replayed' => true, 'result' => $hit['result']];
        }
        $result = $callback();
        Cache::put($key, ['result' => $result], self::TTL_SECONDS);

        return ['replayed' => false, 'result' => $result];
    }

    public static function keyForRequest(\Illuminate\Http\Request $request, string $scope, int|string $actor, array $payload): string
    {
        $header = $request->header('Idempotency-Key');

        return self::key(is_string($header) ? $header : null, $scope, $actor, $payload);
    }
}
