<?php

use frontend\helpers\Url;
//use common\helpers\StringHelper;
//use common\models\User;
use common\models\Country;
//use common\models\Language;
use \yii\web\View;

?>
<form method="post" action="<?= Url::toRoute('/user/profile', ['category' => 'education'])?>">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>">
    <input type="hidden" name="category" value="education">


    <div class="title_edu">
        <?= Yii::t('user', 'Середня освіта'); ?>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Країна'); ?>
        </div>
        <div class="right_reg_label item_param item_show sec_educ">
            <select name="Profile[secEduc][country]" class="country">
                <?php $countries = Country::getCountriesList()?>
                <?php foreach($countries as $country): ?>
                    <option value="<?php echo $country->id; ?>" <?php if($secondaryEducation){if($country->id == $secondaryEducation->country_id):?>selected<?php endif;}?>><?php echo $country->name; ?></option>
                <?php endforeach;?>>
            </select>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Регіон'); ?>
        </div>
        <div class="right_reg_label item_param item_show sec_educ region">
            <input name="Profile[secEduc][region]" type="text" id="regionSec" value="<?php echo $secondaryEducation?$secondaryEducation->region_id:'';?>" style="display: none">
            <input type="text" class="autocomplete" id="regionACSec" value="<?php echo $secondaryEducation?$secondaryEducation->region?$secondaryEducation->region->name:'':'';?>">
            <a href="javascript:void(0)" class="del_btn"></a>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Населений пункт'); ?>
        </div>
        <div id="city_profile_b" class="right_reg_label item_param item_show sec_educ city">
            <input name="Profile[secEduc][city]" type="text" id="citySec" value="<?php echo $secondaryEducation?$secondaryEducation->city_id:'';?>" style="display: none">
            <input type="text" id="cityACSec" class="autocomplete" autocomplete="off" value="<?php echo $secondaryEducation?$secondaryEducation->city?$secondaryEducation->city->name:'':'';?>">
            <a class="del_btn" href="javascript:void(0)"></a>
            <div style="position: absolute; display: none; width: 100px; max-height: 300px; z-index: 9999;" class="autocomplete-suggestions"></div>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Школа'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[secEduc][school]" type="text" class="autocomplete" value="<?php echo $secondaryEducation?$secondaryEducation->school:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Роки навчання'); ?>
        </div>
        <div class="right_reg_label item_param item_show year_sudy">
            <select name="Profile[secEduc][yearBegin]">
                <option value="0"><?= Yii::t('user', 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($secondaryEducation){if($i == $secondaryEducation->year_begin):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select name="Profile[secEduc][yearEnd]">
                <option value="0"><?= Yii::t('user', 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($secondaryEducation){if($i == $secondaryEducation->year_end):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </div>
    </div>

    <div class="divider_my_acc"></div>

    <div class="title_edu">
        <?= Yii::t('user', 'Вища освіта'); ?>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Країна'); ?>
        </div>
        <div class="right_reg_label item_param item_show high_educ">
            <select name="Profile[highEduc][country]" class="country">
                <?php $countries = Country::getCountriesList()?>
                <?php foreach($countries as $country): ?>
                    <option value="<?php echo $country->id; ?>" <?php if($highEducation){if($country->id == $highEducation->country_id):?>selected<?php endif;}?>><?php echo $country->name; ?></option>
                <?php endforeach;?>>
            </select>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Регіон'); ?>
        </div>
        <div class="right_reg_label item_param item_show high_educ region">
            <input name="Profile[highEduc][region]" type="text" id="regionHigh" value="<?php echo $highEducation?$highEducation->region_id:'';?>" style="display: none">
            <input type="text" class="autocomplete" id="regionACHigh" value="<?php echo $highEducation?$highEducation->region?$highEducation->region->name:'':'';?>">
            <a href="javascript:void(0)" class="del_btn"></a>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Населений пункт'); ?>
        </div>
        <div id="city_profile_b" class="right_reg_label item_param item_show high_educ city">
            <input name="Profile[highEduc][city]" type="text" id="cityHigh" value="<?php echo $highEducation?$highEducation->city_id:'';?>" style="display: none">
            <input type="text" id="cityACHigh" class="autocomplete" autocomplete="off" value="<?php echo $highEducation?$highEducation->city?$highEducation->city->name:'':'';?>">
            <a class="del_btn" href="javascript:void(0)"></a>
            <div style="position: absolute; display: none; width: 100px; max-height: 300px; z-index: 9999;" class="autocomplete-suggestions"></div>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'ВНЗ'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[highEduc][university]" type="text" class="autocomplete" value="<?php echo $highEducation?$highEducation->university:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Факультет'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[highEduc][faculty]" type="text" class="autocomplete" value="<?php echo $highEducation?$highEducation->faculty:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Спеціальність'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[highEduc][speciality]" type="text" class="autocomplete" value="<?php echo $highEducation?$highEducation->speciality:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Статус'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[highEduc][status]" type="text" class="autocomplete" value="<?php echo $highEducation?$highEducation->status:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Роки навчання'); ?>
        </div>
        <div class="right_reg_label item_param item_show year_sudy">
            <select name="Profile[highEduc][yearBegin]">
                <option value="0"><?= Yii::t('user', 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($highEducation){if($i == $highEducation->year_begin):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select name="Profile[highEduc][yearEnd]">
                <option value="0"><?= Yii::t('user', 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($highEducation){if($i == $highEducation->year_end):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </div>
    </div>
    <div class="btn_save_b">
        <button type="submit" class="my_profile modal_add next_modal_btn"><?= Yii::t('user', 'ЗБЕРЕГТИ'); ?></button>
    </div>
</form>
<?php /* * / 
<script>
    <?php if($error && $category == 'education'): ?>
        alert(<?php echo strip_tags($error); ?>);
    <?php endif;?>

    $(function(){
        $(document).on('change','.sec_educ .country',function(){
            refreshRegions($(this).val(),$('div.sec_educ.region'),'regionACSec','regionSec','cityACSec',$('.sec_educ.city'),'citySec');
            $('#regionSec').val(0);
            $('#regionACSec').val('');
            $('#citySec').val(0);
            $('#cityACSec').val('');
        });

        $(document).on('change','.high_educ .country',function(){
            refreshRegions($(this).val(),$('div.high_educ.region'),'regionACHigh','regionHigh','cityACHigh',$('.high_educ.city'),'cityHigh');
            $('#regionHigh').val(0);
            $('#regionACHigh').val('');
            $('#cityHigh').val(0);
            $('#cityACHigh').val('');
        });

        $(document).on('change','#regionACSec',function(){
            $('#citySec').val(0);
            $('#cityACSec').val('');
        });

        $(document).on('change','#regionACHigh',function(){
            $('#cityHigh').val(0);
            $('#cityACHigh').val('');
        });

        refreshRegions($('.sec_educ .country').val(),$('div.sec_educ.region'),'regionACSec','regionSec','cityACSec',$('.sec_educ.city'),'citySec');
        refreshRegions($('.high_educ .country').val(),$('div.high_educ.region'),'regionACHigh','regionHigh','cityACHigh',$('.high_educ.city'),'cityHigh');

        $(document).on('click','.sec_educ.region .del_btn',function(){
            $('#regionSec').val(0);
            $('#regionACSec').val('');
            $('#citySec').val(0);
            $('#cityACSec').val('');
        });

        $(document).on('click','.high_educ.region .del_btn',function(){
            $('#regionHigh').val(0);
            $('#regionACHigh').val('');
            $('#cityHigh').val(0);
            $('#cityACHigh').val('');
        });

        $(document).on('click','.sec_educ.city .del_btn',function(){
            $('#citySec').val(0);
            $('#cityACSec').val('');
        });

        $(document).on('click','.high_educ.city .del_btn',function(){
            $('#cityHigh').val(0);
            $('#cityACHigh').val('');
        });
    });
</script>
<?php /* */ 

/* */
if($error && $category == 'education') {
    $script = " alert('". strip_tags($error)."');";
} else {
    $script = '';
}
// InnerCode:
$scriptJS = <<<JS_CODE
        $(document).on('change','.sec_educ .country',function(){
            refreshRegions($(this).val(),$('div.sec_educ.region'),'regionACSec','regionSec','cityACSec',$('.sec_educ.city'),'citySec');
            $('#regionSec').val(0);
            $('#regionACSec').val('');
            $('#citySec').val(0);
            $('#cityACSec').val('');
        });

        $(document).on('change','.high_educ .country',function(){
            refreshRegions($(this).val(),$('div.high_educ.region'),'regionACHigh','regionHigh','cityACHigh',$('.high_educ.city'),'cityHigh');
            $('#regionHigh').val(0);
            $('#regionACHigh').val('');
            $('#cityHigh').val(0);
            $('#cityACHigh').val('');
        });

        $(document).on('change','#regionACSec',function(){
            $('#citySec').val(0);
            $('#cityACSec').val('');
        });

        $(document).on('change','#regionACHigh',function(){
            $('#cityHigh').val(0);
            $('#cityACHigh').val('');
        });

        refreshRegions($('.sec_educ .country').val(),$('div.sec_educ.region'),'regionACSec','regionSec','cityACSec',$('.sec_educ.city'),'citySec');
        refreshRegions($('.high_educ .country').val(),$('div.high_educ.region'),'regionACHigh','regionHigh','cityACHigh',$('.high_educ.city'),'cityHigh');

        $(document).on('click','.sec_educ.region .del_btn',function(){
            $('#regionSec').val(0);
            $('#regionACSec').val('');
            $('#citySec').val(0);
            $('#cityACSec').val('');
        });

        $(document).on('click','.high_educ.region .del_btn',function(){
            $('#regionHigh').val(0);
            $('#regionACHigh').val('');
            $('#cityHigh').val(0);
            $('#cityACHigh').val('');
        });

        $(document).on('click','.sec_educ.city .del_btn',function(){
            $('#citySec').val(0);
            $('#cityACSec').val('');
        });

        $(document).on('click','.high_educ.city .del_btn',function(){
            $('#cityHigh').val(0);
            $('#cityACHigh').val('');
        });

JS_CODE;

$script .= <<<JS_FINAL
jQuery(document).ready(function() {
{$scriptJS}

});
JS_FINAL;

$this->registerJs($script, View::POS_END);
/* */
