<?php

namespace app\modules\ajax\controllers;

use yii\web\Controller;
use frontend\models\forms\RegisterForm;
use frontend\models\forms\PollForm as Poll;
use frontend\models\SignupForm;
use Yii;
use yii\bootstrap\Html;

/**
 * Modal controller for the `ajax` module
 */
class ModalController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionRegistrtionStepOne()
    {
        $model = new RegisterForm();
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $signupForm = new SignupForm();
                $signupForm->load(['SignupForm' => [
                    'username' => $model->login,
                    'email' => $model->email,
                    'password' => $model->password,
                ]]);
                if (!$signupForm->signup()) {
                    $model->addErrors($signupForm->getErrors());
                }
            }
        }

        return $this->renderPartial('registrtion-step-one', [
            'registerForm' => $model,
            'error' => json_encode(Html::errorSummary($model)),
        ]);
    }

    /**
     * create-poll-step-one
     * Renders the content of modal form
     * @return string
     */
    public function actionCreatePollStepOne()
    {
        
            $pollModel = new Poll;
            $pollModel->presetAttributes();

//            $model = new Poll;
/*
            $pollModel = new Poll;
            $pollModel->unsetAttributes();

 */
        return $this->renderPartial('create-poll-step-one', [
                    'pollModel' => $pollModel,
                    'error' => json_encode(Html::errorSummary($pollModel)),
        ]);
    }

    public function actionStorePollForm()
    {
        echo '<h2>store-poll-form</h2>';
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        die(__METHOD__);
        
    }
}
