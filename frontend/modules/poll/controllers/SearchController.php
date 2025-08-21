<?php

namespace frontend\modules\poll\controllers;

use Yii;
use yii\bootstrap\Html;
use yii\web\NotFoundHttpException;
// use yii\web\Controller;
use frontend\modules\poll\controllers\AbstractController as Controller;

use common\models\search\PollSearch;
use frontend\models\forms\SearchForm;
use common\models\Tag;
// use common\models\User;
use common\models\Poll;

class SearchController extends Controller
{
    public $menu = [];
    protected $data = [
        'category' => 'search',
        'limit' => 10,
        'tag' => '',
        'searchModel' => null,
        'searchModel' => null,
    ];

    public function actionSearch($limit = 10, $sorting = '')
    {
        $this->data['searchModel'] = new PollSearch();
        $this->data['searchForm'] = new SearchForm();
        $requestQueryParams = $this->request->queryParams;

        $this->data['searchForm']->load($this->request->post());

        $dataProvider = $this->data['searchModel']->searchForm(
            $this->request->queryParams,
            $this->data['searchForm']
        );

        return $this->render('search', [
            'searchForm'  => $this->data['searchForm'],
            'searchModel' => $this->data['searchModel'],
            'dataProvider'=> $dataProvider,
            'tag'         => '',
            'category'    => $this->data['category'],
        ]);
    }

    public function actionTag($tag)
    {
        $this->data['tag'] = $tag;

        $tagModel = Tag::find()->where(['name' => $tag])->one();
        if (empty($tagModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        // === Canonical за мовою ТЕГУ (аналогічно до PollController) ===
        $langDomains = [
            1 => 'ua.referendum.social', // українська
            2 => 'ru.referendum.social', // російська
            3 => 'en.referendum.social', // англійська
            4 => 'no.referendum.social', // норвезька
        ];

        $canonicalDomain = $langDomains[$tagModel->language_id] ?? 'en.referendum.social';

        // Підтримка пагінації в canonical (якщо є /page/N)
        $page = (int) Yii::$app->request->get('page', 1);
        $path = '/tag/' . rawurlencode($tag);
        if ($page > 1) {
            $path .= '/page/' . $page;
        }

        $canonicalUrl = 'https://' . $canonicalDomain . $path;

        Yii::$app->view->registerLinkTag([
            'rel' => 'canonical',
            'href' => $canonicalUrl,
        ]);
        // === /Canonical ===

        $this->data['searchModel'] = new PollSearch();
        $this->data['searchForm'] = new SearchForm();
        $this->data['searchForm']->load($this->request->queryParams);

        $dataProvider = $this->data['searchModel']->searchTag(
            $this->request->queryParams,
            $this->data['searchForm'],
            $tag
        );

        return $this->render('tag', [
            'searchForm'  => $this->data['searchForm'],
            'searchModel' => $this->data['searchModel'],
            'dataProvider'=> $dataProvider,
            'tag'         => $tag,
            'category'    => $this->data['category'],
        ]);
    }

    public function actionHotPolls()
    {
        $this->data['category'] = 'hot';
        $this->data['searchModel'] = new PollSearch();
        $dataProvider = $this->data['searchModel']->search($this->request->queryParams);

        return $this->render('hot-polls', [
            'searchModel' => $this->data['searchModel'],
            'dataProvider'=> $dataProvider,
            'category'    => $this->data['category'],
        ]);
    }

    public function actionText()
    {
        return $this->render('text');
    }

    public function renderIndex($params)
    {
        $user = Yii::$app->user ?? null;

        // limit for page: 2,5,10
        if (!$this->data['limit'] = intval(Yii::$app->request->get('limit', false))) {
            $this->data['limit'] = Yii::$app->params['POLLS_LIMIT_MAIN_PAGE'];
        }

        // sort polls at page: asc,desc
        if (!$this->data['sort'] = Html::encode(Yii::$app->request->get('sort', false))) {
            if ($this->data['category'] == 'own') {
                $this->data['sort'] = 'default';
            } else {
                $this->data['sort'] = 'desc';
            }
        }

        // filter by poll`s tag
        if (empty($this->data['tag'])) {
            $this->data['tag'] = Html::encode(Yii::$app->request->get('tag', false));
        }

        // sort period
        if (!$this->data['period'] = Html::encode(Yii::$app->request->get('period', false))) {
            $this->data['period'] = 'month';
        }

        $this->data['language'] = substr(Yii::$app->language, 0, 2);

        // main page polls and pagination
        $limit      = $this->data['limit'];
        $tag        = $this->data['tag'];
        $sort       = $this->data['sort'];
        $period     = $this->data['period'];
        $language   = $this->data['language'];
        $category   = $this->data['category'];
        $searchModel= $this->data['searchModel'];

        $items = Poll::getPublishedPolls(
            $limit,
            $tag,
            $sort,
            $category,
            $user ? $user->id : 0,
            $searchModel,
            $period,
            $language
        );

        if (count($items["polls"]) == 0 && !Yii::$app->request->getQuery('click', false)) {
            $period = 'week';
            $items = Poll::getPublishedPolls(
                $limit,
                $tag,
                $sort,
                $category,
                $user ? $user->id : 0,
                $searchModel,
                $period
            );
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
                    . Yii::t('main', '{hashtag} title_pag', ['{hashtag}' => $searchModel->tag->name]);
                $this->setPageDescription($page . ' '
                    . Yii::t('main', '{hashtag} description_pag', ['{hashtag}' => $searchModel->tag->name]));
            } else {
                $this->pageTitle = Yii::t('main', '{hashtag} title', ['{hashtag}' => $searchModel->tag->name]);
                $this->setPageDescription(Yii::t('main', '{hashtag} description', ['{hashtag}' => $searchModel->tag->name]));
            }
        }

        $pollModel = new Poll;
        $pollModel->unsetAttributes();

        return $this->render('index', [
            'polls'       => $items['polls'],
            'pages'       => $items['pages'],
            'totalItems'  => $items['pages']->itemCount,
            'sort'        => $this->data['sort'],
            'limit'       => $this->data['limit'],
            'category'    => $this->data['category'],
            'tag'         => $this->data['tag'],
            'pollModel'   => $this->data['pollModel'],
            'search'      => $this->data['searchModel'],
            'period'      => $this->data['period'],
            'language'    => $this->data['language'],
            'pollsCount'  => $items['pollsCount'],
        ]);
    }
}
