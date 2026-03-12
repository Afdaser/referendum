<?php

namespace app\modules\ajax\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use frontend\models\forms\RegisterForm;
use frontend\models\forms\PollForm;

/**
 * Контролер для AJAX-модалок сайту.
 *
 * Важливо: цей клас повинен жити саме в namespace модуля `app\modules\ajax\controllers`,
 * інакше маршрути `/ajax/modal/*` повертають 500 (контролер не знаходиться роутером).
 */
class ModalController extends Controller
{
    /**
     * Рендерить перший крок модалки реєстрації.
     *
     * @return string
     */
    public function actionRegistrtionStepOne()
    {
        // Віддаємо порожню форму для завантаження в модалку через jQuery.load().
        $registerForm = new RegisterForm();

        return $this->renderAjax('registrtion-step-one', [
            'registerForm' => $registerForm,
        ]);
    }

    /**
     * Рендерить перший крок модалки створення опитування.
     *
     * @return string
     */
    public function actionCreatePollStepOne()
    {
        // Підготовлюємо форму опитування з дефолтними параметрами.
        $model = new PollForm();
        $model->presetAttributes();

        return $this->renderAjax('create-poll-step-one', [
            'model' => $model,
        ]);
    }

    /**
     * Приймає submit форми опитування з модалки.
     *
     * Повертаємо JSON-відповідь, щоб фронт не падав на 500.
     * Реальне збереження опитування виконується в профільному poll-контролері,
     * тож тут навмисно не дублюємо логіку (принцип "не нашкодь").
     *
     * @return array<string,mixed>
     */
    public function actionStorePollForm()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Явно відповідаємо фронту контрольованою помилкою замість внутрішньої 500.
        return [
            'poll_ok' => false,
            'error_message' => Yii::t('poll', 'Форма опитування тимчасово недоступна. Оновіть сторінку та спробуйте ще раз.'),
        ];
    }
}
