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
 * @property string $robots_index Robots index
 * @property string $robots_follow Robots follow
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
    public const ROBOTS_INDEX = 'index';
    public const ROBOTS_NOINDEX = 'noindex';
    public const ROBOTS_FOLLOW = 'follow';
    public const ROBOTS_NOFOLLOW = 'nofollow';

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
            [['robots_index'], 'default', 'value' => self::ROBOTS_INDEX],
            [['robots_follow'], 'default', 'value' => self::ROBOTS_FOLLOW],
            [['robots_index'], 'in', 'range' => [self::ROBOTS_INDEX, self::ROBOTS_NOINDEX]],
            [['robots_follow'], 'in', 'range' => [self::ROBOTS_FOLLOW, self::ROBOTS_NOFOLLOW]],
            [['date_add', 'date_update'], 'safe'],
            [['slug'], 'string', 'max' => 128],
            [['name', 'title'], 'string', 'max' => 255],
            [['robots_index', 'robots_follow'], 'string', 'max' => 10],
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
            'scripts' => Yii::t('app', 'Scripts'),
            // Прапорець, чи дозволено індексацію сторінки пошуковими системами.
            'robots_index' => 'Індексація',
            // Прапорець, чи дозволено переходити за посиланнями зі сторінки.
            'robots_follow' => 'Кравлінг посилань',
            'date_add' => Yii::t('app', 'Date add'),
            'date_update' => Yii::t('app', 'Date update'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }



    /**
     * Перевіряє, чи міграція robots-полів вже застосована в БД.
     *
     * Не нашкодити: якщо схема тимчасово недоступна або колонки відсутні,
     * повертаємо false і не використовуємо ці поля у UI/запитах.
     */
    public static function supportsRobotsFlags(): bool
    {
        try {
            $schema = Yii::$app->db->schema->getTableSchema(self::tableName(), true);

            if ($schema === null) {
                return false;
            }

            return isset($schema->columns['robots_index'], $schema->columns['robots_follow']);
        } catch (\Throwable $exception) {
            return false;
        }
    }

    /**
     * Повертає опції для випадаючого списку індексації.
     */
    public static function robotsIndexOptions(): array
    {
        return [
            self::ROBOTS_INDEX => 'index',
            self::ROBOTS_NOINDEX => 'noindex',
        ];
    }

    /**
     * Повертає опції для випадаючого списку кравлінгу посилань.
     */
    public static function robotsFollowOptions(): array
    {
        return [
            self::ROBOTS_FOLLOW => 'follow',
            self::ROBOTS_NOFOLLOW => 'nofollow',
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
}
