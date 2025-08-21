<?php

namespace app\modules\ajax\controllers;

use Yii;
use yii\web\Controller;

use common\models\form\LoginForm;

/**
 * Dev controller for the `ajax` module
 */
class DevController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('login');
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }


    /**
     * Submit Logs in a user.
     *
     * @return mixed
     */
    public function actionSubmit()
    {
        echo '<h2>POST:</h2>';
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        die(__METHOD__);

    }
}
