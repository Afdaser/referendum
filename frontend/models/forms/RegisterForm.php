<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\User;

/**
 * Форма реєстрації користувача.
 */
class RegisterForm extends Model
{
    public $email;
    public $login;
    public $password;
    public $passwordRepeat;
    public $agreeTerms;
    public $verifyCode;

    /**
     * Правила валідації.
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email', 'login', 'password'], 'required', 'message' => Yii::t('main', 'Введіть') . ' {attribute}.'],
            ['email', 'email', 'message' => Yii::t('main', 'Введіть email коректного формату!')],
            ['login', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username', 'message' => Yii::t('main', 'Оберіть інший логін!')],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email', 'message' => Yii::t('main', 'Оберіть інший email!')],
            ['password', 'string', 'length' => [6, 255], 'message' => Yii::t('main', 'Довжина пароля має бути від 6 до 255 символів.')],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('main', 'Повторіть пароль вточності!')],
            ['passwordRepeat', 'required', 'message' => Yii::t('main', 'Повторіть пароль.')],
            ['agreeTerms', 'required', 'requiredValue' => 1, 'message' => Yii::t('main', 'Ви маєте погодитись з правилами та умовами сайту.')],
            ['verifyCode', 'captcha', 'captchaAction' => '/site/captcha', 'message' => Yii::t('main', 'Введіть вірний код перевірки.')],
        ];
    }

    /**
     * Назви полів.
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'login' => Yii::t('main', 'Логін'),
            'email' => Yii::t('main', 'Email'),
            'password' => Yii::t('main', 'Пароль'),
            'passwordRepeat' => 'Password Again',
            'agreeTerms' => 'Accept Terms',
        ];
    }

    /**
     * Реєструє користувача.
     * @return bool
     */
    public function register(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->username = Html::encode($this->login);
        $user->email = Html::encode($this->email);
        $user->password = $this->password;
        $user->status = User::STATUS_INACTIVE;

        if ($user->save()) {
            Yii::$app->user->login($user, 3600 * 24 * 30);
            return true;
        }

        return false;
    }
}

