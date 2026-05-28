<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

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
    /**
     * @var UploadedFile|null Тимчасовий файл із форми адмінки; у БД зберігаємо тільки публічний URL.
     */
    public $imageFile;

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
            [
                ['imageFile'],
                'image',
                'extensions' => ['jpg', 'jpeg', 'png'],
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'maxSize' => 5 * 1024 * 1024,
                'skipOnEmpty' => true,
            ],
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
            'imageFile' => 'Зображення',
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


    /**
     * Зберігає завантажене зображення у публічну папку фронтенда й оновлює image_url.
     */
    public function uploadImage(): bool
    {
        if (!$this->imageFile instanceof UploadedFile) {
            return true;
        }

        $directory = $this->getImagesDirectory();
        if (!FileHelper::createDirectory($directory)) {
            $this->addError('imageFile', 'Не вдалося створити папку для зображень новин.');
            return false;
        }

        $extension = strtolower($this->imageFile->extension);
        $baseName = pathinfo($this->imageFile->baseName, PATHINFO_FILENAME);
        $safeBaseName = Inflector::slug($baseName) ?: 'news-image';
        // Додаємо випадковий суфікс, щоб новий файл не перезаписав наявні зображення.
        $fileName = $safeBaseName . '-' . Yii::$app->security->generateRandomString(10) . '.' . $extension;
        $filePath = $directory . DIRECTORY_SEPARATOR . $fileName;

        if (!$this->imageFile->saveAs($filePath)) {
            $this->addError('imageFile', 'Не вдалося зберегти завантажене зображення.');
            return false;
        }

        $this->image_url = $this->getImagesUrlPrefix() . $fileName;

        return true;
    }

    /**
     * Папка всередині frontend/web доступна за прямим URL /uploads/news/*.
     */
    public function getImagesDirectory(): string
    {
        return Yii::getAlias('@frontend/web/uploads/news');
    }

    /**
     * URL-префікс, який однаково працює для сторінки новини та блоку новин на фронтенді.
     */
    public function getImagesUrlPrefix(): string
    {
        return '/uploads/news/';
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
