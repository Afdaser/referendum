<?php

use frontend\helpers\Url;
use common\helpers\StringHelper;
use common\models\User;
use common\models\Country;
use common\models\Language;


?>
<div style="border: 3px dotted red;"><?= __FILE__; ?> </div>
<FORM METHOD="POST" ACTION="<?= Url::toRoute('/user/registration', ['category' => 'main'])?>">
    <div class="modal new_poll main" id="my_profile_main" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span class="sr-only"><?= Yii::t("main", 'Close'); ?></span>
                    </button>
                    <div class="modal_title"><?= Yii::t("main", 'Мій профіль'); ?></div>
                </div>
                <div class="welcome_b">
                    <div class="large_text_welcome"><?= Yii::t("main", 'Вітаємо'); ?>!</div>
                    <div class="small_text_welcome">
                        <?= Yii::t("main", 'Розкажіть про себе детальніше, щоб для вас відображались'); ?><br>
                        <?= Yii::t("main", 'актуальні та цікаві теми опитувань'); ?>.
                    </div>
                </div>
                <div class="tabs_popup_b">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active"><a href="#"><?= Yii::t("main", 'Основне'); ?></a></li>
                        <li><a href="#"><?= Yii::t("main", 'Інтереси'); ?></a></li>
                        <li><a href="#"><?= Yii::t("main", 'Освіта'); ?></a></li>
                        <li><a href="#"><?= Yii::t("main", 'Кар’єра'); ?></a></li>
                    </ul>
                </div>
                <?php if($error && !Yii::app()->user->getUser()->is_active):?>
                    <div class="error_b">
                        <?php echo json_decode($error); ?>
                        <a href="#" class="close_error_window">
                        </a>
                    </div>
                <?php endif;?>
                <div class="modal-body">
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?= Yii::t("user", 'Стать'); ?> *
                        </div>
                        <div class="right_reg_label item_param item_show">
                            <select name="Profile[sex]">
                                <?php $sexes = User::getUserSexList();?>
                                <?php foreach($sexes as $index=>$sex): ?>
                                    <option value="<?php echo $index; ?>" <?php if($index == $user->sex):?>selected<?php endif;?>><?php echo $sex; ?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?= Yii::t("user", 'Дата народження'); ?> *
                            <?php $date_birthday = date_parse($user->date_birthday);?>
                            <?php if(!$user->date_birthday) $date_birthday['year'] = 2000;?>
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
                            <?= Yii::t("user", 'Країна'); ?> *
                        </div>
                        <div class="right_reg_label item_param item_show main">
                            <select name="Profile[country]" class="country">
                                <?php $countries = Country::getCountriesList()?>
                                <?php foreach($countries as $country): ?>
                                    <option value="<?php echo $country->id; ?>" <?php if($country->id == $user->country_id):?>selected<?php endif;?>><?= Yii::t('countries', $country->name); ?></option>
                                <?php endforeach;?>>
                            </select>
                        </div>
                    </div>
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?= Yii::t("user", 'Регіон'); ?> *
                        </div>
                        <div class="right_reg_label item_param item_show main region" id="region_profile_b">
                            <input name="Profile[region]" type="text" id="regionMainReg" value="<?= $user->region_id;?>" style="display: none">
                            <input type="text" class="autocomplete" id="regionACMainReg" value="<?= $user->regionName; ?>">
                            <a href="javascript:void(0)" class="del_btn"></a>
                        </div>
                    </div>
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?= Yii::t("user", 'Населений пункт'); ?> *
                        </div>
                        <div id="city_profile_b" class="right_reg_label item_param item_show main city">
                            <input name="Profile[city]" type="text" id="cityMainReg" value="<?= $user->city_id; ?>" style="display: none">
                            <input type="text" id="cityACMainReg" class="autocomplete" autocomplete="off" value="<?php echo isset($user->city)?$user->city->name:'';?>">
                            <a class="del_btn" href="javascript:void(0)"></a>
                            <div style="position: absolute; display: none; width: 100px; max-height: 300px; z-index: 9999;" class="autocomplete-suggestions"></div></div>
                    </div>
                    <div class="item_reg clearfix">
                        <div class="left_reg_label with_sub_text">
                            <?= Yii::t("user", 'Мови'); ?> *<br>
            <span class="sub_text">
                <?= Yii::t("user", 'Вкажіть допустимі мови'); ?><br> <?= Yii::t("user", 'опитування'); ?>.
            </span>
                        </div>
                        <div class="right_reg_label item_param item_show for_check">
                            <?php $languages = Language::getLanguagesList();?>
                            <?php foreach($languages as $i=>$language):?>
                                <label><input type="checkbox" name="Profile[languages][]" value="<?= $i;?>" <?php if($user->useLanguage($i)):?>checked<?php endif;?>><?php echo $language;?></label>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?= Yii::t("user", 'Сімейний стан'); ?> *
                        </div>
                        <div class="right_reg_label item_param item_show">
                            <select name="Profile[marital]">
                                <option value="<?= User::MARRIED;?>" <?php if($user->marital == User::MARRIED):?>selected<?php endif;?>><?= Yii::t("user", 'Одружений(a)'); ?></option>
                                <option value="<?= User::SINGLE;?>" <?php if($user->marital == User::SINGLE):?>selected<?php endif;?>><?= Yii::t("user", 'Неодружений(a)'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="item_reg clearfix">
                        <div class="left_reg_label">
                            <?= Yii::t("user", 'Вподобання'); ?> *<br>
            <span class="sub_text">
                <?= Yii::t("user", 'Перелічіть декілька цікавих'); ?><br>  <?= Yii::t("user", 'для вас тем, наприклад'); ?>:<br> <?= Yii::t("user", 'технології, музика, фото'); ?>.
            </span>
                        </div>
                        <div class="right_reg_label item_param item_show">
                            <textarea name="Profile[preferences]"><?= $user->preferences;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="sub_title_modal"></div>
                    <div class="btn_b_modal">
                        <button type="submit" class="my_profile modal_add next_modal_btn"><?= Yii::t("user", 'ЗБЕРЕГТИ'); ?></button>
                    </div>
                    <a href="#" class="create_new_poll" data-dismiss="modal"><?= Yii::t("user", 'Скасувати'); ?></a>
                </div>
            </div>
        </div>
    </div>
</FORM>

<script>
    $(function(){
        $('.close_error_window').on('click',function(){
           $('.error_b').remove();
        });

        $(document).on('change','.modal.main .main .country',function(){
            refreshRegions($('.modal.main .main .country').val(),$('.modal.main div.main.region'),'regionACMainReg','regionMainReg','cityACMainReg',$('.modal.main .main.city'),'cityMainReg');
            $('#regionMainReg').val(0);
            $('#regionACMainReg').val('');
            $('#cityMainReg').val(0);
            $('#cityACMainReg').val('');
        });

        $(document).on('change','#regionACMainReg',function(){
            $('#cityMainReg').val(0);
            $('#cityACMainReg').val('');
        });

        refreshRegions($('.modal.main .main .country').val(),$('.modal.main div.main.region'),'regionACMainReg','regionMainReg','cityACMainReg',$('.modal.main .main.city'),'cityMainReg');

        $(document).on('click','.modal.main .region .del_btn',function(){
            $('#regionMainReg').val('');
            $('#regionACMainReg').val('');
            $('#cityMainReg').val('');
            $('#cityACMainReg').val('');
        });

        $(document).on('click','.modal.main .city .del_btn',function(){
            $('#cityMainReg').val('');
            $('#cityACMainReg').val('');
        });

        days = getDaysCount($('#my_profile_main .year_birth').val(),$('#my_profile_main .month_birth').val());
        options = $('#my_profile_main .day_birth option');
        for(var i = 0; i < options.length; i++){
            if($(options[i]).val() > days)
                $(options[i]).remove();
        }
    });
</script>