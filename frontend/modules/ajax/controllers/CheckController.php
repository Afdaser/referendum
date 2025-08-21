<?php

namespace app\modules\ajax\controllers;

use Yii;
use yii\web\Controller;
use yii\bootstrap\Html;

use common\models\User;


/**
 * Check controller for the `ajax` module
 */
class CheckController extends Controller {
    /*
     * Ajax checking user login
     */

    public function actionLogin() {
        if (Yii::$app->request->isPost) {
            $login = Html::encode(Yii::$app->request->post('login'));
            if (User::find()->where(['username' => $login])->count()) {
                $result = 0;
            } else {
                $result = 1;
            }
            echo $result;
        }
    }

}
