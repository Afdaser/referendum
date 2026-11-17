<?php

namespace common\helpers;

/** ISO 3166-2:NZ regions, plus the Chatham Islands special island authority. */
final class NzRegions
{
    public static function all(): array
    {
        return [
            'AUK' => 'Auckland',
            'BOP' => 'Bay of Plenty',
            'CAN' => 'Canterbury',
            'CIT' => 'Chatham Islands',
            'GIS' => 'Gisborne',
            'HKB' => "Hawke's Bay",
            'MWT' => 'Manawatū-Whanganui',
            'MBH' => 'Marlborough',
            'NSN' => 'Nelson',
            'NTL' => 'Northland',
            'OTA' => 'Otago',
            'STL' => 'Southland',
            'TKI' => 'Taranaki',
            'TAS' => 'Tasman',
            'WKO' => 'Waikato',
            'WGN' => 'Wellington',
            'WTC' => 'West Coast',
        ];
    }
}
