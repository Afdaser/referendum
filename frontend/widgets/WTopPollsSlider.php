<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap4\Widget;
use common\models\Poll;

/**
 * Description of WTopPollsSlider
 *
 * @author alex
 */
class WTopPollsSlider extends Widget {

    public $data;
    protected $view = 'top-polls-slider';
    public $activePolls;

    public function initYiiOne() {
        $criteria = new CDbCriteria;
        $criteria->addCondition('show_on_slider = 1');
//		$criteria->addCondition('status = :status');
//		$criteria->params[':status'] = Poll::POLL_STATUS_ACTIVE;
        $criteria->addCondition('id <> :holderPoll');
        $criteria->params[':holderPoll'] = Poll::HOLDER_PAGE_POLL_ID;

        $SERVER = explode('.', $_SERVER['SERVER_NAME']);
        if ($SERVER[0] == 'en') {
            $criteria->addCondition('poll_language_id = :polllanguageid');
            $criteria->params[':polllanguageid'] = '3';
        } else if ($SERVER[0] == 'ru') {
            $criteria->addCondition('poll_language_id = :polllanguageid');
            $criteria->params[':polllanguageid'] = '2';
        } else if ($SERVER[0] == 'ua') {
            $criteria->addCondition('poll_language_id = :polllanguageid');
            $criteria->params[':polllanguageid'] = '1';
        }else if ($SERVER[0] == 'no') {
            $criteria->addCondition('poll_language_id = :polllanguageid');
            $criteria->params[':polllanguageid'] = '4';
        }

        $criteria->limit = Yii::$app->params['POLLS_LIMIT_TOP_SLIDER'];
        ;
        $criteria->order = 't.date_add DESC';
        $this->activePolls = Poll::model()->findAll($criteria);
    }

    public function init() {
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
        


        $this->activePolls = Poll::find()
                ->where(['poll_language_id' => $languageId, 'show_on_slider' => 1 ])
                ->andWhere(['<>','id', Poll::HOLDER_PAGE_POLL_ID])
                ->limit(Yii::$app->params['POLLS_LIMIT_TOP_SLIDER'])
                ->orderBy(['date_add' => SORT_DESC])
                ->all();
        //         $criteria->addCondition('id <> :holderPoll');
        // $criteria->params[':holderPoll'] = Poll::HOLDER_PAGE_POLL_ID;
//		$criteria->addCondition('status = :status');
//		$criteria->params[':status'] = Poll::POLL_STATUS_ACTIVE;
    }

    public function run() {
        return $this->render($this->view, [
                    'data' => $this->data,
                    'activePolls' => $this->activePolls,
        ]);
    }

}
