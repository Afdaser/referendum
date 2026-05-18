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
            <?php if (Yii::$app->request->url == '/') : ?>
                <div class="logo" aria-label="Referendum.social">
                    <?php // Зберігаємо оригінальні пропорції логотипа 184x58 за вимогою. ?>
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58"><br>
                    <span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                </div>
            <?php else: ?>
                <a href="/" class="logo">
                    <?php // Зберігаємо оригінальні пропорції логотипа 184x58 за вимогою. ?>
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58"><br>
                    <span class="sub_text_logo"><?php echo Yii::t("main", 'кожен голос важливий'); ?></span>
                </a>
            <?php endif; ?>

            <div class="header_controls" aria-label="<?= Yii::t("main", 'Інструменти хедера'); ?>">
                <div class="header_controls_top">
                    <?= WLanguageSelector::widget(); ?>
                </div>
<?php /* * / ?>
                <div class="search_b" style="border:1px dashed red;">
                    <?= WSearchForm::widget([]); ?>
                </div>
<?php /* */ ?>
                <div class="search_b">
                    <form method="post" action="/site/search" name="HeaderSearch">
                        <?php // Залишаємо CSRF-поле в формі, щоб пошук працював коректно. ?>
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                        <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Yii::t("main", 'Пошук'); ?>...">
                        <button type="submit" class="search_btn" aria-label="<?= Yii::t("main", 'Пошук'); ?>"></button>
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
