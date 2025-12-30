<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель для зберігання мета-даних сторінок опитувань.
 *
 * @property int $id
 * @property int $language_id
 * @property string|null $heading
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Language $language
 */
class PollStaticText extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%poll_static_text}}';
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
            [['language_id', 'created_at', 'updated_at'], 'integer'],
            [['meta_description'], 'string'],
            [['heading', 'meta_title'], 'string', 'max' => 255],
            [['language_id'], 'unique'],
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
            'heading' => 'Заголовок H1',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'created_at' => 'Створено',
            'updated_at' => 'Оновлено',
        ];
    }

    /**
     * Перевіряє, чи всі поля мета-даних порожні.
     */
    public function isEmpty(): bool
    {
        return trim((string) $this->heading) === ''
            && trim((string) $this->meta_title) === ''
            && trim((string) $this->meta_description) === '';
    }

    /**
     * Звʼязок з мовою, для якої налаштовано мета-дані.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }
}
