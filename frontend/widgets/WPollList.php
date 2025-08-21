<?php

namespace frontend\widgets;

use Yii;
use yii\widgets\BaseListView AS BaseWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;

/**
 * Description of WPollList
 *
 * @author alex
 */
class WPollList extends BaseWidget {

    protected $view = 'poll-list';

//    const CSS_OPERATION_BASE = 'unit-operation-base';
//    const CSS_OPERATION_DONE = 'unit-operation-done';
//    const CSS_OPERATION_WORK = 'unit-operation-work';
//    const CSS_OPERATION_START = 'unit-operation-start';
//    const CSS_OPERATION_ERROR = 'unit-operation-error';
//    const CSS_BOTTLENECK = 'unit-operation-error';
//
//    const CSS_TEXT_ORDER_DONE = 'text-order-done';
//    const CSS_TEXT_ORDER_WORK = 'text-order-work';
//    const CSS_TEXT_ORDER_START = 'text-order-start';
//    const CSS_TEXT_ORDER_ERROR = 'text-order-error';
//    const SEPARATOR = '&nbsp;|&nbsp;';
//    const SEPARATOR_TH = ' | ';
//
//    protected $users;
//    protected $operations;
//
//    public $params;

    public $menu = [];
    public $data;
    public $dataProvider;
    public $searchModel;
    public $searchForm;
//    public $itemOptions;
//    public $batches;
    public $count;
    public $totalCount;
    public $pages;

    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     */
//    public $layout = "{summary}\n{items}\n{pager}";
/*
\n
<div style="border:2px dashed blue; width:90%;">
{debug}
</div>\n
 */

    /**
     * Initializes the view.
     */
    public function init()
    {
        $this->layout = <<<LAYOUT
<div style="border:0px dashed blue; width:96%;">
{debug}
</div>
{summary}\n{items}\n
<div class="bottom_pagination_b clearfix">
{sorter}\n
{pager}
</div>

LAYOUT;
/*

<ul class="pagination" id="yw1">
<li class="first hidden"><a href="/"></a></li>
<li class="previous hidden"><a href="/">‹</a></li>
<li class="page selected"><a href="/">1</a></li>
<li class="page"><a href="?page=2">2</a></li>
<li class="next"><a href="?page=2">›</a></li>
<li class="last"><a href="?page=2"></a></li>
</ul>
<div class="right_count_select">
Polls on the page:
<select class="count_article" onchange="document.location.href = &quot;/site/hotPolls/desc/month/&quot;+$(this).val()">
<option value="10" selected="">10</option>
<option value="5">5</option>
<option value="2">2</option>
</select>
</div>
</div>
 */
        /* /var/www/vhosts_yii/referendum.social/referendum.social.local/vendor/yiisoft/yii2/widgets/BaseListView.php
        if ($this->dataProvider === null) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }
        if ($this->emptyText === null) {
            $this->emptyText = Yii::t('yii', 'No results found.');
        }
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        /* */
        $this->emptyText = Yii::t('yii', 'No results found.');
        $this->emptyTextOptions = ['class' => 'empty', 'style' => 'border:1px dotted green;'];
        $this->showOnEmpty = true;

        $initResult = parent::init();

        return $initResult;
    }

