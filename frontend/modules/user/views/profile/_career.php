<?php


use frontend\helpers\Url;
//use common\helpers\StringHelper;
//use common\models\User;
use common\models\Country;
//use common\models\Language;

// use yii\helpers\Html;
// use yii\widgets\DetailView;
// use app\components\ActiveForm;
use \yii\web\View;


?>

<form method="post" action="<?= Url::toRoute('/user/profile', ['category' => 'career'])?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>">
    <input type="hidden" name="category" value="career">

    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Країна'); ?>
        </div>
        <div class="right_reg_label item_param item_show career">
            <select name="Profile[country]" class="country">
                <?php $countries = Country::getCountriesList()?>
                <?php foreach($countries as $country): ?>
                    <option value="<?php echo $country->id; ?>" <?php if($career){if($country->id == $career->country_id):?>selected<?php endif;}?>><?php echo $country->name; ?></option>
                <?php endforeach;?>>
            </select>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Регіон'); ?>
        </div>
        <div class="right_reg_label item_param item_show career region">
            <input name="Profile[region]" type="text" id="regionCareer" value="<?php echo $career?$career->region_id:'';?>" style="display: none">
            <input type="text" class="autocomplete" id="regionACCareer" value="<?php echo $career?$career->region?$career->region->name:'':'';?>">
            <a href="javascript:void(0)" class="del_btn"></a>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Населений пункт'); ?>
        </div>
        <div id="city_profile_b" class="right_reg_label item_param item_show career city">
            <input name="Profile[city]" type="text" id="cityCareer" value="<?php echo $career?$career->city_id:'';?>" style="display: none">
            <input type="text" id="cityACCareer" class="autocomplete" autocomplete="off" value="<?php echo $career?$career->city?$career->city->name:'':'';?>">
            <a class="del_btn" href="javascript:void(0)"></a>
            <div style="position: absolute; display: none; width: 100px; max-height: 300px; z-index: 9999;" class="autocomplete-suggestions"></div>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Компанія'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[company]" type="text" class="autocomplete" value="<?php echo $career?$career->company:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Посада'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[office]" type="text" class="autocomplete" value="<?php echo $career?$career->office:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Період роботи'); ?>
        </div>
        <div class="right_reg_label item_param item_show year_sudy">
            <select name="Profile[yearBegin]">
                <option value="0"><?= Yii::t('user', 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($career){if($i == $career->year_begin):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select name="Profile[yearEnd]">
                <option value="0"><?= Yii::t('user', 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($career){if($i == $career->year_end):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </div>
    </div>
    <div class="btn_save_b">
        <button type="submit" class="my_profile modal_add next_modal_btn"><?= Yii::t('user', 'ЗБЕРЕГТИ'); ?></button>
    </div>
</FORM>
<?php /*
<script>
    <?php if($error && $category == 'career'): ?>
        alert(<?php echo strip_tags($error); ?>);
    <?php endif;?>

    $(function(){
        $(document).on('change','.career .country',function(){
            refreshRegions($(this).val(),$('div.career.region'),'regionACCareer','regionCareer','cityACCareer',$('.career.city'),'cityCareer');
            $('#regionCareer').val(0);
            $('#regionACCareer').val('');
            $('#cityCareer').val(0);
            $('#cityACCareer').val('');
        });

        $(document).on('change','#regionACCareer',function(){
            $('#cityCareer').val(0);
            $('#cityACCareer').val('');
        });

        refreshRegions($('.career .country').val(),$('div.career.region'),'regionACCareer','regionCareer','cityACCareer',$('.career.city'),'cityCareer');

        $(document).on('click','.career.region .del_btn',function(){
            $('#regionCareer').val(0);
            $('#regionACCareer').val('');
            $('#cityCareer').val(0);
            $('#cityACCareer').val('');
        });

        $(document).on('click','.career.city .del_btn',function(){
            $('#cityCareer').val(0);
            $('#cityACCareer').val('');
        });
    });

</script>

<?php /* */ 

/* */
if($error && $category == 'career') {
    $script = " alert('". strip_tags($error)."');";
} else {
    $script = '';
}
// InnerCode:
$scriptJS = <<<JS_CODE
        $(document).on('change','.career .country',function(){
            refreshRegions($(this).val(),$('div.career.region'),'regionACCareer','regionCareer','cityACCareer',$('.career.city'),'cityCareer');
            $('#regionCareer').val(0);
            $('#regionACCareer').val('');
            $('#cityCareer').val(0);
            $('#cityACCareer').val('');
        });

        $(document).on('change','#regionACCareer',function(){
            $('#cityCareer').val(0);
            $('#cityACCareer').val('');
        });

        refreshRegions($('.career .country').val(),$('div.career.region'),'regionACCareer','regionCareer','cityACCareer',$('.career.city'),'cityCareer');

        $(document).on('click','.career.region .del_btn',function(){
            $('#regionCareer').val(0);
            $('#regionACCareer').val('');
            $('#cityCareer').val(0);
            $('#cityACCareer').val('');
        });

        $(document).on('click','.career.city .del_btn',function(){
            $('#cityCareer').val(0);
            $('#cityACCareer').val('');
        });
JS_CODE;

$script .= <<<JS_FINAL
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
/* */
$this->registerJs($script, View::POS_END);
/* */
