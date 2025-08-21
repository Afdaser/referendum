<?php

namespace backend\modules\poll\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `poll` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $query = <<<SQL1
SELECT d.the_month, d.`the_date`, IFNULL(gv.guest_votes, 0) AS guest_votes, IFNULL(uv.user_votes, 0) AS user_votes
FROM
(
SELECT seq,
        DATE_FORMAT( DATE_ADD(CURRENT_DATE(), INTERVAL - ( seq-1 ) MONTH), '%Y-%M') AS `the_month`,
        DATE_FORMAT( DATE_ADD(CURRENT_DATE(), INTERVAL - ( seq-1 ) MONTH), '%Y-%m-01') AS `the_date` 
FROM seq_1_to_12 ) d
LEFT JOIN (
SELECT DATE_FORMAT(`date_add`, '%Y-%m-01') AS uv_date, COUNT(*) AS user_votes FROM `option_vote` WHERE `date_add` > DATE_ADD(CURRENT_DATE(),INTERVAL -400 DAY)
GROUP BY uv_date
) uv ON uv.uv_date = d.`the_date`
LEFT JOIN (
SELECT DATE_FORMAT(`date_add`, '%Y-%m-01') AS gv_date, COUNT(*) AS guest_votes FROM `option_guest_vote` WHERE `date_add` > DATE_ADD(CURRENT_DATE(),INTERVAL -400 DAY)
GROUP BY gv_date
) gv ON gv.gv_date = d.`the_date`
ORDER BY the_date ASC
SQL1;

        $dataVotes = Yii::$app->db->CreateCommand($query)->queryAll();

        $data['monthly_diagram'] = [
            'labels' => [],
            'active' => [],
            'inactive' => [],
        ];
        foreach ($dataVotes as $key => $item) {
            $data['monthly_diagram']['labels'][] = $item['the_month'];
            $data['monthly_diagram']['active'][] = $item['user_votes'];
            $data['monthly_diagram']['inactive'][] = $item['guest_votes'];
        }
        return $this->render('index', ['data' => $data]);
    }

}
