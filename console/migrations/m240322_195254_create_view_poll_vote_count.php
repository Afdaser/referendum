<?php

use yii\db\Migration;

/**
 * Class m240322_195254_create_view_poll_vote_count
 */
class m240322_195254_create_view_poll_vote_count extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $queries = [
/*
"CREATE VIEW poll_vote_count AS 
SELECT po.`poll_id`, COUNT(ogv.id ) + COUNT(ov.id ) AS vote_count,
COUNT(ogv.id ) AS guest_vote_count, COUNT(ov.id ) AS user_vote_count
FROM `poll_option` po
LEFT JOIN  `option_vote` ov ON po.id = ov.option_id
LEFT JOIN  `option_guest_vote` ogv ON po.id = ogv.option_id
GROUP BY po.`poll_id`"
,
/* */

    "CREATE VIEW poll_vote_count_guest AS 
SELECT po.`poll_id`, COUNT(ogv.id ) AS guest_vote_count
FROM `poll_option` po
LEFT JOIN  `option_guest_vote` ogv ON po.id = ogv.option_id
GROUP BY po.`poll_id`"
,

    "CREATE VIEW poll_vote_count_user AS 
SELECT po.`poll_id`, COUNT(ov.id ) AS user_vote_count
FROM `poll_option` po
LEFT JOIN  `option_vote` ov ON po.id = ov.option_id
GROUP BY po.`poll_id`"
,

    "CREATE VIEW poll_vote_count AS 
SELECT p.`id` AS poll_id, (ogv.guest_vote_count  + ov.user_vote_count) AS vote_count,
ogv.`guest_vote_count`, ov.`user_vote_count` 
FROM `poll` p
LEFT JOIN  `poll_vote_count_guest` ogv ON p.id = ogv.poll_id
LEFT JOIN  `poll_vote_count_user` ov ON p.id = ov.poll_id
",

        ];

        foreach ($queries as $key => $query) {
            $this->execute($query);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $queries = [
            "DROP VIEW poll_vote_count ;",
            "DROP VIEW poll_vote_count_guest ;",
            "DROP VIEW poll_vote_count_user ;"
        ];

        foreach ($queries as $key => $query) {
            $this->execute($query);
        }   
//        echo "m240322_195254_create_view_poll_vote_count cannot be reverted.\n";
//        return false;
    }

}
