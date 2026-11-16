<?php

use frontend\widgets\WLanguageSelector;
use frontend\helpers\Url;
use common\models\form\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $this Controller */
$headerLoginModel = Yii::$app->user->isGuest ? new LoginForm() : null;
$isHomePage = Yii::$app->request->url == '/';
?>
<header class="header">
    <div class="container">
        <div class="header_row">
<?php if ($isHomePage): ?>
            <div class="logo">
                <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                <span class="sub_text_logo"><?= Html::encode(Yii::t('main', 'кожен голос важливий')) ?></span>
            </div>
<?php else: ?>
            <a href="/" class="logo">
                <img src="/img/layout/logo.png" alt="logo of website referendum.social" width="184" height="58">
                <span class="sub_text_logo"><?= Html::encode(Yii::t('main', 'кожен голос важливий')) ?></span>
            </a>
<?php endif; ?>
            <div class="right_header_b">
                <details class="header_menu">
                    <summary class="header_menu_toggle" aria-label="<?= Html::encode(Yii::t('main', 'Меню сайту')) ?>">
                        <span class="header_menu_icon" aria-hidden="true"><span></span><span></span><span></span></span>
                    </summary>
                    <nav class="header_menu_panel" aria-label="<?= Html::encode(Yii::t('main', 'Меню сайту')) ?>">
                        <a class="header_menu_link" href="<?= Url::to(['/news/index']) ?>"><?= Html::encode(Yii::t('poll', 'Новини')) ?></a>
<?php if ($headerLoginModel !== null): ?>
                        <a class="header_menu_link toggle_modal_login" href="#header_login_modal" role="button"><?= Html::encode(Yii::t('main', 'Увійти')) ?></a>
                        <a class="header_menu_link header_menu_link_accent toggle_modal_registrtion" href="#"><?= Html::encode(Yii::t('main', 'Зареєструватись')) ?></a>
<?php endif; ?>
                        <div class="search_b header_menu_search">
                            <form method="post" action="/site/search" name="HeaderSearch">
                                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                <input class="search_input" type="search" name="SearchForm[text]" value="" placeholder="<?= Html::encode(Yii::t('main', 'Пошук')) ?>...">
                                <button class="search_btn" type="submit" aria-label="<?= Html::encode(Yii::t('main', 'Пошук')) ?>"></button>
                                <input type="checkbox" name="SearchForm[search_in_title]" checked hidden value="1">
                            </form>
                        </div>
                    </nav>
                </details>
                <?= WLanguageSelector::widget() ?>
            </div>
        </div>
    </div>
<?php if ($headerLoginModel !== null): ?>
    <div class="modal new_poll header_login_modal" id="header_login_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span class="sr-only"><?= Html::encode(Yii::t('main', 'Close')) ?></span>
                    </button>
                    <div class="modal_title"><?= Html::encode(Yii::t('main', 'Авторизація')) ?></div>
                </div>
                <div class="modal-body header_login_modal_body">
<?php $headerLoginForm = ActiveForm::begin([
    'id' => 'header-login-form',
    'action' => Url::toRoute('/site/login'),
]); echo "\n"; ?>
                    <?= $headerLoginForm->field($headerLoginModel, 'username', [
                        'inputOptions' => [
                            'autofocus' => false,
                            'class' => 'custom_input_auth',
                            'placeholder' => Yii::t('main', 'UserName'),
                            'id' => 'header-loginform-username',
                        ],
                    ])->textInput()->label(false) ?>
                    <?= $headerLoginForm->field($headerLoginModel, 'password', [
                        'inputOptions' => [
                            'autofocus' => false,
                            'class' => 'custom_input_auth',
                            'placeholder' => Yii::t('main', 'Password'),
                            'id' => 'header-loginform-password',
                        ],
                    ])->passwordInput()->label(false) ?>
                    <div class="btn_block clearfix">
                        <?= Html::submitButton(Yii::t('main', 'Вхід'), [
                            'class' => 'login',
                            'name' => 'login-button',
                            'id' => 'headerLoginBtn',
                        ]) ?>
                        <a href="#" class="toggle_modal_registrtion"><?= Html::encode(Yii::t('main', 'Реєстрація')) ?></a>
                    </div>
<?php ActiveForm::end(); echo "\n"; ?>
                    <div class="refresh_password_block_for_ankor">
                        <a href="<?= Url::toRoute('/user/passwordRecovery') ?>"><?= Html::encode(Yii::t('user', 'Забули пароль?')) ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
</header>
