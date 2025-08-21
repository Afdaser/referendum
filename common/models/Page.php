<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;

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
            [['content', 'describe'], 'string'],
            [['date_add', 'date_update'], 'safe'],
            [['slug'], 'string', 'max' => 128],
            [['name', 'title'], 'string', 'max' => 255],
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
            'name' => Yii::t('app', 'Name'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'describe' => Yii::t('app', 'Describe'),
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
}
