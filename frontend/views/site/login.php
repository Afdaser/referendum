<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap\ActiveForm $form */
/** @var \common\models\form\LoginForm $model */

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    <br>
                    Need new verification email? <?= Html::a('Resend', ['site/resend-verification-email']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<div class="col-md-4">
    <div class="row left_cut_row">
        <div class="auth_b">
            <div class="title_auth">
                Авторизація</div>
            <div class="inner_auth_b" style="border:1px dashed red;">
                <h2>FORM:</h2>
                <div class="divider_auth"></div>
                
            </div>
        </div>
    </div>
    <div class="row left_cut_row">
        <div class="auth_b">
            <div class="title_auth">
                Авторизація</div>
            <div class="inner_auth_b">

                <form id="login-form" action="/login.html" method="post">
                    <input type="hidden" name="_csrf-frontend" value="rY7v56UFhfCKp1qrgJBTXKXld5GTEjd3jLJhJh6MtInFxoGu5kLMibLVKcrn3hIKxL1CxcRoWDnExwBgV9Tj_g==">
                    <div class="form-group field-loginform-username required">

                        <input type="username" id="loginform-username" class="custom_input_auth" name="LoginForm[username]" placeholder="UserName" aria-required="true">

                        <div class="help-block"></div>
                    </div>
                    <div class="form-group field-loginform-password required">

                        <input type="password" id="loginform-password" class="custom_input_auth" name="LoginForm[password]" placeholder="Password" aria-required="true">

                        <div class="help-block"></div>
                    </div>

                    <div class="btn_block clearfix">
                        <button type="submit" id="loginBtn" class="login" name="login-button">Вхід</button>
<?php /* #DEV:REGISTRATION:OLD */ ?>
                        <a href="#" data-toggle="modal" data-target="#registrtion_step_1">Реєстрація #D3B</a>
                        <hr>
<?php /* */ ?>
                        <a href="#" class="toggle_modal_registrtion">Реєстрація</a>
                    </div>
                </form>

                <div class="refresh_password_block_for_ankor">
                    <a href="">Забули пароль?</a>
                </div>
            </div>
            <div class="divider_auth"></div>
        </div>
    </div>
</div>
