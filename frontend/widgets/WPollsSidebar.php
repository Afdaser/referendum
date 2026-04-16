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

    /**
     * Визначає ідентифікатор мови для поточного піддомену.
     *
     * @return int
     */
    private function resolveLanguageId(): int
    {
        // Підтягуємо визначену мову з LanguageRequest, якщо вона є.
        $languageId = Yii::$app->request->languageId ?? null;
        if ($languageId) {
            return (int) $languageId;
        }

        // Якщо піддомен не задано, пробуємо визначити мову через локаль.
        $langCode = substr(Yii::$app->language, 0, 2);
        if ($langCode === 'uk') {
            $langCode = 'ua';
        }
        $languageId = Language::getLanguageByName($langCode);

        // Зберігаємо стару поведінку як запасний варіант.
        return $languageId ? (int) $languageId : 3;
    }

    /**
     * Визначає, чи поточний хост є головним доменом без мовного піддомену.
     *
     * @return bool
     */
    private function isMainDomain(): bool
    {
        // Тимчасове обмеження вимкнено: дозволяємо NZ-опитування для всіх піддоменів.
        return true;
    }

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
        // Визначаємо ідентифікатор мови з урахуванням поточного піддомену.
        $languageId = $this->resolveLanguageId();
        $commentsCountExpression = new Expression('(SELECT COUNT(*) FROM {{%poll_comment}} pc WHERE pc.poll_id = p.id)');

        $pollsLastQuery = Poll::find()
                ->alias('p')
                ->select(['p.*', 'comments_count' => $commentsCountExpression])
                ->andWhere(['<>', 'status', Poll::POLL_STATUS_UNPUBLISHED])
                ->andWhere(['<>', 'id', Poll::HOLDER_PAGE_POLL_ID])
                // Показуємо лише п'ять останніх опитувань, аби сайдбар залишався компактним.
                ->limit(Yii::$app->params['POLLS_LIMIT_SIDEBAR'])
                ->orderBy(['date_add' => SORT_DESC]);

        $lastPollLanguageFilter = ['or',
            ['poll_language_id' => $languageId],
            ['show_for_all_languages' => 1],
        ];

        if ($this->isMainDomain()) {
            // На головному домені додаємо локаль NZ, щоб її опитування стабільно потрапляли у віджет.
            $lastPollLanguageFilter[] = ['poll_language_id' => Language::getLanguageByName('nz')];
        }

        $pollsLastQuery->andWhere($lastPollLanguageFilter);

        $this->pollsLast = $pollsLastQuery->all();
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

        $pollsPopularQuery = Poll::find()
                ->alias('p')
                ->select([
                    'p.*',
                    'votes' => new Expression('(COALESCE(uv.user_votes,0) + COALESCE(gv.guest_votes,0))'),
                    'comments_count' => $commentsCountExpression,
                ])
                ->leftJoin(['uv' => $userVotes], 'uv.poll_id = p.id')
                ->leftJoin(['gv' => $guestVotes], 'gv.poll_id = p.id')
                ->andWhere(['<>', 'p.status', Poll::POLL_STATUS_UNPUBLISHED])
                ->andWhere(['<>', 'p.id', Poll::HOLDER_PAGE_POLL_ID])
                ->groupBy('p.id')
                ->orderBy(['votes' => SORT_DESC])
                // Та сама межа кількості застосовується і для популярних опитувань.
                ->limit(Yii::$app->params['POLLS_LIMIT_SIDEBAR']);

        $popularPollLanguageFilter = ['or',
            ['p.poll_language_id' => $languageId],
            ['p.show_for_all_languages' => 1],
        ];

        if ($this->isMainDomain()) {
            // Для популярних опитувань застосовуємо таке саме включення NZ на головному домені.
            $popularPollLanguageFilter[] = ['p.poll_language_id' => Language::getLanguageByName('nz')];
        }

        $pollsPopularQuery->andWhere($popularPollLanguageFilter);

        $this->pollsPopular = $pollsPopularQuery->all();
    }

    public function run() {
        return $this->render($this->view, [
//                    'data' => $this->data,
                    'pollsPopular' => $this->pollsPopular,
                    'pollsLast' =>  $this->pollsLast,
        ]);
    }

}
