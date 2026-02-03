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
$pageNumber = (int) Yii::$app->request->get('page', 1);
$isFirstPage = $this->params['isMainPageFirst'] ?? $pageNumber <= 1;
$isMainIndexPage = (
    Yii::$app->controller->id === 'site'
    && Yii::$app->controller->action->id === 'index'
    && $isFirstPage
);

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

    <?php if ($isMainIndexPage): ?>
        <?php
        // Додаємо hreflang лише для головної сторінки домену або піддомену, без пагінації.
        ?>
        <link rel="alternate" hreflang="uk-UA" href="https://ua.referendum.social/" />
        <link rel="alternate" hreflang="ru-RU" href="https://ru.referendum.social/" />
        <link rel="alternate" hreflang="nb-NO" href="https://no.referendum.social/" />
        <link rel="alternate" hreflang="en-US" href="https://en.referendum.social/" />
        <link rel="alternate" hreflang="en-NZ" href="https://nz.referendum.social/" />
        <link rel="alternate" hreflang="x-default" href="https://referendum.social/" />
    <?php endif; ?>

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
    <?php
    // AppAsset підставляє preload-лінки зі службовим атрибутом data-rel, тому після завантаження
    // браузер одразу перемикає їх у звичайні stylesheet без HTML-екранізації у JS-виразі onload.
    ?>
    <?php $this->head() ?>
    <noscript>
        <?php
        // Якщо JavaScript вимкнено (або зламаний), даємо браузеру стандартні stylesheet-посилання.
        // Так Googlebot і користувачі без JS все одно отримують ідентичний вигляд сторінки.
        ?>
        <?= Html::cssFile('/css/bootstrap.min.css', ['rel' => 'stylesheet']) ?>
        <?php foreach ($bundle->css as $cssFile): ?>
            <?= Html::cssFile(rtrim($bundle->baseUrl, '/') . '/' . ltrim($cssFile, '/'), ['rel' => 'stylesheet']) ?>
        <?php endforeach; ?>
    </noscript>

    <?= Yii::$app->page->faviconHtml ?>

    <?= $this->render('main/meta'); ?>
    <?php
    // Вставляємо індивідуальні скрипти конкретної сторінки без додаткового обгортання,
    // щоб уникнути вкладених <script>-тегів (напр. для JSON-LD).
    if (!empty($this->params['pageCustomScripts'])) {
        echo $this->params['pageCustomScripts'];
    }
    ?>
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
    <?php
    // Віджет шапки сайту рендериться одразу, без попереджувальних банерів про застарілий браузер.
    ?>
    <?= $this->render('main/header'); ?>

            <div class="sub_header_slider_b">
                <?= WTopPollsSlider::widget(); ?>
            </div>
            <div class="container clearfix">
                <div class="row clearfix">
                    <div class="top_banner_b" style="height:auto !important;">
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
// Перевіряємо, чи користувач на головній сторінці (site/index) або на головній сторінці піддомену.
// Додаємо умову, щоб SEO-текст показувався лише на першій сторінці пагінації.
if ($isMainIndexPage) :
?>
    <div class="top_banner_b">
        <?php
        // Якщо категорія 'hot' — показуємо головний SEO-текст з адмінки для домену/піддомену.
        if (Yii::$app->params['category'] == 'hot') {
            // Показуємо лише SEO-текст: заголовок перенесли у верхню частину сторінки.
            $mainPageText = $this->params['mainPageText'] ?? '';
            if ($mainPageText !== '') {
                echo $mainPageText;
            }
        }
        ?>
    </div>
<?php endif; ?>

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
        // Ссилкуємося на слаг "about", щоб меню вказувало на чисту адресу без /site.
        ['label' => 'About', 'url' => ['/page/view', 'page' => 'about']],
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
