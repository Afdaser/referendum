<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Налаштування cookie consent для конкретного піддомену/локалі.
 *
 * @property int $id
 * @property string $host
 * @property string $language_code
 * @property string $banner_text
 * @property string $policy_url
 * @property string $essential_button_label
 * @property string $accept_button_label
 * @property int $is_active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class CookieConsentSetting extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%cookie_consent_setting}}';
    }

    public function rules(): array
    {
        return [
            [['host', 'banner_text', 'policy_url'], 'required'],
            [['banner_text'], 'string'],
            [['is_active', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['host'], 'string', 'max' => 255],
            [['language_code'], 'string', 'max' => 32],
            [['policy_url'], 'string', 'max' => 2048],
            [['essential_button_label', 'accept_button_label'], 'string', 'max' => 100],
            [['host', 'language_code', 'policy_url', 'essential_button_label', 'accept_button_label'], 'trim'],
            [['language_code'], 'default', 'value' => ''],
            [['essential_button_label'], 'default', 'value' => 'Essential only'],
            [['accept_button_label'], 'default', 'value' => 'Accept all'],
            [['is_active'], 'default', 'value' => 1],
            [['host'], 'match', 'pattern' => '/^(\*|[a-z0-9.-]+)$/i', 'message' => 'Вкажіть хост (напр. en.referendum.social) або *.'],
            [['policy_url'], 'match', 'pattern' => '/^(\/|https?:\/\/).+/i', 'message' => 'Вкажіть відносний шлях (/page) або абсолютний URL (https://...).'],
            [['language_code'], 'validateLanguageCode'],
            [['host', 'language_code'], 'unique', 'targetAttribute' => ['host', 'language_code'], 'message' => 'Запис для цієї пари хост + локаль уже існує.'],
        ];
    }

    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        // Не нашкодь: приводимо хост до нижнього регістру, щоб уникнути дублювань через регістр.
        $this->host = mb_strtolower((string) $this->host);
        $this->policy_url = $this->normalizeUrl((string) $this->policy_url);

        return true;
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }

        return preg_replace('/\s+/', '-', $url);
    }

    public function validateLanguageCode(string $attribute): void
    {
        if ((string) $this->$attribute === '') {
            return;
        }

        $exists = Language::find()->where(['locale' => $this->$attribute])->exists();
        if (!$exists) {
            $this->addError($attribute, 'Оберіть мову/країну з доступного списку.');
        }
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'host' => 'Хост/піддомен',
            'language_code' => 'Локаль',
            'banner_text' => 'Текст банера',
            'policy_url' => 'URL Cookie Policy',
            'essential_button_label' => 'Текст кнопки "Essential only"',
            'accept_button_label' => 'Текст кнопки "Accept all"',
            'is_active' => 'Активно',
        ];
    }

    /**
     * Повертає опції доступних локалей для селектора в адмінці.
     *
     * @return array<string, string>
     */
    public static function getLanguageOptions(): array
    {
        $languages = Language::find()->orderBy(['title' => SORT_ASC])->all();
        $options = [];

        foreach ($languages as $language) {
            $countryTitle = Language::getCountryTitle($language);
            $options[$language->locale] = sprintf('%s — %s (%s)', $language->title, $countryTitle, $language->locale);
        }

        return $options;
    }

    public static function getLanguageLabel(string $languageCode): string
    {
        if ($languageCode === '') {
            return 'Усі локалі';
        }

        return ArrayHelper::getValue(self::getLanguageOptions(), $languageCode, $languageCode);
    }

    /**
     * Підбирає найбільш специфічне активне налаштування для поточного host + locale.
     */
    public static function resolveForRequest(string $host, string $locale): ?self
    {
        $host = mb_strtolower(trim($host));
        $baseLocale = explode('-', $locale)[0] ?? $locale;

        $candidates = self::find()
            ->where(['is_active' => 1])
            ->andWhere(['host' => [$host, '*']])
            ->andWhere(['language_code' => [$locale, $baseLocale, '']])
            ->all();

        if (empty($candidates)) {
            return null;
        }

        $best = null;
        $bestScore = -1;
        foreach ($candidates as $candidate) {
            $score = 0;

            $score += ($candidate->host === $host) ? 100 : 10;

            if ($candidate->language_code === $locale) {
                $score += 20;
            } elseif ($candidate->language_code === $baseLocale) {
                $score += 15;
            } else {
                $score += 5;
            }

            if ($score > $bestScore) {
                $best = $candidate;
                $bestScore = $score;
            }
        }

        return $best;
    }
}

