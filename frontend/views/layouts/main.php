<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use frontend\widgets\WTopPollsSlider;
use frontend\helpers\Url;

//use yii\bootstrap4\Breadcrumbs;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$bundle = AppAsset::register($this);
$this->registerCssFile('/css/bootstrap.min.css');

$locale = Yii::$app->language;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= $locale ?>" class="no-js">
<head>
    <!-- Meta tags
    ================================================== -->
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Yii::$app->page->robotsHtml; ?>
    <?= Yii::$app->page->metaDescriptionHtml; ?>

    <?= Yii::$app->page->prepare(); ?>
    <?= Yii::$app->page->openGraphHtml; ?>
    <?php /* = Yii::$app->page->adjustOpenGraph(); /* */ ?>
    
<?php /*
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
/* */ ?>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php $this->registerCsrfMetaTags() ?>

    <!-- Site title
    ================================================== -->
    <title><?= Yii::$app->page->title ?></title>

    <!-- Styles
    ================================================== -->
    <?php $this->head() ?>
    <noscript>
        <?= Html::cssFile('/css/bootstrap.min.css') ?>
        <?php foreach ($bundle->css as $cssFile): ?>
            <?= Html::cssFile(rtrim($bundle->baseUrl, '/') . '/' . ltrim($cssFile, '/')) ?>
        <?php endforeach; ?>
    </noscript>

    <?= Yii::$app->page->faviconHtml ?>

    <?= $this->render('main/meta'); ?>
    <script>
        window.rfrndm = <?=
    json_encode([
        'csrf' => Yii::$app->request->csrfToken,
        'locale' => Yii::$app->language,
        'languageId' => Yii::$app->request->languageId,
        'routes' => [
            'ajax' => [
                'registrtion_step_one' => Url::toRoute(['/ajax/modal/registrtion-step-one']),
                'create_poll_step_one' => Url::toRoute(['/ajax/modal/create-poll-step-one']),
                'poll_form_ajax' => Url::toRoute(['/ajax/modal/store-poll-form']),
            ],
            'customer' => [
                'home' => "/home",
            ]
        ],
    ]);
    ?>;
    </script>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>
    <div id="outdated">
        <h6>Your browser is out-of-date!</h6>
        <p>Update your browser to view this website correctly.</p>
        <p class="last"><a href="#" id="btnCloseUpdateBrowser" title="Close">&times;</a></p>
    </div>

    <?= $this->render('main/header'); ?>
    <div class="content">
            <div class="sub_header_slider_b">
                <?= WTopPollsSlider::widget(); ?>
            </div>
            <div class="container clearfix">
                <div class="row clearfix">
                    <div class="top_banner_b" style="min-height:270px;height:auto !important;">
<?php if(YII_ENV == 'prod'): ?>
                            <ins class="adsbygoogle"
                                 style="display:block"
                                 data-ad-client="ca-pub-3234808971320300"
                                 data-ad-slot="5179063077"
                                 data-ad-format="auto">
                            </ins>
<?php else: ?>
                        <div <?= (YII_ENV == 'dev') ? ' style="border:2px dotted red;"' : '' ?>
                            <h4>adsbygoogle</h4>
                            data-ad-client="ca-pub-3234808971320300"
                            <br>
                            data-ad-slot="5179063077"
                        </div>
<?php endif; ?>
                        <?php /* OLD.adsbygoogle :
                          <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                          <?php /* /OLD.adsbygoogle */ ?>

                        <?php /* /OLD.adsbygoogle */ ?>
                    </div>
                    <?= $content; ?>
                    <?= $this->render('main/right_column'); ?>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="top_banner_b">
                        <?php /* OLD.adsbygoogle :
                          <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                          <?php /* /OLD.adsbygoogle */ ?>
                        <!-- bot -->
<?php if(YII_ENV == 'prod'): ?>
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="ca-pub-3234808971320300"
                             data-ad-slot="5039462279"
                             data-ad-format="auto"></ins>
