<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель для зберігання FAQ на сторінках тегів.
 *
 * @property int $id
 * @property int $language_id
 * @property string|null $question
 * @property string|null $answer
 * @property int $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class TagFaq extends ActiveRecord
{
    /**
     * Внутрішній кеш для очищеного HTML відповіді, щоб не перераховувати щоразу.
     *
     * @var string|null
     */
    private ?string $trimmedAnswer = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tag_faq}}';
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
    public function beforeValidate()
    {
        $this->trimmedAnswer = null;

        return parent::beforeValidate();
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
            [['question'], 'validateRequiredFields'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'question' => 'Питання',
            'answer' => 'Відповідь',
        ];
    }

    /**
     * Перевіряє, чи заповнені обидва поля. Якщо користувач почав вводити текст, але не завершив, показуємо помилку.
     */
    public function validateRequiredFields(): void
    {
        if ($this->isEmpty()) {
            // Якщо блок повністю порожній, нехай контролер вирішує, чи потрібно його зберігати/видаляти.
            $this->clearErrors('question');
            $this->clearErrors('answer');
            return;
        }

        if ($this->getTrimmedQuestion() === '' || $this->getTrimmedAnswer() === '') {
            $this->addError('question', 'Необхідно заповнити і питання, і відповідь або видалити блок.');
            $this->addError('answer', 'Необхідно заповнити і питання, і відповідь або видалити блок.');
        }
    }

    /**
     * Повертає true, якщо обидва поля порожні і блок можна видалити.
     */
    public function isEmpty(): bool
    {
        return $this->getTrimmedQuestion() === '' && $this->getTrimmedAnswer() === '';
    }

    /**
     * Нормалізує питання: обрізає пробіли та забирає контрольні символи.
     */
    public function getTrimmedQuestion(): string
    {
        return trim((string) $this->question);
    }

    /**
     * Нормалізує відповідь, видаляючи HTML для перевірки на пустоту.
     */
    public function getTrimmedAnswer(): string
    {
        if ($this->trimmedAnswer !== null) {
            return $this->trimmedAnswer;
        }

        $answer = (string) $this->answer;
        // Видаляємо HTML-теги лише для перевірки на пустий вміст – сама відповідь може містити розмітку.
        $this->trimmedAnswer = trim(strip_tags($answer));

        return $this->trimmedAnswer;
    }
}
