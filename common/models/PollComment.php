<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%poll_comment}}".
 *
 * @property int $id ID
 * @property int|null $parent_id Parent
 * @property int $poll_id Poll
 * @property int|null $user_id User
 * @property int $is_guest Is guest comment
 * @property string|null $guest_nickname Guest nickname
 * @property int|null $user_ip User IP
 * @property string|null $ip_of_user User IP text
 * @property string $content Content
 * @property int $status Status
 * @property int $is_new Is new
 * @property int $has_new Has_new
 * @property int $read_by_parent Read by parent
 * @property int $rating Rating
 * @property string $date_add Date add
 * @property string $date_update Date update
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property PollComment $parent
 * @property Poll $poll
 * @property PollCommentRating[] $pollCommentRatings
 * @property PollComment[] $pollComments
 * @property User $user
 * @property User[] $users
 */
class PollComment extends ActiveRecord
{
    # poll comments statuses
    const NEW_COMMENT = 1;
    const COMMENT_STATUS_PUBLISHED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%poll_comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'poll_id', 'user_id', 'status', 'is_new', 'has_new', 'read_by_parent', 'rating', 'created_by', 'updated_by', 'created_at', 'updated_at', 'user_ip'], 'integer'],
            [['poll_id', 'content', 'status', 'is_guest'], 'required'],
            [['date_add', 'date_update'], 'safe'],
            [['content'], 'string'],
            [['guest_nickname'], 'trim'],
            [['guest_nickname'], 'string', 'max' => 60],
            [['ip_of_user'], 'string', 'max' => 67],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['poll_id'], 'exist', 'skipOnError' => true, 'targetClass' => Poll::class, 'targetAttribute' => ['poll_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => PollComment::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['guest_nickname'], 'required', 'when' => function (self $model) {
                return (bool) $model->is_guest;
            }, 'whenClient' => "function () { return false; }"],
            ['user_ip', 'validateGuestCommentIpLimit'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent'),
            'poll_id' => Yii::t('app', 'Poll'),
            'user_id' => Yii::t('app', 'User'),
            'is_guest' => Yii::t('app', 'Is guest comment'),
            'guest_nickname' => Yii::t('app', 'Guest nickname'),
            'user_ip' => Yii::t('app', 'User IP'),
            'ip_of_user' => Yii::t('app', 'User IP text'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'is_new' => Yii::t('app', 'Is new'),
            'has_new' => Yii::t('app', 'Has_new'),
            'read_by_parent' => Yii::t('app', 'Read by parent'),
            'rating' => Yii::t('app', 'Rating'),
            'date_add' => Yii::t('app', 'Date add'),
            'date_update' => Yii::t('app', 'Date update'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentQuery
     */
    public function getParent()
    {
//        return $this->hasOne(PollComment::class, ['parent_id' => 'id']);
        return $this->hasOne(PollComment::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentQuery
     */
    public function getCommentChilds()
    {
//        return $this->hasMany(PollComment::class, ['id' => 'parent_id']);
        return $this->hasMany(PollComment::class, ['parent_id' => 'id']);
    }

    public function commentChilds(array $condition)
    {
        $coments = $this->getCommentChilds()->where($condition)->all();
        return $coments;
    }
    /**
     * Gets query for [[Poll]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollQuery
     */
    public function getPoll()
    {
        return $this->hasOne(Poll::class, ['id' => 'poll_id']);
    }

    /**
     * Gets query for [[PollCommentRatings]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentRatingQuery
     */
    public function getPollCommentRatings()
    {
        return $this->hasMany(PollCommentRating::class, ['poll_comment_id' => 'id']);
    }

    /**
     * Gets query for [[PollComments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentQuery
     */
    public function getPollComments()
    {
        return $this->hasMany(PollComment::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('{{%poll_comment_rating}}', ['poll_comment_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PollCommentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PollCommentQuery(get_called_class());
    }

    /*
     * set attributes and return model
     */

    public function setCommentAttributes($attributes)
    {

        $this->poll_id = intval($attributes['poll_id']);
        if (intval($attributes['parent_id'])) {
            $this->parent_id = intval($attributes['parent_id']);
        }

        // Для гостей зберігаємо нік та IP, а для авторизованих — user_id.
        if (Yii::$app->user->isGuest) {
            $this->is_guest = 1;
            $this->user_id = null;
            $this->guest_nickname = Html::encode(trim((string) ($attributes['guest_nickname'] ?? '')));
            $this->user_ip = ip2long(Yii::$app->request->getUserIP());
            $this->ip_of_user = Yii::$app->request->getUserIP();
        } else {
            $this->is_guest = 0;
            $this->user_id = Yii::$app->user->id;
            $this->guest_nickname = null;
            $this->user_ip = null;
            $this->ip_of_user = null;
        }

        $this->content = Html::encode($attributes['content']);
        $this->status = PollComment::COMMENT_STATUS_PUBLISHED;
        $this->date_add = date('Y-m-d H:i:s');

        return $this;
    }

    /**
     * Гість може лишити лише один коментар в межах одного опитування з одного IP.
     */
    public function validateGuestCommentIpLimit($attribute, $params)
    {
        if (!$this->is_guest || !$this->poll_id || !$this->user_ip) {
            return;
        }

        $exists = self::find()
            ->where(['poll_id' => $this->poll_id, 'is_guest' => 1, 'user_ip' => $this->user_ip])
            ->andFilterWhere(['<>', 'id', $this->id])
            ->exists();

        if ($exists) {
            $this->addError('content', Yii::t('poll', 'З цього IP вже додано коментар у цьому опитуванні.'));
        }
    }

    /**
     * Повертає ім’я автора для відображення в шаблонах.
     */
    public function getDisplayAuthorName()
    {
        if ($this->is_guest) {
            return $this->guest_nickname ?: Yii::t('poll', 'Гість');
        }

        return User::getUserName($this->user_id);
    }

    /**
     * Чи є коментар гостьовим.
     */
    public function getIsGuestComment()
    {
        return (bool) $this->is_guest;
    }

    public function readCommentAnswers()
    {
        if ($comments = $this->commentChilds(['read_by_parent' => 0])) {
            foreach ($comments as $comment) {
                $comment->read_by_parent = 1;
                $comment->save();
            }
        }
    }
}
