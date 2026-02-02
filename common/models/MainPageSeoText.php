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
     * Маркер для кодування емодзі у випадку, якщо БД/з'єднання не підтримує utf8mb4.
     */
    private const EMOJI_ENTITY_PATTERN = '/[\x{10000}-\x{10FFFF}]/u';

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
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Якщо з'єднання не в utf8mb4, кодуємо емодзі у HTML-сутності, щоб їх не замінювало на "???".
        if ($this->shouldEncodeEmoji()) {
            $this->meta_title = $this->encodeEmojiEntities((string) $this->meta_title);
            $this->meta_description = $this->encodeEmojiEntities((string) $this->meta_description);
            $this->heading = $this->encodeEmojiEntities((string) $this->heading);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();

        // Розкодовуємо емодзі після отримання з БД, щоб їх бачили в адмінці та мета-тегах.
        $this->meta_title = $this->decodeEmojiEntities((string) $this->meta_title);
        $this->meta_description = $this->decodeEmojiEntities((string) $this->meta_description);
        $this->heading = $this->decodeEmojiEntities((string) $this->heading);
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

    /**
     * Перевіряє, чи потрібно кодувати емодзі у числові HTML-сутності.
     */
    private function shouldEncodeEmoji(): bool
    {
        $charset = static::getDb()->charset;
        if ($charset === null) {
            return true;
        }

        return mb_strtolower((string) $charset) !== 'utf8mb4';
    }

    /**
     * Кодує емодзі у числові HTML-сутності, щоб зберегти їх у БД з utf8.
     */
    private function encodeEmojiEntities(string $value): string
    {
        return preg_replace_callback(self::EMOJI_ENTITY_PATTERN, static function (array $matches): string {
            $codepoint = unpack('N', mb_convert_encoding($matches[0], 'UCS-4BE', 'UTF-8'))[1];
            return '&#x' . strtoupper(dechex($codepoint)) . ';';
        }, $value);
    }

    /**
     * Декодує HTML-сутності назад у емодзі.
     */
    private function decodeEmojiEntities(string $value): string
    {
        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
