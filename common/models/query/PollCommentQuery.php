<?php

namespace common\models\query;

use common\models\PollComment;

/**
 * This is the ActiveQuery class for [[\common\models\PollComment]].
 *
 * @see \common\models\PollComment
 */
class PollCommentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function commentsWithAnswers($userId)
    {
//        return $this->andWhere(['status' => 1]);
//        return $this->andWhere(['status' => 1]);
        
        $query = $this->select('pc1.*')->distinct()->from(['pc1' => PollComment::tableName()])
                        ->leftJoin(['pc2' => PollComment::tableName()], ' pc1.id = pc2.parent_id ')
                        ->andWhere(['pc1.user_id' => $userId, 'pc2.read_by_parent' => 0]);
        return $query;


//                        ->select(['p.*', 'pvc.vote_count', 'pvc.guest_vote_count', 'pvc.user_vote_count']);
//        $query = $this->select()->from([''])->
//                andWhere(['status' => 1]);

/*
`read_by_parent` AND
 * 
SELECT * FROM `poll_comments` WHERE  `parent_id` IS NOT NULL AND `read_by_parent`;
 * 
UPDATE `poll_comments` SET `read_by_parent` = '1' WHERE `poll_comments`.`id` = 234;
UPDATE `poll_comments` SET `read_by_parent` = '1' WHERE `poll_comments`.`id` IN (223, 232, 234);

SELECT * FROM `poll_comments` WHERE  `id` IN (222, 232, 233);
 * 
 * 
 * 
 * 
 * 
SELECT `t`.`id`, `t`.`parent_id`, `t`.`poll_id`, `t`.`user_id`, `t`.`content`, `t`.`status`,
  `t`.`is_new`, `t`.`has_new`, `t`.`read_by_parent`, `t`.`rating`, `t`.`date_add`, `t`.`date_update`
        FROM `poll_comments` `t` 
        LEFT JOIN poll_comments ON t.id = poll_comments.parent_id
        WHERE (t.user_id = 1186) AND (poll_comments.read_by_parent = 0)
        GROUP BY poll_comments.parent_id
 */
    }

    /**
     * {@inheritdoc}
     * @return \common\models\PollComment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\PollComment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
