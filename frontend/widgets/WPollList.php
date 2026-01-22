<?php

namespace frontend\widgets;

use Yii;
use yii\widgets\BaseListView AS BaseWidget;
use yii\helpers\Html;

/**
 * Description of WPollList
 *
 * @author alex
 */
class WPollList extends BaseWidget
{
    /** @var string название view, яку використовуємо для відмальовування списку */
    protected $view = 'poll-list';

    /** @var array перелік пунктів меню для виджета */
    public $menu = [];
    /** @var array додаткові дані, які приходять від контролера */
    public $data = [];
    /** @var \yii\data\DataProviderInterface джерело даних для списку */
    public $dataProvider;
    public $searchModel;
    /** @var object|null форма пошуку опитувань */
    public $searchForm;
    public $count;
    public $totalCount;
    public $pages;
    /** @var bool чи потрібно показувати службовий відладочний блок */
    public $showDebug = false;

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
    /**
     * Initializes the view.
     */
    public function init()
    {
        // Відладочний блок показуємо лише у разі явного запиту, щоб не засмічувати UI звичайним користувачам.
        $debugSection = $this->showDebug && YII_DEBUG
            ? "<div style=\"border:0px dashed blue; width:96%;\">\n{debug}\n</div>\n"
            : '';

        $this->layout = <<<LAYOUT
{$debugSection}{summary}\n{items}\n
<div class="bottom_pagination_b clearfix">
{sorter}\n
{pager}
</div>

LAYOUT;
        $this->emptyText = Yii::t('yii', 'No results found.');
        $this->emptyTextOptions = ['class' => 'empty', 'style' => 'border:1px dotted green;'];
        $this->showOnEmpty = true;

        $initResult = parent::init();

        return $initResult;
    }

    public function renderItems()
    {
        $user = $this->resolveUser();
        $this->applyDefaultFilters();

        return $this->render($this->view, [
            'data' => $this->data,
            'dataProvider' => $this->dataProvider,
            'search' => $this->searchForm,
            'sort' => $this->data['sort'],
            'limit' => $this->data['limit'],
            'category' => $this->data['category'],
            'tag' => $this->data['tag'],
            'period' => $this->data['period'],
            'language' => $this->data['language'],
            'user' => $user,
            'pollsCount' => $this->dataProvider->getTotalCount(),
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

    public function renderDebug()
    {
        if (!$this->showDebug || !YII_DEBUG) {
            return '';
        }

        $yiiLanguage = Yii::$app->language;
        $yiiLanguageId = Yii::$app->request->languageId ?? 'n/a';

        return <<<HTML
<div style="padding:4px; margin:2px; border:1px dashed red;">
Polls debug:
<br>sort => [{$this->data['sort']}]
<br>limit => [{$this->data['limit']}]
<br>category => [{$this->data['category']}]
<br>tag => [{$this->data['tag']}]
<br>period => [{$this->data['period']}]
<br>language => [{$this->data['language']}]
<br>Yii::app -> language => [{$yiiLanguage}]
<br>Yii::app -> request -> languageId => [{$yiiLanguageId}]
</div>
HTML;
    }

    /**
     * Renders the sorter.
     * @return string the rendering result
     */
    public function renderSorter()
    {
        return $this->render('poll-list-soter', $this->data);
    }

    /**
     * Шукаємо користувача лише за потреби, аби не тягнути зайві об'єкти.
     */
    protected function resolveUser()
    {
        // Профіль конкретного користувача передається контролерами явно,
        // тому не підтягуємо поточну сесію автоматично: це викликає показ
        // блоку мікророзмітки на головній, у тегах та в пошуку.
        if (array_key_exists('user', $this->data)) {
            return $this->data['user'];
        }

        return null;
    }

    /**
     * Проставляємо дефолтні значення фільтрів уніфіковано, щоб не дублювати перевірки.
     */
    protected function applyDefaultFilters(): void
    {
        $this->data['language'] = $this->data['language'] ?? substr(Yii::$app->language, 0, 2);
        $this->data['category'] = $this->data['category'] ?? 'search';
        $this->data['tag'] = $this->data['tag'] ?? $this->getRequestString('tag');
        // За замовчуванням беремо всі опитування без обмеження по даті.
        $this->data['period'] = $this->data['period'] ?? $this->getRequestString('period', 'all');
        $this->data['limit'] = (int)($this->data['limit'] ?? $this->getRequestInt('limit', Yii::$app->params['POLLS_LIMIT_MAIN_PAGE']));
        $this->data['sort'] = $this->data['sort']
            ?? $this->getRequestString('sort')
            ?? ($this->data['category'] === 'own' ? 'default' : 'desc');
    }

    protected function getRequestString(string $name, ?string $default = null): ?string
    {
        $value = Yii::$app->request->get($name);
        if ($value === null || $value === '') {
            return $default;
        }

        return Html::encode($value);
    }

    protected function getRequestInt(string $name, int $default): int
    {
        $value = Yii::$app->request->get($name);
        if ($value === null || $value === '') {
            return $default;
        }

        $value = (int)$value;

        return $value > 0 ? $value : $default;
    }
}
