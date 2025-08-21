<?php

namespace backend\modules;

use yii\filters\AccessControl;
/**
 * content module definition class
 */
abstract class ModuleTemplate extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\content\controllers';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
/*                    [
                        'controllers' => [$this->id . '/auth'],
                        'actions' => ['login'],
                        'allow' => true,
                    ], /**/
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
