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
        $availableLocales = $this->resolveAvailableLocales();
        $models = [];
        $groupedLanguages = [];

        foreach ($languages as $language) {
            // Пропускаємо мови без підтверджених перекладів, щоб не показувати порожні вкладки.
            if (!$this->isLanguageTranslated($language, $availableLocales)) {
                continue;
            }

            // Тягнемо або створюємо модель для поточної підмови.
            $models[$language->id] = TagStaticText::find()->where(['language_id' => $language->id])->one();
            if (!$models[$language->id]) {
                $models[$language->id] = new TagStaticText([
                    'language_id' => $language->id,
                ]);
            }

            $groupKey = $this->extractLanguageGroupKey($language);
            if (!isset($groupedLanguages[$groupKey])) {
                $groupedLanguages[$groupKey] = [
                    'label' => $this->buildGroupLabel($language),
                    'languages' => [],
                ];
            }
            $groupedLanguages[$groupKey]['languages'][] = $language;
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
            'groupedLanguages' => $groupedLanguages,
        ]);
    }

    /**
     * Повертає перелік локалей, для яких існують переклади або налаштування у конфігурації.
     *
     * @return string[]
     */
    private function resolveAvailableLocales(): array
    {
        $locales = [];
        $messagesPath = Yii::getAlias('@common/messages');
        if (is_dir($messagesPath)) {
            foreach (scandir($messagesPath) ?: [] as $directory) {
                if ($directory === '.' || $directory === '..') {
                    continue;
                }
                $fullPath = $messagesPath . DIRECTORY_SEPARATOR . $directory;
                if (is_dir($fullPath)) {
                    $locales[] = $this->normalizeLocale($directory);
                }
            }
        }

        $configured = Yii::$app->params['locales'] ?? [];
        foreach ((array) $configured as $locale) {
            if (is_string($locale)) {
                $locales[] = $this->normalizeLocale($locale);
            }
        }

        $locales = array_values(array_unique(array_filter($locales)));
        sort($locales);

        return $locales;
    }

    /**
     * Перевіряє, чи має мова готовий переклад.
     */
    private function isLanguageTranslated(Language $language, array $availableLocales): bool
    {
        $locale = $this->normalizeLocale($language->locale);
        return $locale !== '' && in_array($locale, $availableLocales, true);
    }

    /**
     * Створює ключ групи для вкладки з урахуванням основної мови.
     */
    private function extractLanguageGroupKey(Language $language): string
    {
        $locale = $this->normalizeLocale($language->locale);
        $primary = explode('-', $locale)[0] ?? $locale;
        $primary = $primary !== '' ? $primary : 'lang';

        return preg_replace('/[^a-z0-9\-]/', '', $primary) ?: 'lang';
    }

    /**
     * Формує людинозрозумілий заголовок для групи підмов.
     */
    private function buildGroupLabel(Language $language): string
    {
        $locale = $this->normalizeLocale($language->locale);
        if ($locale === '') {
            return $language->title;
        }

        return sprintf('%s (%s)', $language->title, $locale);
    }

    /**
     * Нормалізує запис локалі до формату `ll-RR`.
     */
    private function normalizeLocale(?string $locale): string
    {
        $locale = trim((string) $locale);
        if ($locale === '') {
            return '';
        }

        $locale = str_replace('_', '-', $locale);
        $parts = explode('-', $locale);

        if (count($parts) === 1) {
            return strtolower($parts[0]);
        }

        $primary = strtolower(array_shift($parts));
        $region = strtoupper(array_shift($parts));

        return $primary . '-' . $region;
    }
}
