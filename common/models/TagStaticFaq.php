<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель для зберігання FAQ на сторінках тегів.
 *
 * @property int $id
 * @property int $language_id
 * @property string $question
 * @property string $answer
 * @property int $position
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Language $language
 */
class TagStaticFaq extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tag_static_faq}}';
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
            [['language_id'], 'required'],
            [['language_id', 'position', 'created_at', 'updated_at'], 'integer'],
            [['question', 'answer'], 'string'],
            [['question', 'answer'], 'required'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'language_id' => 'Мова',
            'question' => 'Питання',
            'answer' => 'Відповідь',
            'position' => 'Порядок',
            'created_at' => 'Створено',
            'updated_at' => 'Оновлено',
        ];
    }

    /**
     * Звʼязок із мовою, для якої налаштований пункт FAQ.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }
}
