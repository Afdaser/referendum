<?php

namespace frontend\modules\poll\controllers;

use Yii;
use yii\bootstrap\Html;
//use yii\web\Controller;
use frontend\modules\poll\controllers\AbstractController AS Controller;

use common\models\User;
use common\models\Poll;
use common\models\search\PollSearch;
use common\models\Tag;

class SiteController extends Controller
{

    public $layout='/main';
    public $menu = [];
    public $forcePollModal = false;
    protected $sorting;
    protected $period;
    protected $limit;
    protected $category;


    protected $data = [
        'category' => 'search',
//        'limit' => 10,
        'tag' => '',
        'searchModel' => null,
        'searchModel' => null,
    ];

    /*
     * Main page - category HotPolls
     */
// sorting:(desc|asc)>/<period:\w+>/<perPage
    public function actionIndex($language = null, $sorting = 'desc', $period = 'halfyear', $limit = 10) {
        $this->sorting = $sorting;
        $this->period = $period;
        $this->limit = $limit;
        $this->category = 'hot';
        return self::renderIndex($this->category);
    }

    /*
     * Main page - category HotPolls
     */
// sorting:(desc|asc)>/<period:\w+>/<perPage
    public function actionHotPolls($language = null, $sorting = 'desc', $period = '', $limit = 10) {
		
		
        $this->sorting = $sorting;
        $this->period = $period;
        $this->limit = $limit;
        //var_dump($language);die();
//        $this->pageTitle = Yii::t("main", "Online-statistic.com найкращі опитування за день");
//        $this->pageKeywords = Yii::t("main", "соцопрос, соцопросы, соцопитування");
//        $this->pageDescription = Yii::t("main", "Сайт зроблений як унікальна платформа для опитувань. Кожен може створити опитування на любу тему яку він забажає, та відповісти на опитування інших.");

        $this->category = 'hot';
        return self::renderIndex($this->category);
    }

    /*
     * Main page - category Own
     */

    public function actionMyPolls($language = null, $sorting = 'desc', $period = '', $limit = 10)
    {
        if (Yii::$app->request->isPost) {
            $resultOfSaving = $this->processCreatePoll();
            if ($resultOfSaving) {
                Yii::$app->session->setFlash('success', 'Poll saved successfully');
//                return Yii::$app->response->redirect(['/poll/site/my-polls',]);
                return $this->redirect(['/poll/site/my-polls', ]);
            } else {
                $this->forcePollModal = true;
            }
        }

//        $this->pageTitle = Yii::t("main", "Ваші опитування");
//        $this->pageKeywords = Yii::t("main", "слова моїх опитувань");
//        $this->pageDescription = Yii::t("main", "Сайт зроблений як унікальна платформа для опитувань. Кожен може створити опитування на любу тему яку він забажає, та відповісти на опитування інших.");
        Yii::$app->page->setTitle(Yii::t("main", "Ваші опитування"));
        Yii::$app->page->setKeywords(Yii::t("main", "слова моїх опитувань"));
        Yii::$app->page->setDescription(Yii::t("main", "Сайт зроблений як унікальна платформа для опитувань. Кожен може створити опитування на любу тему яку він забажає, та відповісти на опитування інших."));

        if(!Yii::$app->user->isGuest){
            $this->menu['MyPolls']['label'] .= Html::tag('span', User::getPollsCount(Yii::$app->user->id), ['class' => 'count_poll']);
        }
        $this->category = 'own';
        return self::renderIndex($this->category);
    }

    /*
     * Main page - category Actual
     */

    public function actionActualPolls() {
        Yii::$app->page->setTitle(Yii::t("main", "Актуальні для вас теми"));
        Yii::$app->page->setKeywords(Yii::t("main", "Опис"));
        Yii::$app->page->setDescription(Yii::t("main", "Актуальні для вас теми"));
//        $this->pageTitle = Yii::t("main", "Актуальні для вас теми");
//        $this->pageKeywords = Yii::t("main", "Опис");
//        $this->pageDescription = Yii::t("main", "Актуальні для вас теми");

        $this->category = 'actual';
        return self::renderIndex($this->category);
    }

    /*
     * Test:
     * Userprofile
     */
//    public function actionUserprofilex() {
//        die(__METHOD__);
//    }

    /*
     * User profile - category Actual
     */
    public function actionUserProfile($id) {
        if($user = User::findByPk($id)){
            $this->menu = [];
            return self::renderIndex('user', $user);
        } else {
            $this->redirect('/');
        }
    }

    /*
     * Render the main page
     */

