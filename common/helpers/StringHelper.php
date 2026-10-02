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
        // Збираємо серії через масив, щоб уникнути зайвих конкатенацій у циклі.
        $series = [];
        foreach ($data as $item) {
            $option = $item['option'] ?? ' ';
            $value = $item['value'] ?? 0;
            $series[] = "{name: '" . $option . "',data: [" . $value . "]}";
        }

        return ['series' => empty($series) ? '' : implode(',', $series) . ','];
    }

    /*
     * Formed string to build the bar chart
     */

    public static function formatForBarAjax($data) {
        $result = array();
        foreach ($data as $i => $item) {
            $result['series'][$i] = ['name' => $item['option'], 'data' => [0 => $item['value']]];
        }

        return $result;
    }

    /*
     * Formed string to build the pie chart
     */

    public static function formatForPie($data) {
        // Формуємо список елементів і склеюємо вкінці (менше операцій рядків).
        $items = [];
        foreach ($data as $item) {
            $option = $item['option'] ?? ' ';
            $value = $item['value'] ?? 0;

            if (isset($item['isMax'])) {
                $items[] = "{name:'" . $option . "',y:" . $value . ", sliced: true, selected: true }";
            } else {
                $items[] = "['" . $option . "'," . $value . "]";
            }
        }

        return empty($items) ? '' : implode(',', $items) . ',';
    }

    /*
     * Formed string to build the pie chart
     */

    public static function formatForPieAjax($data) {
        $result = array();
        foreach ($data as $i => $item) {
            if (isset($item['isMax'])) {
                $result[$i]['name'] = $item['option'];
                $result[$i]['y'] = intval($item['value']);
                $result[$i]['sliced'] = true;
                $result[$i]['selected'] = true;
            } else {
                $result[$i] = [$item['option'], intval($item['value'])];
            }
        }

        return $result;
    }

    /*
     * Return months list in ukrainian
     */

    public static function getMonthList() {
        $result = array();
        $result[1] = Yii::t("user", 'Січень');
        $result[2] = Yii::t("user", 'Лютий');
        $result[3] = Yii::t("user", 'Березень');
        $result[4] = Yii::t("user", 'Квітень');
        $result[5] = Yii::t("user", 'Травень');
        $result[6] = Yii::t("user", 'Червень');
        $result[7] = Yii::t("user", 'Липень');
        $result[8] = Yii::t("user", 'Серпень');
        $result[9] = Yii::t("user", 'Вересень');
        $result[10] = Yii::t("user", 'Жовтень');
        $result[11] = Yii::t("user", 'Листопад');
        $result[12] = Yii::t("user", 'Грудень');

        return $result;
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
        // Використовуємо implode замість ручного циклу: коротше і швидше.
        return implode(', ', array_map(static function ($tag) {
            return $tag->name;
        }, $tags));
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

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

}
