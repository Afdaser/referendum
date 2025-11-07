<?php

namespace backend\modules\poll\controllers;

use Yii;
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
     * Показує перелік підмов, для яких можна налаштувати статичний текст.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $languages = $this->findLanguagesWithTranslations();

        return $this->render('index', [
            'languages' => $languages,
        ]);
    }

    /**
     * Дозволяє редагувати статичний текст для окремої підмови.
     *
     * @param int $languageId Ідентифікатор мови, яку треба змінити.
     *
     * @return string|Response
     */
    public function actionUpdate(int $languageId)
    {
        $languages = $this->findLanguagesWithTranslations();
        $language = $this->findLanguage($languageId, $languages);

        $model = TagStaticText::find()->where(['language_id' => $language->id])->one();
        if (!$model) {
            $model = new TagStaticText([
                'language_id' => $language->id,
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            // Обрізаємо контент і видаляємо запис, якщо поле залишили порожнім.
            if (trim((string) $model->content) === '') {
                if (!$model->isNewRecord) {
                    $model->delete();
                }
                Yii::$app->session->setFlash('success', 'Статичний текст видалено.');
                return $this->redirect(['index']);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Статичний текст оновлено.');
                return $this->redirect(['index']);
            }

            Yii::$app->session->setFlash('error', 'Не вдалося зберегти статичний текст.');
        }

        return $this->render('update', [
            'language' => $language,
            'model' => $model,
            'tokens' => Tag::getInfoTextTokens(),
        ]);
    }

    /**
     * Шукає мови, для яких є перекладені теги або вже збережений статичний текст.
     *
     * @return Language[]
     */
    private function findLanguagesWithTranslations(): array
    {
        $tagLanguageIds = Tag::find()
            ->select('language_id')
            ->andWhere(['not', ['language_id' => null]])
            ->distinct()
            ->column();

        $staticTextLanguageIds = TagStaticText::find()
            ->select('language_id')
            ->distinct()
            ->column();

        // Додаємо ідентифікатори мов, які налаштовані у конфігурації сайту (щоб одразу редагувати потрібну підмову).
        $configuredLanguageIds = array_keys(Yii::$app->params['langDomains'] ?? []);

        // Об'єднуємо усі знайдені ідентифікатори, щоб показати повний перелік доступних підмов.
        $languageIds = array_merge($tagLanguageIds, $staticTextLanguageIds, $configuredLanguageIds);
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

        throw new \yii\web\NotFoundHttpException('Мову не знайдено або вона не має перекладів.');
    }
}
