<?php

namespace app\modules\ajax\controllers;

use yii\web\Controller;
use frontend\models\forms\RegisterForm;
use frontend\models\forms\PollForm as Poll;
use Yii;
use yii\helpers\Html;

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
        $request = Yii::$app->request;
        if ($model->load($request->post()) && $model->register()) {
            // успішна реєстрація, додаткова логіка за потреби
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
/*
            $model = new RegisterForm;
            if(isset($_POST['RegisterForm'])){
                $model->attributes = $_POST['RegisterForm'];
                if($model->validate() && $model->register())  {
  //                  $this->render('userSidebar/_sidebar',array('refresh'=>true));
                }
/*
                else {
                    $this->render('userSidebar/_login', array("model" => $this->model,'registerForm'=>$model,'error'=>json_encode(CHtml::errorSummary($model))));
                }
            } else {
                $this->render('userSidebar/_login', array("model" => $this->model,'registerForm'=>$model));

            }
/* */

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
