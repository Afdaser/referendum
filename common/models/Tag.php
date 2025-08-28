<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use yii\bootstrap\Html;
use common\models\Poll;

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
     * Формує короткий текстовий опис тегу
     *
     * @return string
     */
    public function getInfoText()
    {
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
        }

        $parts = [];
        $parts[] = Yii::t('tag', 'Опитування на тему "{tag}" були створені {date}, і відтоді колекція думок {tag} зросла до {count} опитувань.', [
            'tag' => $this->name,
            'date' => $created,
            'count' => $pollCount,
        ]);

        if ($popularPoll) {
            $parts[] = Yii::t('tag', 'Найпопулярніше серед них — {title}, який набрав {votes} голосів.', [
                'title' => Html::a(Html::encode($popularPoll->title), $popularPoll->getUrl()),
                'votes' => $popularPoll->countPollOptionsVoters,
            ]);
        }

        if ($topRatedPoll) {
            $parts[] = Yii::t('tag', 'Опитування з найбільшим рейтингом — {title} має рейтинг {rating}.', [
                'title' => Html::a(Html::encode($topRatedPoll->title), $topRatedPoll->getUrl()),
                'rating' => $topRatedPoll->rating,
            ]);
            $parts[] = Yii::t('tag', 'Рейтинг присвоюється користувачами сайту до кожного опитування.', [
                'tag' => $this->name,
            ]);
        }

        if ($latestPoll) {
            $parts[] = Yii::t('tag', 'Останнє опитування по темі {tag} було додано {date}.', [
                'tag' => $this->name,
                'date' => $formatter->asDate(strtotime($latestPoll->date_add), 'long'),
            ]);
        }

        return implode(' ', $parts);
    }
}
