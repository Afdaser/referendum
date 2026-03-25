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
        // Триматимемо загальні суми за поточний і попередній місяці для великого індикатора у шапці дашборду.
        $data['monthly_totals'] = [
            'current' => 0,
            'previous' => 0,
        ];
        foreach ($dataVotes as $item) {
            $data['monthly_diagram']['labels'][] = $item['the_month'];
            $data['monthly_diagram']['active'][] = (int)$item['user_votes'];
            $data['monthly_diagram']['inactive'][] = (int)$item['guest_votes'];
        }

        // Останні 2 елементи після сортування ASC — це поточний і попередній місяць відповідно.
        $currentMonthIndex = count($dataVotes) - 1;
        $previousMonthIndex = count($dataVotes) - 2;

        if ($currentMonthIndex >= 0) {
            $data['monthly_totals']['current'] = (int)$dataVotes[$currentMonthIndex]['user_votes']
                + (int)$dataVotes[$currentMonthIndex]['guest_votes'];
        }

        if ($previousMonthIndex >= 0) {
            $data['monthly_totals']['previous'] = (int)$dataVotes[$previousMonthIndex]['user_votes']
                + (int)$dataVotes[$previousMonthIndex]['guest_votes'];
        }
        return $this->render('index', ['data' => $data]);
    }

}