    public function renderItems() {
        if (isset($this->data['user'])) {
            $user = $this->data['user'];
        }else{
            // $user = Yii::$app->user->isGuest ? null : Yii::$app->user->identity;
            $user = null;
        }
        if (empty($this->data['language'])) {
            $this->data['language'] = substr(Yii::$app->language, 0 ,2 );
        }
        if (empty($this->data['category'])) {
            $this->data['category'] = 'search';
        }
        //filter by poll`s tag
        if (empty($this->data['tag'])) {
            $this->data['tag'] = Html::encode(Yii::$app->request->get('tag', false));
        }
        //limit for page: 2,5,10
//        if (empty($this->data['limit'])) {
//            if (!$this->data['limit'] = intval(Yii::$app->request->get('limit', false))) {
//                $this->data['limit'] = Yii::$app->params['POLLS_LIMIT_MAIN_PAGE'];
//            }
//        }

        //sort polls at page: asc,desc
//        if (empty($this->data['sort'])) {
//            if (!$this->data['sort'] = Html::encode(Yii::$app->request->get('sort', false))) {
//                if ($this->data['category'] == 'own') {
//                    $this->data['sort'] = 'default';
//                } else {
//                    $this->data['sort'] = 'desc';
//                }
//            }
//        }



        //sort period

        if (!$this->data['period'] = Html::encode(Yii::$app->request->get('period', false))) {
//                $this->data['period'] = 'day';
//                $this->data['period'] = 'week';
            $this->data['period'] = 'month';
//                $this->data['period'] = 'year';
        }

//        $this->dataProvider =  $this->searchForm->publishedPolls($this->data);

        $qty = $this->dataProvider->count;

        if($qty == 0) {
//            $this->view = 'poll-empty';
        }
//die(__FILE__.'#'.__LINE__);
        return $this->render($this->view, [
                    'data' => $this->data,
//            'dataUnits' => $this->dataUnits,
                    'dataProvider' => $this->dataProvider,
                    'search' => $this->searchForm,
//                    'searchForm' => $this->searchForm,

//            'batches' => $this->batches,
//            'pages' => $this->pages,
//            'operations' => $this->operations,
//                    'polls' => $items['polls'],
//                    'pages' => $items['pages'],
//                    'totalItems' => $items['pages']->itemCount,
                    'sort' => $this->data['sort'] ?? '',
                    'limit' => $this->data['limit'] ?? Yii::$app->params['POLLS_LIMIT_MAIN_PAGE'],
                    'category' => $this->data['category'],
                    'tag' => $this->data['tag'],
                    'period' => $this->data['period'],
                    'language' => $this->data['language'],

//                    'pollModel' => $this->data['pollModel'],
                    'user' => $user,

                    'pollsCount' => $this->dataProvider->getTotalCount(),
//                    'pollsCount' => $items['pollsCount'],
        ]);
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|bool the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{summary}':
                return $this->renderSummary();
            case '{items}':
                return $this->renderItems();
            case '{pager}':
                return $this->renderPager();
            case '{sorter}':
                return $this->renderSorter();
            case '{debug}':
                return $this->renderDebug();
            default:
                return false;
        }
    }

    public function renderDebug(){
        return '';
        $yiiLanguage = Yii::$app->language;
        $yiiLanguageId = Yii::$app->request->languageId;

        $outHtml = <<<SORTER
<div style="padding:4px; margin:2px; border:1px dashed red;">
Polls debug:
<br>sort => [{$this->data['sort']}]
<br>limit => [{$this->data['limit']}]
<br>category => [{$this->data['category']}]
<br>tag => [{$this->data['tag']}]
<br>period => => [{$this->data['period']}]
<br>language => [{$this->data['language']}]
<br>Yii::app - > language => [{$yiiLanguage}]
<br>Yii::app - > request - > languageId  => [{$yiiLanguageId}]

</div>
SORTER;
        return $outHtml;
    }

    /**
     * Renders the sorter.
     * @return string the rendering result
     */
    public function renderSorter()
    {
        return $this->render('poll-list-soter', $this->data);

        $outHtml = <<<SORTER
<div class="right_count_select">
Polls on the page:
<select class="count_article" onchange="document.location.href = &quot;/site/hotPolls/desc/month/&quot;+$(this).val()">
<option value="10" selected="">10</option>
<option value="5">5</option>
<option value="2">2</option>
</select>
</div>
SORTER;
//        $sort = $this->dataProvider->getSort();
//        if ($sort === false || empty($sort->attributes) || $this->dataProvider->getCount() <= 0) {
//            return '';
//        }
//        /* @var $class LinkSorter */
//        $sorter = $this->sorter;
//        $class = ArrayHelper::remove($sorter, 'class', LinkSorter::className());
//        $sorter['sort'] = $sort;
//        $sorter['view'] = $this->getView();

//        return $outHtml;
    }
}
