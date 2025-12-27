<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель для зберігання статичних шаблонів тексту на сторінці опитування.
 *
 * @property int $id
 * @property int $language_id
 * @property string|null $heading
 * @property string|null $summary
 * @property string|null $options_intro
 * @property string|null $most_popular
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
            [['summary', 'options_intro', 'most_popular'], 'string'],
            [['heading'], 'string', 'max' => 255],
            [['language_id'], 'unique'],
            [
                ['language_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Language::class,
                'targetAttribute' => ['language_id' => 'id'],
            ],
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
            'heading' => 'Заголовок блоку',
            'summary' => 'Опис опитування',
            'options_intro' => 'Вступ до варіантів відповіді',
            'most_popular' => 'Опис найпопулярнішої відповіді',
            'created_at' => 'Створено',
            'updated_at' => 'Оновлено',
        ];
    }

    /**
     * Перевіряє, чи всі текстові поля порожні.
     */
    public function isEmpty(): bool
    {
        return trim((string) $this->heading) === ''
            && trim((string) $this->summary) === ''
            && trim((string) $this->options_intro) === ''
            && trim((string) $this->most_popular) === '';
    }

    /**
     * Звʼязок із мовою, для якої задані шаблони.
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }
}
