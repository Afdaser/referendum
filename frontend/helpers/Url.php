<?php

namespace frontend\helpers;

//use yii\helpers\Url AS BaseUrl;
use yii\helpers\BaseUrl;
use ashch\sitecore\models\Lang;


/**
 * Url provides a set of static methods for managing URLs.
 * For more details and usage information on Url, see the [guide article on url helpers](guide:helper-url).
 */
class Url extends BaseUrl
{

    public static function home($scheme = false)
    {
        $langCurrent = Lang::getCurrent();
        if($langCurrent->default){
            $url = parent::home($scheme);
        }else{
            $url = Url::toRoute(['/page/default/index']).'/';
        }
        return $url;
    }

    public static function toRoute($route, $scheme = false)
    {
        $route = (array) $route;

        $url = parent::toRoute($route, $scheme);

        return self::clearEndDot($url);
    }

    public static function clearEndDot($str)
    {
        $str = trim($str);
        while (substr($str, -1, 1) == '.') {
            $str = substr($str, 0, strlen($str) - 1);
        }
        return $str;
    }

    public static function setActivActivityFlag($menuItems, $route = NULL)
    {
        $debug = 0;
        if (empty($route)) {
//            $route = \Yii::$app->request->pathInfo;
            $route = \Yii::$app->request->url;
        }
//        return $menuItems;
        $currenUrlSlice = self::sliceUrl($route);
//        var_dump(\Yii::$app->controller->route);
//        var_dump(\Yii::$app->request);
//        var_dump(\Yii::$app->request->pathInfo);

        if ($debug) {
            echo '<h2>$ route</h2>';
            var_dump($route);
        }


        foreach ($menuItems as $key => $item) {
            if (!empty($item['url'])) {
                if ($debug) {
                    echo "<h2>Key:[{$key}]</h2>";
                    var_dump($item['url']);
                }

                if ($item['url'] == $route) {
                    $menuItems[$key]['active'] = true;
//                    var_dump($menuItems[$key]);
//                    die(__FILE__ . '#' . __LINE__);
                } else {
                    $itemUrlSlice = self::sliceUrl($item['url']);
                    foreach ($itemUrlSlice AS $k => $v) {
                        if ($debug) {
                            echo "<hr>k:[{$k}] > v:[{$v}]";
                        }
                        if (isset($currenUrlSlice[$k]) AND $v == $currenUrlSlice[$k]) {
                            if ($debug) {
                                echo " + ";
                            }
                            $menuItems[$key]['active'] = true;
                        } else {
                            if ($debug) {
                                echo " - ";
                            }
                            $menuItems[$key]['active'] = false;
                            break;
                        }
                    }
                    /* * /
                    if ($menuItems[$key]['active'] AND ! empty($menuItems[$key]['items'])) {
                        foreach ($menuItems[$key]['items'] as $k2 => $item2) {
                            if ($item2['url'] == $route) {
                                $menuItems[$key]['items'][$k2]['active'] = true;
                            } else {

                            }
                        }
                        $debug = 0;
                        if ($debug) {
                            echo '<h2>$ menuItems2</h2>';
                            var_dump($menuItems[$key]['items']);

                            die(__FILE__ . '#' . __LINE__);
                        }
                    }
                    /* */
                }
            }
        }

        if ($debug) {
            echo '<h2>$ menuItems</h2>';
            var_dump($menuItems);

            die(__FILE__ . '#' . __LINE__);
        }
        return $menuItems;
    }

    public function sliceUrl($uri)
    {
        if (is_array($uri) and count($uri) == 1 ) {
            $uri = $uri[0];
        }
        if (is_array($uri)) {
//            var_dump($uri);
//            die(__FILE__ . '#' . __LINE__);
            return array_values($uri);
        } else {
            $urlSlice = explode('/', $uri);

            foreach ($urlSlice as $key => $item) {
                if (empty($item)) {
                    unset($urlSlice[$key]);
                }
            }
            return array_values($urlSlice);
        }
    }

}
