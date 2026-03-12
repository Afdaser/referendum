<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use Throwable;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return null;
        }

        $requiresEmailConfirmation = $this->isEmailConfirmationEnabled();

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($requiresEmailConfirmation) {
            // Якщо підтвердження email увімкнене — залишаємо користувача неактивним і генеруємо токен.
            $user->generateEmailVerificationToken();
        } else {
            // У поточній конфігурації підтвердження email вимкнене,
            // тому активуємо акаунт одразу, щоб користувач міг увійти без ручної активації.
            $user->status = User::STATUS_ACTIVE;
        }

        if (!$user->save()) {
            return false;
        }

        // Надсилаємо verification-лист лише коли реально використовується флоу підтвердження.
        if ($requiresEmailConfirmation && !$this->sendEmail($user)) {
            // Лист не має призводити до 500: лог фіксує проблему для адміністратора.
            Yii::warning(sprintf('Не вдалося надіслати лист підтвердження для користувача ID=%d', (int)$user->id), __METHOD__);
        }

        return true;
    }

    /**
     * Перевіряє, чи увімкнений флоу підтвердження email у модулі користувачів.
     *
     * @return bool
     */
    private function isEmailConfirmationEnabled(): bool
    {
        try {
            $module = Yii::$app->getModule('user');

            return $module !== null && (bool)$module->enableConfirmation;
        } catch (Throwable $exception) {
            // Якщо модуль недоступний у поточному контексті — працюємо у безпечному режимі без підтвердження.
            Yii::warning('Не вдалося отримати налаштування модуля user для перевірки підтвердження email.', __METHOD__);

            return false;
        }
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}


