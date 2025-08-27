<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%poll_tag}}".
 *
 * @property int $id ID
 * @property int $poll_id Poll
 * @property int $tag_id Tag
 *
 * @property Poll $poll
 * @property Tag $tag
 */
class PollTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%poll_tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poll_id', 'tag_id'], 'required'],
            [['poll_id', 'tag_id'], 'integer'],
            [['poll_id'], 'exist', 'skipOnError' => true, 'targetClass' => Poll::class, 'targetAttribute' => ['poll_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => ['tag_id' => 'id']],
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
            'tag_id' => Yii::t('app', 'Tag'),
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
     * Gets query for [[Tag]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TagQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::class, ['id' => 'tag_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            Tag::updateAllCounters(['polls_count' => 1], ['id' => $this->tag_id]);
        } elseif (array_key_exists('tag_id', $changedAttributes)) {
            Tag::updateAllCounters(['polls_count' => -1], ['id' => $changedAttributes['tag_id']]);
            Tag::updateAllCounters(['polls_count' => 1], ['id' => $this->tag_id]);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        Tag::updateAllCounters(['polls_count' => -1], ['id' => $this->tag_id]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PollTagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PollTagQuery(get_called_class());
    }
}
