<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\bootstrap\Html;
use common\models\Poll;
use common\models\TagStaticText;
use common\models\TagStaticFaq;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property int $id ID
 * @property string $name Name
 * @property int|null $language_id Language
 * @property string|null $description Description
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 * @property int $polls_count Polls Count
 *
 * @property Language $language
 * @property PollTag[] $pollTags
 * @property Poll[] $polls
 */
class Tag extends ActiveRecord
{
    public static $subdomen = null;

    /**
     * Кеш для вже порахованих статистичних даних тегу.
     * Використовуємо як для статичного тексту, так і для FAQ.
     *
     * @var array|null
     */
    private $_staticContentData;

    /**
     * Кеш сформованих елементів FAQ, щоб не перевантажувати базу.
     *
     * @var array|null
     */
    private $_faqList;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['language_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'polls_count'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
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
            'name' => Yii::t('app', 'Name'),
            'language_id' => Yii::t('app', 'Language'),
            'description' => Yii::t('app', 'Description'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
            'polls_count' => Yii::t('app', 'Polls Count'),
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
     * Gets query for [[PollTags]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollTagQuery
     */
    public function getPollTags()
    {
        return $this->hasMany(PollTag::class, ['tag_id' => 'id']);
    }

    /**
     * Gets query for [[Polls]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollQuery
     */
    public function getPolls()
    {
        return $this->hasMany(Poll::class, ['id' => 'poll_id'])->viaTable('{{%poll_tag}}', ['tag_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\TagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TagQuery(get_called_class());
    }

    /*
     * return tag`s id by name
     * @tag - tag`s name
     */
    public static function getTagId($tag)
    {
        $result = 0;
//        $item = Tag::model()->findByAttributes(array('name' => CHtml::encode($tag)));
        $item = Tag::find()->where(['name' => Html::encode($tag)])->one();
        if ($item) {
            $result = $item->id;
        }
        return $result;
    }

    /*
     * Create new tag and return it`s ID
     */
    public static function createNewTag($name, $languageId)
    {
        $tagId = 0;
        $name = trim($name);
        if ($name != '') {
            $tagId = self::getTagId($name);
            if (!$tagId) {
                $tag = new Tag;
                $tag->name = $name;
                $tag->language_id = $languageId;
                $tag->save();
                $tagId = $tag->id;
            }
        }
        return $tagId;
    }

    public function getUrl()
    {
        if (is_null(self::$subdomen)) {
            $host = explode('.', $_SERVER['SERVER_NAME']);
            if (in_array($host[0], ['en', 'uk', 'ua', 'ru', 'no'])) {
                self::$subdomen = $host[0];
            } else {
                self::$subdomen = '';
            }
        }

        $tag = urlencode($this->name);
        if (self::$subdomen === '') {
            return SITE_PROTOCOL . "{$this->language->name}." . SITE_DOMAIN . "/tag/{$tag}";
        }
        return "/tag/{$tag}";
    }

    /**
     * Перелік змінних, які можна використати у статичному тексті тегу.
     *
     * @return array
     */
    public static function getInfoTextTokens()
    {
        return [
            '@tag' => 'Назва тегу.',
            '@countpoll' => 'Кількість активних опитувань з цим тегом.',
            '@firstdate' => 'Дата появи першого опитування за тегом.',
            '@lastdate' => 'Дата додавання найновішого опитування.',
            '@averagecomments' => 'Середня кількість коментарів в одному активному опитуванні.',
            '@totalcomments' => 'Сумарна кількість коментарів до активних опитувань.',
            '@popularlink' => 'Посилання на найпопулярніше опитування.',
            '@popularname' => 'Назва найпопулярнішого опитування без посилання.',
            '@popularvotes' => 'Кількість голосів у найпопулярнішому опитуванні.',
            '@averagevotes' => 'Середня кількість голосів в опитуваннях за тегом.',
            '@ratinglink' => 'Посилання на опитування з найвищим рейтингом.',
            '@ratingname' => 'Назва опитування з найвищим рейтингом без посилання.',
            '@rating' => 'Рейтинг найкращого опитування.',
            '@latestlink' => 'Посилання на останнє за часом опитування.',
            '@latestname' => 'Назва останнього опитування без посилання.',
            '@latestdate' => 'Дата публікації останнього опитування.',
            '@mostcomments' => 'Кількість коментарів у найактивнішому обговоренні.',
            '@totalvotes' => 'Сумарна кількість голосів в усіх активних опитуваннях.',
            '@mostcommentedlink' => 'Посилання на опитування з найбільшою кількістю коментарів.',
        ];
    }

    /**
     * Збирає статистику за тегом, щоб підставляти змінні у шаблони.
     * Робимо це один раз за запит, а потім перевикористовуємо і для FAQ.
     *
     * @return array
     */
    private function getStaticContentData(): array
    {
        if ($this->_staticContentData !== null) {
            return $this->_staticContentData;
        }

        $pollQuery = $this->getPolls()->where(['status' => Poll::POLL_STATUS_ACTIVE]);
        $polls = $pollQuery->all();
        $pollCount = count($polls);

        $formatter = Yii::$app->formatter;
        $formatter->locale = Yii::$app->language;
        $created = $this->created_at
            ? $formatter->asDate($this->created_at, 'long')
            : Yii::t('tag', 'невідомо');

        $latestPoll = null;
        $popularPoll = null;
        $topRatedPoll = null;

        $totalVotes = 0;
        $totalComments = 0;
        $mostCommentedPoll = null;
        $mostComments = 0;

        foreach ($polls as $poll) {
            if ($latestPoll === null || strtotime($poll->date_add) > strtotime($latestPoll->date_add)) {
                $latestPoll = $poll;
            }
            if ($popularPoll === null || $poll->countPollOptionsVoters > $popularPoll->countPollOptionsVoters) {
                $popularPoll = $poll;
            }
            if ($topRatedPoll === null || $poll->rating > $topRatedPoll->rating) {
                $topRatedPoll = $poll;
            }

            $pollVotes = $poll->countPollOptionsVoters;
            $totalVotes += $pollVotes;

            $commentCount = $poll->getCommentsCount();
            $totalComments += $commentCount;

            if ($mostCommentedPoll === null || $commentCount > $mostComments) {
                $mostCommentedPoll = $poll;
                $mostComments = $commentCount;
            }
        }

        $averageVotes = $pollCount > 0 ? $totalVotes / $pollCount : 0;
        $averageComments = $pollCount > 0 ? $totalComments / $pollCount : 0;
        $latestDate = $latestPoll ? $formatter->asDate(strtotime($latestPoll->date_add), 'long') : '';

        $replacements = [
            '@tag' => Html::encode($this->name),
            '@countpoll' => $pollCount,
            '@firstdate' => $created,
            '@lastdate' => $latestDate,
            '@averagecomments' => $formatter->asDecimal($averageComments, 1),
            '@totalcomments' => $totalComments,
            '@popularlink' => $popularPoll ? Html::a(Html::encode($popularPoll->title), $popularPoll->getUrl()) : '',
            '@popularname' => $popularPoll ? Html::encode($popularPoll->title) : '',
            '@popularvotes' => $popularPoll ? $popularPoll->countPollOptionsVoters : '',
            '@averagevotes' => $formatter->asDecimal($averageVotes, 1),
            '@ratinglink' => $topRatedPoll ? Html::a(Html::encode($topRatedPoll->title), $topRatedPoll->getUrl()) : '',
            '@ratingname' => $topRatedPoll ? Html::encode($topRatedPoll->title) : '',
            '@rating' => $topRatedPoll ? $topRatedPoll->rating : '',
            '@latestlink' => $latestPoll ? Html::a(Html::encode($latestPoll->title), $latestPoll->getUrl()) : '',
            '@latestname' => $latestPoll ? Html::encode($latestPoll->title) : '',
            '@latestdate' => $latestDate,
            '@mostcomments' => $mostComments,
            '@totalvotes' => $totalVotes,
            '@mostcommentedlink' => $mostCommentedPoll ? Html::a(
                Html::encode($mostCommentedPoll->title),
                $mostCommentedPoll->getUrl()
            ) : '',
        ];

        return $this->_staticContentData = [
            'polls' => $polls,
            'pollCount' => $pollCount,
            'created' => $created,
            'latestPoll' => $latestPoll,
            'popularPoll' => $popularPoll,
            'topRatedPoll' => $topRatedPoll,
            'mostCommentedPoll' => $mostCommentedPoll,
            'mostComments' => $mostComments,
            'totalVotes' => $totalVotes,
            'totalComments' => $totalComments,
            'latestDate' => $latestDate,
            'replacements' => $replacements,
        ];
    }

    /**
     * Формує короткий текстовий опис тегу.
     * Якщо адміністратор додав шаблон, використовуємо його та підставляємо змінні.
     * Інакше повертаємо стандартний текст.
     *
     * @return string
     */
    public function getInfoText()
    {
        $data = $this->getStaticContentData();

        $staticText = null;
        if ($this->language_id) {
            $staticText = TagStaticText::find()->where(['language_id' => $this->language_id])->one();
        }

        if ($staticText && trim((string)$staticText->content) !== '') {
            return strtr($staticText->content, $data['replacements']);
        }

        $parts = [];
        $parts[] = Yii::t('tag', 'Опитування на тему "{tag}" були створені {date}, і відтоді колекція думок {tag} зросла до {count} опитувань.', [
            'tag' => $this->name,
            'date' => $data['created'],
            'count' => $data['pollCount'],
        ]);

        if ($data['popularPoll']) {
            $parts[] = Yii::t('tag', 'Найпопулярніше серед них — {title}, який набрав {votes} голосів.', [
                'title' => Html::a(Html::encode($data['popularPoll']->title), $data['popularPoll']->getUrl()),
                'votes' => $data['popularPoll']->countPollOptionsVoters,
            ]);
        }

        if ($data['topRatedPoll']) {
            $parts[] = Yii::t('tag', 'Опитування з найбільшим рейтингом — {title} має рейтинг {rating}.', [
                'title' => Html::a(Html::encode($data['topRatedPoll']->title), $data['topRatedPoll']->getUrl()),
                'rating' => $data['topRatedPoll']->rating,
            ]);
            $parts[] = Yii::t('tag', 'Рейтинг присвоюється користувачами сайту до кожного опитування.', [
                'tag' => $this->name,
            ]);
        }

        if ($data['latestPoll']) {
            $parts[] = Yii::t('tag', 'Останнє опитування по темі {tag} було додано {date}.', [
                'tag' => $this->name,
                'date' => $data['latestDate'],
            ]);
        }

        return implode(' ', $parts);
    }

    /**
     * Формує перелік FAQ зі вже підставленими змінними.
     *
     * @return array[] Кожен елемент містить ключі question та answer.
     */
    public function getFaqList(): array
    {
        if ($this->_faqList !== null) {
            return $this->_faqList;
        }

        if (!$this->language_id) {
            return $this->_faqList = [];
        }

        $data = $this->getStaticContentData();

        $faqRecords = TagStaticFaq::find()
            ->where(['language_id' => $this->language_id])
            ->orderBy(['position' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        $items = [];
        foreach ($faqRecords as $faqRecord) {
            $question = strtr((string) $faqRecord->question, $data['replacements']);
            $answer = strtr((string) $faqRecord->answer, $data['replacements']);

            if (trim(strip_tags($question)) === '' || trim(strip_tags($answer)) === '') {
                continue;
            }

            $items[] = [
                'question' => $question,
                'answer' => $answer,
            ];
        }

        return $this->_faqList = $items;
    }
}
