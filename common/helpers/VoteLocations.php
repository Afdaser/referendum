<?php

namespace common\helpers;

/** Location collection depends on the request host, never the poll's language. */
final class VoteLocations
{
    public static function countryForHost(string $host): ?string
    {
        return [
            'en.referendum.social' => 'US',
            'nz.referendum.social' => 'NZ',
        ][strtolower($host)] ?? null;
    }

    public static function regions(?string $countryCode): array
    {
        switch ($countryCode) {
            case 'US': return UsStates::all();
            case 'NZ': return NzRegions::all();
            default: return [];
        }
    }
}
