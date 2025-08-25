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

    <?php ActiveForm::end(); ?>

    <div class="refresh_password_block_for_ankor">
        <a href="<?= Url::toRoute('/user/passwordRecovery'); ?>">
            <?= Yii::t("user", 'Забули пароль?'); ?>
        </a>
    </div>

</div>

<div class="divider_auth"></div>
<!-- Modal DEV2404_M01 -->
<div class="modal new_poll" id="registrtion_step_1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="sr-only"><?php echo Yii::t("main", 'Close'); ?></span>
                </button>
                <div class="modal_title"><?php echo Yii::t("main", 'Реєстрація'); ?></div>
            </div>
<?php /* * / ?>
            <FORM METHOD="POST" ACTION="/">
                <div class="modal-body" id="registrationBody">
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?php echo Yii::t("main", 'Логін'); ?> *
                        </div>
                        <div class="right_reg_label item_param item_show">
                            <input name="RegisterForm[login]" id="login" type="text" class="autocomplete" value="<?php echo $registerForm->login;?>">
                        </div>
                    </div>
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?php echo Yii::t("main", 'Email'); ?> *
                        </div>
                        <div class="right_reg_label item_param item_show email">
                            <input name="RegisterForm[email]" id="email" type="text" class="autocomplete" value="<?php echo $registerForm->email;?>">
                            <a href="javascript:void(0)" class="del_btn"></a>
                        </div>
                    </div>
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?php echo Yii::t("main", 'Пароль'); ?> *
                        </div>
                        <div class="right_reg_label item_param item_show">
                            <input name="RegisterForm[password]" type="password" class="autocomplete" value="">
                        </div>
                    </div>
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?php echo Yii::t("main", 'Повторіть пароль'); ?> *
                        </div>
                        <div class="right_reg_label item_param item_show">
                            <input name="RegisterForm[passwordRepeat]" type="password" class="autocomplete" value="">
                        </div>
                    </div>
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?php echo Yii::t("main", 'Введіть код'); ?> *
                        </div>
                        <div class="right_reg_label item_param item_show clearfix">
                            <input name="RegisterForm[verifyCode]" type="text" class="autocomplete for_captcha" value="">
                            <span class="right_captcha_b">
                                <?= \yii\captcha\Captcha::widget([
                                    'name' => 'RegisterForm[verifyCode]',
                                    'captchaAction' => '/site/captcha',
                                    'imageOptions' => [
                                        'alt' => 'Captcha',
                                        'title' => 'Натисніть для оновлення',
                                        'style' => 'cursor:pointer;',
                                        'onclick' => "this.src = this.src.split('?')[0] + '?' + Math.random();",
                                    ],
                                    'template' => '{image}',
                                ]);
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="bottom_text_reg">
                        <input name="RegisterForm[agreeTerms]" type="checkbox" id="agreeTerms">
                        <?php echo Yii::t("main", 'Погоджуюсь з'); ?> <a href="#" id="rules-block"><?php echo Yii::t("main", 'Правилами та умовами'); ?></a> <?php echo Yii::t("main", 'сайту'); ?>.
                    </div>
                    <div class="clearfix"></div>
                    <div class="rules_block">
                        <div class="top_title_rules clearfix">
                            <?php echo Yii::t("main", 'Правила та умови сайту'); ?>
                            <a href="#"><?php echo Yii::t("main", 'Сховати'); ?></a>
                        </div>
                        <?php echo Yii::t("main", 'Правил не багато, але їх необхідно дотримуватись'); ?>
                        <?php echo Yii::t("main", '<ul>
                            <li>На сайті забороняється писати повідомлення де пропагандується ворожнеча, або насильство проти окремої людини, або групи осіб за такими ознаками, як расове походження, національність, віросповідання, інвалідність, стать, вік, участь у бойових діях, сексуальна орієнтація, або гендерна самоідентифікація, також повідомлення та опитування, які містять домагання і знущання або спрямовані на заподіяння шкоди окремій людині або групі осіб. Поважайте один одного. </li>
                            <li>На сайті користувачам заборонено розміщати рекламу у вигляді опитувань, і коментарів, також заборонений спам.</li>
                        </ul>'); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="sub_title_modal"></div>
                    <div class="btn_b_modal">
                        <button type="submit" class="my_profile modal_add next_modal_btn"><?php echo Yii::t("main", 'ЗАРЕЄСТРУВАТИСЯ'); ?></button>
                    </div>
                    <a href="#" class="create_new_poll" id="registrationCancel" data-dismiss="modal"><?php echo Yii::t("main", 'Скасувати'); ?></a>
                </div>
            </FORM>
<?php /* */ ?>
        </div>
    </div>
</div>


<div class="modal new_poll" id="registration_info" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="sr-only"><?php echo Yii::t("main", 'Close'); ?></span>
                </button>
                <div class="modal_title"><?php echo Yii::t("main", 'Реєстрація'); ?>#DEV24-01</div>
            </div>
            <div class="modal-body">
                <?php echo Yii::t("poll", 'Для того щоб прийняти участь в опитуванні, або побачити результати необхідно зареєструватись, або увійти.'); ?>
<?php /* OLD.adsbygoogle :
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3234808971320300"
                     crossorigin="anonymous"></script>
<?php /* /OLD.adsbygoogle */?>
                <!-- modal -->
<?php if(YII_ENV == 'prod'): ?>
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-3234808971320300"
                     data-ad-slot="5767596412"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
<?php else: ?>
                        <div <?= (YII_ENV == 'dev') ? ' style="border:2px dotted red;"' : '' ?>>
                            <h4>adsbygoogle</h4>
                            data-ad-client="ca-pub-3234808971320300"<br>
                            data-ad-slot="5767596412"
                        </div>
<?php endif; ?>
<?php /* OLD.adsbygoogle :
                <script>
                     (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
<?php /* /OLD.adsbygoogle */?>
            </div>
<!--            <div class="modal-footer">
                <div class="sub_title_modal"></div>
                <div class="btn_b_modal">
                    <button type="submit" class="my_profile modal_add next_modal_btn"><?php /*echo Yii::t("main", 'ЗАРЕЄСТРУВАТИСЯ'); */?></button>
                </div>
                <a href="#" class="create_new_poll" id="registrationCancel" data-dismiss="modal"><?php /*echo Yii::t("main", 'Скасувати'); */?></a>
            </div-->
        </div>
</div>
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
            $('#middle_text_input_b > form > div.bottom_btn_b > button.send_btn').click(function(){
            alert('DEV.point#01');
/*
//                $('#1registration_info').modal('show'); //  DEV.R#01
/* */
                return false;
            });
            $('#middle_text_input__comment_b > form > div.bottom_btn_b > button.send_btn').click(function(){
            alert('DEV.point#2402');
/*
//                $('#1registration_info').modal('show'); //  DEV.R#02
/* */
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
            $('#middle_text_input__comment_b > form > div.bottom_btn_b > button.send_btn').click(function(){
                $('#registration_info').modal('show'); //DEV.Rx#07
                return false;
            });
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