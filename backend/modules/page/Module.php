<?php

namespace backend\modules\page;

use backend\modules\ModuleTemplate;

/**
 * page module definition class
 */
class Module extends ModuleTemplate
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\page\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
