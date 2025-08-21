<?php

/**
 * Description of MenuHelper
 *
 * @author alex
 */

namespace common\helpers;

use Yii;

class MenuHelper
{

    protected static $urlItems = null;

    public static function isActiveMenu($params = [], $url)
    {
        if (!is_array(self::$urlItems)) {
            //self::$urlItems = explode('/', $this->context->route);
            self::$urlItems = explode('/', $url);
        }
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                if (empty(self::$urlItems[$key])) {
                    return false;
                }
                if ($value != self::$urlItems[$key]) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

}
