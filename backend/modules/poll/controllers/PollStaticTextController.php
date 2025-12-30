<?php

namespace backend\modules\poll\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\models\Language;
use common\models\Poll;
use common\models\PollStaticText;

/**
 * Керує сторінкою "Статичний текст для опитувань" в адмінці.
 */
class PollStaticTextController extends Controller
{
    /**
     * Показує перелік підмов, для яких можна налаштувати мета-теги опитувань.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $languages = $this->findLanguagesWithPolls();

        return $this->render('index', [
            'languages' => $languages,
        ]);
    }

    /**
     * Дозволяє редагувати мета-теги для окремої підмови.
     *
     * @param int $languageId Ідентифікатор мови, яку треба змінити.
     *
     * @return string|Response
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
            // Додаємо підтримку HTML-блоку для опису статистики опитування.
            $model->content = trim((string) $model->content);
            $model->heading = trim((string) $model->heading);
            $model->meta_title = trim((string) $model->meta_title);
            $model->meta_description = trim((string) $model->meta_description);

            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', 'Не вдалося зберегти мета-теги.');
            } else {
                if ($model->isEmpty()) {
                    if (!$model->isNewRecord) {
                        $model->delete();
                    }
                } elseif (!$model->save(false)) {
                    Yii::$app->session->setFlash('error', 'Не вдалося зберегти мета-теги.');
                    return $this->render('update', [
                        'language' => $language,
                        'model' => $model,
                        'metaTokens' => Poll::getStaticMetaTokens(),
                        'infoTokens' => Poll::getStaticInfoTokens(),
                    ]);
                }

                Yii::$app->session->setFlash('success', 'Статичний текст та мета-теги для опитувань оновлено.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'language' => $language,
            'model' => $model,
            'metaTokens' => Poll::getStaticMetaTokens(),
            'infoTokens' => Poll::getStaticInfoTokens(),
        ]);
    }

    /**
     * Шукає мови, для яких є опитування або вже збережені мета-теги.
     *
     * @return Language[]
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

        // Додаємо ідентифікатори мов, які налаштовані у конфігурації сайту.
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
     *
     * @param int $languageId
     * @param Language[] $languages
     *
     * @return Language
     */
    private function findLanguage(int $languageId, array $languages): Language
    {
        foreach ($languages as $language) {
            if ((int) $language->id === $languageId) {
                return $language;
            }
        }

        throw new \yii\web\NotFoundHttpException('Мову не знайдено або вона не має опитувань.');
    }
}
