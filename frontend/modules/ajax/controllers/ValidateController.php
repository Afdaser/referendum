<?php

namespace app\modules\ajax\controllers;

use Yii;
use yii\web\Controller;

use common\models\form\LoginForm;

/**
 * Validate controller for the `ajax` module
 */
class ValidateController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return boolean or json
     */
    public function actionLogin()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $outData = [
            'method' => __METHOD__,
        ];

        if (!Yii::$app->user->isGuest) {
            $outData['already_login'] = 'ok';
//            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())){
            if ($model->login()){
                $outData['login_ok'] = true;
                $outData['redirect_uri'] = '/';
                $outData['csrf'] = Yii::$app->request->csrfParam;;
            }else{
                $outData['error_message'] = Yii::t('app', 'Wrong username or password');
            }
        }

//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
////            return $this->goBack();
//            $outData['login_ok'] = 'ok+';
//        }
        $model->password = '';

//        return $this->render('login', [
//            'model' => $model,
//        ]);
        return $outData;
    }
}
