<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Модель FAQ для конкретного опитування.
 */
class PollStaticFaq extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%poll_static_faq}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['poll_id'], 'required'],
            [['poll_id', 'position', 'created_at', 'updated_at'], 'integer'],
            [['is_dynamic'], 'boolean'],
            [['question', 'answer'], 'string'],
            [['question', 'answer'], 'required'],
            [['poll_id'], 'exist', 'skipOnError' => true, 'targetClass' => Poll::class, 'targetAttribute' => ['poll_id' => 'id']],
        ];
    }
}
