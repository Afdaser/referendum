<?php

use frontend\helpers\Url;
//use common\helpers\StringHelper;
//use common\models\User;
//use common\models\Country;
//use common\models\Language;

use \yii\web\View;

?>

<form method="post" action="<?= Url::toRoute('/user/profile', ['category' => 'password'])?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>">
    <input type="hidden" name="category" value="password">

    <div class="title_edu">
        <?= Yii::t('user', 'Змiнити пароль'); ?>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Поточний пароль'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[oldPassword]" type="password" class="autocomplete" value="">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Новий пароль'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[password]" type="password" class="autocomplete" value="">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Повторіть пароль'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[passwordRepeat]" type="password" class="autocomplete" value="">
        </div>
    </div>
    <div class="btn_b_set">
        <button type="submit" class="my_profile modal_add next_modal_btn _uppercase"><?= Yii::t('user', 'Змінити пароль'); ?></button>
    </div>
</form>
<div class="divider_my_acc"></div>
<form method="post" action="<?= Url::toRoute('/user/profile', ['category' => 'email'])?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>">
    <input type="hidden" name="category" value="email">
    <div class="title_edu">
        <?= Yii::t('user', 'Адреса Вашої електронної пошти'); ?>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Поточна адреса'); ?>
        </div>
        <div class="right_reg_label item_param item_show equal_height">
            <?php echo $user->oldEmail?$user->oldEmail:'-';?>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Нова адреса'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[email]" type="text" class="autocomplete" value="<?php echo $user->email ?>">
        </div>
    </div>
    <div class="btn_b_set">
        <button type="submit" class="my_profile modal_add next_modal_btn _uppercase"><?= Yii::t('user', 'Змінити адресу'); ?></button>
    </div>
    <div class="divider_my_acc"></div>
    <div class="sub_title_modal"></div>
</form>
<?php /* 
<script>
    <?php if($error && $category == 'settings'): ?>
        alert(<?php echo strip_tags($error); ?>);
    <?php endif;?>
</script>
<?php /* */ 

if($error && $category == 'settings') {
    $script = " alert('". strip_tags($error)."');";
    $this->registerJs($script, View::POS_END);  
}