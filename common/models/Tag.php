<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\bootstrap\Html;
use common\models\Poll;
use common\models\TagStaticText;
use common\models\TagStaticFaq;
use yii\db\Query;

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
     * Кешує повʼязаний запис зі статичними налаштуваннями для тегу.
     * Використовуємо прапорець, щоб відрізняти «ще не завантажено» від «немає запису».
     */
    private ?TagStaticText $_staticTextRecord = null;

    private bool $_staticTextRecordLoaded = false;
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
            // Додаємо nz до переліку підтримуваних піддоменів тегів.
            if (in_array($host[0], ['en', 'uk', 'ua', 'ru', 'no', 'nz'])) {
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
            '@votes90d' => 'Кількість нових голосів за останні 90 днів у межах тегу.',
            '@polls90d' => 'Кількість нових опитувань за останні 90 днів у межах тегу.',
            '@activepoll90d' => 'Найактивніше опитування за останні 90 днів (з посиланням).',
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

        // Для блоку "Latest activity" рахуємо окрему оперативну статистику.
        // На прохання бізнесу виводимо динаміку голосів саме за 90 днів.
        $votesFromDate = date('Y-m-d H:i:s', strtotime('-90 days'));
        // Усі метрики цього блоку рахуємо в одному періоді — 90 днів.
        $activityFromDate = $votesFromDate;

        $votes90dRegistered = (new Query())
            ->from('{{%option_vote}} ov')
            ->innerJoin('{{%poll_option}} po', 'po.id = ov.option_id')
            ->innerJoin('{{%poll_tag}} pt', 'pt.poll_id = po.poll_id')
            ->innerJoin('{{%poll}} p', 'p.id = po.poll_id')
            ->where(['pt.tag_id' => $this->id, 'p.status' => Poll::POLL_STATUS_ACTIVE])
            ->andWhere(['>=', 'ov.date_add', $votesFromDate])
            ->count();

        $votes90dGuests = (new Query())
            ->from('{{%option_guest_vote}} ogv')
            ->innerJoin('{{%poll_option}} po', 'po.id = ogv.option_id')
            ->innerJoin('{{%poll_tag}} pt', 'pt.poll_id = po.poll_id')
            ->innerJoin('{{%poll}} p', 'p.id = po.poll_id')
            ->where(['pt.tag_id' => $this->id, 'p.status' => Poll::POLL_STATUS_ACTIVE])
            ->andWhere(['>=', 'ogv.date_add', $votesFromDate])
            ->count();

        $polls90d = (new Query())
            ->from('{{%poll}} p')
            ->innerJoin('{{%poll_tag}} pt', 'pt.poll_id = p.id')
            ->where(['pt.tag_id' => $this->id, 'p.status' => Poll::POLL_STATUS_ACTIVE])
            ->andWhere(['>=', 'p.date_add', $activityFromDate])
            ->count('DISTINCT p.id');

        $activePollRowsRegistered = (new Query())
            ->select(['po.poll_id', 'votes' => 'COUNT(ov.id)'])
            ->from('{{%option_vote}} ov')
            ->innerJoin('{{%poll_option}} po', 'po.id = ov.option_id')
            ->innerJoin('{{%poll_tag}} pt', 'pt.poll_id = po.poll_id')
            ->innerJoin('{{%poll}} p', 'p.id = po.poll_id')
            ->where(['pt.tag_id' => $this->id, 'p.status' => Poll::POLL_STATUS_ACTIVE])
            ->andWhere(['>=', 'ov.date_add', $activityFromDate])
            ->groupBy(['po.poll_id'])
            ->all();

        $activePollRowsGuests = (new Query())
            ->select(['po.poll_id', 'votes' => 'COUNT(ogv.id)'])
            ->from('{{%option_guest_vote}} ogv')
            ->innerJoin('{{%poll_option}} po', 'po.id = ogv.option_id')
            ->innerJoin('{{%poll_tag}} pt', 'pt.poll_id = po.poll_id')
            ->innerJoin('{{%poll}} p', 'p.id = po.poll_id')
            ->where(['pt.tag_id' => $this->id, 'p.status' => Poll::POLL_STATUS_ACTIVE])
            ->andWhere(['>=', 'ogv.date_add', $activityFromDate])
            ->groupBy(['po.poll_id'])
            ->all();

        // Склеюємо голоси авторизованих і гостьових користувачів в один рейтинг за 90 днів.
        $activeVotesByPoll90d = [];
        foreach ($activePollRowsRegistered as $row) {
            $pollId = (int) $row['poll_id'];
            $activeVotesByPoll90d[$pollId] = (int) ($activeVotesByPoll90d[$pollId] ?? 0) + (int) $row['votes'];
        }
        foreach ($activePollRowsGuests as $row) {
            $pollId = (int) $row['poll_id'];
            $activeVotesByPoll90d[$pollId] = (int) ($activeVotesByPoll90d[$pollId] ?? 0) + (int) $row['votes'];
        }

        $activePoll90d = null;
        if (!empty($activeVotesByPoll90d)) {
            arsort($activeVotesByPoll90d);
            $activePoll90dId = (int) array_key_first($activeVotesByPoll90d);
            $activePoll90d = Poll::findOne($activePoll90dId);
        }

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
            '@votes90d' => (int) $votes90dRegistered + (int) $votes90dGuests,
            '@polls90d' => (int) $polls90d,
            '@activepoll90d' => $activePoll90d
                ? Html::a(Html::encode($activePoll90d->title), $activePoll90d->getUrl())
                : Yii::t('tag', 'Немає даних'),
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
            'votes90d' => (int) $votes90dRegistered + (int) $votes90dGuests,
            'polls90d' => (int) $polls90d,
            'activePoll90d' => $activePoll90d,
            'replacements' => $replacements,
        ];
    }

    /**
     * Повертає HTML блоку «Latest activity in {TAG} polls».
     * Якщо шаблон у адмінці порожній — блок не показуємо.
     */
    public function getLatestActivityBlockHtml(): string
    {
        $staticText = $this->getStaticTextRecord();
        if (!$staticText || trim((string) $staticText->latest_activity_template) === '') {
            // Нічого не рендеримо, якщо адміністратор не задав шаблон явно.
            return '';
        }

        $data = $this->getStaticContentData();

        return strtr($staticText->latest_activity_template, $data['replacements']);
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

        $staticText = $this->getStaticTextRecord();

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
     * Повертає користувацькі мета-налаштування (title, description, H1) для сторінки тегу.
     * Значення одразу проходять через підстановку змінних.
     */
    public function getStaticMeta(): array
    {
        $staticText = $this->getStaticTextRecord();
        if (!$staticText) {
            return [
                'heading' => null,
                'title' => null,
                'description' => null,
            ];
        }

        $data = $this->getStaticContentData();
        $replacements = $data['replacements'];

        return [
            'heading' => $this->replaceTokens($staticText->heading, $replacements),
            'title' => $this->replaceTokens($staticText->meta_title, $replacements),
            'description' => $this->replaceTokens($staticText->meta_description, $replacements),
        ];
    }

    /**
     * Завантажує повʼязаний запис TagStaticText лише один раз за запит.
     */
    private function getStaticTextRecord(): ?TagStaticText
    {
        if (!$this->_staticTextRecordLoaded) {
            $this->_staticTextRecord = $this->language_id
                ? TagStaticText::find()->where(['language_id' => $this->language_id])->one()
                : null;
            $this->_staticTextRecordLoaded = true;
        }

        return $this->_staticTextRecord;
    }

    /**
     * Акуратно підставляє змінні у вказане значення.
     * Якщо значення після тримінгу порожнє — повертаємо null.
     */
    private function replaceTokens(?string $value, array $replacements): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        return strtr($value, $replacements);
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
