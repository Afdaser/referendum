<?php

use frontend\widgets\WLanguageSelector;
use frontend\widgets\WSearchForm;

/** @var $this Controller */
$isHomePage = Yii::$app->request->url == '/';
?>
<!-- ~/frontend/views/layouts/main/header.php -->
<!-- Header
================================================== -->
<header class="header">
    <div class="container">
        <div class="row header_row">
            <?php if ($isHomePage) : ?>
                <?php // На головній лишаємо логотип без посилання, щоби не дублювати переходи на поточну сторінку. ?>
                <div class="logo" aria-label="referendum.social">
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                    <span class="sub_text_logo"><?= Yii::t("main", 'кожен голос важливий'); ?></span>
                </div>
            <?php else: ?>
                <?php // На внутрішніх сторінках зберігаємо перехід на головну через логотип. ?>
                <a href="/" class="logo" aria-label="referendum.social">
                    <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                    <span class="sub_text_logo"><?php echo Yii::t("main", 'кожен голос важливий'); ?></span>
                </a>
            <?php endif; ?>

            <div class="right_header_b">
                <div class="header_top_tools">
                    <?= WLanguageSelector::widget(); ?>
                </div>
<?php /* * / ?>
                <div class="search_b" style="border:1px dashed red;"><?= WSearchForm::widget([]); ?></div>
<?php /* */ ?>
                <div class="search_b">
                    <form method="post" action="/site/search" name="HeaderSearch">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                        <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Yii::t("main", 'Пошук'); ?>...">
                        <?php // Кнопку залишаємо посиланням для сумісності зі старим JS-кліком. ?>
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
