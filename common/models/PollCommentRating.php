<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
//use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%poll_comment_rating}}".
 *
 * @property int $id ID
 * @property int $poll_comment_id Poll comment
 * @property int $user_id User
 * @property int $rating Rrating
 * @property string $date_add Date add
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property PollComment $pollComment
 * @property User $user
 */
class PollCommentRating extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%poll_comment_rating}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poll_comment_id', 'user_id', 'rating'], 'required'],
            [['poll_comment_id', 'user_id', 'rating', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['date_add'], 'safe'],
            [['poll_comment_id', 'user_id'], 'unique', 'targetAttribute' => ['poll_comment_id', 'user_id']],
            [['poll_comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => PollComment::class, 'targetAttribute' => ['poll_comment_id' => 'id']],
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
            'poll_comment_id' => Yii::t('app', 'Poll comment'),
            'user_id' => Yii::t('app', 'User'),
            'rating' => Yii::t('app', 'Rrating'),
            'date_add' => Yii::t('app', 'Date add'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[PollComment]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentQuery
     */
    public function getPollComment()
    {
        return $this->hasOne(PollComment::class, ['id' => 'poll_comment_id']);
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
     * {@inheritdoc}
     * @return \common\models\query\PollCommentRatingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PollCommentRatingQuery(get_called_class());
    }
}
