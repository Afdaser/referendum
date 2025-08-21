<?php

use yii\web\View;

use common\models\Language;
use common\models\User;
use common\models\Country;
?>
<?= (YII_ENV != 'dev') ? '' : "<!-- #DEV24-05 \n". __FILE__."\n -->"; ?>
<?= '<!-- Modal class="modal new_poll" id="new_poll0" tabindex="-1" role="dialog" aria-hidden="true" -->'; ?>
<?php /*
<!-- Modal -->
<div class="modal new_poll" id="new_poll<?php echo $model->id?$model->id:0?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="sr-only"><?php echo Yii::t("poll", 'Close'); ?></span>
                </button>
                <h4 class="modal-title"><?php echo Yii::t("poll", 'Нове Опитування'); ?></h4>
            </div>
            <div class="modal-body">
                <FORM METHOD="POST" ACTION="/poll/<?php if(!$model->id):?>createPoll<?php else:?>editPoll<?php endif;?>">
                    <input name="Poll[id]" type="hidden" value="<?php echo $model->id?$model->id:''?>">

                    <div class="title_modal"><?php echo Yii::t("poll", 'Питання'); ?></div>
                    <div class="input_text_modal_b middle_text_input_b">
                        <textarea name="Poll[title]" id="title"><?php echo $model->title;?></textarea>
                        <div class="count_symbols">
                            <?php echo Yii::t("poll", 'Залишилось'); ?>: 50 <?php echo Yii::t("poll", 'символів'); ?>
                        </div>
                    </div>
                    <div class="title_modal"><?php echo Yii::t("poll", 'Опис'); ?> <span>(<?php echo Yii::t("poll", 'не обовязково'); ?>)</span></div>
                    <div class="input_text_modal_b middle_text_input_b">
                        <textarea name="Poll[describe]"><?php echo $model->describe;?></textarea>
                    </div>

                    <div class="title_modal"><?php echo Yii::t("poll", 'Варіанти відповіді'); ?></div>
                        <?php if(isset($model->pollOptions) && count($model->pollOptions)>0):?>
                            <?php foreach($model->pollOptions as $index=>$option):?>
                                <div class="item_variants">
                                    <span><?php echo $index+1;?></span>
                                    <input name="Poll[options][]" class="variant_text" type="text" value="<?php echo $option->title;?>">
                                    <a href="#" class="del_btn" data-id="<?php echo $model->id?$model->id:0?>"></a>
                                </div>
                            <?php endforeach;?>
                        <?php else:?>
                    <div class="item_variants">
                        <span>1</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
						<div class="count_symbols">
                            <?php echo Yii::t("poll", 'Залишилось'); ?>: <div class="answer_left">60</div> <?php echo Yii::t("poll", 'символів'); ?>
                        </div>
                        <a href="#" class="del_btn" data-id="<?php echo $model->id?$model->id:0?>"></a>
                    </div>
                    <div class="item_variants">
                        <span>2</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
						<div class="count_symbols">
                            <?php echo Yii::t("poll", 'Залишилось'); ?>: <div class="answer_left">60</div> <?php echo Yii::t("poll", 'символів'); ?>
                        </div>
                        <a href="#" class="del_btn" data-id="<?php echo $model->id?$model->id:0?>"></a>
                    </div>
                    <div class="item_variants">
                        <span>3</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
						<div class="count_symbols">
                            <?php echo Yii::t("poll", 'Залишилось'); ?>: <div class="answer_left">60</div> <?php echo Yii::t("poll", 'символів'); ?>
                        </div>
                        <a href="#" class="del_btn" data-id="<?php echo $model->id?$model->id:0?>"></a>
                    </div>
                    <div class="item_variants">
                        <span>4</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var" maxlength="60" type="text" value="">
						<div class="count_symbols">
                            <?php echo Yii::t("poll", 'Залишилось'); ?>: <div class="answer_left">60</div> <?php echo Yii::t("poll", 'символів'); ?>
                        </div>
                        <a href="#" class="del_btn" data-id="<?php echo $model->id?$model->id:0?>"></a>
                    </div>
                        <?php endif;?>
                    <a href="#" class="create_new_poll my_profile modal_add" data-id="<?php echo $model->id?$model->id:0?>"><?php echo Yii::t("poll", 'Додати варіант'); ?></a>
                </div>
                <div class="modal-body">
                    <div class="title_modal"><?php echo Yii::t("poll", 'Теги'); ?></div>
                    <div class="sub_title_modal"><?php echo Yii::t("poll", 'Введіть теги для опитування, через кому'); ?>.</div>
                    <div class="input_text_modal_b middle_text_input_b">
                        <textarea name="Poll[tags]"><?php if(isset($model->Tags)) echo StringHelper::tagsToString($model->Tags);?></textarea>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="title_modal"><?php echo Yii::t("poll", 'Відображення результатів'); ?></div>
                    <div class="sub_title_modal"><?php echo Yii::t("poll", 'Оберіть тип графіка для показу результатів голосування'); ?>:</div>
                    <div class="white_b_graphs">
                        <div class="tabs_graphs">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="<?php if(!$model->result_type || $model->result_type == 1):?>active<?php endif;?>"><input id="type_1" type="radio" name="Poll[type]" value="1" <?php if(!$model->result_type || $model->result_type == 1):?>checked<?php endif;?> hidden><a href="#horizontal_b_chart" class="horizontal_b_chart" role="tab" data-toggle="tab"></a></li>
                                <li class="<?php if($model->result_type == 2):?>active<?php endif;?>"><input id="type_2" type="radio" name="Poll[type]" value="2" <?php if($model->result_type == 2):?>checked<?php endif;?> hidden><a href="#vertical_b_chart" class="vertical_b_chart" role="tab" data-toggle="tab"></a></li>
                                <li class="<?php if($model->result_type == 3):?>active<?php endif;?>"><input id="type_3" type="radio" name="Poll[type]" value="3" <?php if($model->result_type == 3):?>checked<?php endif;?> hidden><a href="#pie_chart" class="pie_chart" role="tab" data-toggle="tab"></a></li>
                            </ul>
                        </div>
                        <div class="tab-content tab_visual">
                            <div id="horizontal_b_chart" class="tab-pane <?php if(!$model->result_type || $model->result_type == 1):?>active<?php endif;?>" style="width:500px; height:400px;"></div>
                            <div id="vertical_b_chart" class="tab-pane <?php if($model->result_type == 2):?>active<?php endif;?>" style="width:500px; height:400px;"></div>
                            <div id="pie_chart" class="tab-pane <?php if($model->result_type == 3):?>active<?php endif;?>" style="width:500px; height:400px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="my_profile modal_add next_modal_btn" data-dismiss="modal"  data-target="#new_poll_next_step<?php echo $model->id?$model->id:0?>" data-toggle="modal"><?php echo Yii::t("poll", 'ДАЛІ'); ?></a>
                    <div><a href="#" class="create_new_poll newPollCancel" data-dismiss="modal"><?php echo Yii::t("poll", 'Скасувати'); ?></a></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal new_poll poll" id="new_poll_next_step<?php echo $model->id?$model->id:0?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title"><?php echo Yii::t("poll", 'Нове Опитування'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="title_modal"><?php echo Yii::t("poll", 'Тип опитування'); ?></div>
                    <div class="sub_title_modal">- <?php echo Yii::t("poll", 'Відкрите голосування - користувачам одразу доступні результати опитування'); ?>;</div>
                    <div class="sub_title_modal">- <?php echo Yii::t("poll", 'Закрите голосування - результати доступні після голосування'); ?></div>
                    <div class="radio_b_chose">
                        <input id="vote_1" type="radio" name="Poll[status]" value="0" <?php if($model->status == NULL || $model->status == 0):?>checked<?php endif;?>>
                        <label for="vote_1" class="radio_open_vote">
                            <?php echo Yii::t("poll", 'Відкрите голосування'); ?>
                            <i class="fa fa-unlock"></i>
                        </label>
                        <input id="vote_2" type="radio" name="Poll[status]" value="1" <?php if($model->status == 1):?>checked<?php endif;?>>
                        <label for="vote_2" class="radio_close_vote">
                            <?php echo Yii::t("poll", 'Закрите голосування'); ?>
                            <i class="fa fa-lock"></i>
                        </label>
                    </div>
                    <div class="for_close_radio clearfix">
                        <?php echo Yii::t("poll", 'Закрите до кількості голосів'); ?>:
                        <input name="Poll[votes_count_close]" type="text" value="<?php echo $model->votes_count_close ?>" placeholder="0">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="title_modal"><?php echo Yii::t("poll", 'Параметри показу опитування'); ?></div>
                    <div class="sub_title_modal"><?php echo Yii::t("poll", 'Налаштування показу опитування для груп користувачів за мовою, віком, регіоном та ін.'); ?></div>
                    <div class="item_param item_show clearfix">
                            <span class="left_label_text">
                                <?php echo Yii::t("poll", 'Мова опитування'); ?>
                            </span>
                            <span class="right_select_b">
                                <select name="Poll[language]" class="lang_select">
                                    <?php $languages = Language::getLanguagesList();?>
                                    <?php foreach($languages as $index=>$language): ?>
                                        <option value="<?php echo $index; ?>" <?php if($model->poll_language_id == $index):?>selected<?php endif;?>><?php echo $language; ?></option>
                                    <?php endforeach;?>
                                </select>
                                <!--label class="for_all_check">
                                    <input name="Poll[all_language]" type="checkbox" value="1" <?php if($model->show_for_all_languages):?>checked<?php endif;?>>
                                    <?php echo Yii::t("poll", 'Показувати для всіх мов'); ?>
                                </label-->
                            </span>
                    </div>
                    <div class="item_param item_show clearfix">
                            <span class="left_label_text">
                                <?php echo Yii::t("poll", 'Стать'); ?>
                            </span>
                            <span class="right_select_b">
                                <select name="Poll[sex]">
                                    <option value="0" <?php if(!$model->poll_sex):?>selected<?php endif;?>><?php echo Yii::t("poll", 'Всі'); ?></option>
                                    <?php $sexes = User::getUserSexList();?>
                                    <?php foreach($sexes as $index=>$sex): ?>
                                        <option value="<?php echo $index; ?>" <?php if($model->poll_sex == $index):?>selected<?php endif;?>><?php echo $sex; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </span>
                    </div>
                    <?php $ages = User::getAgeList();?>
                    <div class="item_param item_show clearfix ages">
                        <span class="left_label_text">
                           <?php echo Yii::t("poll", 'Вік'); ?>
                        </span>
                        <span class="right_select_b clearfix">
                            <span class="for_ages_left clearfix">
                                <span class="small_text"><?php echo Yii::t("poll", 'від'); ?></span>
                                <select name="Poll[min_age]">
                                    <option value="0" <?php if(!$model->poll_min_age):?>selected<?php endif;?>><?php echo Yii::t("poll", 'Всі'); ?></option>
                                    <?php foreach($ages as $index=>$age): ?>
                                        <option value="<?php echo $index; ?>" <?php if($model->poll_min_age == $index):?>selected<?php endif;?>><?php echo $age; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </span>
                            <span class="for_ages clearfix">
                                <span class="small_text pull_left_pad"><?php echo Yii::t("poll", 'до'); ?></span>
                                <select name="Poll[max_age]">
                                    <option value="0" <?php if(!$model->poll_max_age):?>selected<?php endif;?>><?php echo Yii::t("poll", 'Всі'); ?></option>
                                    <?php foreach($ages as $index=>$age): ?>
                                        <option value="<?php echo $index; ?>" <?php if($model->poll_max_age == $index):?>selected<?php endif;?>><?php echo $age; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </span>
                        </span>
                    </div>
                    <div class="item_param item_show clearfix">
                            <span class="left_label_text">
                                <?php echo Yii::t("poll", 'Країна'); ?>
                            </span>
                            <span class="right_select_b">
                                <select name="Poll[country]" class="country">
                                    <option value="0" <?php if(!$model->poll_country_id):?>selected<?php endif;?>><?php echo Yii::t("poll", 'Всі'); ?></option>
                                    <?php $countries = Country::getCountriesList()?>
                                    <?php foreach($countries as $country): ?>
                                        <option value="<?php echo $country->id; ?>" <?php if($model->poll_country_id == $country->id):?>selected<?php endif;?>><?php echo $country->name; ?></option>
                                    <?php endforeach;?>>
                                </select>
                            </span>
                    </div>
                    <div class="item_param item_show clearfix region">
                            <span class="left_label_text">
                                <?php echo Yii::t("poll", 'Регіон'); ?>
                            </span>
                            <span class="right_select_b" id="appended_b">
                                <input name="Poll[region]" type="text" id="regionPoll" style="display: none" value="<?php echo $model->poll_region_id;?>">
                                <input type="text" class="autocomplete" id="regionACPoll" value="<?php echo $model->poll_region_id?$model->pollRegion->name:'';?>">
                                <a href="#" class="del_btn" data-id="new_poll_next_step<?php echo $model->id?$model->id:0?>"></a>
                            </span>
                    </div>
                    <div class="item_param item_show clearfix city">
                            <span class="left_label_text">
                                <?php echo Yii::t("poll", 'Населений пункт'); ?>
                            </span>
                            <span class="right_select_b" id="appended_b">
                                <input name="Poll[city]" type="text" id="cityPoll" style="display: none" value="<?php echo $model->poll_city_id; ?>">
                                <input type="text" class="autocomplete" id="cityACPoll" value="<?php echo $model->poll_city_id?$model->pollCity->name:'';?>">
                                <a href="#" class="del_btn" data-id="new_poll_next_step<?php echo $model->id?$model->id:0?>"></a>
                            </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="sub_title_modal"></div>
                    <div class="btn_b_modal">
                        <a href="#" class="my_profile modal_add next_modal_btn back_btn_modal" data-dismiss="modal" data-toggle="modal" data-target="#new_poll<?php echo $model->id?$model->id:0?>"><?php echo Yii::t("poll", 'НАЗАД'); ?></a>

                        <button type="submit" class="my_profile modal_add next_modal_btn"><?php echo $model->id?Yii::t("poll", 'ЗБЕРЕГТИ'):Yii::t("poll", 'СТВОРИТИ'); ?></button>
                    </FORM>
                </div>
                <a href="#" class="create_new_poll newPollCancel" data-dismiss="modal"><?php echo Yii::t("poll", 'Скасувати'); ?></a>
            </div>
        </div>
    </div>
</div>

<?php
/*
DEV.JS f=~/frontend/views/poll/_newPoll.php
//alert('~/frontend/views/poll/_newPoll.php');
 *  */
