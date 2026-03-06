<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap4\Widget;
use common\models\form\LoginForm;
use frontend\models\SignupForm;
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
    private const DUPLICATE_POST_TTL = 10;

    private static $registrationHandled = false;

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

    public function run()
    {
        if (Yii::$app->user->isGuest) {
            $model = new RegisterForm;
            $attributes = Yii::$app->request->post('RegisterForm');

            if (is_array($attributes)) {
                $model->load(['RegisterForm' => $attributes]);

                if ($this->isDuplicateRegistrationPost($attributes)) {
                    return $this->render('user-sidebar', ['refresh' => true]);
                }

                if (!self::$registrationHandled) {
                    self::$registrationHandled = true;

                    if ($model->validate()) {
                        $signupFormModel = new SignupForm();
                        $signupFormModel->load(['SignupForm' => [
                            'username' => $attributes['login'] ?? '',
                            'email' => $attributes['email'] ?? '',
                            'password' => $attributes['password'] ?? '',
                        ]]);

                        if ($signupFormModel->signup(false)) {
                            $this->rememberSuccessfulRegistration($attributes);
                            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
                            return $this->render('user-sidebar', ['refresh' => true]);
                        }

                        foreach ($signupFormModel->getFirstErrors() as $error) {
                            $model->addError('email', $error);
                        }

                        if (!$model->hasErrors()) {
                            $model->addError('email', Yii::t('main', 'Registration failed. Please try again.'));
                        }
                    }

                    return $this->render('user-sidebar-login', [
                        'model' => $this->model,
                        'registerForm' => $model,
                        'error' => json_encode(Html::errorSummary($model)),
                    ]);
                }
            }

            return $this->render('user-sidebar-login', [
                'model' => $this->model,
                'registerForm' => $model,
            ]);
        }

        $pollModel = new Poll;
        $pollModel->presetAttributes();

        return $this->render('user-sidebar', [
            'refresh' => false,
            'pollModel' => $pollModel,
            'error' => json_encode(Html::errorSummary($pollModel)),
        ]);
    }

    private function buildRegistrationFingerprint(array $attributes)
    {
        $login = strtolower(trim((string)($attributes['login'] ?? '')));
        $email = strtolower(trim((string)($attributes['email'] ?? '')));

        return sha1($login . '|' . $email);
    }

    private function isDuplicateRegistrationPost(array $attributes)
    {
        $session = Yii::$app->session;
        $fingerprint = $this->buildRegistrationFingerprint($attributes);

        $lastFingerprint = $session->get('registration.last_success_fingerprint');
        $lastSuccessAt = (int)$session->get('registration.last_success_at', 0);

        if ($lastFingerprint !== $fingerprint || $lastSuccessAt === 0) {
            return false;
        }

        if ((time() - $lastSuccessAt) > self::DUPLICATE_POST_TTL) {
            return false;
        }

        $session->remove('registration.last_success_fingerprint');
        $session->remove('registration.last_success_at');

        return true;
    }

    private function rememberSuccessfulRegistration(array $attributes)
    {
        $session = Yii::$app->session;
        $session->set('registration.last_success_fingerprint', $this->buildRegistrationFingerprint($attributes));
        $session->set('registration.last_success_at', time());
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
