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

        // Рахуємо коментарі окремо від голосів, щоб не змінювати існуючу SQL-логіку діаграми.
        $commentPeriodsQuery = <<<SQL2
SELECT
    SUM(CASE WHEN `date_add` >= DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01') THEN 1 ELSE 0 END) AS current_comments,
    SUM(CASE
        WHEN `date_add` >= DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL -1 MONTH), '%Y-%m-01')
            AND `date_add` < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
        THEN 1 ELSE 0
    END) AS previous_comments
FROM `poll_comment`
WHERE `date_add` >= DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL -1 MONTH), '%Y-%m-01')
SQL2;
        $commentPeriods = Yii::$app->db->createCommand($commentPeriodsQuery)->queryOne();
        $data['comments_monthly_totals'] = [
            'current' => (int)($commentPeriods['current_comments'] ?? 0),
            'previous' => (int)($commentPeriods['previous_comments'] ?? 0),
        ];

        return $this->render('index', ['data' => $data]);
    }

}
