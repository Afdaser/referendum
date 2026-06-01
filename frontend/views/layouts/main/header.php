<?php

use frontend\widgets\WLanguageSelector;
use yii\helpers\Url;

/** @var $this Controller */ ?>
<!-- ~/frontend/views/layouts/main/header.php -->
<!-- Header
================================================== -->
<header class="header">
    <div class="container">
        <div class="header_row">
            <?php if (Yii::$app->request->url == '/') : ?>
                <div class="logo">
                    <?php // Оновлюємо alt-текст логотипа для актуальної назви сайту. ?>
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58"><span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                </div>
            <?php else: ?>
                <a href="/" class="logo">
                    <?php // Оновлюємо alt-текст логотипа для актуальної назви сайту. ?>
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                    <span class="sub_text_logo">
                        <?php echo Yii::t("main", 'кожен голос важливий'); ?>
                    </span>
                </a>
            <?php endif; ?>

            <div class="right_header_b">
                <?php // У хедері лишаємо перемикач піддомену, а пошук і навігацію переносимо у компактне бургер-меню. ?>
                <?= WLanguageSelector::widget(); ?>

                <details class="header_menu">
                    <summary class="header_menu_toggle" aria-label="<?= Yii::t('main', 'Меню'); ?>">
                        <span class="header_menu_icon" aria-hidden="true"></span>
                        <span class="header_menu_text"><?= Yii::t('main', 'Меню'); ?></span>
                    </summary>

                    <div class="header_menu_panel">
                        <nav class="header_menu_nav" aria-label="<?= Yii::t('main', 'Меню'); ?>">
                            <a class="header_menu_link" href="<?= Url::to(['/news/index']); ?>"><?= Yii::t('poll', 'Новини'); ?></a>
                            <?php if (Yii::$app->user->isGuest): ?>
                                <a class="header_menu_link header_menu_link--primary" href="<?= Url::to(['/site/login']); ?>"><?= Yii::t('main', 'Увійти'); ?></a>
                                <a class="header_menu_link" href="<?= Url::to(['/site/signup']); ?>"><?= Yii::t('main', 'Зареєструватись'); ?></a>
                            <?php endif; ?>
                        </nav>

                        <form class="header_menu_search" method="post" action="<?= Url::to(['/site/search']); ?>" name="HeaderSearch">
                            <?php // CSRF лишаємо у формі пошуку, бо вона відправляється POST-запитом. ?>
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                            <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Yii::t('main', 'Пошук'); ?>...">
                            <button class="search_btn" type="submit" aria-label="<?= Yii::t('main', 'Пошук'); ?>"></button>
                            <input type="checkbox" name="SearchForm[search_in_title]" checked hidden value="1">
                        </form>
                    </div>
                </details>
            </div>
        </div>
    </div>
<!-- Global site tag (gtag.js) - Google Analytics -->
<?php /*
<script async src="https://www.googletagmanager.com/gtag/js?id=G-GBLWFCYF8N"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-GBLWFCYF8N');
</script>
<?php /* */ ?>
</header>
