<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%option_votes}}".
 *
 * @property int $id ID
 * @property int $option_id Option
 * @property int|null $user_id User
 * @property int|null $user_ip User IP
 * @property string $date_add Date add
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property PollOption $option
 * @property User $user
 */
class OptionVote extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%option_vote}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_id'], 'required'],
            [['option_id', 'user_id', 'user_ip', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['date_add'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => PollOption::class, 'targetAttribute' => ['option_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'option_id' => Yii::t('app', 'Option'),
            'user_id' => Yii::t('app', 'User'),
            'user_ip' => Yii::t('app', 'User IP'),
            'date_add' => Yii::t('app', 'Date add'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[Option]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollOptionQuery
     */
    public function getOption()
    {
        return $this->hasOne(PollOption::class, ['id' => 'option_id']);
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
     * @return \common\models\query\OptionVoteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\OptionVoteQuery(get_called_class());
    }
}
