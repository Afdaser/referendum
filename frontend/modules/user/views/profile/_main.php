<?php

use frontend\helpers\Url;
use common\helpers\StringHelper;
use common\models\User;
use common\models\Country;
use common\models\Language;

use \yii\web\View;

if (empty($profile)){
    $profile = $user->newProfile();
}
?>
<?php if(YII_ENV == 'dev') :?>
<div style="border: 3px dotted red;"><?= __FILE__; ?>
    <h2>User: [<?= $user->id; ?>]</h2>
    <pre>
    <?php
//    var_dump($user->interest);
    ?>
    </pre>
    <hr>
    <ul>
        <?php $languages = Language::getLanguagesList(); ?>
        <?php foreach ($languages as $i => $language): ?>
            <li> value= <?php echo $i; ?> <?php if ($user->useLanguage($i)): ?>checked<?php endif; ?>><?php echo $language; ?></li>
        <?php endforeach; ?>
    </ul>

<?php
//  var_dump()  
?>
</div>
<?php endif; ?>
<form method="post" action="<?= Url::toRoute('/user/profile', ['category' => 'main'])?>" id="profile_main">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>">
    <input type="hidden" name="category" value="main">

    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Стать'); ?> *
        </div>
        <div class="right_reg_label item_param item_show">
            <select name="Profile[sex]">
                <?php $sexes = User::getUserSexList();?>
                <?php foreach($sexes as $index=>$sex): ?>
                    <option value="<?php echo $index; ?>" <?php if($index == $profile->gender):?>selected<?php endif;?>><?php echo $sex; ?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Дата народження'); ?> *
            <?php $date_birthday = date_parse($profile->date_birthday);?>
        </div>
        <div class="right_reg_label item_param item_show date_birth">
            <select class="day_birth" name="Profile[birthday][day]">
                <?php for($i=1;$i<=31;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($i == $date_birthday['day']):?>selected<?php endif;?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select class="month_birth" name="Profile[birthday][month]">
                <?php $months = StringHelper::getMonthList();?>
                <?php foreach($months as $i=>$month):?>
                    <option value="<?php echo $i;?>" <?php if($i == $date_birthday['month']):?>selected<?php endif;?>><?php echo $month;?></option>
                <?php endforeach;?>
            </select>
            <select class="year_birth" name="Profile[birthday][year]">
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($i == $date_birthday['year']):?>selected<?php endif;?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Країна'); ?> *
        </div>
        <div class="right_reg_label item_param item_show mainProfile">
            <select name="Profile[country]" class="country">
                <?php $countries = Country::getCountriesList()?>
                <?php foreach($countries as $country): ?>
                    <option value="<?php echo $country->id; ?>" <?php if($country->id == $profile->country_id):?>selected<?php endif;?>><?php echo $country->name; ?></option>
                <?php endforeach;?>>
            </select>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Регіон'); ?> *
        </div>
        <div class="right_reg_label item_param item_show main regionProfile" id="region_profile_b">
            <input name="Profile[region]" type="text" id="regionMain" value="<?php echo $profile->region_id;?>" style="display: none">
            <input type="text" class="autocomplete" id="regionACMain" value="<?php echo isset($profile->region)?$profile->region->name:'';?>">
            <a href="javascript:void(0)" class="del_btn"></a>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Населений пункт'); ?> *
        </div>
        <div id="city_profile_b" class="right_reg_label item_param item_show main cityProfile">
            <input name="Profile[city]" type="text" id="cityMain" value="<?php echo $profile->city_id;?>" style="display: none">
            <input type="text" id="cityACMain" class="autocomplete" autocomplete="off" value="<?php echo isset($profile->city)?$profile->city->name:'';?>">
            <a class="del_btn" href="javascript:void(0)"></a>
            <div style="position: absolute; display: none; width: 100px; max-height: 300px; z-index: 9999;" class="autocomplete-suggestions"></div></div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label with_sub_text">
            <?= Yii::t('user', 'Мови'); ?> *<br>
            <span class="sub_text">
                <?= Yii::t('user', 'Вкажіть допустимі мови'); ?><br> <?= Yii::t('user', 'опитування'); ?>.
            </span>
        </div>
        <div class="right_reg_label item_param item_show for_check">
            <?php $languages = Language::getLanguagesList();?>
            <?php foreach($languages as $i=>$language):?>
                <label><input type="checkbox" name="Profile[languages][]" value="<?php echo $i;?>" <?php if($user->useLanguage($i)):?>checked<?php endif;?>><?php echo $language;?></label>
            <?php endforeach;?>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Сімейний стан'); ?> *
        </div>
        <div class="right_reg_label item_param item_show">
            <select name="Profile[marital]">
                <option value="<?php echo User::MARRIED;?>" <?php if($profile->marital == User::MARRIED):?>selected<?php endif;?>><?= Yii::t('user', 'Одружений(a)'); ?></option>
                <option value="<?php echo User::SINGLE;?>" <?php if($profile->marital == User::SINGLE):?>selected<?php endif;?>><?= Yii::t('user', 'Неодружений(a)'); ?></option>
            </select>
        </div>
    </div>

    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t('user', 'Вподобання'); ?> *<br>
            <span class="sub_text">
                <?= Yii::t('user', 'Перелічіть декілька цікавих'); ?><br>  <?= Yii::t('user', 'для вас тем, наприклад'); ?>:<br> <?= Yii::t('user', 'технології, музика, фото'); ?>.
            </span>
        </div>
        <div class="right_reg_label item_param item_show">
            <textarea name="Profile[preferences]"><?php echo $profile->preferences;?></textarea>
        </div>
    </div>
    <div class="btn_save_b">
        <button type="submit" class="my_profile modal_add next_modal_btn"><?= Yii::t('user', 'ЗБЕРЕГТИ'); ?></button>
    </div>
</FORM>
<?php /*
<script>
    $(function(){
        $(document).on('change','.mainProfile .country',function(){
            refreshRegions($('.mainProfile .country').val(),$('div.main.regionProfile'),'regionACMain','regionMain','cityACMain',$('.main.cityProfile'),'cityMain');
            $('#regionMain').val(0);
            $('#regionACMain').val('');
            $('#cityMain').val(0);
            $('#cityACMain').val('');
        });

        $(document).on('change','#regionACMain',function(){
            $('#cityMain').val(0);
            $('#cityACMain').val('');
        });

        refreshRegions($('.mainProfile .country').val(),$('div.main.regionProfile'),'regionACMain','regionMain','cityACMain',$('.main.cityProfile'),'cityMain');

        $(document).on('click','.regionProfile .del_btn',function(){
            $('#regionMain').val('');
            $('#regionACMain').val('');
            $('#cityMain').val('');
            $('#cityACMain').val('');
        });

        $(document).on('click','.cityProfile .del_btn',function(){
            $('#cityMain').val('');
            $('#cityACMain').val('');
        });

        days = getDaysCount($('#profile_main .year_birth').val(),$('#profile_main .month_birth').val());
        options = $('#profile_main .day_birth option');
        for(var i = 0; i < options.length; i++){
            if($(options[i]).val() > days)
                $(options[i]).remove();
        }
    });

    <?php if($error && $category == 'main' && !$other): ?>
        alert(<?php echo strip_tags($error); ?>);
    <?php endif;?>
</script>
<?php /*  */ 

if($error && $category == 'main') {
    $script = " alert('". strip_tags($error)."');";
} else {
    $script = '';
}
// InnerCode:
$scriptJS = <<<JS_CODE

        $(document).on('change','.mainProfile .country',function(){
            refreshRegions($('.mainProfile .country').val(),$('div.main.regionProfile'),'regionACMain','regionMain','cityACMain',$('.main.cityProfile'),'cityMain');
            $('#regionMain').val(0);
            $('#regionACMain').val('');
            $('#cityMain').val(0);
            $('#cityACMain').val('');
        });

        $(document).on('change','#regionACMain',function(){
            $('#cityMain').val(0);
            $('#cityACMain').val('');
        });

        refreshRegions($('.mainProfile .country').val(),$('div.main.regionProfile'),'regionACMain','regionMain','cityACMain',$('.main.cityProfile'),'cityMain');

        $(document).on('click','.regionProfile .del_btn',function(){
            $('#regionMain').val('');
            $('#regionACMain').val('');
            $('#cityMain').val('');
            $('#cityACMain').val('');
        });

        $(document).on('click','.cityProfile .del_btn',function(){
            $('#cityMain').val('');
            $('#cityACMain').val('');
        });

        days = getDaysCount($('#profile_main .year_birth').val(),$('#profile_main .month_birth').val());
        options = $('#profile_main .day_birth option');
        for(var i = 0; i < options.length; i++){
            if($(options[i]).val() > days)
                $(options[i]).remove();
        }

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

