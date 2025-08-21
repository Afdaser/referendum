<?php

/**
 * Description of DateHelper
 *
 * @author alex
 */

namespace common\helpers;

class DateHelper
{

    protected static $patternSQL = '~^((19|20)\d\d)[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$~';
    protected static $patternDMY = '~^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.]((19|20)\d\d)$~';

    public static function convertDateByFormat($dateString, $format = 'dd/mm/yyyy')
    {
        if ($format == 'dd/mm/yyyy') {

            $res = preg_match(self::$patternDMY, $dateString, $dateParts);
            if ($res) {
                return "{$dateParts[3]}-{$dateParts[2]}-{$dateParts[1]}";
            }
        }
        return $dateString;
    }

    public static function convertSQLDateToFormated($dateString, $format = 'dd/mm/yyyy')
    {
        $res = preg_match(self::$patternSQL, $dateString, $dateParts);
        if ($res) {
            if ($format == 'dd/mm/yyyy') {
                return "{$dateParts[4]}/{$dateParts[3]}/{$dateParts[1]}";
            }
        }
        return $dateString;
    }

}
