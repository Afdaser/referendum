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
                if($model->register())  {
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

    public function run()
    {
        // Форма входу завжди доступна через $this->model, тому одразу готуємо модель реєстрації.
        $registerForm = new RegisterForm();

        if (Yii::$app->user->isGuest) {
            if ($registerForm->load(Yii::$app->request->post())) {
                if ($registerForm->register()) {
                    // Після успішної реєстрації показуємо той самий шаблон, що й для авторизованих користувачів,
                    // щоб спрацював існуючий сценарій оновлення сторінки.
                    $pollModel = new Poll();
                    $pollModel->presetAttributes();

                    Yii::$app->session->setFlash('success', Yii::t('main', 'Дякуємо за реєстрацію. Перевірте пошту.'));

                    return $this->render('user-sidebar', [
                        'refresh' => true,
                        'pollModel' => $pollModel,
                        'error' => json_encode(Html::errorSummary($pollModel)),
                    ]);
                }

                // Якщо помилки валідації — повертаємо форму з повідомленнями.
                return $this->render('user-sidebar-login', [
                    'model' => $this->model,
                    'registerForm' => $registerForm,
                    'error' => json_encode(Html::errorSummary($registerForm)),
                ]);
            }

            return $this->render('user-sidebar-login', [
                'model' => $this->model,
                'registerForm' => $registerForm,
            ]);
        }

        $pollModel = new Poll();
        $pollModel->presetAttributes();

        return $this->render('user-sidebar', [
            'refresh' => false,
            'pollModel' => $pollModel,
            'error' => json_encode(Html::errorSummary($pollModel)),
        ]);
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