$modelId = $model->id ? $model->id : 0;


$jsCode = <<<JS_CODE
/* DEV.JS f=~/frontend/modules/poll/views/poll/options.php */
$(function () {
    console.log('~/frontend/views/poll/_newPoll.php');
// ADD:
        $(document).on('change','#new_poll_next_step{$modelId}.country',function(){
            $('#new_poll_next_step{$modelId} #regionACPoll').val('');
            $('#new_poll_next_step{$modelId} #regionPoll').val(0);
            $('#new_poll_next_step{$modelId} #cityACPoll').val('');
            $('#new_poll_next_step{$modelId} #cityPoll').val(0);
            refreshRegions($('#new_poll_next_step{$modelId}.country').val(),$('#new_poll_next_step{$modelId} div.region .right_select_b'),'new_poll_next_step{$modelId} #regionACPoll','new_poll_next_step{$modelId} #regionPoll','new_poll_next_step{$modelId} #cityACPoll',$('#new_poll_next_step{$modelId} .city  .right_select_b'),'new_poll_next_step{$modelId} #cityPoll');
        });

        $(document).on('change','#new_poll_next_step{$modelId} #regionACPoll',function(){
            $('#new_poll_next_step{$modelId} #cityACPoll').val('');
            $('#new_poll_next_step{$modelId} #cityPoll').val(0);
        });

        refreshRegions($('#new_poll_next_step{$modelId}.country').val(),$('#new_poll_next_step{$modelId} div.region .right_select_b'),'new_poll_next_step{$modelId} #regionACPoll','new_poll_next_step{$modelId} #regionPoll','new_poll_next_step{$modelId} #cityACPoll',$('#new_poll_next_step{$modelId} .city .right_select_b'),'new_poll_next_step{$modelId} #cityPoll');

        $(document).on('change','#new_poll{$modelId} #title, #new_poll{$modelId}  .variant_text',function(){
           refreshChart('{$modelId}');
        });

        $(document).on('click','#new_poll{$modelId} .del_btn',function(){
            setTimeout(refreshChartWithTimeout, 1000)
        });

        function refreshChartWithTimeout(){
            refreshChart('{$modelId}');
        }

});
JS_CODE;
$script = <<<JS_FINAL
jQuery(document).ready(function() {
{$jsCode}

});
JS_FINAL;

