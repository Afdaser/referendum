<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Індивідуальний FAQ для конкретного опитування.
 */
class PollFaq extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%poll_faq}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['poll_id', 'question', 'answer'], 'required'],
            [['poll_id', 'position', 'created_at', 'updated_at'], 'integer'],
            [['question', 'answer'], 'string'],
            [['poll_id'], 'exist', 'skipOnError' => true, 'targetClass' => Poll::class, 'targetAttribute' => ['poll_id' => 'id']],
        ];
    }
}
