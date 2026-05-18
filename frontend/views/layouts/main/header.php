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
                <div class="logo" style="text-decoration: none;">
                    <?php // Залишаємо той самий розмір логотипу (184x58), щоб зберегти поточну айдентику. ?>
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58"><br>
                    <span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                </div>
            <?php else: ?>
                <a href="/" class="logo">
                    <?php // Залишаємо той самий розмір логотипу (184x58), щоб зберегти поточну айдентику. ?>
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                    <br>
                    <span class="sub_text_logo">
                        <?php echo Yii::t("main", 'кожен голос важливий'); ?>
                    </span>
                </a>
            <?php endif; ?>

            <div class="right_header_b">
                <div class="header_controls">
                    <?= WLanguageSelector::widget(); ?>
<?php /* * / ?>
                    <div class="search_b" style="border:1px dashed red;"><?= WSearchForm::widget([]); ?></div>
<?php /* */ ?>
                    <div class="search_b">
                        <form method="post" action="/site/search" name="HeaderSearch">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                            <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Yii::t("main", 'Пошук'); ?>...">
                            <?php // Кнопку залишаємо anchor-елементом для сумісності з поточними JS-обробниками. ?>
                            <a href="#" class="search_btn" onclick="document.forms['HeaderSearch'].submit()" aria-label="<?= Yii::t('main', 'Пошук'); ?>"></a>
                            <input type="checkbox" name="SearchForm[search_in_title]" checked hidden value="1">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
