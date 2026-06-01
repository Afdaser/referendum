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
 * @property int $language_id
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
 *
 * @property Language $language
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
            [['title', 'slug', 'language_id'], 'required'],
            [['excerpt', 'content', 'desc'], 'string'],
            [['published_at'], 'safe'],
            [['language_id', 'is_published', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
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
            // Slug має бути унікальним тільки в межах вибраного піддомену, щоб різні локалі не блокували одна одну.
            [['slug', 'language_id'], 'unique', 'targetAttribute' => ['slug', 'language_id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language_id' => 'id']],
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
            'language_id' => 'Піддомен',
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
     * Повертає мову/піддомен, для якого призначена новина.
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }

    /**
     * Формує список піддоменів для адмінки з урахуванням поточних мов у БД.
     */
    public static function getSubdomainItems(): array
    {
        $items = [];
        $domains = Yii::$app->params['langDomains'] ?? [];
        $languages = Language::find()->orderBy(['id' => SORT_ASC])->all();

        foreach ($languages as $language) {
            $domain = $domains[$language->id] ?? ($language->name . '.' . (defined('SITE_DOMAIN') ? SITE_DOMAIN : 'referendum.social'));
            // Показуємо і піддомен, і країну, щоб редактор не переплутав мовну версію.
            $items[$language->id] = $domain . ' — ' . Language::getCountryTitle($language);
        }

        return $items;
    }

    /**
     * Визначає мовну версію поточного фронтенд-запиту для фільтрації новин.
     */
    public static function resolveCurrentLanguageId(): ?int
    {
        $request = Yii::$app->has('request') ? Yii::$app->request : null;
        if ($request !== null && property_exists($request, 'languageId') && (int) $request->languageId > 0) {
            return (int) $request->languageId;
        }

        $language = Language::find()->where(['locale' => Yii::$app->language])->one();
        if ($language !== null) {
            return (int) $language->id;
        }

        return null;
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
