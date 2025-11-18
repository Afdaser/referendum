<?php

namespace common\helpers;

use Yii;

use common\models\Poll;

/**
 * String helper
 * Class StringHelper
 * @author alex
 */
class StringHelper {
    #email of site support

    const EMAIL = 'webmaster@example.com';

    #const for static pages
    const AUTHORS = 'про авторів';
    const PARTNERS = 'про партнерів';
    const SPONSORS = 'про спонсорів';

    /**
     * Нормалізує стандартну пару «варіант-значення» для подальшого використання у графіках.
     * Єдине місце перевірки значень спрощує форматери та мінімізує дублювання коду.
     *
     * @param array $item
     * @return array{option:string,value:int}
     */
    private static function prepareChartItem(array $item): array {
        return [
            'option' => $item['option'] ?? ' ',
            'value' => isset($item['value']) ? (int) $item['value'] : 0,
        ];
    }

    //Relative Date Function
    public static function relative_date($date) {
        $result = '';
        $timestamp = strtotime($date);
        $today = strtotime(date('M j, Y'));
        $reldays = ($timestamp - $today) / 86400;

        if ($reldays >= 0 && $reldays < 1) {
            $result = 'Сьогодні. ' . date('G:i', $timestamp);
        } else if ($reldays >= -1 && $reldays < 0) {
            $result = 'Вчора. ' . date('G:i', $timestamp);
        } else {
            $result = date('d-m-Y. G:i', $timestamp);
        }

        return $result;
    }

    /*
     * Formed string to build the bar chart
     */

    public static function formatForBar($data) {
        $seriesParts = [];

        foreach ((array) $data as $item) {
            $parsed = self::prepareChartItem((array) $item);
            // Шаблонізуємо рядок лише один раз, уникаючи повторної конкатенації змінних у циклі.
            $seriesParts[] = "{name: '" . $parsed['option'] . "',data: [" . $parsed['value'] . "]},";
        }

        return ['series' => implode('', $seriesParts)];
    }

    /*
     * Formed string to build the bar chart
     */

    public static function formatForBarAjax($data) {
        // Формуємо масив серій через map-підхід, щоб зменшити кількість допоміжного коду.
        $series = [];
        foreach ((array) $data as $item) {
            $parsed = self::prepareChartItem((array) $item);
            $series[] = ['name' => $parsed['option'], 'data' => [$parsed['value']]];
        }

        return ['series' => $series];
    }

    /*
     * Formed string to build the pie chart
     */

    public static function formatForPie($data) {
        $result = '';
        foreach ((array) $data as $item) {
            $parsed = self::prepareChartItem((array) $item);
            if (isset($item['isMax'])) {
                $result .= "{name:'" . $parsed['option'] . "',y:" . $parsed['value'] . ", sliced: true, selected: true },";
            } else {
                $result .= "['" . $parsed['option'] . "'," . $parsed['value'] . "],";
            }
        }

        return $result;
    }

    /*
     * Formed string to build the pie chart
     */

    public static function formatForPieAjax($data) {
        $result = [];
        foreach ((array) $data as $i => $item) {
            $parsed = self::prepareChartItem((array) $item);
            if (isset($item['isMax'])) {
                // Найбільше значення одразу отримує усі необхідні прапорці Highcharts.
                $result[$i] = [
                    'name' => $parsed['option'],
                    'y' => $parsed['value'],
                    'sliced' => true,
                    'selected' => true,
                ];
            } else {
                $result[$i] = [$parsed['option'], $parsed['value']];
            }
        }

        return $result;
    }

    /*
     * Return months list in ukrainian
     */

    public static function getMonthList() {
        $months = ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];
        // Переклад здійснюємо в одному місці за допомогою array_map, щоб уникнути 12 однакових викликів.
        $translated = array_map(function ($month) {
            return Yii::t('user', $month);
        }, $months);

        return array_combine(range(1, 12), $translated);
    }

    /*
     * Formed date from parameters
     */

    public static function formatDate($day, $month, $year) {
        $result = $year . '-' . $month . '-' . $day;
        return $result;
    }

    /*
     * Format Boolean Type To Yes\No
     */

    public static function formatBoolean($value) {
        return $value ? Yii::t('main', "Так") : Yii::t('main', "Ні");
    }

    /*
     * Format Poll Status to string
     */

    public static function formatPollStatus($value) {
        $result = "";
        switch ($value) {
            case Poll::POLL_STATUS_ACTIVE:$result = Yii::t('main', "Активне");
                break;
            case Poll::POLL_STATUS_CLOSED:$result = Yii::t('main', "Закрите");
                break;
            case Poll::POLL_STATUS_UNPUBLISHED:$result = Yii::t('main', "Неопубліковане");
                break;
        }
        return $result;
    }

    /*
     * Format Poll option Status to string
     */

    public static function formatPollOptionStatus($value) {
        $result = "";
        switch ($value) {
            case PollOption::OPTION_STATUS_PUBLISHED:$result = Yii::t('main', "Опубліковане");
                break;
            case PollOption::OPTION_STATUS_UNPUBLISHED:$result = Yii::t('main', "Неопубліковане");
                break;
        }
        return $result;
    }

    /*
     * Format poll tags to string
     */

    public static function tagsToString($tags) {
        if (empty($tags)) {
            return '';
        }

        // Збираємо імена тегів через map + implode, щоб позбутися ручних лічильників у циклі.
        $names = array_map(function ($tag) {
            return $tag->name;
        }, $tags);

        return implode(', ', $names);
    }

    /**
     * Generate a random salt in the crypt(3) standard Blowfish format.
     *
     * @param int $cost Cost parameter from 4 to 31.
     *
     * @throws Exception on invalid cost parameter.
     * @return string A Blowfish hash salt for use in PHP's crypt()
     */
    public static function blowfishSalt($cost = 13) {
        if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
            throw new Exception("cost parameter must be between 4 and 31");
        }
        $rand = array();
        for ($i = 0; $i < 8; $i += 1) {
            $rand[] = pack('S', mt_rand(0, 0xffff));
        }
        $rand[] = substr(microtime(), 2, 6);
        $rand = sha1(implode('', $rand), true);
        $salt = '$2a$' . sprintf('%02d', $cost) . '$';
        $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
        return $salt;
    }

    /*
     * Generate password
     */

    public static function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);
        $length = max(1, (int) $length);
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            // random_int забезпечує криптостійкість та однаковий розподіл символів.
            $index = random_int(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

}
