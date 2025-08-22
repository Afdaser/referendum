<?php
//use yii\helpers\Html;
//use yii\widgets\ActiveForm;
//use yii\web\View;
//use frontend\helpers\Url;
?>
<?= (YII_ENV != 'dev') ? '' : "<!-- #DEV24-02 \n". __FILE__."\n -->"; ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span class="sr-only"><?= Yii::t('main', 'Close'); ?></span>
            </button>
            <div class="modal_title"><?= Yii::t('main', 'Реєстрація'); ?> <?= (YII_ENV != 'dev') ? '' : '#DEV24-02'; ?></div>
        </div>
<?php /*
        <FORM METHOD="POST" ACTION="/">
* */ ?>
        <form method="post" action="/">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>">
            <div class="modal-body" id="registrationBody" style="border:1px dashed red;">
                <div class="item_reg clearfix">
                    <div class="left_reg_label">
<?= Yii::t('main', 'Логін'); ?> *
                    </div>
                    <div class="right_reg_label item_param item_show">
                        <input name="RegisterForm[login]" id="login" type="text" class="autocomplete" value="<?= $registerForm->login; ?>">
                    </div>
                </div>
                <div class="item_reg clearfix">
                    <div class="left_reg_label">
<?= Yii::t('main', 'Email'); ?> *
                    </div>
                    <div class="right_reg_label item_param item_show email">
                        <input name="RegisterForm[email]" id="email" type="text" class="autocomplete" value="<?= $registerForm->email; ?>">
                        <a href="javascript:void(0)" class="del_btn"></a>
                    </div>
                </div>
                <div class="item_reg clearfix">
                    <div class="left_reg_label">
<?= Yii::t('main', 'Пароль'); ?> *
                    </div>
                    <div class="right_reg_label item_param item_show">
                        <input name="RegisterForm[password]" type="password" class="autocomplete" value="">
                    </div>
                </div>
                <div class="item_reg clearfix">
                    <div class="left_reg_label">
<?= Yii::t('main', 'Повторіть пароль'); ?> *
                    </div>
                    <div class="right_reg_label item_param item_show">
                        <input name="RegisterForm[passwordRepeat]" type="password" class="autocomplete" value="">
                    </div>
                </div>
                <div class="item_reg clearfix">
                    <div class="left_reg_label">
<?= Yii::t('main', 'Введіть код'); ?> *
                    </div>
                    <div class="right_reg_label item_param item_show clearfix">
                        <?php // Віджет капчі Yii2 для перевірки користувача ?>
                        <?= \yii\captcha\Captcha::widget([
            'name' => 'RegisterForm[verifyCode]',
            'captchaAction' => 'site/captcha',
            'options' => [
                'class' => 'autocomplete for_captcha',
            ],
            'imageOptions' => [
                'alt' => 'Captcha',
                'title' => 'Натисніть для оновлення',
                'class' => 'right_captcha_b',
            ],
            'template' => '{input}{image}',
        ]); ?>
                    </div>
                </div>
                <div class="bottom_text_reg">
                    <input name="RegisterForm[agreeTerms]" type="checkbox" id="agreeTerms">
<?= Yii::t('main', 'Погоджуюсь з'); ?> <a href="#" id="rules-block"><?= Yii::t('main', 'Правилами та умовами'); ?></a> <?= Yii::t('main', 'сайту'); ?>.
                </div>
                <div class="clearfix"></div>
                <div class="rules_block">
                    <div class="top_title_rules clearfix">
<?= Yii::t('main', 'Правила та умови сайту'); ?>
                        <a href="#"><?= Yii::t('main', 'Сховати'); ?></a>
                    </div>
<?= Yii::t('main', 'Правил не багато, але їх необхідно дотримуватись'); ?>
                    <?= Yii::t('main', '<ul>
                            <li>На сайті забороняється писати повідомлення де пропагандується ворожнеча, або насильство проти окремої людини, або групи осіб за такими ознаками, як расове походження, національність, віросповідання, інвалідність, стать, вік, участь у бойових діях, сексуальна орієнтація, або гендерна самоідентифікація, також повідомлення та опитування, які містять домагання і знущання або спрямовані на заподіяння шкоди окремій людині або групі осіб. Поважайте один одного. </li>
                            <li>На сайті користувачам заборонено розміщати рекламу у вигляді опитувань, і коментарів, також заборонений спам.</li>
                        </ul>'); ?>
                </div>
            </div>
            <div class="modal-footer">
                <div class="sub_title_modal"></div>
                <div class="btn_b_modal">
                    <button type="submit" class="my_profile modal_add next_modal_btn"><?= Yii::t('main', 'ЗАРЕЄСТРУВАТИСЯ'); ?></button>
                </div>
                <a href="#" class="create_new_poll" id="registrationCancel" data-dismiss="modal"><?= Yii::t('main', 'Скасувати'); ?></a>
            </div>
        </FORM>
    </div>
</div>

<?= (YII_ENV != 'dev') ? '' : '<!-- //#DEV24-02 -->'; ?>