    private function renderIndex($category = null, $user = null, $searchModel = null) {

        if(empty($this->limit)){
        //limit for page: 2,5,10
            if (!$this->limit = intval(Yii::$app->request->get('limit', false))) {
                $this->limit = Yii::$app->params['POLLS_LIMIT_MAIN_PAGE'];
            }
        }

        if(!empty($category)){
            $this->category = $category;
        }else{
            if(empty($this->category)){
                $this->category = 'hot';
            }
        }

        if(empty($this->sorting)){
            if (!$this->sorting = Html::encode(Yii::$app->request->get('sort', false))) {
                if ($this->data['category'] == 'own') {
                    $this->sorting = 'default';
                } else {
                    $this->sorting = 'desc';
                }
            }
        }

        $this->data['searchModel'] = new PollSearch();

        $params = [
            'requestQueryParams' => $this->request->queryParams,
            'limit' => $this->limit,
            'tag' => $tag ?? '',
            'sort' => $this->sorting,
            'category' => $this->category,
            'user' => !empty($user) ? $user : null,
            //$searchModel,
            'period' => $this->period,
            'language' => $this->request->languageId ?? '',
//            'limit' => $limit,
//            'tag' => $tag,
//            'sort' => $sort,
//            'category' => $category,
//            'user' => $user ? $user->id : 0,
//            //$searchModel,
//            'period' => $period,
//            'language' => $language
        ];
//        getPublishedPolls($limit, $tag, $sort, $category, $user ? $user->id : 0, $searchModel, $period, $language);

//        echo '<h2>'.__METHOD__.'</h2><pre>';
//        var_dump($params);
//        echo '</pre>';
//        die(__FILE__.'#'.__LINE__);
        $dataProvider = $this->data['searchModel']->publishedPolls($params);
//        $dataProvider = null;

        return $this->render('index2', [
                    'searchModel' => $this->data['searchModel'],
                    'dataProvider' => $dataProvider,
//                    'category' => $this->data['category'],
                    'params' => $params,
                    'forcePollModal' => $this->forcePollModal,
        ]);
    }

    /*
     * Render the main page in YiiOne
     */

    private function renderIndexYiiOne($category, $user = null, $searchModel = null) {

        //limit for page: 2,5,10
        if (!$limit = intval(Yii::$app->request->get('limit', false))) {
            $limit = Yii::$app->params['POLLS_LIMIT_MAIN_PAGE'];
        }

        //sort polls at page: asc,desc
        if (!$sort = Html::encode(Yii::$app->request->get('sort', false))) {
            if ($category == 'own') {
                $sort = 'default';
            } else {
                $sort = 'desc';
            }
        }

        //filter by poll`s tag
        $tag = Html::encode(Yii::$app->request->get('tag', false));

        //sort period
        if (!$period = Html::encode(Yii::$app->request->get('period', false))) {
//                $period = 'day';
//                $period = 'week';
            $period = 'month';
//                $period = 'year';
        }

//        $language = Yii::$app->request->get('language');
        $language = Yii::$app->language;

        //main page polls and pagination
        $items = Poll::getPublishedPolls($limit, $tag, $sort, $category, $user ? $user->id : 0, $searchModel, $period, $language);

        if (count($items["polls"]) == 0 && !Yii::$app->request->getQuery('click', false)) {
            $period = 'week';
            $items = Poll::getPublishedPolls($limit, $tag, $sort, $category, $user ? $user->id : 0, $searchModel, $period);
        }

        if ($items && $category == 'own') {
            foreach ($items['polls'] as $item) {
                $item->checkEditable();
            }
        }

        if (($category == 'search') && $searchModel->tag) {
            $page = (int) Yii::$app->getRequest()->getParam('page');
            if ($page > 0) {
                $this->pageTitle = $page . ' '
                        . Yii::t('main', '{hashtag} title_pag', array('{hashtag}' => $searchModel->tag->name));
                $this->setPageDescription($page . ' '
                        . Yii::t('main', '{hashtag} description_pag', array('{hashtag}' => $searchModel->tag->name)));
            } else {
                $this->pageTitle = Yii::t('main', '{hashtag} title', array('{hashtag}' => $searchModel->tag->name));
                $this->setPageDescription(Yii::t('main', '{hashtag} description', array('{hashtag}' => $searchModel->tag->name)));
            }
        }

        $pollModel = new Poll;
        $pollModel->unsetAttributes();

        $this->render('index', array(
            'polls' => $items['polls'],
            'pages' => $items['pages'],
//            'totalItems' => $items['pages']->itemCount,
            'totalItems' => $items['pollsCount'],
            'sort' => $sort,
            'limit' => $limit,
            'category' => $category,
            'tag' => $tag,
            'pollModel' => $pollModel,
            'user' => $user,
            'search' => $searchModel,
            'period' => $period,
            'language' => $language,
            'pollsCount' => $items['pollsCount'],
        ));
        return $this->render('index');
    }

    /*
//    public function actionHot()
//    {
//        return $this->render('hot');
//    }

    public function actionHotPolls()
    {
        return $this->render('hot-polls');
    }

    public function actionActualPolls()
    {
        return $this->render('actual-polls');
    }

    public function actionMyPolls()
    {
        return $this->render('my-polls');
    }

/* */
    public function actionAuthors()
    {
        return $this->render('authors');
    }


    public function actionSearch()
    {
        return $this->render('search');
    }

    public function actionSponsors()
    {
        return $this->render('sponsors');
    }

    protected function processCreatePoll()
    {
        $postData = Yii::$app->request->post('Poll');
        if($postData){
            $poll = new Poll;
            $poll->setPollAttributes($postData);
            $options = array();

            if(isset($postData['options'])){
                $options = $postData['options'];
            }

            if($poll->validate() && $poll->save()){
                $poll->createNewPollOptions($options);
                $poll->createNewPollTags($postData['tags']);
                return true;
            }
        }
    }
}
