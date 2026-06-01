<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap\ActiveForm $form */
/** @var \common\models\form\LoginForm $model */

use frontend\helpers\Url;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('main', 'Авторизація');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <div class="auth_b login_page_auth">
            <div class="title_auth">
                <?= Html::encode(Yii::t('main', 'Авторизація')); ?>
            </div>
            <div class="inner_auth_b">
                <?php // Сторінка входу лишається фолбеком для користувачів без JS і використовує ту саму модель LoginForm. ?>
                <?php $form = ActiveForm::begin([
                    'id' => 'page-login-form',
                    'action' => Url::toRoute('/site/login'),
                ]); ?>

                <?= $form->field($model, 'username', [
                    'inputOptions' => [
                        'autofocus' => true,
                        'class' => 'custom_input_auth',
                        'placeholder' => Yii::t('main', 'UserName'),
                        'id' => 'page-loginform-username',
                    ],
                ])->textInput()->label(false); ?>

                <?= $form->field($model, 'password', [
                    'inputOptions' => [
                        'class' => 'custom_input_auth',
                        'placeholder' => Yii::t('main', 'Password'),
                        'id' => 'page-loginform-password',
                    ],
                ])->passwordInput()->label(false); ?>

                <div class="btn_block clearfix">
                    <?= Html::submitButton(Yii::t('main', 'Вхід'), [
                        'class' => 'login',
                        'name' => 'login-button',
                        'id' => 'pageLoginBtn',
                    ]); ?>
                    <a href="#" class="toggle_modal_registrtion"><?= Html::encode(Yii::t('main', 'Реєстрація')); ?></a>
                </div>

                <?php ActiveForm::end(); ?>

                <div class="refresh_password_block_for_ankor">
                    <a href="<?= Url::toRoute('/user/passwordRecovery'); ?>">
                        <?= Html::encode(Yii::t('user', 'Забули пароль?')); ?>
                    </a>
                </div>
            </div>
            <div class="divider_auth"></div>
        </div>
    </div>
</div>