<?php else: ?>
                        <div <?= (YII_ENV == 'dev') ? ' style="border:2px dotted red;"' : '' ?>
                            <h4>adsbygoogle</h4>
                            data-ad-client="ca-pub-3234808971320300"<br>
                             data-ad-slot="5039462279"
                        </div>
<?php endif; ?>

                             <?php /* OLD.adsbygoogle :
                               <script>
                               (adsbygoogle = window.adsbygoogle || []).push({});
                               </script>
                               <?php /* /OLD.adsbygoogle */ ?>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
<?php
// Перевіряємо, чи користувач на головній сторінці (site/index) або на головній сторінці піддомену
if (Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index') :
?>
    <div class="top_banner_b">
        <?php
        // Якщо категорія 'hot' — показуємо головний SEO-текст (з common/messages/.../seo.php)
        if (Yii::$app->params['category'] == 'hot') {
            echo Yii::t('seo', 'main_page_text');
        }

        // Якщо категорія 'search' і є опис тега, показуємо його (але тільки якщо це перша сторінка)
        $getPage = isset($_GET['page']) ? $_GET['page'] : null;
        if (Yii::$app->params['category'] == 'search' && isset(Yii::$app->params['model']->tag->description) && !$getPage) {
            echo Yii::$app->params['model']->tag->description;
        }
        ?>
    </div>
<?php endif; ?>
<?php
use common\models\Tag;

/*
 * Вивід опису тега (якщо такий є).
 * Показується:
 *  - лише на сторінках тегів (шлях містить 'tag/<name>' або є GET параметр 'tag')
 *  - лише на першій сторінці пагінації (без ?page або page==1)
 * Опис виводиться всередині класу top_banner_b (щоб підхопились існуючі стилі).
 */

$getPage = Yii::$app->request->get('page', null);

// 1) Першочергово беремо GET-параметр ?tag=
$tagName = Yii::$app->request->get('tag', null);

// 2) Якщо його немає — шукаємо в URL-сегментах (наприклад: /tag/USA)
if (!$tagName) {
    $path = trim(Yii::$app->request->pathInfo, '/'); // наприклад "tag/USA"
    if ($path !== '') {
        $segments = explode('/', $path);
        $pos = array_search('tag', $segments, true);
        if ($pos !== false && isset($segments[$pos + 1]) && $segments[$pos + 1] !== '') {
            $tagName = urldecode($segments[$pos + 1]);
        }
    }
}

// 3) Якщо назва тега знайдена і ми на першій сторінці — підтягуємо опис з БД і виводимо
if ($tagName && ($getPage === null || (int)$getPage === 1)) {
    // Знаходимо тег за назвою (поле name). Якщо у вас теги зберігаються іншим полі — заміни тут.
    $tag = Tag::find()->where(['name' => $tagName])->one();
    if ($tag && !empty($tag->description)) {
        echo '<div class="top_banner_b">' . $tag->description . '</div>';
    }
}
?>

                </div>
            </div>
        </div>
<?= $this->render('main/footer'); ?>
<!-- Javascript
================================================== -->
<?php /* Yii2 base layout: * / ?>
<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php /* /END of Yii2 base layout */ ?>
<?php $this->endBody() ?>
<?php if(YII_ENV == 'prod'): ?>
<script type="text/javascript">//<![CDATA[
var adsenseLazyload = false; window.addEventListener("scroll", function(){ if ((document.documentElement.scrollTop != 0 && adsenseLazyload === false) || (document.body.scrollTop != 0 && adsenseLazyload === false)) { (function() { var ad = document.createElement('script'); var att = document.createAttribute('data-ad-client'); att.value = 'ca-pub-3234808971320300'; ad.setAttributeNode(att ); ad.async = true; ad.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'; var sc = document.getElementsByTagName('head')[0]; sc.parentNode .insertBefore(ad, sc); })(); adsenseLazyload = true; } }, true)
//]]></script>
<script>
   jQuery(function ($) {
$(".adsbygoogle").each(function () { (adsbygoogle = window.adsbygoogle || []).push({}); })
});</script>

<?php endif; ?>
</body>
</html>
<?php $this->endPage(); ?>