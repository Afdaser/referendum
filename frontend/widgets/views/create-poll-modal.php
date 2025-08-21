<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\helpers\Url;
//use common\helpers\StringHelper;
use common\models\Language;
use common\models\User;
use common\models\Country;
?>
<?= (YII_ENV != 'dev') ? '' : "<!-- #DEV2404_MX01 \n" . __FILE__ . "\n -->"; ?>
<?php
$model = $pollModel;

/*
 *     <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken); ?>
 *
 */
?>


<!-- Modal DEV2404_MX01 -->

<div class="modal new_poll in" id="new_poll0" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <?php $form = ActiveForm::begin(['id' => 'poll-form', 'action' => Url::toRoute(['/poll/site/my-polls']),]); ?>

        <div class="modal-content" id="poll_modal_content_step0">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="sr-only"><?= Yii::t('poll', 'Close'); ?></span>
                </button>
                <h4 class="modal-title"><?= Yii::t('poll', 'Нове Опитування'); ?></h4>
            </div>
            <div class="modal-body">
                <input name="Poll[id]" type="hidden" value="<?= $model->id ? $model->id : '' ?>">
                <div class="title_modal"><?= Yii::t('poll', 'Питання'); ?></div>
                <div class="input_text_modal_b middle_text_input_b">
                    <textarea name="Poll[title]" id="title"><?= $model->title; ?></textarea>
                    <div class="count_symbols">
                        <?= Yii::t('poll', 'Залишилось'); ?>: 50 <?= Yii::t('poll', 'символів'); ?>
                    </div>
                </div>
                <div class="title_modal"><?= Yii::t('poll', 'Опис'); ?> <span>(<?= Yii::t('poll', 'не обовязково'); ?>)</span></div>
                <div class="input_text_modal_b middle_text_input_b">
                    <textarea name="Poll[describe]"><?= $model->describe; ?></textarea>
                    <?php /* /* */ ?>
                    <?php /* <?= $form->field($model, 'describe')->textarea(['rows' => 2]) /* */ ?>
                </div>

                <div class="title_modal"><?= Yii::t('poll', 'Варіанти відповіді'); ?></div>
                <?php if (isset($model->pollOptions) && count($model->pollOptions) > 0): ?>
                    <?php foreach ($model->pollOptions as $index => $option): ?>
                        <div class="item_variants">
                            <span><?= $index + 1; ?></span>
                            <input name="Poll[options][]" class="variant_text" type="text" value="<?= $option->title; ?>">
                            <a href="#" class="del_btn" data-id="<?= $model->id ? $model->id : 0 ?>"></a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="item_variants">
                        <span>1</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var input_with_count_symbols" maxlength="60" type="text" value="">
                        <div class="count_symbols">
                            <?= Yii::t('poll', 'Залишилось'); ?>: <div class="answer_left">60</div> <?= Yii::t('poll', 'символів'); ?>
                        </div>
                        <a href="#" class="del_btn" data-id="<?= $model->id ? $model->id : 0 ?>"></a>
                    </div>
                    <div class="item_variants">
                        <span>2</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var input_with_count_symbols" maxlength="60" type="text" value="">
                        <div class="count_symbols">
                            <?= Yii::t('poll', 'Залишилось'); ?>: <div class="answer_left">60</div> <?= Yii::t('poll', 'символів'); ?>
                        </div>
                        <a href="#" class="del_btn" data-id="<?= $model->id ? $model->id : 0 ?>"></a>
                    </div>
                    <div class="item_variants">
                        <span>3</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var input_with_count_symbols" maxlength="60" type="text" value="">
                        <div class="count_symbols">
                            <?= Yii::t('poll', 'Залишилось'); ?>: <div class="answer_left">60</div> <?= Yii::t('poll', 'символів'); ?>
                        </div>
                        <a href="#" class="del_btn" data-id="<?= $model->id ? $model->id : 0 ?>"></a>
                    </div>
                    <div class="item_variants">
                        <span>4</span>
                        <input name="Poll[options][]" style="margin-left:10px;" class="variant_text answer_var input_with_count_symbols" maxlength="60" type="text" value="">
                        <div class="count_symbols">
                            <?= Yii::t('poll', 'Залишилось'); ?>: <div class="answer_left">60</div> <?= Yii::t('poll', 'символів'); ?>
                        </div>
                        <a href="#" class="del_btn" data-id="<?= $model->id ? $model->id : 0 ?>"></a>
                    </div>
                <?php endif; ?>
                <a href="#" class="create_new_poll my_profile modal_add" data-id="<?= $model->id ? $model->id : 0 ?>"><?= Yii::t('poll', 'Додати варіант'); ?></a>
            </div>
            <div class="modal-body">
                <div class="title_modal"><?= Yii::t('poll', 'Теги'); ?></div>
                <div class="sub_title_modal"><?= Yii::t('poll', 'Введіть теги для опитування, через кому'); ?>.</div>
                <div class="input_text_modal_b middle_text_input_b">
                    <textarea name="Poll[tags]"><?php if (isset($model->Tags)) echo StringHelper::tagsToString($model->Tags); ?></textarea>
                </div>
            </div>
            <div class="modal-body">
                <div class="title_modal"><?= Yii::t('poll', 'Відображення результатів'); ?></div>
                <div class="sub_title_modal"><?= Yii::t('poll', 'Оберіть тип графіка для показу результатів голосування'); ?>:</div>
                <div class="white_b_graphs">
                    <div class="tabs_graphs">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="<?php if (!$model->result_type || $model->result_type == 1): ?>active<?php endif; ?>"><input id="type_1" type="radio" name="Poll[type]" value="1" <?php if (!$model->result_type || $model->result_type == 1): ?>checked<?php endif; ?> hidden><a href="#horizontal_b_chart" class="horizontal_b_chart" role="tab" data-toggle="tab"></a></li>
                            <li class="<?php if ($model->result_type == 2): ?>active<?php endif; ?>"><input id="type_2" type="radio" name="Poll[type]" value="2" <?php if ($model->result_type == 2): ?>checked<?php endif; ?> hidden><a href="#vertical_b_chart" class="vertical_b_chart" role="tab" data-toggle="tab"></a></li>
                            <li class="<?php if ($model->result_type == 3): ?>active<?php endif; ?>"><input id="type_3" type="radio" name="Poll[type]" value="3" <?php if ($model->result_type == 3): ?>checked<?php endif; ?> hidden><a href="#pie_chart" class="pie_chart" role="tab" data-toggle="tab"></a></li>
                        </ul>
                    </div>
                    <div class="tab-content tab_visual">
                        <div id="horizontal_b_chart" class="tab-pane <?php if (!$model->result_type || $model->result_type == 1): ?>active<?php endif; ?>" style="width:500px; height:400px;"></div>
                        <div id="vertical_b_chart" class="tab-pane <?php if ($model->result_type == 2): ?>active<?php endif; ?>" style="width:500px; height:400px;"></div>
                        <div id="pie_chart" class="tab-pane <?php if ($model->result_type == 3): ?>active<?php endif; ?>" style="width:500px; height:400px;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php /*
                  <a href="#" class="my_profile modal_add next_modal_btn" data-dismiss="modal"  data-target="#new_poll_next_step<?= $model->id ? $model->id : 0 ?>" data-toggle="modal"><?= Yii::t('poll', 'ДАЛІ'); ?>[0]</a>
                  <?php /* */ ?>
                <a href="#" class="my_profile modal_add next_modal_btn" id="new_poll_next_step1"><?= Yii::t('poll', 'ДАЛІ'); ?></a>
                <div><a href="#" class="create_new_poll newPollCancel" data-dismiss="modal"><?= Yii::t('poll', 'Скасувати'); ?></a></div>
            </div>
        </div>

        <!-- Modal DEV2404_MX02 -->
        <div class="modal-content" id="poll_modal_content_step1" style="display:none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title"><?= Yii::t('poll', 'Нове Опитування'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="title_modal"><?= Yii::t('poll', 'Тип опитування'); ?></div>
                <div class="sub_title_modal">- <?= Yii::t('poll', 'Відкрите голосування - користувачам одразу доступні результати опитування'); ?>;</div>
                <div class="sub_title_modal">- <?= Yii::t('poll', 'Закрите голосування - результати доступні після голосування'); ?></div>
                <div class="radio_b_chose">
                    <input id="vote_1" type="radio" name="Poll[status]" value="0" <?php if ($model->status == NULL || $model->status == 0): ?>checked<?php endif; ?>>
                    <label for="vote_1" class="radio_open_vote">
                        <?= Yii::t('poll', 'Відкрите голосування'); ?>
                        <i class="fa fa-unlock"></i>
                    </label>
                    <input id="vote_2" type="radio" name="Poll[status]" value="1" <?php if ($model->status == 1): ?>checked<?php endif; ?>>
                    <label for="vote_2" class="radio_close_vote">
                        <?= Yii::t('poll', 'Закрите голосування'); ?>
                        <i class="fa fa-lock"></i>
                    </label>
                </div>
                <div class="for_close_radio clearfix" <?= empty($model->status) ? ' style="display: none;"' : ''; ?>>
                    <?= Yii::t('poll', 'Закрите до кількості голосів'); ?>:
                    <input name="Poll[votes_count_close]" type="text" value="<?= $model->votes_count_close ?>" placeholder="0">
                </div>
            </div>
            <div class="modal-body">
                <div class="title_modal"><?= Yii::t('poll', 'Параметри показу опитування'); ?></div>
                <div class="sub_title_modal"><?= Yii::t('poll', 'Налаштування показу опитування для груп користувачів за мовою, віком, регіоном та ін.'); ?></div>
                <div class="item_param item_show clearfix">
                    <span class="left_label_text">
                        <?= Yii::t('poll', 'Мова опитування'); ?>
                    </span>
                    <span class="right_select_b">
                        <select name="Poll[language]" class="lang_select">
                            <?php $languages = Language::getLanguagesList(); ?>
                            <?php foreach ($languages as $index => $language): ?>
                                <option value="<?= $index; ?>" <?php if ($model->poll_language_id == $index): ?>selected<?php endif; ?>><?php echo $language; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <!--label class="for_all_check">
                            <input name="Poll[all_language]" type="checkbox" value="1" <?php if ($model->show_for_all_languages): ?>checked<?php endif; ?>>
                        <?= Yii::t('poll', 'Показувати для всіх мов'); ?>
                        </label-->
                    </span>
                </div>
                <div class="item_param item_show clearfix">
                    <span class="left_label_text">
                        <?= Yii::t('poll', 'Стать'); ?>
                    </span>
                    <span class="right_select_b">
                        <select name="Poll[sex]">
                            <option value="0" <?php if (!$model->poll_sex): ?>selected<?php endif; ?>><?= Yii::t('poll', 'Всі'); ?></option>
                            <?php $sexes = User::getUserSexList(); ?>
                            <?php foreach ($sexes as $index => $sex): ?>
                                <option value="<?= $index; ?>" <?php if ($model->poll_sex == $index): ?>selected<?php endif; ?>><?php echo $sex; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </span>
                </div>
                <?php $ages = User::getAgeList(); ?>
                <div class="item_param item_show clearfix ages">
                    <span class="left_label_text">
                        <?= Yii::t('poll', 'Вік'); ?>
                    </span>
                    <span class="right_select_b clearfix">
                        <span class="for_ages_left clearfix">
                            <span class="small_text"><?= Yii::t('poll', 'від'); ?></span>
                            <select name="Poll[min_age]">
                                <option value="0" <?php if (!$model->poll_min_age): ?>selected<?php endif; ?>><?= Yii::t('poll', 'Всі'); ?></option>
                                <?php foreach ($ages as $index => $age): ?>
                                    <option value="<?php echo $index; ?>" <?php if ($model->poll_min_age == $index): ?>selected<?php endif; ?>><?php echo $age; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </span>
                        <span class="for_ages clearfix">
                            <span class="small_text pull_left_pad"><?= Yii::t('poll', 'до'); ?></span>
                            <select name="Poll[max_age]">
                                <option value="0" <?php if (!$model->poll_max_age): ?>selected<?php endif; ?>><?= Yii::t('poll', 'Всі'); ?></option>
                                <?php foreach ($ages as $index => $age): ?>
                                    <option value="<?= $index; ?>" <?php if ($model->poll_max_age == $index): ?>selected<?php endif; ?>><?php echo $age; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </span>
                    </span>
                </div>
                <div class="item_param item_show clearfix">
                    <span class="left_label_text">
                        <?= Yii::t('poll', 'Країна'); ?>
                    </span>
                    <span class="right_select_b">
                        <select name="Poll[country]" class="country">
                            <option value="0" <?php if (!$model->poll_country_id): ?>selected<?php endif; ?>><?= Yii::t('poll', 'Всі'); ?></option>
                            <?php $countries = Country::getCountriesList() ?>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?php echo $country->id; ?>" <?php if ($model->poll_country_id == $country->id): ?>selected<?php endif; ?>><?php echo $country->name; ?></option>
                            <?php endforeach; ?>>
                        </select>
                    </span>
                </div>
                <div class="item_param item_show clearfix region">
                    <span class="left_label_text">
                        <?= Yii::t('poll', 'Регіон'); ?>
                    </span>
                    <span class="right_select_b" id="appended_b">
                        <input name="Poll[region]" type="text" id="regionPoll" style="display: none" value="<?= $model->poll_region_id; ?>">
                        <input type="text" class="autocomplete" id="regionACPoll" value="<?= $model->poll_region_id ? $model->pollRegion->name : ''; ?>">
                        <a href="#" class="del_btn" data-id="new_poll_next_step<?= $model->id ? $model->id : 0 ?>"></a>
                    </span>
                </div>
                <div class="item_param item_show clearfix city">
                    <span class="left_label_text">
                        <?= Yii::t('poll', 'Населений пункт'); ?>
                    </span>
                    <span class="right_select_b" id="appended_b">
                        <input name="Poll[city]" type="text" id="cityPoll" style="display: none" value="<?= $model->poll_city_id; ?>">
                        <input type="text" class="autocomplete" id="cityACPoll" value="<?= $model->poll_city_id ? $model->pollCity->name : ''; ?>">
                        <a href="#" class="del_btn" data-id="new_poll_next_step<?= $model->id ? $model->id : 0 ?>"></a>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <div class="sub_title_modal"></div>
                <div class="btn_b_modal">
                    <?php /*                     * / ?>
                      <a href="#" class="my_profile modal_add next_modal_btn back_btn_modal" data-dismiss="modal" data-toggle="modal" data-target="#new_poll<?= $model->id ? $model->id : 0 ?>"><?= Yii::t('poll', 'НАЗАД'); ?>[0]</a>
                      <?php /* */ ?>
                    <a href="#" class="my_profile modal_add next_modal_btn" id="new_poll_back_step0"><?= Yii::t('poll', 'НАЗАД'); ?></a>

                    <button type="submit" class="my_profile modal_add next_modal_btn"><?= $model->id ? Yii::t('poll', 'ЗБЕРЕГТИ') : Yii::t('poll', 'СТВОРИТИ'); ?></button>
                    <!-- /FORM -->
                </div>
                <a href="#" class="create_new_poll newPollCancel" data-dismiss="modal"><?= Yii::t('poll', 'Скасувати'); ?></a>
            </div>
        </div>

        <?php /*
         * </form>
         */ ?>

        <?php ActiveForm::end(); ?>
        <?php
/*
        $scriptPoll = <<<JS_POLL_ONE

alert ('~/referendum.social.local/frontend/widgets/views/create-poll-modal.php');

    $('#new_poll_next_step1').click(function () {
        alert('Click on #new_poll_next_step1');
    });

    $('#new_poll_back_step0').click(function () {
        alert('Click on #new_poll_back_step0');
    });

JS_POLL_ONE;
/* */
$scriptPoll = '';
        /*
          if(isset($error) && $error):
          $alertMessage = strip_tags($error);
          $scriptPoll .= "
          $(function() {
          alert('{$alertMessage}');
          });
          ";
          endif;
          /* */


        /*         * /
          $scriptPoll .= <<<JS_POLL_TWO
          $(document).on('change','.gender, .age, .country',function(){
          filterDataChart({$poll->id},'{$poll->title}');
          });

          $(document).on('click','.clear_btn',function(){
          clearChartFilters({$poll->id},'{$poll->title}');
          });

          $(document).on('change','.country',function(){
          refreshRegions($('.country').val(),$('span.region'),'regionAC','region','cityAC',$('.city'),'city');
          $('#regionAC').val('');
          $('#region').val(0);
          });

          $(document).on('click','.del_btn',function(){
          $('#regionAC').val('');
          $('#region').val(0);
          });

          $(document).on('click','.autocomplete-suggestion',function(){
          filterDataChart({$poll->id},'{$poll->title}');
          });

          $(document).on('click','.pie_chart,.horizontal_b_chart,.vertical_b_chart',function(){
          $('#container{$poll->id}').removeClass('pie').removeClass('bar').removeClass('column');
          $('#container{$poll->id}').addClass($(this).attr('data-id'));
          filterDataChart({$poll->id},'{$poll->title}');
          });
          JS_POLL_TWO;
          /* */

        $script = <<<JS_FINAL
jQuery(document).ready(function() {
{$scriptPoll}

});
JS_FINAL;

        $this->registerJs($script, View::POS_END);
        ?>
    </div>
</div>
<!-- /Modal DEV2404_MX01 -->

<?= (YII_ENV != 'dev') ? '' : '<!-- //#DEV2404_MX01 -->'; ?>