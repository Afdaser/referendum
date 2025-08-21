<?php

namespace frontend\components;

use Yii;
use yii\base\BaseObject;
use yii\helpers\Url;
use yii\bootstrap\Html;

/**
 * Description of PageMetaData
 *
 * @author alex
 */
class PageMetaData extends BaseObject {

    const OG = 'og';
    const TWITTER = 'twitter';

    public $imageDefault = '/img/logo.png';
    protected $baseDataKeys = [
        'host',
        'uri',
        'local_uri',
        'title',
        'description',
        'page',
        'translation',
        'image',
    ];
    protected $data = [
        'uri' => '',
        'title' => '',
//        'title' => '#default title: Statistics - Site',
//        'title' => 'Statistics - Site',
//        'description' => '',
//        'description' => '#default description: Statistics - Authors Site',
        'description' => 'Statistics - Authors Site',
        'keywords' => '',
        'robots' => 'index, follow',
        'image' => [
            'uri' => '',
            'width' => '1200',
            'height' => '630',
            'mime' => 'image/jpeg',
        ],
    ];
    /*
      <meta property="og:title" content='Statistics - Site'>
      <meta property="og:description" content="Statistics - Authors Site">
      //        <meta property="og:site_name" content="referendum.social">
      //        <meta property="og:url" content="http://en.referendum.social/site/authors">
      //        <meta property="og:image" content="http://referendum.social/img/layout/logo_social.png">
      //       <meta property="og:type" content="website">
     */
    protected $meta = [
        self::OG => [
            'locale' => 'uk_UA',
            'title' => '',
            'description' => '',
            'site_name' => SITE_DOMAIN,
            'url' => '',
            'image' => '',
            'type' => 'website',
        ],
        self::TWITTER => [
            'card' => ['type' => 'name', 'content' => 'summary_large_image',],
            'domain' => ['type' => 'property', 'content' => SITE_DOMAIN],
            'url' => ['type' => 'property', 'content' => '',],
            'title' => ['type' => 'name', 'content' => '',],
            'description' => ['type' => 'name', 'content' => '',],
            'image' => ['type' => 'name', 'content' => '',],
        ]
    ];

    public function init() {
        if (empty($this->data['title'])){
            $this->data['title'] = Yii::t("main", "Online-statistic.com - сайт який повністю присвячен статистиці");
        }
        $this->establishAuto();
        return parent::init();
    }

    public function getTitle() {
        return Html::encode($this->data['title']);
    }

    public function setTitle($value) {
        $this->data['title'] = $value;
    }

    public function setKeywords($value) {
        $this->data['keywords'] = $value;
    }

    public function setDescription($value) {
        $this->data['description'] = $value;
    }

    public function getMetaDescriptionHtml() {
        //         <meta name="description" content="Page description">
        if (!empty($this->data['description']))
            return <<<HTML_META_DESCRIPTION
<meta name="description" content="{$this->data['description']}">

HTML_META_DESCRIPTION;
    }

    public function getRobotsHtml() {
        // <meta name="robots" content="index, follow">
        if (!empty($this->data['robots']))
            return <<<HTML_ROBOTS
<meta name="robots" content="{$this->data['robots']}">

HTML_ROBOTS;
    }

    public function getFaviconHtml() {

        return <<<HTML_FAVICON
        <!-- Favicons + Touch Icons
        ================================================== -->
        <link rel="icon" href="/img/favicons/favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon" sizes="76x76" href="/img/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/img/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/img/favicons/apple-touch-icon-152x152.png">
HTML_FAVICON;
    }

    public function getOpenGraphHtml() {
        $dataHtml = '';
        foreach ($this->meta[self::OG] as $key => $value) {
            if (!empty($value)) {
                $dataHtml .= '<meta property="og:' . $key . '" content="' . $value . '">' . "\n";
            }
        }
        return $dataHtml;
//        return <<<HTML_OPEN_GRAPH
//        <!-- Open Graph
//        ================================================== -->
//{$dataHtml}
//HTML_OPEN_GRAPH;
    }

    public function establishAuto() {
        $this->data['image']['uri'] = Url::to(\Yii::$app->request->hostInfo . $this->imageDefault);
        $this->data['host'] = $_SERVER['HTTP_HOST'];
        $this->data['locale'] = \Yii::$app->language;
        if (empty($this->data['uri'])) {
            $this->data['uri'] = Yii::$app->request->hostInfo . Yii::$app->request->url;
//            if (!empty($this->data['local_uri'])) {
//                $this->data['uri'] = \Yii::$app->request->hostInfo . $this->data['local_uri'];
//            }else{
//                $this->data['uri'] = \Yii::$app->request->hostInfo .'/'. $this->data['local_uri'];// = $this->pageUrl();
//            }
        }
    }

    public function establish($data) {
        foreach ($this->baseDataKeys AS $key) {
            if (isset($data[$key])) {
                $this->data[$key] = $data[$key];
            }
        }
    }

