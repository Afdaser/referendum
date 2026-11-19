<?php

namespace app\modules\ajax\controllers;

use frontend\models\forms\RegisterForm;
use frontend\models\forms\PollForm as Poll;
use yii\web\Controller;

/**
 * Контролер модальних вікон модуля `ajax`.
 */
class ModalController extends Controller
{
    public function actionRegistrtionStepOne()
    {
        return $this->renderPartial('registrtion-step-one', [
            'registerForm' => new RegisterForm(),
        ]);
    }

    public function actionCreatePollStepOne()
    {
        $pollModel = new Poll();
        $pollModel->presetAttributes();

        return $this->renderPartial('create-poll-step-one', [
            'pollModel' => $pollModel,
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
