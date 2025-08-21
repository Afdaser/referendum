<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%poll_answer}}".
 *
 * @property int $id ID
 * @property int $poll_id Poll
 * @property int $user_id User
 * @property string $content Content
 * @property int $status Status
 * @property int $rating Rating
 * @property string $date_add Date add
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property Poll $poll
 * @property User $user
 */
class PollAnswer extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%poll_answer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poll_id', 'user_id', 'content', 'status'], 'required'],
            [['poll_id', 'user_id', 'status', 'rating', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['date_add'], 'safe'],
            [['content'], 'string', 'max' => 255],
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
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'rating' => Yii::t('app', 'Rating'),
            'date_add' => Yii::t('app', 'Date add'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
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
     * @return \common\models\query\PollAnswerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PollAnswerQuery(get_called_class());
    }
}
