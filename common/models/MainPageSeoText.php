<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель для SEO-тексту головної сторінки по доменах/піддоменах.
 *
 * @property int $id
 * @property string $domain
 * @property string|null $heading
 * @property string|null $content
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MainPageSeoText extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%main_page_seo_text}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['domain'], 'required'],
            [['content', 'meta_description'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['domain', 'heading', 'meta_title'], 'string', 'max' => 255],
            [['domain'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Домен',
            'heading' => 'Заголовок H1',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'content' => 'SEO-текст',
            'created_at' => 'Створено',
            'updated_at' => 'Оновлено',
        ];
    }

    /**
     * Перевіряє, чи всі текстові поля порожні.
     */
    public function isEmpty(): bool
    {
        // Вважаємо запис порожнім, якщо всі SEO-поля не заповнені.
        return trim((string) $this->heading) === ''
            && trim((string) $this->content) === ''
            && trim((string) $this->meta_title) === ''
            && trim((string) $this->meta_description) === '';
    }

    /**
     * Шукає SEO-текст для поточного домену або основного домену.
     */
    public static function findForHost(string $hostName): ?self
    {
        $normalizedHost = mb_strtolower(trim($hostName));
        if ($normalizedHost === '') {
            return null;
        }

        // Спочатку шукаємо точний збіг для домену/піддомену.
        $record = static::find()->where(['domain' => $normalizedHost])->one();
        if ($record !== null) {
            return $record;
        }

        // Якщо не знайшли — пробуємо підставити основний домен, щоб не ламати головну сторінку.
        if (defined('SITE_DOMAIN')) {
            $fallbackDomain = mb_strtolower((string) SITE_DOMAIN);
            if ($fallbackDomain !== '' && $fallbackDomain !== $normalizedHost) {
                return static::find()->where(['domain' => $fallbackDomain])->one();
            }
        }

        return null;
    }
}
