<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
//use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%poll_option_rating}}".
 *
 * @property int $id ID
 * @property int $poll_option_id Poll option
 * @property int $user_id User
 * @property string $date_add Date add
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property PollOption $pollOption
 * @property User $user
 */
class PollOptionRating extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%poll_option_rating}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poll_option_id', 'user_id'], 'required'],
            [['poll_option_id', 'user_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['date_add'], 'safe'],
            [['poll_option_id', 'user_id'], 'unique', 'targetAttribute' => ['poll_option_id', 'user_id']],
            [['poll_option_id'], 'exist', 'skipOnError' => true, 'targetClass' => PollOption::class, 'targetAttribute' => ['poll_option_id' => 'id']],
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
            'poll_option_id' => Yii::t('app', 'Poll option'),
            'user_id' => Yii::t('app', 'User'),
            'date_add' => Yii::t('app', 'Date add'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[PollOption]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollOptionQuery
     */
    public function getPollOption()
    {
        return $this->hasOne(PollOption::class, ['id' => 'poll_option_id']);
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
     * @return \common\models\query\PollOptionRatingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PollOptionRatingQuery(get_called_class());
    }
}
