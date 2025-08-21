<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap4\Widget;
use common\models\Language;

/**
 * Description of WTopPollsSlider
 *
 * @author alex
 */
class WLanguageSelector extends Widget {

    public $data;
    protected $view = 'language-selector';
    public $languages;

    public function initYiiOne() {
        $this->languages = Language::model()->findAll();
    }


    public function init()
    {
       $this->languages = Language::find()->all();
    }

    public function run() {
        return $this->render($this->view, [
                    'data' => $this->data,
                    'languages' => $this->languages,
        ]);
    }

}