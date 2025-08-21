<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\PollRatingVote]].
 *
 * @see \common\models\PollRatingVote
 */
class PollRatingVoteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function countByAttributes($criteria)
    {
        return $this->andWhere($criteria)->count();
    }

    /**
     * {@inheritdoc}
     * @return \common\models\PollRatingVote[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\PollRatingVote|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
