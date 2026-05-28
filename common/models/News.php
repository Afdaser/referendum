<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\helpers\Inflector;

/**
 * Модель новин/статей для контентного розділу.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $content
 * @property string|null $image_url
 * @property string|null $h1
 * @property string|null $desc
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
            [['published_at'], 'safe'],
            [['is_published', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['title', 'h1'], 'string', 'max' => 255],
            [['image_url'], 'string', 'max' => 1024],
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
            'image_url' => 'Зображення (URL)',
            'h1' => 'H1',
            'desc' => 'meta-description',
            'published_at' => 'Дата публікації',
            'is_published' => 'Опубліковано',
            'created_at' => 'Створено',
            'updated_at' => 'Оновлено',
            'created_by' => 'Створив',
            'updated_by' => 'Оновив',
        ];
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
