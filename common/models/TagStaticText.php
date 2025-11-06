<?php

namespace common\models;

use common\components\ActiveRecord;

/**
 * Модель для таблиці «{{%tag_static_text}}» зі статичними текстами тегів.
 *
 * @property int $id Первинний ключ
 * @property int $tag_id Прив'язаний тег
 * @property int $language_id Мова тексту
 * @property string $content Вміст тексту
 * @property int|null $created_by Хто створив запис
 * @property int|null $updated_by Хто востаннє оновив
 * @property int|null $created_at Час створення
 * @property int|null $updated_at Час оновлення
 *
 * @property Language $language Пов'язана мова
 * @property Tag $tag Пов'язаний тег
 */
class TagStaticText extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tag_static_text}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'language_id', 'content'], 'required'],
            [['tag_id', 'language_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['tag_id', 'language_id'], 'unique', 'targetAttribute' => ['tag_id', 'language_id'], 'message' => 'Для цього тегу та мови текст уже створений.'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_id' => 'Тег',
            'language_id' => 'Мова',
            'content' => 'Текст',
            'created_by' => 'Створив',
            'updated_by' => 'Оновив',
            'created_at' => 'Створено',
            'updated_at' => 'Оновлено',
        ];
    }

    /**
     * Зв'язок з мовою тексту.
     *
     * @return \yii\db\ActiveQuery|\common\models\query\LanguageQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }

    /**
     * Зв'язок із тегом, до якого належить текст.
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TagQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::class, ['id' => 'tag_id']);
    }

    /**
     * Пояснення до доступних змінних, які можна використовувати у тексті.
     *
     * @return array<string,string>
     */
    public static function placeholderDescriptions(): array
    {
        return [
            '@tagname' => 'Назва тегу',
            '@countpoll' => 'Кількість опитувань для тегу',
            '@firstdate' => 'Дата створення тегу або першого опитування',
            '@latestdate' => 'Дата останнього опитування',
            '@popularpoll' => 'Посилання на найпопулярніше опитування',
            '@popularvotes' => 'Голосів у найпопулярнішому опитуванні',
            '@toppoll' => 'Посилання на опитування з найвищим рейтингом',
            '@toprating' => 'Рейтинг найкращого опитування',
            '@latestpoll' => 'Посилання на останнє опитування',
        ];
    }

    /**
     * Формує фінальний HTML після підстановки динамічних значень.
     *
     * @param array<string,string> $data Значення плейсхолдерів
     * @return string
     */
    public function render(array $data): string
    {
        $content = $this->content;

        foreach ($data as $placeholder => $value) {
            $content = str_replace($placeholder, $value, $content);
        }

        // Якщо плейсхолдерів бракує, видаляємо їх, щоб у тексті не лишалися службові позначки.
        $content = preg_replace('/@[a-z0-9_]+/i', '', (string) $content);

        if (!is_string($content)) {
            return '';
        }

        // Адміністратор керує розміткою вручну, тому повертаємо текст без додаткового форматування.
        return trim($content);
    }
}
