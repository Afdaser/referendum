<?php

namespace app\modules\ajax\controllers;

use yii\web\Controller;
use frontend\models\forms\RegisterForm;
//use common\models\Poll;
use frontend\models\forms\PollForm as Poll;

//use Yii;
use yii\bootstrap\Html;

/**
 * Контролер модальних вікон модуля `ajax`.
 */
class ModalController extends Controller
{
    /**
     * Повертає форму реєстрації для модального вікна.
     * @return string
     */
    public function actionRegistrtionStepOne()
    {
            $model = new RegisterForm;
            if(isset($_POST['RegisterForm'])){
                $model->attributes = $_POST['RegisterForm'];
                if($model->validate())  {
  //                  $this->render('userSidebar/_sidebar',array('refresh'=>true));
                }
/*
                else {
                    $this->render('userSidebar/_login', array("model" => $this->model,'registerForm'=>$model,'error'=>json_encode(CHtml::errorSummary($model))));
                }
            } else {
                $this->render('userSidebar/_login', array("model" => $this->model,'registerForm'=>$model));
/* */
            }

        return $this->renderPartial('registrtion-step-one', [
            'registerForm'=>$model,
            'error'=>json_encode(Html::errorSummary($model)),
            ]);
    }

    /**
     * Повертає форму створення опитування для модального вікна.
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
                if($model->validate())  {
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
