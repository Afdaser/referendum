<?php

use frontend\widgets\WLanguageSelector;

/** @var $this Controller */ ?>
<!-- ~/frontend/views/layouts/main/header.php -->
<!-- Header
================================================== -->
<header class="header">
    <div class="container">
        <div class="row header_row">
            <div class="header_brand">
                <?php if (Yii::$app->request->url == '/') : ?>
                    <div class="logo" aria-label="Referendum.social">
                        <?php // Зберігаємо незмінний розмір логотипа для візуальної цілісності бренду. ?>
                        <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58"><br>
                        <span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                    </div>
                <?php else: ?>
                    <a href="/" class="logo">
                        <?php // Зберігаємо незмінний розмір логотипа для візуальної цілісності бренду. ?>
                        <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58"><br>
                        <span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <div class="header_controls">
                <div class="search_b">
                    <form method="post" action="/site/search" name="HeaderSearch">
                        <?php // Залишаємо CSRF-поле в формі, щоб пошук працював коректно. ?>
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                        <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Yii::t("main", 'Пошук'); ?>...">
                        <button type="submit" class="search_btn" aria-label="<?= Yii::t("main", 'Пошук'); ?>"></button>
                        <input type="checkbox" name="SearchForm[search_in_title]" checked hidden value="1">
                    </form>
                </div>

                <div class="language_wrap">
                    <?= WLanguageSelector::widget(); ?>
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