    public function prepare() {
        $this->prepareImage();
        $this->prepareOpenGraph();
        $this->prepareTwitter();
    }

    public function prepareImage() {
        if (!empty($this->data['image'])) {
            if (is_array($this->data['image'])) {
                if (!empty($this->data['image']['uri'])) {
//                $this->meta[self::OG]['image'] = \Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => Url::to(\Yii::$app->request->hostInfo . '/images/og/logo_gbs.png')]);
                    $this->meta[self::OG]['image'] = $this->data['image']['uri'];
                }
                $this->meta[self::OG]['image:width'] = $this->data['image']['width'];
                $this->meta[self::OG]['image:height'] = $this->data['image']['height'];
                $this->meta[self::OG]['image:type'] = $this->data['image']['mime'];
            } else {
                $this->meta[self::OG]['image'] = $this->data['image'];
            }
        }
    }

    public function prepareOpenGraph() {
        $this->meta[self::OG]['locale'] = $this->data['locale'];
        $this->meta[self::OG]['title'] = $this->data['title'];
        $this->meta[self::OG]['description'] = $this->data['description'];
        if (empty($this->meta[self::OG]['url'])) {
            $this->meta[self::OG]['url'] = $this->data['uri'];
        }
    }

    public function prepareTwitter() {
        $this->meta['twitter']['url']['content'] = $this->data['uri'];
        $this->meta['twitter']['title']['content'] = $this->data['title'];
        $this->meta['twitter']['domain']['content'] = $this->data['host'];
        $this->meta['twitter']['title']['content'] = $this->data['title'];
        $this->meta['twitter']['description']['content'] = $this->data['description'];
        if (!empty($this->data['image']['uri'])) {
            $this->meta['twitter']['image']['content'] = $this->data['image']['uri'];
        }
    }

    public function adjust() {
        $this->adjustOpenGraph();
        $this->adjustTwitter();
        /*
          <!-- Facebook Meta Tags -->
          <meta property="og:url" content="http://domen.com.ua/ru/projects/zhivchik/">
          <meta property="og:type" content="website">
          <meta property="og:title" content="Ребрендинг и Разработка Дизайна упаковки | DoMeN.com.ua">
          <meta property="og:description" content="Ребрендинг и разработка дизайна упаковки Звоните!⭐Опыт-️1500 кейсов $ Лучшая✅DoMeN.com.ua">
          <meta property="og:image" content="http://domen.com.ua/images/og/logo_gbs.png">
          /* */

        /*
          <!-- Facebook Meta Tags -->
          <meta property="og:url" content="http://domen.com.ua/ru/projects/zhivchik/">
          <meta property="og:type" content="website">
          <meta property="og:title" content="Ребрендинг и Разработка Дизайна упаковки | DoMeN.com.ua">
          <meta property="og:description" content="Ребрендинг и разработка дизайна упаковки ✅ Звоните!⭐Опыт-️1500 кейсов $ Лучшая✅DoMeN.com.ua">
          <meta property="og:image" content="http://domen.com.ua/images/og/logo_gbs.png">
          <meta property="og:locale" content="ru_RU" />
          <meta property="og:type" content="article" />
          <meta property="og:title" content="Бренд бук СТМ - DoMeN.com.ua" />
          <meta property="og:url" content="http://domen.com.ua/ru/proekt/brandbook-easy-good/" />
          <meta property="og:site_name" content="DoMeN.com.ua" />
          <meta property="article:modified_time" content="2022-03-15T19:36:58+00:00" />
          <meta property="og:image" content="http://domen.com.ua/wp-content/uploads/2022/03/anons-brandbook.jpg?_t=1656508023" />
          <meta property="og:image:width" content="418" />
          <meta property="og:image:height" content="315" />
          <meta property="og:image:type" content="image/jpeg" />
         */
    }

    public function adjustOpenGraph() {
        foreach ($this->meta[self::OG] as $key => $item) {
            if (!empty($item)) {
                \Yii::$app->view->registerMetaTag(['property' => 'og:' . $key, 'content' => $item]);
            }
        }
    }

    public function adjustTwitter() {
        foreach ($this->meta[self::TWITTER] as $key => $item) {
            if (!empty($item['content'])) {
                if (empty($item['type'])) {
                    $item['type'] = 'name';
                }
                switch ($item['type']) {
                    case 'name':
                        \Yii::$app->view->registerMetaTag(['name' => self::TWITTER . ':' . $key, 'content' => $item['content']]);
                        break;
                    case 'property':
                        \Yii::$app->view->registerMetaTag(['property' => self::TWITTER . ':' . $key, 'content' => $item['content']]);
                        break;
                    default:
                        break;
                }
            }
        }
    }

    public function dumpAndDie($info) {
        echo '<pre>';
        echo '<h2>Data:</h2>';
        var_dump($this->data);
        echo '<h2>Meta:</h2>';
        var_dump($this->meta);
        echo '</pre><hr>';
        die($info);
    }

}
