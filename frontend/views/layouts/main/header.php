<?php

use frontend\widgets\WLanguageSelector;
use frontend\helpers\Url;
use yii\helpers\Html;

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
                <details class="header_menu">
                    <summary class="header_menu_toggle" aria-label="<?= Html::encode(Yii::t('main', 'Меню сайту')); ?>">
                        <?php // Іконка бургера сама позначає меню; текст не дублюємо, щоб кнопка була компактною у правому куті. ?>
                        <span class="header_menu_icon" aria-hidden="true"><span></span><span></span><span></span></span>
                    </summary>
                    <nav class="header_menu_panel" aria-label="<?= Html::encode(Yii::t('main', 'Меню сайту')); ?>">
                        <a class="header_menu_link" href="<?= Url::to(['/news/index']); ?>">
                            <?= Html::encode(Yii::t('poll', 'Новини')); ?>
                        </a>
                        <a class="header_menu_link" href="<?= Url::to(['/site/login']); ?>">
                            <?= Html::encode(Yii::t('main', 'Увійти')); ?>
                        </a>
                        <a class="header_menu_link header_menu_link_accent toggle_modal_registrtion" href="#">
                            <?= Html::encode(Yii::t('main', 'Зареєструватись')); ?>
                        </a>

                        <div class="search_b header_menu_search">
                            <?php // Пошук перенесено з відкритої частини хедера всередину бургер-меню. ?>
                            <form method="post" action="/site/search" name="HeaderSearch">
                                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                                <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Html::encode(Yii::t('main', 'Пошук')); ?>...">
                                <button class="search_btn" type="submit" aria-label="<?= Html::encode(Yii::t('main', 'Пошук')); ?>"></button>
                                <input type="checkbox" name="SearchForm[search_in_title]" checked hidden value="1">
                            </form>
                        </div>
                    </nav>
                </details>
                <?= WLanguageSelector::widget(); ?>
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
