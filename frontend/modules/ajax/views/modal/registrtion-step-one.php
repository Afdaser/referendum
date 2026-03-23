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
        <form method="post" action="/site/signup">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>">
            <div class="modal-body" id="registrationBody" style="border:1px dashed red;">
                <div class="item_reg clearfix">
                    <div class="left_reg_label">
<?= Yii::t('main', 'Логін'); ?> *
                    </div>
                    <div class="right_reg_label item_param item_show">
                        <input name="SignupForm[username]" id="login" type="text" class="autocomplete" value="<?= ($registerForm->login ?? $registerForm->username ?? ''); ?>">
                    </div>
                </div>
                <div class="item_reg clearfix">
                    <div class="left_reg_label">
<?= Yii::t('main', 'Email'); ?> *
                    </div>
                    <div class="right_reg_label item_param item_show email">
                        <input name="SignupForm[email]" id="email" type="text" class="autocomplete" value="<?= ($registerForm->email ?? ''); ?>">
                        <a href="javascript:void(0)" class="del_btn"></a>
                    </div>
                </div>
                <div class="item_reg clearfix">
                    <div class="left_reg_label">
<?= Yii::t('main', 'Пароль'); ?> *
                    </div>
                    <div class="right_reg_label item_param item_show">
                        <input name="SignupForm[password]" type="password" class="autocomplete" value="">
                    </div>
                </div>
                <?php // Тимчасово вимкнули додаткові поля signup (повтор пароля, captcha, правила). ?>
            </div>
            <div class="modal-footer">
                <div class="sub_title_modal"></div>
                <div class="btn_b_modal">
                    <button type="submit" class="my_profile modal_add next_modal_btn"><?= Yii::t('main', 'ЗАРЕЄСТРУВАТИСЯ'); ?></button>
                </div>
                <a href="#" class="create_new_poll" id="registrationCancel" data-dismiss="modal"><?= Yii::t('main', 'Скасувати'); ?></a>
            </div>
        </form>
    </div>
</div>

<?= (YII_ENV != 'dev') ? '' : '<!-- //#DEV24-02 -->'; ?>
