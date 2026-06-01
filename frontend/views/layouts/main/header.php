<?php

use frontend\widgets\WLanguageSelector;
use frontend\helpers\Url;

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
                <?= WLanguageSelector::widget(); ?>

                <?php // Переносимо пошук і навігаційні дії у бургер-меню, залишаючи в шапці тільки вибір піддомену. ?>
                <nav class="header_menu" aria-label="<?= Yii::t('main', 'Головне меню'); ?>">
                    <input class="header_menu__toggle" type="checkbox" id="header-menu-toggle" aria-label="<?= Yii::t('main', 'Відкрити меню'); ?>">
                    <label class="header_menu__button" for="header-menu-toggle" aria-hidden="true">
                        <span class="header_menu__line"></span>
                        <span class="header_menu__line"></span>
                        <span class="header_menu__line"></span>
                    </label>

                    <div class="header_menu__panel">
                        <div class="header_menu__search">
                            <form method="post" action="/site/search" name="HeaderSearch">
                                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                                <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Yii::t('main', 'Пошук'); ?>...">
                                <button class="search_btn" type="submit" aria-label="<?= Yii::t('main', 'Пошук'); ?>"></button>
                                <input type="checkbox" name="SearchForm[search_in_title]" checked hidden value="1">
                            </form>
                        </div>

                        <a class="header_menu__link" href="<?= Url::toRoute(['/news/index']); ?>"><?= Yii::t('poll', 'Новини'); ?></a>

                        <?php if (Yii::$app->user->isGuest): ?>
                            <a class="header_menu__link header_menu__link--primary" href="<?= Url::toRoute('/site/login'); ?>"><?= Yii::t('main', 'Увійти'); ?></a>
                            <a class="header_menu__link header_menu__link--accent toggle_modal_registrtion" href="#"><?= Yii::t('main', 'Зареєструватись'); ?></a>
                        <?php endif; ?>
                    </div>
                </nav>
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
