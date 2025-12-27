<?php

namespace backend\modules\poll\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\models\Language;
use common\models\Poll;
use common\models\PollStaticText;

/**
 * Керує сторінкою "Статичний текст на опитуваннях" в адмінці.
 */
class PollStaticTextController extends Controller
{
    /**
     * Показує перелік мов, для яких можна налаштувати статичний текст.
     */
    public function actionIndex(): string
    {
        $languages = $this->findLanguagesWithPolls();

        return $this->render('index', [
            'languages' => $languages,
        ]);
    }

    /**
     * Дозволяє редагувати статичний текст для окремої мови.
     */
    public function actionUpdate(int $languageId)
    {
        $languages = $this->findLanguagesWithPolls();
        $language = $this->findLanguage($languageId, $languages);

        $model = PollStaticText::find()->where(['language_id' => $language->id])->one();
        if (!$model) {
            $model = new PollStaticText([
                'language_id' => $language->id,
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Нормалізуємо введення, щоб порожні значення не зберігались як пробіли.
            $model->heading = trim((string) $model->heading);
            $model->summary = trim((string) $model->summary);
            $model->options_intro = trim((string) $model->options_intro);
            $model->most_popular = trim((string) $model->most_popular);

            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Не вдалося зберегти статичний текст.');
            } else {
                if ($model->isEmpty()) {
                    if (!$model->isNewRecord) {
                        $model->delete();
                    }
                } elseif (!$model->save(false)) {
                    Yii::$app->session->setFlash('error', 'Не вдалося зберегти статичний текст.');
                } else {
                    Yii::$app->session->setFlash('success', 'Статичний текст оновлено.');
                }

                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'language' => $language,
            'model' => $model,
            'tokens' => Poll::getStaticTextTokens(),
        ]);
    }

    /**
     * Шукає мови, для яких є опитування або вже збережений статичний текст.
     */
    private function findLanguagesWithPolls(): array
    {
        $pollLanguageIds = Poll::find()
            ->select('poll_language_id')
            ->andWhere(['not', ['poll_language_id' => null]])
            ->distinct()
            ->column();

        $staticTextLanguageIds = PollStaticText::find()
            ->select('language_id')
            ->distinct()
            ->column();

        // Додаємо конфігуровані мови, щоб список був повним.
        $configuredLanguageIds = array_keys(Yii::$app->params['langDomains'] ?? []);

        $languageIds = array_merge($pollLanguageIds, $staticTextLanguageIds, $configuredLanguageIds);
        $languageIds = array_unique(array_filter(array_map('intval', $languageIds)));

        if (empty($languageIds)) {
            return [];
        }

        return Language::find()
            ->where(['id' => $languageIds])
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }

    /**
     * Повертає модель мови з попередньо завантаженого переліку або кидає 404.
     */
    private function findLanguage(int $languageId, array $languages): Language
    {
        foreach ($languages as $language) {
            if ((int) $language->id === $languageId) {
                return $language;
            }
        }

        throw new \yii\web\NotFoundHttpException('Мову не знайдено або вона не має перекладів.');
    }
}
