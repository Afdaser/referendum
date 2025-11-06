<?php

namespace backend\modules\poll\controllers;

use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use common\models\Language;
use common\models\Tag;
use common\models\TagStaticText;

/**
 * Керує сторінкою "Статичний текст на тегах" в адмінці.
 */
class TagStaticTextController extends Controller
{
    /**
     * Відображає та зберігає статичні тексти для всіх мов.
     *
     * @return string|Response
     */
    public function actionIndex()
    {
        $languages = Language::find()->orderBy(['id' => SORT_ASC])->all();
        $models = [];

        foreach ($languages as $language) {
            $models[$language->id] = TagStaticText::find()->where(['language_id' => $language->id])->one();
            if (!$models[$language->id]) {
                $models[$language->id] = new TagStaticText([
                    'language_id' => $language->id,
                ]);
            }
        }

        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($models as $model) {
                    $model->language_id = (int) $model->language_id;
                    if (trim((string) $model->content) === '') {
                        if (!$model->isNewRecord) {
                            $model->delete();
                        }
                        continue;
                    }
                    if (!$model->save(false)) {
                        throw new \RuntimeException('Не вдалося зберегти статичний текст.');
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Статичні тексти успішно оновлено.');
                return $this->redirect(['index']);
            } catch (\Throwable $exception) {
                $transaction->rollBack();
                Yii::error($exception->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', 'Сталася помилка під час збереження.');
            }
        }

        return $this->render('index', [
            'languages' => $languages,
            'models' => $models,
            'tokens' => Tag::getInfoTextTokens(),
        ]);
    }
}
