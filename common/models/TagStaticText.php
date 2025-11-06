<?php

namespace common\models;

use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%tag_static_text}}".
 *
 * @property int $id ID
 * @property int $tag_id Tag
 * @property int $language_id Language
 * @property string $content Content
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property Language $language
 * @property Tag $tag
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
     * Gets query for [[Language]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\LanguageQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }

    /**
     * Gets query for [[Tag]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TagQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::class, ['id' => 'tag_id']);
    }

    /**
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
     * Builds the final HTML string with all placeholders replaced.
     *
     * @param array<string,string> $data
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
