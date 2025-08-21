<?php

namespace frontend\components;

use Yii;
use yii\web\UrlManager;
use common\models\Language;

/**
 * Description of LanguageUrlManager
 *
 * @author alex
 */
class LanguageUrlManager extends UrlManager {

    public function init() {
//        echo '<h2>'.\Yii::$app->language.'</h2>';
//        die(__METHOD__);
        parent::init();
    }

    public function createLangUrl($language, $ampersand = '&') {
        if (Language::isRightLanguage($language)) {
            return 'https://' . $language . '.' . SITE_DOMAIN;
        } else {
            $route = Yii::$app->createUrl('/');
            $params = $_GET;
            return parent::createUrl($route, $params, $ampersand);
        }
    }

    public function getCurrentLocale() {
        return Yii::$app->language;
    }

    public function createAbsoluteUrl($route, $params = array(), $schema = '', $ampersand = '&') {
        $url = $this->createUrl($route, $params, $ampersand);
        if (strpos($url, 'http') === 0) {
            return $url;
        } else {
            // return $this->getRequest()->getHostInfo($schema) . $url;
            return Yii::$app->request->getHostInfo($schema) . $url;
        }
    }

    /* * /
    public function createUrl($params)
    {
        if (isset($params['lang_id'])) {
            //Если указан идентефикатор языка, то делаем попытку найти язык в БД,
            //иначе работаем с языком по умолчанию
            $lang = Lang::findOne($params['lang_id']);
            if ($lang === null) {
                $lang = Lang::getDefaultLang();
            }

            unset($params['lang_id']);
        } else {
            //Если не указан параметр языка, то работаем с текущим языком
            $lang = Lang::getCurrent();
        }

        $url = parent::createUrl($params);

        return $url == '/' ? '/' . $lang->url : '/' . $lang->url . $url;
    }
/* */
}
