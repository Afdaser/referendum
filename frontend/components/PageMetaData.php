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

    public $imageDefault = '/img/kvadrat.png';
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
            'width' => '',
            'height' => '',
            'mime' => 'image/png',
        ],
    ];
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

    public function setRobots($value) {
        $this->data['robots'] = $value;
    }

    public function getMetaDescriptionHtml() {
        if (!empty($this->data['description'])) {
            $safeDescription = $this->sanitizeMetaValue($this->data['description']);
            return '<meta name="description" content="' . $safeDescription . '">';
        }
    }

    public function getRobotsHtml() {
        if (!empty($this->data['robots'])) {
            $safeRobots = $this->sanitizeMetaValue($this->data['robots']);
            return '<meta name="robots" content="' . $safeRobots . '">';
        }
    }

    public function getFaviconHtml() {
        return '<link rel="icon" href="/img/favicons/favicon.ico" type="image/x-icon">';
    }

    public function getOpenGraphHtml() {
        $tags = [];
        foreach ($this->meta[self::OG] as $key => $value) {
            if (!empty($value)) {
                // Екранування уніфіковане: одна функція для всіх значень мета-тегів.
                $safeKey = $this->sanitizeMetaValue($key);
                $safeValue = $this->sanitizeMetaValue($value);
                $tags[] = '<meta property="og:' . $safeKey . '" content="' . $safeValue . '">';
            }
        }
        return implode("\n    ", $tags);
    }

    public function establishAuto() {
        $this->buildBaseData();
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
        // Пріоритети значень: беремо підготовлені поля з $this->data, щоб не ламати існуючі сценарії.
        $this->fillTwitterContent('url', $this->data['uri']);
        $this->fillTwitterContent('title', $this->data['title']);
        $this->fillTwitterContent('domain', $this->data['host']);
        $this->fillTwitterContent('description', $this->data['description']);
        if (!empty($this->data['image']['uri'])) {
            $this->fillTwitterContent('image', $this->data['image']['uri']);
        }
    }

    public function adjust() {
        $this->adjustOpenGraph();
        $this->adjustTwitter();
    }

    public function adjustOpenGraph() {
        $this->registerOpenGraphTags();
    }

    public function adjustTwitter() {
        $this->registerTwitterTags();
    }

    private function buildBaseData() {
        // Fallback-зображення потрібне, коли сторінка не передала власний OG image.
        $imageUrl = $this->imageDefault;
        $imagePath = Yii::getAlias('@webroot') . $imageUrl;
        if (is_file($imagePath)) {
            $imageUpdatedAt = filemtime($imagePath);
            if ($imageUpdatedAt !== false) {
                $imageUrl .= '?v=' . $imageUpdatedAt;
            }
            $imageInfo = getimagesize($imagePath);
            if ($imageInfo !== false) {
                $this->data['image']['width'] = $imageInfo[0];
                $this->data['image']['height'] = $imageInfo[1];
                $this->data['image']['mime'] = $imageInfo['mime'];
            }
        }
        $this->data['image']['uri'] = Url::to(Yii::$app->request->hostInfo . $imageUrl);
        // Через request-компонент стабільніше у CLI/тестах, ніж прямий доступ до $_SERVER.
        $this->data['host'] = Yii::$app->request->hostName;
        $this->data['locale'] = Yii::$app->language;
        if (empty($this->data['uri'])) {
            // Fallback-URL: повна адреса поточного запиту для коректного og:url/twitter:url.
            $this->data['uri'] = Yii::$app->request->hostInfo . Yii::$app->request->url;
        }
    }

    private function sanitizeMetaValue($value) {
        // Централізоване екранування для атрибутів content/property/name.
        return Html::encode((string) $value);
    }

    private function registerOpenGraphTags() {
        foreach ($this->meta[self::OG] as $key => $item) {
            if (!empty($item)) {
                Yii::$app->view->registerMetaTag(['property' => 'og:' . $key, 'content' => $item]);
            }
        }
    }

    private function registerTwitterTags() {
        foreach ($this->meta[self::TWITTER] as $key => $item) {
            if (!empty($item['content'])) {
                if (empty($item['type'])) {
                    $item['type'] = 'name';
                }
                switch ($item['type']) {
                    case 'name':
                        Yii::$app->view->registerMetaTag(['name' => self::TWITTER . ':' . $key, 'content' => $item['content']]);
                        break;
                    case 'property':
                        Yii::$app->view->registerMetaTag(['property' => self::TWITTER . ':' . $key, 'content' => $item['content']]);
                        break;
                    default:
                        break;
                }
            }
        }
    }

    private function fillTwitterContent($key, $content) {
        if (isset($this->meta[self::TWITTER][$key])) {
            $this->meta[self::TWITTER][$key]['content'] = $content;
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
