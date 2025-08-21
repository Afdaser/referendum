<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\PollCommentRating]].
 *
 * @see \common\models\PollCommentRating
 */
class PollCommentRatingQuery extends \yii\db\ActiveQuery
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
     * @return \common\models\PollCommentRating[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\PollCommentRating|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
