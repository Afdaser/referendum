<?php

use common\models\Country;

?>
<div class="modal-body register">
    <div class="title_edu">
        <?= Yii::t("user", 'Середня освіта'); ?>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Країна'); ?>
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
            <?= Yii::t("user", 'Регіон'); ?>
        </div>
        <div class="right_reg_label item_param item_show sec_educ region">
            <input name="Profile[secEduc][region]" type="text" id="regionSecReg" value="<?php echo $secondaryEducation?$secondaryEducation->region_id:'';?>" style="display: none">
            <input type="text" class="autocomplete" id="regionACSecReg" value="<?php echo $secondaryEducation?$secondaryEducation->region?$secondaryEducation->region->name:'':'';?>">
            <a href="javascript:void(0)" class="del_btn"></a>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Населений пункт'); ?>
        </div>
        <div id="city_profile_b" class="right_reg_label item_param item_show sec_educ city">
            <input name="Profile[secEduc][city]" type="text" id="citySecReg" value="<?php echo $secondaryEducation?$secondaryEducation->city_id:'';?>" style="display: none">
            <input type="text" id="cityACSecReg" class="autocomplete" autocomplete="off" value="<?php echo $secondaryEducation?$secondaryEducation->city?$secondaryEducation->city->name:'':'';?>">
            <a class="del_btn" href="javascript:void(0)"></a>
            <div style="position: absolute; display: none; width: 100px; max-height: 300px; z-index: 9999;" class="autocomplete-suggestions"></div>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Школа'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[secEduc][school]" type="text" class="autocomplete" value="<?php echo $secondaryEducation?$secondaryEducation->school:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Роки навчання'); ?>
        </div>
        <div class="right_reg_label item_param item_show year_sudy">
            <select name="Profile[secEduc][yearBegin]">
                <option value="0"><?= Yii::t("user", 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($secondaryEducation){if($i == $secondaryEducation->year_begin):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select name="Profile[secEduc][yearEnd]">
                <option value="0"><?= Yii::t("user", 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($secondaryEducation){if($i == $secondaryEducation->year_end):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </div>
    </div>
    <div class="divider_my_acc"></div>
    <div class="title_edu">
        <?= Yii::t("user", 'Вища освіта'); ?>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Країна'); ?>
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
            <?= Yii::t("user", 'Регіон'); ?>
        </div>
        <div class="right_reg_label item_param item_show high_educ region">
            <input name="Profile[highEduc][region]" type="text" id="regionHighReg" value="<?php echo $highEducation?$highEducation->region_id:'';?>" style="display: none">
            <input type="text" class="autocomplete" id="regionACHighReg" value="<?php echo $highEducation?$highEducation->region?$highEducation->region->name:'':'';?>">
            <a href="javascript:void(0)" class="del_btn"></a>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Населений пункт'); ?>
        </div>
        <div id="city_profile_b" class="right_reg_label item_param item_show high_educ city">
            <input name="Profile[highEduc][city]" type="text" id="cityHighReg" value="<?php echo $highEducation?$highEducation->city_id:'';?>" style="display: none">
            <input type="text" id="cityACHighReg" class="autocomplete" autocomplete="off" value="<?php echo $highEducation?$highEducation->city?$highEducation->city->name:'':'';?>">
            <a class="del_btn" href="javascript:void(0)"></a>
            <div style="position: absolute; display: none; width: 100px; max-height: 300px; z-index: 9999;" class="autocomplete-suggestions"></div>
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'ВНЗ'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[highEduc][university]" type="text" class="autocomplete" value="<?php echo $highEducation?$highEducation->university:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Факультет'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[highEduc][faculty]" type="text" class="autocomplete" value="<?php echo $highEducation?$highEducation->faculty:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Спеціальність'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[highEduc][speciality]" type="text" class="autocomplete" value="<?php echo $highEducation?$highEducation->speciality:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Статус'); ?>
        </div>
        <div class="right_reg_label item_param item_show">
            <input name="Profile[highEduc][status]" type="text" class="autocomplete" value="<?php echo $highEducation?$highEducation->status:'';?>">
        </div>
    </div>
    <div class="item_reg clearfix">
        <div class="left_reg_label">
            <?= Yii::t("user", 'Роки навчання'); ?>
        </div>
        <div class="right_reg_label item_param item_show year_sudy">
            <select name="Profile[highEduc][yearBegin]">
                <option value="0"><?= Yii::t("user", 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($highEducation){if($i == $highEducation->year_begin):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
            <select name="Profile[highEduc][yearEnd]">
                <option value="0"><?= Yii::t("user", 'Не обрано'); ?></option>
                <?php for($i=1940;$i<=2014;$i++):?>
                    <option value="<?php echo $i;?>" <?php if($highEducation){if($i == $highEducation->year_end):?>selected<?php endif;}?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>
        </div>
    </div>
</div>

<script>
    $(function(){
        $(document).on('change','.register .sec_educ .country',function(){
            refreshRegions($('this').val(),$('.register div.sec_educ.region'),'regionACSecReg','regionSecReg','cityACSecReg',$('.register .sec_educ.city'),'citySecReg');
            $('#regionSecReg').val(0);
            $('#regionACSecReg').val('');
            $('#citySecReg').val(0);
            $('#cityACSecReg').val('');
        });

        $(document).on('change','.register .high_educ .country',function(){
            refreshRegions($('this').val(),$('.register div.high_educ.region'),'regionACHighReg','regionHighReg','cityACHighReg',$('.register .high_educ.city'),'cityHighReg');
            $('#regionHighReg').val(0);
            $('#regionACHighReg').val('');
            $('#cityHighReg').val(0);
            $('#cityACHighReg').val('');
        });

        $(document).on('change','#regionACSecReg',function(){
            $('#citySecReg').val(0);
            $('#cityACSecReg').val('');
        });

        $(document).on('change','#regionACHighReg',function(){
            $('#cityHighReg').val(0);
            $('#cityACHighReg').val('');
        });

        refreshRegions($('.register .sec_educ .country').val(),$('.register div.sec_educ.region'),'regionACSecReg','regionSecReg','cityACSecReg',$('.register .sec_educ.city'),'citySecReg');
        refreshRegions($('.register .high_educ .country').val(),$('.register div.high_educ.region'),'regionACHighReg','regionHighReg','cityACHighReg',$('.register .high_educ.city'),'cityHighReg');

        $(document).on('click','.register .sec_educ.region .del_btn',function(){
            $('#regionSecReg').val(0);
            $('#regionACSecReg').val('');
            $('#citySecReg').val(0);
            $('#cityACSecReg').val('');
        });

        $(document).on('click','.register .high_educ.region .del_btn',function(){
            $('#regionHighReg').val(0);
            $('#regionACHighReg').val('');
            $('#cityHighReg').val(0);
            $('#cityACHighReg').val('');
        });

        $(document).on('click','.register .sec_educ.city .del_btn',function(){
            $('#citySecReg').val(0);
            $('#cityACSecReg').val('');
        });

        $(document).on('click','.register .high_educ.city .del_btn',function(){
            $('#cityHighReg').val(0);
            $('#cityACHighReg').val('');
        });
    });
</script>