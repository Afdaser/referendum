<?php

namespace backend\modules\geo;

use backend\modules\ModuleTemplate;

/**
 * geo module definition class
 */
class Module extends ModuleTemplate
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\geo\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
