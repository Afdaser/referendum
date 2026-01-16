<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap4\Widget;
use common\models\Poll;
use common\models\Language;

/**
 * Description of WTopPollsSlider
 *
 * @author alex
 */
class WTopPollsSlider extends Widget {

    public $data;
    protected $view = 'top-polls-slider';
    public $activePolls;

    /**
     * Визначає ідентифікатор мови для поточного піддомену.
     *
     * @return int
     */
    private function resolveLanguageId(): int
    {
        // Спершу беремо вже визначений ідентифікатор із LanguageRequest.
        $languageId = Yii::$app->request->languageId ?? null;
        if ($languageId) {
            return (int) $languageId;
        }

        // Якщо піддомен не визначено, намагаємося визначити мову за поточною локаллю.
        $langCode = substr(Yii::$app->language, 0, 2);
        if ($langCode === 'uk') {
            $langCode = 'ua';
        }
        $languageId = Language::getLanguageByName($langCode);

        // Повертаємо запасне значення, щоб не ламати існуючу поведінку.
        return $languageId ? (int) $languageId : 3;
    }

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
        // Використовуємо визначену мову, щоб піддомен nz мав власні «вибрані» опитування.
        $languageId = $this->resolveLanguageId();
        


        $this->activePolls = Poll::find()
                ->where(['poll_language_id' => $languageId, 'show_on_slider' => 1])
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
