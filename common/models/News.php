<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * Модель новин/статей для контентного розділу.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $h1
 * @property string|null $desc
 * @property string|null $image
 * @property string|null $published_at
 * @property int $is_published
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class News extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%news}}';
    }

    public function beforeValidate(): bool
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        // Автоматично формуємо slug із заголовка, щоб редактору було простіше.
        if (empty($this->slug) && !empty($this->title)) {
            $this->slug = Inflector::slug($this->title);
        }

        return true;
    }

    public function rules(): array
    {
        return [
            [['title', 'slug'], 'required'],
            [['excerpt', 'content', 'desc'], 'string'],
            [['image'], 'string', 'max' => 255],
            [['published_at'], 'safe'],
            [['is_published', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'h1'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 128],
            [['slug'], 'unique'],
            [['is_published'], 'default', 'value' => 0],
            [['is_published'], 'in', 'range' => [0, 1]],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'slug' => 'Slug',
            'excerpt' => 'Короткий опис',
            'content' => 'Контент',
            'h1' => 'H1',
            'desc' => 'meta-description',
            'image' => 'Зображення',
            'published_at' => 'Дата публікації',
            'is_published' => 'Опубліковано',
            'created_at' => 'Створено',
            'updated_at' => 'Оновлено',
            'created_by' => 'Створив',
            'updated_by' => 'Оновив',
        ];
    }


    /**
     * Повертає абсолютний URL зображення для публічної сторінки новини.
     */
    public function getImageUrl(): ?string
    {
        $image = trim((string) $this->image);
        if ($image === '') {
            return null;
        }

        // Не нашкодити: підтримуємо і повні URL, і локальні шляхи з адмінки.
        if (preg_match('~^https?://~i', $image) === 1) {
            return $image;
        }

        return Url::to('/' . ltrim($image, '/'), true);
    }

    /**
     * Формує безпечний alt для hero-зображення новини.
     */
    public function getImageAlt(): string
    {
        $title = trim((string) ($this->h1 ?: $this->title));

        return $title !== '' ? $title : 'Зображення новини';
    }

    public static function getPublishedItems(): array
    {
        return [
            1 => 'Опубліковано',
            0 => 'Чернетка',
        ];
    }

    public static function find()
    {
        return new \common\models\query\NewsQuery(get_called_class());
    }
}
