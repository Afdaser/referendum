<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\bootstrap\Html;
use common\helpers\StringHelper;

/**
 * RegisterForm class.
 * RegisterForm is the data structure for keeping
 * user register form data. It is used by the 'registration' action of 'SiteController'.
 */
class RegisterForm extends Model {

    public $email;
    public $login;
    public $password;
    public $passwordRepeat;
    public $agreeTerms = 0;
    public $verifyCode;
    public $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array(
                ['email', 'login', 'password'],
                'required',
                'message' => Yii::t("main", 'Введіть') . ' {attribute}.'
            ),
            // username must have email format
            array(
                'email',
                'email',
                'message' => Yii::t("main", 'Введіть email коректного формату!')
            ),
            // username must have unique email
            array(
                'login',
                'loginUnique',
                'message' => Yii::t("main", 'Оберіть інший логін!')
            ),
            // username must have unique email
            array(
                'email',
                'emailUnique',
                'message' => Yii::t("main", 'Оберіть інший email!')
            ),
            // password must have correct string length
            [
                'password',
                'string',
                'length' => [6, 255],
                'message' => Yii::t("main", 'Довжина пароля має бути від 6 до 255 символів.')
            ],
            /*
              array(
              'password',
              'length',
              'min'=>6,
              'max'=>255,
              'message'=>Yii::t("main", 'Довжина пароля має бути від 6 до 255 символів.')
              ), /* */
            // password and repeat_password must be equal
            array(
                'passwordRepeat',
                'compare',
                'compareAttribute' => 'password',
                'message' => Yii::t("main", 'Повторіть пароль вточності!')
            ),
            // repeat_password are required
            array(
                'passwordRepeat',
                'required',
                'message' => Yii::t("main", 'Повторіть пароль.')
            ),
            // agree with site terms is required
            [
                'agreeTerms',
                'required',
                'requiredValue' => 1,
                'message' => Yii::t("main", 'Ви маєте погодитись з правилами та умовами сайту.'),
            ],
            [
                'agreeTerms',
                'boolean',
                'trueValue' => 1,
                'falseValue' => 0,
            ],
            array(
                'verifyCode',
                'captcha',
                'captchaAction' => '/site/captcha',
                'caseSensitive' => true,
                'skipOnEmpty' => false,
                'message' => Yii::t("main", 'Введіть вірний код перевірки.')
            ),
        );
    }

    /**
     * Rules login unique
     * @param $attribute
     * @param $params
     */
    public function loginUnique($attribute, $params) {
        if ($this->$attribute) {
            // # Yii1:OLD:
            // $user = User::model()->findByAttributes(array('login' => CHtml::encode($this->$attribute)));
            $exists = User::find()->where(['username' => Html::encode($this->$attribute)])->exists();
            if ($exists) {
                $this->addError($attribute, Yii::t("main", 'Оберіть інший логін!'));
            }
        }
    }

    /**
     * Rules email unique
     * @param $attribute
     * @param $params
     */
    public function emailUnique($attribute, $params) {
        if ($this->$attribute) {
            // # Yii1:OLD:
            // $user = User::model()->findByAttributes(array('email' => CHtml::encode($this->$attribute)));
            $exists = User::find()->where(['email' => Html::encode($this->$attribute)])->exists();
            if ($exists) {
                $this->addError($attribute, Yii::t("main", 'Оберіть інший email!'));
            }
        }
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'login' => Yii::t("main", 'Логін'),
            'email' => Yii::t("main", 'Email'),
            'password' => Yii::t("main", 'Пароль'),
            'passwordRepeat' => 'Password Again',
            'agreeTerms' => 'Accept Terms',
        );
    }

    /**
     * Register the user using the given email and password in the model.
     * @return boolean whether register is successful
     */
    public function register() {
        if (!$this->validate()) {
            return false;
        }

        $result = false;
        $createDate = date('Y-m-d h:i:s');
        $user = new User;

        // set main data
        $user->username = Html::encode($this->login);
        $user->email = Html::encode($this->email);
        $user->password = crypt($this->password, StringHelper::blowfishSalt());
        $user->is_active = 0;
        $user->date_add = $createDate;
        $user->date_update = $createDate;

        if ($user->save()) {
            /** @var Email $email */
            /* $email = Yii::app()->email;
              $email->from = Yii::app()->params['adminEmail'];
              $email->to = $user->email;
              $email->subject = Yii::t('email', 'Регистрация на сайте') . ' online-statistics.org';
              $email->message = Yii::t('email', 'Ви успішно зареєструвались на сайті') . ' online-statistics.org';
              $email->send(); */

            $mailer = Yii::createComponent('application.extensions.mailer.EMailer');
            $mailer->From = Yii::app()->params['adminEmail'];
            $mailer->AddReplyTo(Yii::app()->params['adminEmail']);
            $mailer->AddAddress($user->email);
            $mailer->FromName = 'Webmaster';
            $mailer->CharSet = 'UTF-8';
            $mailer->Subject = Yii::t('email', 'Регистрация на сайте') . ' online-statistics.org';
            $mailer->Body = Yii::t('email', 'Ви успішно зареєструвались на сайті') . ' online-statistics.org';
            ;

            $this->_identity = new UserIdentity($this->login, $this->password);
            $this->_identity->authenticate();
            if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
                $duration = 3600 * 24 * 30; // 30 days
                Yii::app()->user->login($this->_identity, $duration);
                return true;
            }
        }

        return $result;
    }

}