$this->registerJs($script, View::POS_END);
?>



<?php /* OLD: * / ?>

<script>


    $(function(){
        $(document).on('change','#new_poll_next_step<?php echo $model->id?$model->id:0?>.country',function(){
            $('#new_poll_next_step<?php echo $model->id?$model->id:0?> #regionACPoll').val('');
            $('#new_poll_next_step<?php echo $model->id?$model->id:0?> #regionPoll').val(0);
            $('#new_poll_next_step<?php echo $model->id?$model->id:0?> #cityACPoll').val('');
            $('#new_poll_next_step<?php echo $model->id?$model->id:0?> #cityPoll').val(0);
            refreshRegions($('#new_poll_next_step<?php echo $model->id?$model->id:0?>.country').val(),$('#new_poll_next_step<?php echo $model->id?$model->id:0?> div.region .right_select_b'),'new_poll_next_step<?php echo $model->id?$model->id:0?> #regionACPoll','new_poll_next_step<?php echo $model->id?$model->id:0?> #regionPoll','new_poll_next_step<?php echo $model->id?$model->id:0?> #cityACPoll',$('#new_poll_next_step<?php echo $model->id?$model->id:0?> .city  .right_select_b'),'new_poll_next_step<?php echo $model->id?$model->id:0?> #cityPoll');
        });

        $(document).on('change','#new_poll_next_step<?php echo $model->id?$model->id:0?> #regionACPoll',function(){
            $('#new_poll_next_step<?php echo $model->id?$model->id:0?> #cityACPoll').val('');
            $('#new_poll_next_step<?php echo $model->id?$model->id:0?> #cityPoll').val(0);
        });

        refreshRegions($('#new_poll_next_step<?php echo $model->id?$model->id:0?>.country').val(),$('#new_poll_next_step<?php echo $model->id?$model->id:0?> div.region .right_select_b'),'new_poll_next_step<?php echo $model->id?$model->id:0?> #regionACPoll','new_poll_next_step<?php echo $model->id?$model->id:0?> #regionPoll','new_poll_next_step<?php echo $model->id?$model->id:0?> #cityACPoll',$('#new_poll_next_step<?php echo $model->id?$model->id:0?> .city .right_select_b'),'new_poll_next_step<?php echo $model->id?$model->id:0?> #cityPoll');

        $(document).on('change','#new_poll<?php echo $model->id?$model->id:0?> #title, #new_poll<?php echo $model->id?$model->id:0?>  .variant_text',function(){
           refreshChart('<?php echo $model->id?$model->id:0?>');
        });

        $(document).on('click','#new_poll<?php echo $model->id?$model->id:0?> .del_btn',function(){
            setTimeout(refreshChartWithTimeout, 1000)
        });

        function refreshChartWithTimeout(){
            refreshChart('<?php echo $model->id?$model->id:0?>');
        }

        <?php if($model->status == NULL || $model->status == 0):?>
            $('#new_poll_next_step<?php echo $model->id?$model->id:0?> .for_close_radio').hide();
        <?php endif;?>

        <?php if($model->id):?>
            refreshChart('<?php echo $model->id?$model->id:0?>');
        <?php endif;?>
    })
</script>
<?php /*  */
?>
<?= (YII_ENV != 'dev') ? '' : '<!-- //#DEV24-05 -->'; ?>
<?php /*  */
