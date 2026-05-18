<?php

use frontend\widgets\WLanguageSelector;
use frontend\widgets\WSearchForm;

/** @var $this Controller */ ?>
<!-- ~/frontend/views/layouts/main/header.php -->
<!-- Header
================================================== -->
<header class="header">
    <div class="container">
        <div class="row header_row">
            <div class="header_brand_block">
                <?php if (Yii::$app->request->url == '/') : ?>
                    <div class="logo" aria-label="Referendum home brand block">
                        <?php // Зберігаємо той самий розмір логотипа (184x58), щоб не ламати композицію сторінок. ?>
                        <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                        <span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                    </div>
                <?php else: ?>
                    <a href="/" class="logo" aria-label="<?= Yii::t("main", 'На головну'); ?>">
                        <?php // Зберігаємо той самий розмір логотипа (184x58), щоб не ламати композицію сторінок. ?>
                        <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                        <span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <div class="right_header_b">
                <?= WLanguageSelector::widget(); ?>
<?php /* * / ?>
                <div class="search_b" style="border:1px dashed red;"><?= WSearchForm::widget([]); ?></div>
<?php /* */ ?>
                <div class="search_b">
                    <form method="post" action="/site/search" name="HeaderSearch">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                        <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Yii::t("main", 'Пошук'); ?>...">
                        <?php // Лишаємо submit через клік на іконку, щоб зберегти поточну поведінку інтерфейсу. ?>
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
