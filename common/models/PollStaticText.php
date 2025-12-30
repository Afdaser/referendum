<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель для зберігання статичного тексту сторінок опитувань.
 *
 * @property int $id
 * @property int $poll_id
 * @property string|null $content
 * @property string|null $heading
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Poll $poll
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
            [['poll_id'], 'required'],
            [['poll_id', 'created_at', 'updated_at'], 'integer'],
            [['content', 'meta_description'], 'string'],
            [['heading', 'meta_title'], 'string', 'max' => 255],
            [['poll_id'], 'unique'],
            [['poll_id'], 'exist', 'skipOnError' => true, 'targetClass' => Poll::class, 'targetAttribute' => ['poll_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poll_id' => 'Опитування',
            'content' => 'Статичний текст',
            'heading' => 'Заголовок H1',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'created_at' => 'Створено',
            'updated_at' => 'Оновлено',
        ];
    }

    /**
     * Перевіряє, чи всі текстові поля порожні.
     */
    public function isEmpty(): bool
    {
        return trim((string) $this->content) === ''
            && trim((string) $this->heading) === ''
            && trim((string) $this->meta_title) === ''
            && trim((string) $this->meta_description) === '';
    }

    /**
     * Звʼязок з опитуванням.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoll()
    {
        return $this->hasOne(Poll::class, ['id' => 'poll_id']);
    }
}
