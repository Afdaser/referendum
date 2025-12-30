<?php

namespace backend\modules\poll\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\models\Poll;
use common\models\PollStaticText;
use common\models\search\PollSearch;

/**
 * Керує сторінкою "Статичний текст опитувань" в адмінці.
 */
class PollStaticTextController extends Controller
{
    /**
     * Показує список опитувань для редагування статичного тексту.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new PollSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Редагує статичний текст для конкретного опитування.
     *
     * @param int $id ID опитування.
     *
     * @return string|Response
     */
    public function actionUpdate(int $id)
    {
        $poll = $this->findPoll($id);
        $model = PollStaticText::find()->where(['poll_id' => $poll->id])->one();
        if (!$model) {
            $model = new PollStaticText(['poll_id' => $poll->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Нормалізуємо введення, щоб порожні значення не зберігались як пробіли.
            $model->content = trim((string) $model->content);
            $model->heading = trim((string) $model->heading);
            $model->meta_title = trim((string) $model->meta_title);
            $model->meta_description = trim((string) $model->meta_description);

            if ($model->validate()) {
                if ($model->isEmpty()) {
                    if (!$model->isNewRecord) {
                        $model->delete();
                    }
                } else {
                    $model->save(false);
                }

                Yii::$app->session->setFlash('success', 'Статичний текст опитування оновлено.');
                return $this->redirect(['index']);
            }

            Yii::$app->session->setFlash('error', 'Не вдалося зберегти статичний текст.');
        }

        return $this->render('update', [
            'poll' => $poll,
            'model' => $model,
        ]);
    }

    /**
     * Повертає модель опитування або кидає 404.
     */
    private function findPoll(int $id): Poll
    {
        $poll = Poll::findOne(['id' => $id]);
        if (!$poll) {
            throw new \yii\web\NotFoundHttpException('Опитування не знайдено.');
        }

        return $poll;
    }
}
