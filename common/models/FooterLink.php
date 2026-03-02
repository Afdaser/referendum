<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;

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
            [['anchor'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 2048],
            // Дозволяємо відносні шляхи (/about) і абсолютні URL.
            ['url', 'match', 'pattern' => '/^(\/|https?:\/\/).+/i', 'message' => 'Вкажіть відносний шлях (/page) або абсолютний URL (https://...).'],
        ];
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
