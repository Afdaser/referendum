<?php

namespace backend\modules\poll\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\models\Language;
use common\models\Tag;
use common\models\TagStaticText;
use common\models\TagStaticFaq;

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

        $faqItems = TagStaticFaq::find()
            ->where(['language_id' => $language->id])
            ->orderBy(['position' => SORT_ASC, 'id' => SORT_ASC])
            ->all();
        $faqFormModels = $faqItems;

        if ($model->load(Yii::$app->request->post())) {
            $model->content = trim((string) $model->content);

            $faqPost = Yii::$app->request->post('TagStaticFaq', []);
            $faqFormModels = [];
            $faqModelsToSave = [];
            $position = 0;
            $hasFaqErrors = false;

            foreach ($faqPost as $faqRow) {
                $question = trim((string) ($faqRow['question'] ?? ''));
                $answer = trim((string) ($faqRow['answer'] ?? ''));
                $id = isset($faqRow['id']) ? (int) $faqRow['id'] : null;

                if ($question === '' && $answer === '') {
                    // Порожні пункти ігноруємо — так можна видалити існуючий запис.
                    continue;
                }

                $faqModel = null;
                if ($id) {
                    $faqModel = TagStaticFaq::findOne(['id' => $id, 'language_id' => $language->id]);
                }
                if (!$faqModel) {
                    $faqModel = new TagStaticFaq(['language_id' => $language->id]);
                }

                $faqModel->question = $question;
                $faqModel->answer = $answer;
                $faqModel->language_id = $language->id;
                $faqModel->position = $position++;

                if (!$faqModel->validate()) {
                    $hasFaqErrors = true;
                }

                $faqModelsToSave[] = $faqModel;
                $faqFormModels[] = $faqModel;
            }

            $hasErrors = !$model->validate() || $hasFaqErrors;

            if ($hasErrors) {
                if ($hasFaqErrors) {
                    Yii::$app->session->setFlash('error', 'Перевірте FAQ: кожне запитання повинно мати відповідь.');
                } elseif ($model->hasErrors()) {
                    Yii::$app->session->setFlash('error', 'Не вдалося зберегти статичний текст.');
                }
            } else {
                $transaction = TagStaticText::getDb()->beginTransaction();
                try {
                    if ($model->content === '') {
                        if (!$model->isNewRecord) {
                            $model->delete();
                        }
                    } elseif (!$model->save(false)) {
                        throw new \RuntimeException('Не вдалося зберегти статичний текст.');
                    }

                    $savedIds = [];
                    foreach ($faqModelsToSave as $faqModel) {
                        $faqModel->save(false);
                        $savedIds[] = $faqModel->id;
                    }

                    if (!empty($savedIds)) {
                        TagStaticFaq::deleteAll([
                            'and',
                            ['language_id' => $language->id],
                            ['not in', 'id', $savedIds],
                        ]);
                    } else {
                        TagStaticFaq::deleteAll(['language_id' => $language->id]);
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Статичний текст та FAQ оновлено.');
                    return $this->redirect(['index']);
                } catch (\Throwable $exception) {
                    $transaction->rollBack();
                    Yii::error($exception->getMessage(), __METHOD__);
                    Yii::$app->session->setFlash('error', 'Сталася помилка під час збереження.');
                }
            }
        }

        $faqFormModels = $this->ensureFaqPlaceholders($faqFormModels, $language->id);

        return $this->render('update', [
            'language' => $language,
            'model' => $model,
            'tokens' => Tag::getInfoTextTokens(),
            'faqModels' => $faqFormModels,
        ]);
    }

    /**
     * Гарантує наявність щонайменше одного порожнього блоку для додавання FAQ.
     *
     * @param TagStaticFaq[] $faqModels
     * @param int $languageId
     *
     * @return TagStaticFaq[]
     */
    private function ensureFaqPlaceholders(array $faqModels, int $languageId): array
    {
        $faqModels = array_values($faqModels);

        $hasEmptyRow = false;
        foreach ($faqModels as $faqModel) {
            if (trim((string) $faqModel->question) === '' && trim((string) $faqModel->answer) === '') {
                $hasEmptyRow = true;
                break;
            }
        }

        if (!$hasEmptyRow) {
            $faqModels[] = new TagStaticFaq(['language_id' => $languageId]);
        }

        // Додаємо ще один пустий рядок для швидкого додавання кількох пунктів поспіль.
        $faqModels[] = new TagStaticFaq(['language_id' => $languageId]);

        return $faqModels;
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
