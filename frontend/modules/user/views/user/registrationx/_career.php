<?php

use common\models\Country;

?>
<div class="modal-body career">
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Країна'); ?>
        </div>
        <div class="right_reg_label item_param item_show career">
            <select name="Profile[career][country]" class="country">
                <?php $countries = Country::getCountriesList()?>
                <?php foreach($countries as $country): ?>
                    <option value="<?php echo $country->id; ?>" <?php if($career){if($country->id == $career->country_id):?>selected<?php endif;}?>><?php echo $country->name; ?></option>
                <?php endforeach;?>>
            </select>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Регіон'); ?>
        </div>
        <div class="right_reg_label item_param item_show career region">
            <input name="Profile[career][region]" type="text" id="regionCareerReg" value="<?php echo $career?$career->region_id:'';?>" style="display: none">
            <input type="text" class="autocomplete" id="regionACCareerReg" value="<?php echo $career?$career->region?$career->region->name:'':'';?>">
            <a href="javascript:void(0)" class="del_btn"></a>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Населений пункт'); ?>
        </div>
        <div id="city_profile_b" class="right_reg_label item_param item_show career city">
            <input name="Profile[career][city]" type="text" id="cityCareerReg" value="<?php echo $career?$career->city_id:'';?>" style="display: none">
            <input type="text" id="cityACCareerReg" class="autocomplete" autocomplete="off" value="<?php echo $career?$career->city?$career->city->name:'':'';?>">
            <a class="del_btn" href="javascript:void(0)"></a>
            <div style="position: absolute; display: none; width: 100px; max-height: 300px; z-index: 9999;" class="autocomplete-suggestions"></div>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Компанія'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[career][company]" type="text" class="autocomplete" value="<?php echo $career?$career->company:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Посада'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[career][office]" type="text" class="autocomplete" value="<?php echo $career?$career->office:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Період роботи'); ?>
        </div>
        <div class="right_reg_label item_param item_show year_sudy">
            <select name="Profile[career][yearBegin]">
                <option value="0"><?= Yii::t("user", 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($career){if($i == $career->year_begin):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select name="Profile[career][yearEnd]">
                <option value="0"><?= Yii::t("user", 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($career){if($i == $career->year_end):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </div>
    </div>
</div>

<script>
    $(function(){
        $(document).on('change','.modal-body.career .career .country',function(){
            refreshRegions($('this').val(),$('.modal-body.career div.career.region'),'regionACCareerReg','regionCareerReg','cityACCareerReg',$('.modal-body.career .career.city'),'cityCareerReg');
            $('#regionCareerReg').val(0);
            $('#regionACCareerReg').val('');
            $('#cityCareerReg').val(0);
            $('#cityACCareerReg').val('');
        });

        $(document).on('change','#regionACCareerReg',function(){
            $('#cityCareerReg').val(0);
            $('#cityACCareerReg').val('');
        });

        refreshRegions($('.modal-body.career .career .country').val(),$('.modal-body.career div.career.region'),'regionACCareerReg','regionCareerReg','cityACCareerReg',$('.modal-body.career .career.city'),'cityCareerReg');

        $(document).on('click','.modal-body.career .career.region .del_btn',function(){
            $('#regionCareerReg').val(0);
            $('#regionACCareerReg').val('');
            $('#cityCareerReg').val(0);
            $('#cityACCareerReg').val('');
        });

        $(document).on('click','.modal-body.career .career.city .del_btn',function(){
            $('#cityCareerReg').val(0);
            $('#cityACCareerReg').val('');
        });
    });
</script>