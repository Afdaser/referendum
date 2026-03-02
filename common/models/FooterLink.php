<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Модель посилань футера.
 *
 * @property int $id
 * @property string|null $language_code
 * @property string $anchor
 * @property string $url
 * @property int $sort_order
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class FooterLink extends ActiveRecord
{
    /** @var array<string,string>|null Кешуємо опції локалей в межах запиту, щоб не робити зайві звернення до БД. */
    private static $languageOptionsCache = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%footer_link}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['anchor', 'url'], 'required'],
            [['sort_order', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['language_code'], 'string', 'max' => 32],
            [['language_code'], 'trim'],
            [['language_code'], 'default', 'value' => null],
            [['language_code'], 'validateLanguageCode'],
            [['anchor'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 2048],
            // Дозволяємо відносні шляхи (/about) і абсолютні URL.
            ['url', 'match', 'pattern' => '/^(\/|https?:\/\/).+/i', 'message' => 'Вкажіть відносний шлях (/page) або абсолютний URL (https://...).'],
        ];
    }


    /**
     * Нормалізуємо URL перед валідацією, щоб пробіли в шляхах ставали дефісами.
     */
    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        $this->url = $this->normalizeUrl((string) $this->url);

        return true;
    }

    /**
     * Для відносних URL замінюємо пробіли на дефіси (наприклад, /privacy policy -> /privacy-policy).
     */
    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        // Зовнішні URL не змінюємо, щоб не пошкодити повні адреси.
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        // Якщо шлях відносний, прибираємо зайві пробіли та міняємо їх на дефіси.
        $url = preg_replace('/\s+/', '-', $url);

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'language_code' => 'Код мови',
            'anchor' => 'Анкор',
            'url' => 'URL',
            'sort_order' => 'Порядок',
            'created_at' => 'Створено',
            'updated_at' => 'Оновлено',
        ];
    }

    /**
     * Перевіряємо, що код мови/країни існує серед доступних локалей у БД.
     */
    public function validateLanguageCode(string $attribute): void
    {
        if (trim((string) $this->$attribute) === '') {
            return;
        }

        $exists = Language::find()->where(['locale' => $this->$attribute])->exists();
        if (!$exists) {
            $this->addError($attribute, 'Оберіть мову/країну з доступного списку.');
        }
    }

    /**
     * Повертає опції для селектора мови/країни у форматі locale => label.
     *
     * @return array<string, string>
     */
    public static function getLanguageOptions(): array
    {
        if (self::$languageOptionsCache !== null) {
            return self::$languageOptionsCache;
        }

        $languages = Language::find()->orderBy(['title' => SORT_ASC])->all();
        $options = [];

        foreach ($languages as $language) {
            // Додаємо і мову, і країну, щоб розрізняти однакову мову для різних країн.
            $countryTitle = Language::getCountryTitle($language);
            $options[$language->locale] = sprintf('%s — %s (%s)', $language->title, $countryTitle, $language->locale);
        }

        self::$languageOptionsCache = $options;

        return self::$languageOptionsCache;
    }

    /**
     * Людський підпис для збереженого коду локалі.
     */
    public static function getLanguageLabel(?string $languageCode): string
    {
        if (trim((string) $languageCode) === '') {
            return 'Усі мови/країни';
        }

        $options = self::getLanguageOptions();

        return ArrayHelper::getValue($options, $languageCode, (string) $languageCode);
    }


    /**
     * Повертає посилання для конкретної мови + універсальні посилання без мови.
     *
     * @param string $currentLanguage
     * @return self[]
     */
    public static function getForLanguage(string $currentLanguage): array
    {
        $baseLanguage = explode('-', $currentLanguage)[0] ?? $currentLanguage;

        return self::find()
            ->where(['language_code' => null])
            ->orWhere(['language_code' => ''])
            ->orWhere(['language_code' => $currentLanguage])
            ->orWhere(['language_code' => $baseLanguage])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])
            ->all();
    }
}
