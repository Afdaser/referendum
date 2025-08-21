<?php

namespace backend\modules\poll;

use backend\modules\ModuleTemplate;

/**
 * poll module definition class
 */
class Module extends ModuleTemplate
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\poll\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
