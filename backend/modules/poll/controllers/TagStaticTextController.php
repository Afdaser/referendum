<?php

namespace backend\modules\poll\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\base\Model;
use common\models\Language;
use common\models\Tag;
use common\models\TagStaticText;
use common\models\TagFaq;

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

        $faqModels = TagFaq::find()
            ->where(['language_id' => $language->id])
            ->orderBy(['position' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        $existingFaqs = [];
        foreach ($faqModels as $faq) {
            $existingFaqs[$faq->id] = $faq;
        }

        if (empty($faqModels)) {
            $faqModels = [new TagFaq(['language_id' => $language->id])];
        }

        $post = Yii::$app->request->post();
        if (!empty($post)) {
            $model->load($post);

            $faqModels = $this->prepareFaqModels($language->id, $existingFaqs, $post);
            Model::loadMultiple($faqModels, $post);
            foreach ($faqModels as $faqModel) {
                $faqModel->language_id = $language->id;
            }

            $faqValid = Model::validateMultiple($faqModels);

            if ($faqValid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $staticContent = trim((string) $model->content);
                    if ($staticContent === '') {
                        if (!$model->isNewRecord) {
                            $model->delete();
                        }
                    } else {
                        if (!$model->save()) {
                            throw new \RuntimeException('Не вдалося зберегти статичний текст.');
                        }
                    }

                    $savedFaqIds = [];
                    $position = 1;
                    foreach ($faqModels as $faqModel) {
                        if ($faqModel->isEmpty()) {
                            if (!$faqModel->isNewRecord) {
                                $faqModel->delete();
                            }
                            continue;
                        }

                        $faqModel->position = $position++;
                        if (!$faqModel->save()) {
                            throw new \RuntimeException('Не вдалося зберегти FAQ.');
                        }
                        $savedFaqIds[] = $faqModel->id;
                    }

                    $existingIds = array_keys($existingFaqs);
                    $toDelete = array_diff($existingIds, $savedFaqIds);
                    if (!empty($toDelete)) {
                        TagFaq::deleteAll(['id' => $toDelete]);
                    }

                    $transaction->commit();

                    Yii::$app->session->setFlash('success', 'Налаштування статичного тексту та FAQ оновлено.');
                    return $this->redirect(['index']);
                } catch (\Throwable $exception) {
                    $transaction->rollBack();
                    Yii::error($exception->getMessage(), __METHOD__);
                    Yii::$app->session->setFlash('error', 'Під час збереження сталася помилка. Перевірте форму та спробуйте ще раз.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Будь ласка, виправте помилки у блоці FAQ.');
            }
        }

        if (empty($faqModels)) {
            $faqModels = [new TagFaq(['language_id' => $language->id])];
        }

        return $this->render('update', [
            'language' => $language,
            'model' => $model,
            'tokens' => Tag::getInfoTextTokens(),
            'faqModels' => $faqModels,
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

    /**
     * Створює масив моделей FAQ на основі наявних записів та вхідних даних форми.
     * Це дозволяє додавати нові питання/відповіді та редагувати існуючі у довільній кількості.
     *
     * @param int $languageId
     * @param TagFaq[] $existingFaqs
     * @param array $post
     *
     * @return TagFaq[]
     */
    private function prepareFaqModels(int $languageId, array $existingFaqs, array $post): array
    {
        $faqData = $post['TagFaq'] ?? [];
        $faqModels = [];

        foreach ($faqData as $index => $faqAttributes) {
            $faqId = isset($faqAttributes['id']) ? (int) $faqAttributes['id'] : null;
            if ($faqId && isset($existingFaqs[$faqId])) {
                $faqModels[$index] = $existingFaqs[$faqId];
            } else {
                $faqModels[$index] = new TagFaq(['language_id' => $languageId]);
            }
        }

        return $faqModels;
    }
}
