<?php

namespace App\Support;

/**
 * Money — cent-exact (integer minor unit) arithmetic for 2dp currencies.
 *
 * Storage stays decimal(12,2); this helper removes float drift from
 * multi-step settlement math (paid/due/outstanding). All inputs are rounded
 * to the minor unit on entry; outputs are exact integers or 2dp strings.
 * 3dp currencies (KWD/BHD/OMR) remain presentation-only per Money docs.
 */
final class Money
{
    /** Major (123.45) → minor units (12345), half-up. */
    public static function toMinor(float|string|int $major): int
    {
        return (int) round((float) $major * 100);
    }

    /** Minor units (12345) → 2dp major string ('123.45'). */
    public static function toMajor(int $minor): string
    {
        $sign = $minor < 0 ? '-' : '';
        $abs = abs($minor);

        return $sign.sprintf('%d.%02d', intdiv($abs, 100), $abs % 100);
    }

    public static function add(int ...$amounts): int
    {
        return array_sum($amounts);
    }

    public static function sub(int $a, int $b): int
    {
        return $a - $b;
    }

    public static function min(int ...$amounts): int
    {
        return min($amounts);
    }

    public static function max(int ...$amounts): int
    {
        return max($amounts);
    }
}
