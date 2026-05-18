<?php

use frontend\widgets\WLanguageSelector;
use frontend\widgets\WSearchForm;

/** @var $this Controller */ ?>
<!-- ~/frontend/views/layouts/main/header.php -->
<!-- Header
================================================== -->
<header class="header header--rebuilt">
    <div class="container">
        <div class="row header__row">
            <?php if (Yii::$app->request->url == '/') : ?>
                <div class="logo header__logo" aria-label="Referendum Social home">
                    <?php // Зберігаємо розмір логотипа без змін, щоб не ламати візуальну айдентику. ?>
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                    <span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                </div>
            <?php else: ?>
                <a href="/" class="logo header__logo" aria-label="Referendum Social home">
                    <?php // Зберігаємо розмір логотипа без змін, щоб не ламати візуальну айдентику. ?>
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                    <span class="sub_text_logo"><?php echo Yii::t("main", 'кожен голос важливий'); ?></span>
                </a>
            <?php endif; ?>

            <div class="right_header_b header__controls">
                <div class="header__language"><?= WLanguageSelector::widget(); ?></div>
<?php /* * / ?>
                <div class="search_b" style="border:1px dashed red;"><?= WSearchForm::widget([]); ?></div>
<?php /* */ ?>
                <div class="search_b header__search">
                    <form method="post" action="/site/search" name="HeaderSearch" class="header__search-form">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                        <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Yii::t("main", 'Пошук'); ?>...">
                        <?php // Тримаємо submit через посилання для повної сумісності зі старою версткою та подіями. ?>
                        <a href="#" class="search_btn" onclick="document.forms['HeaderSearch'].submit()"></a>
                        <input type="checkbox" name="SearchForm[search_in_title]" checked hidden value="1">
                    </form>
                </div>
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
