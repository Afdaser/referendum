<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\bootstrap\Html;
use frontend\models\SignupForm;

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
    public $agreeTerms;
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
            // rememberMe needs to be a boolean
            array(
                'agreeTerms',
                'agreeTerms',
                'message' => Yii::t("main", 'Ви маєте погодитись з правилами та умовами сайту.')
            ),
            array(
                'verifyCode',
                'captcha',
                'captchaAction' => '/site/captcha',
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
            $user = User::find()->where(['username' => Html::encode($this->$attribute)])->all();
            if ($user) {
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
            $user = User::find()->where(['email' => Html::encode($this->$attribute)])->all();
            if ($user) {
                $this->addError($attribute, Yii::t("main", 'Оберіть інший email!'));
            }
        }
    }

    /**
     * Rules terms required
     * @param $attribute
     * @param $params
     */
    public function agreeTerms($attribute, $params) {
        if (!$this->$attribute) {
            $this->addError($attribute, Yii::t("main", 'Ви маєте погодитись з правилами та умовами сайту.'));
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
     * Виконує реєстрацію користувача на основі даних форми.
     *
     * Метод спочатку перевіряє коректність введених даних, а потім
     * передає їх стандартній формі {@see SignupForm}, щоб не дублювати
     * бізнес-логіку створення користувача.
     *
     * @return bool успіх операції
     */
    public function register(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $signupForm = new SignupForm();
        $signupForm->load([
            'SignupForm' => [
                'username' => $this->login,
                'email' => $this->email,
                'password' => $this->password,
            ],
        ]);

        if (!$signupForm->validate()) {
            $this->collectSignupErrors($signupForm);
            return false;
        }

        if ($signupForm->signup()) {
            return true;
        }

        // Якщо зберегти не вдалося, переносимо помилки й показуємо загальне повідомлення.
        $this->collectSignupErrors($signupForm);
        if (!$this->hasErrors()) {
            $this->addError('email', Yii::t('main', 'Не вдалося завершити реєстрацію. Спробуйте пізніше.'));
        }

        return false;
    }

    /**
     * Переносить помилки з SignupForm у поточну модель.
     */
    private function collectSignupErrors(SignupForm $signupForm): void
    {
        foreach ($signupForm->getErrors() as $attribute => $messages) {
            // У старій формі логін зберігається в полі login, тому мапимо назву атрибуту.
            $targetAttribute = $attribute === 'username' ? 'login' : $attribute;
            foreach ($messages as $message) {
                $this->addError($targetAttribute, $message);
            }
        }
    }

}
