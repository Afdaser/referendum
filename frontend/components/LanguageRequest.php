<?php

namespace frontend\components;

use Yii;
use yii\web\Request;
use common\models\Language;

/**
 * Description of LanguageRequest
 *
 * @author alex
 */
class LanguageRequest  extends Request
{
    private $_lang_url;
    private $languages;
    public $languageId;
    
    public function getUrlReferrer() {
        // Yii::$app->request->referrer;
        return $this->referrer;
    }

    public function resolve() {
        $resolveResult = parent::resolve();
        $host = explode('.', $this->hostName);
        if(strlen($host[0]) == 2){
            $this->_lang_url = $host[0];
            $langSlug = $this->_lang_url;
            if($langSlug == 'uk'){
                $langSlug = 'ua';
            }
            $this->languages = Language::fetchLanguages();
            if(isset($this->languages[$langSlug])){
                \Yii::$app->language = $this->languages[$langSlug]['locale'];
                $this->languageId = $this->languages[$langSlug]['id'];
            }
        }
        return $resolveResult; 
    }
}
