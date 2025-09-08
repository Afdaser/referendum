<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap4\Widget;
use common\models\Poll;
use common\models\Language;
use yii\db\Query;
use yii\db\Expression;
use common\models\OptionVote;
use common\models\OptionGuestVote;

/**
 * Class WPollsSidebar
 */
class WPollsSidebar extends Widget {

    public $data;

    /**
     * @var $pollsLast Poll[]
     */
    public $pollsLast;

    /**
     * @var $pollsPopular Poll[]
     */
    public $pollsPopular;
    protected $view = 'polls-sidebar';

    public function initYiiOne() {
        $criteria = new CDbCriteria;
        $criteria->addCondition('t.status <> :status');
        $criteria->params[':status'] = Poll::POLL_STATUS_UNPUBLISHED;
        $criteria->addCondition('t.id <> :holderPoll');
        $criteria->params[':holderPoll'] = Poll::HOLDER_PAGE_POLL_ID;
        $criteria->addCondition('t.show_for_all_languages = 1 OR t.poll_language_id = :language');
        $criteria->params[':language'] = Language::getLanguageByName(Yii::$app->language);
        $criteria->limit = Yii::$app->params['POLLS_LIMIT_SIDEBAR'];
        $criteria->order = 't.date_add DESC';
        $this->PollsLast = Poll::model()->findAll($criteria);

        $criteria->select = 't.*,count(v.id) as vote_count';
        $criteria->join = 'LEFT JOIN poll_comments v on (v.poll_id = t.id and v.date_add > SUBDATE(CURRENT_TIMESTAMP,INTERVAL 7 DAY))';
        $criteria->group = '1,2';
        $criteria->order = 'vote_count desc';

        $this->PollsPopular = Poll::model()->findAll($criteria);
    }

    public function init() {
        /* */
        $SERVER = explode('.', $_SERVER['SERVER_NAME']);
        switch ($SERVER[0]) {
            case 'en':
                $languageId = '3';
                break;
            case 'ru':
                $languageId = '2';
                break;
            case 'ua':
                $languageId = '1';
                break;
			case 'no':
                $languageId = '4';
                break;
            default:
                $languageId = '3';
                break;
        }
//        $languageId = '3';
        /*         */
//        $languageId = Language::getLanguageByName(Yii::$app->language);

        $this->pollsLast = Poll::find()
                ->where(['or',
                    ['poll_language_id' => $languageId,],
                    ['show_for_all_languages' => 1]
                ])
                ->andWhere(['<>', 'status', Poll::POLL_STATUS_UNPUBLISHED])
                ->andWhere(['<>', 'id', Poll::HOLDER_PAGE_POLL_ID])
                ->limit(Yii::$app->params['POLLS_LIMIT_SIDEBAR'])
                ->orderBy(['date_add' => SORT_DESC])
                ->all();
        //         $criteria->addCondition('id <> :holderPoll');
        // $criteria->params[':holderPoll'] = Poll::HOLDER_PAGE_POLL_ID;
//		$criteria->addCondition('status = :status');
//		$criteria->params[':status'] = Poll::POLL_STATUS_ACTIVE;
        $interval = new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)');

        $userVotes = (new Query())
                ->select(['po.poll_id', 'COUNT(*) AS user_votes'])
                ->from(['ov' => OptionVote::tableName()])
                ->innerJoin(['po' => '{{%poll_option}}'], 'po.id = ov.option_id')
                ->where(['>', 'ov.date_add', $interval])
                ->groupBy('po.poll_id');

        $guestVotes = (new Query())
                ->select(['po.poll_id', 'COUNT(*) AS guest_votes'])
                ->from(['ogv' => OptionGuestVote::tableName()])
                ->innerJoin(['po' => '{{%poll_option}}'], 'po.id = ogv.option_id')
                ->where(['>', 'ogv.date_add', $interval])
                ->groupBy('po.poll_id');

        $this->pollsPopular = Poll::find()
                ->alias('p')
                ->select(['p.*', '(COALESCE(uv.user_votes,0) + COALESCE(gv.guest_votes,0)) AS votes'])
                ->leftJoin(['uv' => $userVotes], 'uv.poll_id = p.id')
                ->leftJoin(['gv' => $guestVotes], 'gv.poll_id = p.id')
                ->where(['or',
                    ['p.poll_language_id' => $languageId],
                    ['p.show_for_all_languages' => 1]
                ])
                ->andWhere(['<>', 'p.status', Poll::POLL_STATUS_UNPUBLISHED])
                ->andWhere(['<>', 'p.id', Poll::HOLDER_PAGE_POLL_ID])
                ->groupBy('p.id')
                ->orderBy(['votes' => SORT_DESC])
                ->limit(Yii::$app->params['POLLS_LIMIT_SIDEBAR'])
                ->all();
    }

    public function run() {
        return $this->render($this->view, [
//                    'data' => $this->data,
                    'pollsPopular' => $this->pollsPopular,
                    'pollsLast' =>  $this->pollsLast,
        ]);
    }

}
