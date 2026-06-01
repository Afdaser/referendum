<?php

use frontend\helpers\Url;
use frontend\widgets\WLanguageSelector;
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
                <?php // У хедері залишаємо тільки вибір піддомену, а навігацію та пошук переносимо у бургер-меню. ?>
                <?= WLanguageSelector::widget(); ?>
                <details class="site_burger_menu">
                    <summary class="site_burger_toggle" aria-label="<?= Html::encode(Yii::t('main', 'Відкрити меню')); ?>">
                        <span class="site_burger_icon" aria-hidden="true"></span>
                        <span class="site_burger_text"><?= Html::encode(Yii::t('main', 'Меню')); ?></span>
                    </summary>
                    <div class="site_burger_panel" role="navigation" aria-label="<?= Html::encode(Yii::t('main', 'Головне меню')); ?>">
                        <a class="site_menu_link" href="<?= Html::encode(Url::toRoute(['/news/index'])); ?>">
                            <?= Html::encode(Yii::t('poll', 'Новини')); ?>
                        </a>
                        <?php if (Yii::$app->user->isGuest): ?>
                            <a class="site_menu_link site_menu_link_accent" href="<?= Html::encode(Url::toRoute(['/site/login'])); ?>">
                                <?= Html::encode(Yii::t('main', 'Увійти')); ?>
                            </a>
                            <?php // Лишаємо наявний modal-flow реєстрації, щоб не ламати поточний сценарій сайту. ?>
                            <a class="site_menu_link site_menu_link_accent toggle_modal_registrtion" href="#">
                                <?= Html::encode(Yii::t('main', 'Зареєструватись')); ?>
                            </a>
                        <?php endif; ?>
                        <div class="site_menu_search">
                            <form method="post" action="<?= Html::encode(Url::toRoute(['/site/search'])); ?>" name="HeaderBurgerSearch">
                                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                                <label class="site_menu_search_label" for="header-burger-search-input">
                                    <?= Html::encode(Yii::t('main', 'Пошук')); ?>
                                </label>
                                <div class="search_b site_menu_search_box">
                                    <input id="header-burger-search-input" class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Html::encode(Yii::t('main', 'Пошук')); ?>...">
                                    <button class="search_btn" type="submit" aria-label="<?= Html::encode(Yii::t('main', 'Пошук')); ?>"></button>
                                    <input type="checkbox" name="SearchForm[search_in_title]" checked hidden value="1">
                                </div>
                            </form>
                        </div>
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
