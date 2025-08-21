<?php

namespace frontend\modules\user\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        die(__METHOD__);
        return $this->render('index');
    }

}
