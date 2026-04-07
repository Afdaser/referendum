<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\helpers\Inflector;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property int $id ID
 * @property string $slug Slug
 * @property int $language_id Language
 * @property string $name Name
 * @property string $title Title
 * @property string|null $content Content
 * @property string|null $describe Describe
 * @property string|null $scripts Scripts
 * @property string|null $robots_tag Robots tag
 * @property string $date_add Date add
 * @property string $date_update Date update
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property Language $language
 */
class Page extends ActiveRecord
{
    public const ROBOTS_INDEX_FOLLOW = 'index, follow';
    public const ROBOTS_NOINDEX_NOFOLLOW = 'noindex, nofollow';
    public const ROBOTS_NOINDEX_FOLLOW = 'noindex, follow';
    public const ROBOTS_INDEX_NOFOLLOW = 'index, nofollow';

    /**
     * Готуємо обробку слагу перед валідацією.
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            // Автоматично створюємо ЧПУ з назви, якщо поле порожнє.
            if (empty($this->slug) && !empty($this->name)) {
                $this->slug = Inflector::slug($this->name);
            }

            return true;
        }

        return false;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'name', 'title'], 'required'],
            [['language_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content', 'describe', 'scripts'], 'string'],
            [['date_add', 'date_update'], 'safe'],
            [['slug'], 'string', 'max' => 128],
            [['name', 'title'], 'string', 'max' => 255],
            [['robots_tag'], 'string', 'max' => 32],
            [['robots_tag'], 'in', 'range' => array_keys(self::getRobotsTagItems())],
            [['slug', 'language_id'], 'unique', 'targetAttribute' => ['slug', 'language_id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'slug' => Yii::t('app', 'Slug'),
            'language_id' => Yii::t('app', 'Language'),
            // Назва поля для редактора статичної сторінки: це H1 на фронтенді.
            'name' => 'H1',
            // Назва поля для SEO: це текст <title>.
            'title' => 'Заголовок',
            'content' => Yii::t('app', 'Content'),
            // Назва поля для SEO-опису сторінки.
            'describe' => 'meta-description',
            // Назва поля для robots-тега сторінки.
            'robots_tag' => 'Robots Tag',
            'scripts' => Yii::t('app', 'Scripts'),
            'date_add' => Yii::t('app', 'Date add'),
            'date_update' => Yii::t('app', 'Date update'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[Language]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\LanguageQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PageQuery(get_called_class());
    }

    /**
     * Повертає варіанти robots-тега для адмін-форми.
     */
    public static function getRobotsTagItems(): array
    {
        return [
            self::ROBOTS_INDEX_FOLLOW => 'index, follow',
            self::ROBOTS_NOINDEX_NOFOLLOW => 'No index, nofollow',
            self::ROBOTS_NOINDEX_FOLLOW => 'no index, follow',
            self::ROBOTS_INDEX_NOFOLLOW => 'index, no follow',
        ];
    }
}
