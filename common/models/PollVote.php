<?php
namespace common\models;

use common\components\ActiveRecord;

/** The single physical store for registered and guest votes. */
class PollVote extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%poll_vote}}';
    }

    public function rules()
    {
        return [
            [['option_id', 'poll_id', 'is_guest'], 'required'],
            [['option_id', 'poll_id', 'user_id', 'is_guest'], 'integer'],
            [['is_guest'], 'in', 'range' => [0, 1]],
            [['guest_ip_key'], 'required', 'when' => function ($model) { return (bool) $model->is_guest; }],
            [['user_id'], 'required', 'when' => function ($model) { return !$model->is_guest; }],
            [['option_id'], 'exist', 'targetClass' => PollOption::class, 'targetAttribute' => 'id'],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            $this->guest_lock_key = $this->is_guest ? $this->guest_ip_key : null;
            $this->registered_lock_key = $this->is_guest ? null : $this->user_id;
        }
        return true;
    }
}
