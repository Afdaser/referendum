<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap4\Widget;
use common\models\form\LoginForm;
use frontend\models\forms\RegisterForm;
use frontend\models\forms\PollForm as Poll;

use yii\bootstrap\Html;

/**
 * Description of WTopPollsSlider
 *
 * @author alex
 */
class WUserSidebar extends Widget
{
    public $model;

    public function init()
    {
       $this->model = new LoginForm;
    }

    public function runYiiOne()
    {
        if(Yii::app()->user->isGuest){
            $model = new RegisterForm;
            if(isset($_POST['RegisterForm'])){
                $model->attributes = $_POST['RegisterForm'];
                if($model->validate() && $model->register())  {
                    $this->render('userSidebar/_sidebar',array('refresh'=>true));
        }
                else {
                    $this->render('userSidebar/_login', array("model" => $this->model,'registerForm'=>$model,'error'=>json_encode(CHtml::errorSummary($model))));
        }
                } else {
                $this->render('userSidebar/_login', array("model" => $this->model,'registerForm'=>$model));
            }
        } else {
            $this->render('userSidebar/_sidebar',array('refresh'=>false));
        }
    }

    public function run() {
        if(Yii::$app->user->isGuest){
//            $model = new LoginForm;
            $model = new RegisterForm;
            if(isset($_POST['RegisterForm'])){
                $attributes = Yii::$app->request->post('RegisterForm');
                // Єдиний детермінований флоу реєстрації через RegisterForm.
                $model->load(['RegisterForm' => $attributes]);

                if ($model->validate() && $model->register()) {
                    // Після успішної реєстрації передаємо pollModel, як у гілці не гостя.
                    $pollModel = new Poll;
                    $pollModel->presetAttributes();

                    return $this->render('user-sidebar', [
                        'refresh' => true,
                        'pollModel' => $pollModel,
                        'error' => json_encode(Html::errorSummary($pollModel)),
                    ]);
                }

                return $this->render('user-sidebar-login', [
                    "model" => $this->model,
                    'registerForm' => $model,
                    'error' => json_encode(Html::errorSummary($model)),
                ]);

//                die(__FILE__.'#'.__LINE__);

//                    echo '<h2>Errors:</h2><pre>';
//                    var_dump($model->getErrors());
//                    var_dump(Html::errorSummary($model));
//                    echo '</pre>';
//                    die(__METHOD__ . '#' . __LINE__);

/*
                if($model->validate() && $model->register())  {
                    return $this->render('user-sidebar',array('refresh'=>true));
                }
                else {
                    return $this->render('user-sidebar-login', array("model" => $this->model,'registerForm'=>$model,'error'=>json_encode(Html::errorSummary($model))));
                }
/* */
            } else {
                return $this->render('user-sidebar-login', array("model" => $this->model,'registerForm'=>$model));
            }
        } else {
//            if(Yii::$app->request->isPost){
//                $resultOfSaving = $this->processNewPoll();
//                if($resultOfSaving){
//                    Yii::$app->session->setFlash('success', 'Poll saved successfully');
//                    return Yii::$app->response->redirect(['/poll/site/my-polls', ]);
////                    return $this->redirect(['/poll/site/my-polls', ]);
//                }
//            }
            $pollModel = new Poll;
            $pollModel->presetAttributes();
            
            return $this->render('user-sidebar',[
                'refresh'=>false,
                    'pollModel' => $pollModel,
                    'error' => json_encode(Html::errorSummary($pollModel)),
//                    'forceModal' => $this->forceModal,
                ]
                    );
        }


//        return $this->render($this->view, [
//                    'data' => $this->data,
//                    'activePolls' => $this->activePolls,
//
//        ]);
    }

    public function processNewPoll()
    {
        $this->forceModal = true;
        return false;
//        return true;
        echo "\n\n\n\n\n\n\n<hr><hr><hr><hr>\n";
        echo '<h2>processNewPoll():</h2>';
        echo '<pre>';
        echo var_dump($_POST);
        echo '</pre>';
        die(__METHOD__);
    }
}
