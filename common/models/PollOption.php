<?php

namespace common\models;

use Yii;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;
use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%poll_option}}".
 *
 * @property int $id ID
 * @property int $poll_id Poll
 * @property int $user_id User
 * @property string $title Title
 * @property int $status Status
 * @property int $rating Rating
 * @property string $date_add Date add
 * @property string $date_update Date update
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property OptionGuestVote[] $optionGuestVotes
 * @property OptionVote[] $optionVotes
 * @property Poll $poll
 * @property PollOptionRating[] $pollOptionRatings
 * @property User $user
 * @property User[] $users
 */
class PollOption extends ActiveRecord
{

    #poll`s option statuses
    const OPTION_STATUS_PUBLISHED = 1;
    const OPTION_STATUS_UNPUBLISHED = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%poll_option}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poll_id', 'user_id', 'title', 'status'], 'required'],
            [['poll_id', 'user_id', 'status', 'rating', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['date_add', 'date_update'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['poll_id'], 'exist', 'skipOnError' => true, 'targetClass' => Poll::class, 'targetAttribute' => ['poll_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'poll_id' => Yii::t('app', 'Poll'),
            'user_id' => Yii::t('app', 'User'),
            'title' => Yii::t('app', 'Title'),
            'status' => Yii::t('app', 'Status'),
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
     * @return array relational rules.
     */
    public function yiiOneRelations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
                'optionVotes' => array(self::HAS_MANY, 'OptionVote', 'option_id'),
                'optionVotesCount' => array(self::STAT, 'OptionVote', 'option_id'),
                'optionGuestVotesCount' => array(self::STAT, 'OptionGuestVote', 'option_id'),
                'pollOptionRatings' => array(self::HAS_MANY, 'PollOptionRating', 'poll_option_id'),
                'user' => array(self::BELONGS_TO, 'User', 'user_id'),
                'poll' => array(self::BELONGS_TO, 'Poll', 'poll_id'),
        );
    }

    /**
     * Gets query for [[OptionGuestVotes]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OptionGuestVoteQuery
     */
    public function getOptionGuestVotes()
    {
        return $this->hasMany(OptionGuestVote::class, ['option_id' => 'id']);
    }

    public function getOptionGuestVotesCount()
    {
        return $this->getOptionGuestVotes()->count();
    }

    /**
     * Gets query for [[OptionVotes]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OptionVoteQuery
     */
    public function getOptionVotes()
    {
        return $this->hasMany(OptionVote::class, ['option_id' => 'id']);
    }

    public function getOptionVotesCount()
    {
        return $this->getOptionVotes()->count();
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
     * Gets query for [[PollOptionRatings]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollOptionRatingQuery
     */
    public function getPollOptionRatings()
    {
        return $this->hasMany(PollOptionRating::class, ['poll_option_id' => 'id']);
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
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('{{%poll_option_rating}}', ['poll_option_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PollOptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PollOptionQuery(get_called_class());
    }

    /*
     * set attributes and return model
     */
    public function setUnpublishedOptionAttributes($attributes)
    {

        $this->poll_id = intval($attributes['poll_id']);
        $this->user_id =  Yii::$app->user->identity->id;
        $this->title = Html::encode($attributes['title']);
        $this->status = self::OPTION_STATUS_UNPUBLISHED;
        $this->rating = 0;
        $this->date_add = date('Y-m-d H:i:s');
        $this->date_update = date('Y-m-d H:i:s');

        return $this;
    }

    /*
     * Increments answer rating and return new value
     */
    public function upRating(){
        $newRating = $this->rating;
        $changeLog = new PollOptionRating;
        $changeLog->poll_option_id = $this->id;
        $changeLog->user_id =  Yii::$app->user->id;
        $changeLog->date_add = date('Y-m-d H:i:s');
        if($changeLog->save()){
            $this->rating++;
            $this->save();
            $newRating = $this->rating;
        }

        return $newRating;
    }

    /*
     * Return TRUE if poll`s option has status UNPUBLISHED
     */
    public function isUnpublished(){
        $result = false;
        if($this->status == self::OPTION_STATUS_UNPUBLISHED){
            $result = true;
        }

        return $result;
    }

    /*
     * Vote for poll`s option
     */
    public function vote(){
        if( Yii::$app->user->isGuest){
           return $this->voteByGuest();
        }
        $result = false;

        $vote = new OptionVote;
        $vote->option_id = $this->id;
// $TASK:3.1
//        if(! Yii::$app->user->isGuest){
            $vote->user_id =  Yii::$app->user->id;
//        } else {
//          $vote->user_id = User::DEMO_USER_ID;
//        }
        $vote->user_ip = ip2long( Yii::$app->request->userIP);
        $vote->date_add = date('Y-m-d H:i:s');
        if($vote->save()){
            if (YII_ENV == 'dev') {
                echo '<h2>SAVED:!!</h2>';
                echo '<p>vote->id:['.$vote->id.']</p>';
                echo '<pre>';
                var_dump($vote);
                echo '</pre>';
            }
            $result = true;
        }

        return $result;
    }

    /*
     * Vote by Guest for poll`s option
     */
    public function voteByGuest(){
        $result = false;
        $vote = new OptionGuestVote();
        $vote->option_id = $this->id;
        $vote->user_ip = ip2long(Yii::$app->request->userIP);
        $vote->ip_of_user = Yii::$app->request->userIP;
        $vote->date_add = date('Y-m-d H:i:s');
        if($vote->save()){
            $result = true;
        }
        return $result;
    }

    /*
     * Return the percent of votes for poll`s option
     */
    public static function getPercentRating($count,$votes){
        if($count){
            $percent = $votes / $count * 100;
        } else {
            $percent = 0;
        }

        return $percent;
    }

}
