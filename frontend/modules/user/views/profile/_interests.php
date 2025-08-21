<?php

use frontend\helpers\Url;
//use common\helpers\StringHelper;
//use common\models\User;
//use common\models\Country;
//use common\models\Language;

use \yii\web\View;

//$interests = $user->$interest

//echo '<h2>$interests:</h2>';
//var_dump($interests);
?>
<form method="post" action="<?= Url::toRoute('/user/profile', ['category' => 'interests'])?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>">
    <input type="hidden" name="category" value="interests">

    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Діяльність'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[activity]"><?= $interests ? $interests->activity : ''; ?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Інтереси'); ?><br>
            <span class="sub_text">
                <?= Yii::t('user', 'Перелічіть декілька цікавих'); ?><br> <?= Yii::t('user', 'для вас тем, наприклад'); ?>:<br> <?= Yii::t('user', 'технології, музика, фото'); ?>.
            </span>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[interests]"><?= $interests ? $interests->interests : ''; ?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Улюблена музика'); ?><br>
            <span class="sub_text">
                <?= Yii::t('user', 'Улюблені гурти та'); ?><br> <?= Yii::t('user', 'виконавці'); ?>
            </span>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[music]"><?= $interests ? $interests->music : ''; ?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Улюблені фільми'); ?><br>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[films]"><?= $interests ? $interests->films : ''; ?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Улюблені телешоу'); ?>:<br>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[shows]"><?= $interests ? $interests->shows : ''; ?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Улюблені книги'); ?><br>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[books]"><?= $interests ? $interests->books : ''; ?></textarea>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Улюблені ігри'); ?><br>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[games]"><?= $interests ? $interests->games : ''; ?></textarea>
        </div>
    </div>
    <div class="btn_save_b">
        <button type="submit" class="my_profile modal_add next_modal_btn"><?= Yii::t('user', 'ЗБЕРЕГТИ'); ?></button>
    </div>
</form>
<?php /*
<script>
    <?php if($error && $category == 'interests'): ?>
        alert(<?php echo strip_tags($error); ?>);
    <?php endif;?>
</script>
/*  */

if($error && $category == 'interests') {
    $script = " alert('". strip_tags($error)."');";
    $this->registerJs($script, View::POS_END);  
}
/*
// InnerCode:
$scriptJS = <<<JS_CODE
JS_CODE;

$script = <<<JS_FINAL
jQuery(document).ready(function() {
{$scriptJS}

});
JS_FINAL;
/*
$styleAccordion = <<<CSS_ACCORDION
.accordion-option .toggle-accordion:before {content: "{$expandAll}";}
.accordion-option .toggle-accordion.active:before {content: "{$collapseAll}";}
CSS_ACCORDION;

$this->registerCssFile('/css/accordion.css');
$this->registerCss($styleAccordion);
/* * /
$this->registerJs($script, View::POS_END);
/* */

