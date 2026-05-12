<?php

namespace common\models;

use common\components\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Статичний FAQ для сторінки опитування (на рівні мови/піддомену).
 */
class PollStaticFaq extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%poll_static_faq}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['language_id', 'question', 'answer'], 'required'],
            [['language_id', 'position', 'created_at', 'updated_at'], 'integer'],
            [['question', 'answer'], 'string'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language_id' => 'id']],
        ];
    }
}
