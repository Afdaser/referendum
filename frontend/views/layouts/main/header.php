<?php

use frontend\widgets\WLanguageSelector;

/** @var $this Controller */ ?>
<!-- ~/frontend/views/layouts/main/header.php -->
<!-- Header
================================================== -->
<header class="header">
    <div class="container">
        <?php // Головна обгортка хедера: адаптивний лейаут для десктопу і мобільних. ?>
        <div class="row header_row">
            <div class="header_brand_block">
                <?php if (Yii::$app->request->url == '/') : ?>
                    <div class="logo">
                        <?php // Залишаємо ті самі розміри логотипа (184x58), як вимагалось. ?>
                        <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58"><br>
                        <span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                    </div>
                <?php else: ?>
                    <a href="/" class="logo">
                        <?php // Залишаємо ті самі розміри логотипа (184x58), як вимагалось. ?>
                        <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                        <br>
                        <span class="sub_text_logo">
                            <?php echo Yii::t("main", 'кожен голос важливий'); ?>
                        </span>
                    </a>
                <?php endif; ?>
            </div>

            <div class="right_header_b">
                <div class="header_controls">
                    <?= WLanguageSelector::widget(); ?>

                    <div class="search_b">
                        <form method="post" action="/site/search" name="HeaderSearch">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                            <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Yii::t("main", 'Пошук'); ?>...">
                            <a href="#" class="search_btn" onclick="document.forms['HeaderSearch'].submit()"></a>
                            <input type="checkbox" name="SearchForm[search_in_title]" checked hidden value="1">
                        </form>
                    </div>
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
