<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%poll_vote_count}}".
 *
 * @property int $poll_id Poll
 * @property int $vote_count
 * @property int $guest_vote_count
 * @property int $user_vote_count
 */
class PollVoteCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%poll_vote_count}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poll_id'], 'required'],
            [['poll_id', 'vote_count', 'guest_vote_count', 'user_vote_count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'poll_id' => Yii::t('app', 'Poll'),
            'vote_count' => Yii::t('app', 'Vote Count'),
            'guest_vote_count' => Yii::t('app', 'Guest Vote Count'),
            'user_vote_count' => Yii::t('app', 'User Vote Count'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PollVoteCountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PollVoteCountQuery(get_called_class());
    }
}
