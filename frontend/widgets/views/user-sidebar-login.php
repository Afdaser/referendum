<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use frontend\helpers\Url;

?>
<div class="title_auth">
    <?= Yii::t("main", 'Авторизація'); ?>
</div>
<div class="inner_auth_b">

    <?php // Вхід навмисно доступний лише через локальний обліковий запис, без зовнішніх соцмереж. ?>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'action' => Url::toRoute('/site/login'),
    ]); ?>

    <?= $form->field($model, 'username', [
        'inputOptions' => [
            'autofocus' => false,
            'class' => 'custom_input_auth',
            'placeholder' => Yii::t("main", 'UserName'),
            'id' => 'loginform-username',
        ]
    ])->textInput()->label(false); ?>

    <?= $form->field($model, 'password', [
        'inputOptions' => [
            'autofocus' => false,
            'class' => 'custom_input_auth',
            'placeholder' => Yii::t("main", 'Password'),
            'id' => 'loginform-password',
        ]
    ])->passwordInput()->label(false); ?>

    <div class="btn_block clearfix">
        <?= Html::submitButton(Yii::t("main", 'Вхід'), [
            'class' => 'login',
            'name' => 'login-button',
            'id' => 'loginBtn'
        ]) ?>

        <a href="#" class="toggle_modal_registrtion"><?= Yii::t("main", 'Реєстрація'); ?></a>
    </div>

<?php ActiveForm::end(); echo "\n"; ?>
    <div class="refresh_password_block_for_ankor">
        <a href="<?= Url::toRoute('/user/passwordRecovery') ?>"><?= Yii::t('user', 'Забули пароль?') ?></a>
    </div>
</div>

<div class="divider_auth"></div>
<div class="modal new_poll" id="registrtion_step_1" tabindex="-1" role="dialog" aria-hidden="true"></div>
<?php
// Попередній модальний блок з інформацією про реєстрацію прибрано,
// щоб не дублювати повідомлення та рекламу у блоці авторизації.
?>
<?php /* * /
$form = ActiveForm::begin([
                        'id' => 'login-form', // Optional. I just like to keep things under control
                        'validateOnType' => true, // Optional, but it helps to get rid of extra click
                        'beforeSubmit' => new \yii\web\JsExpression('function($form) {
        $.post(
            $form.attr("action"),
            $form.serialize()
        )
        .done(function(result) {
            $form.parent().html(result);
        })
        .fail(function() {
            console.log("server error");
        });
        return false;
    }'),
            ]);
/* */
/* */
/* */
/*
 * //        alert('Работает!' + form.attr("action"));
 * $.post(
            loginForm.attr("action"),
            loginForm.serialize()
        )
 * //        alert('Submit to ' + loginForm.attr('action'));
 * //        alert('Ajax validation uri:' + '{$uriForValidate}');
 * 
 * 
 * 
 *         console.log(form);
 * 
 *             console.log("server error");
 * 
 *         console.log('loginFormState:'+ loginFormState);
        console.log('loginFormResult:'+ loginFormResult);
        console.log(loginFormResult);
 * 
 *  */

// $scriptLogin = 'alert(\'Inline: ~/frontend/widgets/views/user-sidebar-login.php\');';
$scriptLogin = '';
$uriForValidate =  Yii::$app->UrlManager->createUrl('/ajax/validate/login');
$js = <<<JS_SUBMIT
    $('#login-form').on('beforeSubmit', function(form){
        var loginForm = $('#login-form');
        var loginFormResult = {};
        var loginFormState = false;

        $.post('{$uriForValidate}', loginForm.serialize())
        .done(function(result) {
            loginFormResult = result;
            if(result.error_message){
                alert(result.error_message);
            }else{
                loginFormState = true;
            }
            if(result.login_ok){
                if(result.redirect_uri){
                    window.location.assign(result.redirect_uri);
                }
                loginFormState = true;
            }
        })
        .fail(function(result) {
            loginFormResult = result;
            if(result.error_message){
                alert(result.error_message);
            }
        });
        return loginFormState;
    });
JS_SUBMIT;

$this->registerJs($js);

if(isset($error)) {
    $errorMessage = strip_tags($error);
    $scriptLogin .= <<<JS_LOGIN
    $(function() {
alert('#registrtion_step_1');
/*
//        $('#registrtion_step_1').modal('show'); //DEV.R#03
/* */
        alert({$errorMessage});
    });
JS_LOGIN;
}
/*
if(Yii::$app->user->hasFlash('error')){
    $errorMessage = Yii::$app->user->getFlash('error');
    $scriptLogin .= <<<JS_ERROR
    alert('{$errorMessage}');
JS_ERROR;
}
/*  */
if (Yii::$app->user->isGuest) {
/*
        $(function() {
            /* #TASK:3.1
            $('.poll-option-vote').click(function(){
                $('#registration_info').modal('show');
                return false;
            });
/* */

    $scriptLogin .= <<<JS_GUEST
        $(function() {
            // Для гостей лишаємо блокування тільки на "додати варіант відповіді".
            // Коментарі для гостей дозволені, тому обробник для кнопки коментаря не блокуємо.
            $('#middle_text_input_b > form > div.bottom_btn_b > button.send_btn').click(function(){
                return false;
            });
        });
JS_GUEST;
}


/*
$scriptLogin .= <<<JS_LOGIN_BUTTON
    $(function(){
        $('#loginBtn').on('click',function(){
           if(!$('#username').val() || !$('#password').val()){
               return false;
           }
        });
    });
JS_LOGIN_BUTTON;
/* */

$script = <<<JS_FINAL
jQuery(document).ready(function() {
{$scriptLogin}

});
JS_FINAL;

$this->registerJs($script, View::POS_END);
?>
<?php /* * / ?>
// $scriptAccordion = <<<JS_ACCORDION
alert('Alert - 1');
jQuery(document).ready(function( $ ) {
    alert('Alert - 2');
});
<?php /* * / ?>
<script>

    <?php if(isset($error)):?>
        $(function() {
            $('#registrtion_step_1').modal('show');  //DEV.Rx#04
            alert(<?php echo strip_tags($error);?>);
        });
    <?php endif;?>

    <?php /* if(Yii::$app->user->hasFlash('error')): ?>
        alert('<?php echo Yii::$app->user->getFlash('error'); ?>');
    <?php endif; /* * / ?>

    <?php if (Yii::$app->user->isGuest) { ?>
        $(function() {
            /* #TASK:3.1
            $('.poll-option-vote').click(function(){
                $('#registration_info').modal('show');  //DEV.Rx#05
                return false;
            });
            /* * /

            $('#middle_text_input_b > form > div.bottom_btn_b > button.send_btn').click(function(){
                $('#registration_info').modal('show'); //DEV.Rx#06
                return false;
            });
            // Коментарі для гостей дозволені, тому кнопку надсилання коментаря не блокуємо.
        });
    <?php } ?>

    $(function(){
        $('#loginBtn').on('click',function(){
           if(!$('#username').val() || !$('#password').val()){
               return false;
           }
        });
    });
</script>
<?php endif; /* */ ?>
