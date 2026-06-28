<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $poll \common\models\Poll */

if (!empty($poll->pollLanguage)) {
    Yii::$app->params['canonical'] = Yii::$app->urlManager->createPollLangUrl($poll->pollLanguage->name, '//poll/view', ['id' => $poll->id]);
}

?>
<div class="col-md-8">
	<div class="row right_cut_row">
		<div class="chart_b" style="margin-bottom: 5px;">
			<div class="top_b_chart">
                                <a class="btn_prev_var" href="<?= Yii::$app->request->referrer; ?>"><?= Yii::t("poll", 'Назад'); ?></a>
			</div>
			<div class="inner_b_chart">
				<div class="poll_block poll_view_page">
                    <?php // Готуємо дані для рендеру графіків і варіантів відповідей. ?>
                    <?php $chartData = $poll->getChartData();?>
                    <?php $bar = StringHelper::formatForBar($chartData); ?>
                    <?php $pie = StringHelper::formatForPie($chartData); ?>
                    <div class="poll_heading_b clearfix">
						<div class="left_rating_b">
							<?php // Кнопки рейтингу лишають класи та data-id для наявних jQuery-обробників. ?>
							<button type="button" class="arrow_rating_top" data-id="<?php echo $poll->id; ?>" aria-label="<?php echo Yii::t('poll', 'Підняти рейтинг опитування'); ?>"></button><br>
							<span class="poll_rating" data-id="<?php echo $poll->id; ?>"><?php echo $poll->rating; ?></span><br>
							<button type="button" class="arrow_rating_down" data-id="<?php echo $poll->id; ?>" aria-label="<?php echo Yii::t('poll', 'Знизити рейтинг опитування'); ?>"></button>
						</div>
                        <div class="middle_title_b">
                            <div class="title_poll poll_view_title_row">
                                <?php // Додаємо префікс "Poll:" до заголовка опитування. ?>
                                <h1><?php echo Yii::t('poll', 'Опитування: {title}', ['title' => $poll->title]); ?></h1>
                                <?php if (!empty($poll->image_url)): ?>
                                    <?php // Alt береться з поля опитування, а за відсутності безпечно підставляємо назву. ?>
                                    <?php echo Html::img($poll->image_url, ['alt' => $poll->image_alt ?: $poll->title, 'class' => 'poll-view-image']); ?>
                                <?php endif; ?>
                                <span class="right_block_share_icon poll_view_share">
                                    <?php // Переміщаємо кнопку поширення ближче до заголовка для органічного вигляду. ?>
                                    <?php $this->renderPartial('//site/polls/_shareSocial', array('poll' => $poll)); ?>
                                </span>
                            </div>
                        </div>
                    </div>
					<div class="middle_name_poll_b clearfix">
                        <div class="desc_my_chart">
                            <?php echo $poll->getClearedDescribe() ?>
                        </div>
                        <?php if(!$poll->isShowResult()):?>
                            <div class="inner_block_chosen">
                                <?php $this->renderPartial('//poll/options', array('poll' => $poll,'chartData'=>$chartData,'bar'=>$bar,'pie'=>$pie)); ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="container_graph poll_chart_on_demand" style="display: none;">
                                <?php // Графік уже має дані, але ліниво малюється лише після кліку на перемикач типу. ?>
                                <?php $this->renderPartial('//poll/options', array('poll' => $poll,'chartData'=>$chartData,'bar'=>$bar,'pie'=>$pie,'showChart'=>true,'lazyChart'=>true)); ?>
                            </div>
                        <?php endif;?>
                        <?php if($poll->isShowResult()):?>
                            <div class="clearfix"></div>
                            <div class="container_graph">
                                <?php $this->renderPartial('//poll/options', array('poll' => $poll,'chartData'=>$chartData,'bar'=>$bar,'pie'=>$pie)); ?>
                            </div>
                        <?php endif;?>
					</div>
					<div class="top_poll_b clearfix bottom_space_for_chart poll_view_footer">
                        <div class="poll_view_tags">
						    <?php foreach ($poll->Tags as $pollTag) : ?>
                                <a href="<?= Url::to(['/site/search', 'tag' => $pollTag->name]) ?>"
                                   class="link_poll">#<?= $pollTag->name; ?></a>
						    <?php endforeach; ?>
                        </div>
                        <div class="poll_view_chart_toggle">
                            <span class="chosen_graph_b animated_b">
                                <span class="inner_chosen_graph">
                                    <?php // Додаємо доступні назви: вони потрібні і для скрінрідерів, і як підказки при наведенні. ?>
                                    <?php $pieChartLabel = Yii::t("poll", 'Показати кругову діаграму'); ?>
                                    <?php $horizontalChartLabel = Yii::t("poll", 'Показати горизонтальний графік'); ?>
                                    <?php $verticalChartLabel = Yii::t("poll", 'Показати вертикальний графік'); ?>
                                    <?php $lineChartLabel = Yii::t("poll", 'Показати лінійний графік'); ?>
                                    <?php // Кожна кнопка містить усі іконки, щоб активний пункт міг плавно "дихати" між типами графіків. ?>
                                    <a href="javascript:void(0)" class="pie_chart <?php echo ($poll->result_type == 3) ? 'active' : ''; ?>" data-id="pie" aria-label="<?php echo $pieChartLabel; ?>" title="<?php echo $pieChartLabel; ?>">
                                        <span class="pie_chart_img"></span>
                                        <span class="vertical_chart_img"></span>
                                        <span class="horizontal_chart_img"></span>
                                        <span class="line_chart_img"></span>
                                    </a>
                                    <a href="javascript:void(0)" class="horizontal_b_chart <?php echo (!$poll->result_type || $poll->result_type == 1) ? 'active' : ''; ?>" data-id="bar" aria-label="<?php echo $horizontalChartLabel; ?>" title="<?php echo $horizontalChartLabel; ?>">
                                        <span class="pie_chart_img"></span>
                                        <span class="vertical_chart_img"></span>
                                        <span class="horizontal_chart_img"></span>
                                        <span class="line_chart_img"></span>
                                    </a>
                                    <a href="javascript:void(0)" class="vertical_b_chart <?php echo ($poll->result_type == 2) ? 'active' : ''; ?>" data-id="column" aria-label="<?php echo $verticalChartLabel; ?>" title="<?php echo $verticalChartLabel; ?>">
                                        <span class="pie_chart_img"></span>
                                        <span class="vertical_chart_img"></span>
                                        <span class="horizontal_chart_img"></span>
                                        <span class="line_chart_img"></span>
                                    </a>
                                    <a href="javascript:void(0)" class="line_chart <?php echo ($poll->result_type == 4) ? 'active' : ''; ?>" data-id="line" aria-label="<?php echo $lineChartLabel; ?>" title="<?php echo $lineChartLabel; ?>">
                                        <span class="pie_chart_img"></span>
                                        <span class="vertical_chart_img"></span>
                                        <span class="horizontal_chart_img"></span>
                                        <span class="line_chart_img"></span>
                                    </a>
                                </span>
                            </span>
                        </div>
					</div>
					<div class="show_voices_b">
						<div class="title_show_voice clearfix">
                                            <span class="toggle_b collapsed" data-toggle="collapse"
                                                  data-target=".for_collapsing">
                                                <?php echo Yii::t("poll", 'Показати голоси'); ?>
                                                <i class="fa fa-angle-up"></i>
                                            </span>
                                            <span class="right_clear_btn">
                                                <a href="javascript:void(0)" class="clear_btn">
	                                                <i class="clear_btn_ico"></i>
	                                                <span><?php echo Yii::t("poll", 'Очистити'); ?></span>
                                                </a>
                                            </span>
						</div>
						<div class="for_collapsing collapse">
							<div class="select_blocks">
                                            <span class="item_show">
                                                <?php echo Yii::t("poll", 'Стать'); ?><br>
                                                <select class="gender">
                                                    <option value="0"><?php echo Yii::t("poll", 'Всі'); ?></option>
                                                    <?php $sexes = User::getUserSexList();?>
                                                    <?php foreach($sexes as $index=>$sex): ?>
                                                        <option value="<?php echo $index; ?>"><?php echo $sex; ?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </span>
                                            <span class="item_show">
                                                <?php echo Yii::t("poll", 'Вік'); ?><br>
                                                <select class="age">
                                                    <option value="0"><?php echo Yii::t("poll", 'Всі'); ?></option>
                                                    <?php $ages = User::getUserAgeIntervalList();?>
                                                    <?php foreach($ages as $index=>$age): ?>
                                                        <option value="<?php echo $index; ?>"><?php echo $age; ?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </span>
                                            <span class="item_show">
                                                <?php echo Yii::t("poll", 'Країна'); ?><br>
                                                <select class="country">
	                                                <option value="0"><?php echo Yii::t("poll", 'Всі'); ?></option>
                                                    <?php foreach($poll->getVoteCountries() as $key => $country): ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $country; ?></option>
                                                    <?php endforeach;?>>
                                                </select>
                                            </span>
                                            <!--
                                            <span class="item_show region">
                                                <?php echo Yii::t("poll", 'Регіон'); ?><br>
                                                <input type="text" id="region" style="display: none">
                                                <input type="text" class="autocomplete region" id="regionAC">
                                                <a href="#" class="del_btn"></a>
                                                <div class="autocomplete-suggestions" style="position: absolute; display: none; max-height: 300px; z-index: 9999;"></div>
                                            </span>
                                            -->
                                            <span class="item_show">
                                                <?php echo Yii::t("poll", 'Реєстрація'); ?><br>
                                                <select class="registration">
                                                    <option value="0"><?php echo Yii::t("poll", 'Всі'); ?></option>
                                                    <option value="1"><?php echo Yii::t("poll", 'Зареєстровані'); ?></option>
                                                    <option value="2"><?php echo Yii::t("poll", 'Незареєстровані'); ?></option>
                                                </select>
                                            </span>
                                            </div>
						</div>
					</div>
					<div class="bottom_poll_b clearfix">
                                            <span class="item_bottom_poll"> <?php echo Yii::t("poll", 'Користувач'); ?>: <a href="<?= Url::to(['/poll/site/user-profile', 'id' => $poll->user_id]); ?>"><?= $poll->user->getFullUserName();?></a></span>
						<span class="item_bottom_poll"> <?php echo Yii::t("poll", 'голосів'); ?>: <?= $poll->countPollOptionsVoters; ?></span>
						<?php if ($poll->isOpen()): ?>
							<div class="right_poll_status open">
                                <?php echo Yii::t("poll", 'Відкрите голосування'); ?>
								<span><i class="fa fa-unlock"></i></span>
							</div>
						<?php else: ?>
							<div class="right_poll_status closed"  title="<?= $poll->countPollOptionsVoters; ?>/<?php echo $poll->votes_count_close;?>">
                                <?php echo Yii::t("poll", 'Закрите голосування'); ?>
								<span><i class="fa fa-lock"></i></span>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
                <?php if(0 && Yii::$app->user->isGuest){
                    // Guest can vote
                    ?>
			<a data-target="#registrtion_step_1" data-toggle="modal" href="#" class="item_list_poll" style="text-align:center;padding-left:70px;padding-right:70px;">
                            <?php echo Yii::t("poll", 'Для того щоб прийняти участь в опитуванні, або побачити результати необхідно зареєструватись.'); ?>
                        </a>
		<?php } ?>
		<?php if($poll->isShowResult() && !$poll->isVoted()){?>
<?php /*  2022-07-30
			<div class="item_list_poll" style="text-align:center;"><?php echo Yii::t("poll", 'Це опитування вам не підходить, тому ви бачите одразу результати.'); ?></div>
 <?php  /*  */ ?>
		<?php } ?>
		<div class="comments_add-answer_b">
            <ul class="nav nav-tabs" role="tablist">
                <li>
                    <a href="#middle_comments_list_b" role="tab" data-toggle="tab"><?php echo Yii::t("poll", 'Коментарі'); ?>
                        <span class="count_poll"><?php echo count($poll->pollComments); ?></span>
                    </a>
                </li>
                <li class="active">
                    <a href="#inner_add-answer_b" role="tab" data-toggle="tab"><?php echo Yii::t("poll", 'Запропоновані відповіді'); ?>
                        <span class="count_poll"><?php echo count($poll->pollAnswers); ?></span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="inner_add-answer_b" class="inner_add-answer_b tab-pane active">
                    <?php $this->renderPartial('//poll/answersBlock', array('answers' => $poll->pollAnswers)); ?>
                </div>
                <div id="middle_comments_list_b" class="middle_comments_list_b tab-pane">
                    <?php $this->renderPartial('//poll/commentsBlock', array('comments' => $poll->pollCommentsRoot)); ?>
                </div>
            </div>
            <div class="comments_block_bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#middle_text_input__comment_b" role="tab" data-toggle="tab"><?php echo Yii::t("poll", 'Додати коментар'); ?></a></li>
                    <li><a href=" #middle_text_input_b" role="tab" data-toggle="tab"><?php echo Yii::t("poll", 'Додати варіант відповіді'); ?></a></li>
                </ul>
                <div class="tab-content">
                    <div id="middle_text_input_b" class="middle_text_input_b tab-pane">
                        <form method="POST" action="<?= Url::to(['/poll/addAnswer']) ?>">
                        <input name="Profile[answer][poll_id]" type="text" class="autocomplete" value="<?php echo $poll->id;?>" hidden>
                        <textarea id="answer_text" maxlength="60" name="Profile[answer][title]"><?php echo $answerModel->title;?></textarea>
                        <div class="count_symbols">
                            <?php echo Yii::t("poll", 'Залишилось'); ?>: <span id="textareaFeedback">60</span> <?php echo Yii::t("poll", 'символів'); ?>
                        </div>
                        <div class="bottom_btn_b">
                            <button type="submit" class="send_btn"><?php echo Yii::t("poll", 'Надіслати'); ?><i class="send_icon"></i></button>
                        </div>
                        </form>
                    </div>
                    <div id="middle_text_input__comment_b" class="middle_text_input_b comments tab-pane active">
                        <form method="POST" action="<?= Url::to(['/poll/addComment']) ?>">
                            <input name="Profile[comment][poll_id]" type="text" class="autocomplete" value="<?php echo $poll->id;?>" hidden>
                            <input name="Profile[comment][parent_id]" type="text" class="autocomplete" value="<?php echo isset($commentModel->parent_id)?$commentModel->parent_id:'';?>" hidden>
                            <textarea name="Profile[comment][content]"><?php echo $commentModel->content;?></textarea>
                            <div class="bottom_btn_b">
                                <button type="submit" class="send_btn"><?php echo Yii::t("poll", 'Надіслати'); ?><i class="send_icon"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>


<script>

        jQuery(function()
        {
                var maxLength = jQuery('#answer_text').attr('maxlength');        //(1)
                jQuery('#answer_text').on('keyup', function()
                {
                        var curLength = jQuery('#answer_text').val().length;         //(2)
                        jQuery(this).val(jQuery(this).val().substr(0, maxLength));     //(3)
                        var remaning = maxLength - curLength;
                        if (remaning < 0) remaning = 0;
                        jQuery('#textareaFeedback').html(remaning);//(4)
                        if (remaning < 10)          //(5)
                        {
                                jQuery('#textareaFeedback').addClass('warning')
                        }
                        else
                        {
                                jQuery('#textareaFeedback').removeClass('warning')
                        }
                })
        })

    jQuery(function() {
        <?php if(isset($error) && $error):?>
            alert(<?php echo strip_tags($error);?>);
        <?php endif;?>
    });

    jQuery(document).on('change','.gender, .age, .country, .registration',function(){
        filterDataChart(<?php echo $poll->id;?>,'<?php echo $poll->title;?>');
    });

    jQuery(document).on('click','.clear_btn',function(){
        clearChartFilters(<?php echo $poll->id;?>,'<?php echo $poll->title;?>');
    });

/*
    jQuery(document).on('change','.country',function(){
        refreshRegions(jQuery('.country').val(),jQuery('span.region'),'regionAC','region','cityAC',jQuery('.city'),'city');
        jQuery('#regionAC').val('');
        jQuery('#region').val(0);
    });

    jQuery(document).on('click','.del_btn',function(){
        jQuery('#regionAC').val('');
        jQuery('#region').val(0);
    });

   jQuery(document).on('click','.autocomplete-suggestion',function(){
       filterDataChart(<?php echo $poll->id;?>,'<?php echo $poll->title;?>');
    });
*/

    jQuery(document).on('click','.pie_chart,.horizontal_b_chart,.vertical_b_chart,.line_chart',function(){
        // Після вибору одразу фіксуємо активну кнопку внизу перемикача, щоб hover-стан не показував порожній квадрат.
        jQuery(this).addClass('active').siblings('.pie_chart,.horizontal_b_chart,.vertical_b_chart,.line_chart').removeClass('active');
        jQuery('#container<?php echo $poll->id; ?>').removeClass('pie').removeClass('bar').removeClass('column').removeClass('line');
        jQuery('#container<?php echo $poll->id; ?>').addClass(jQuery(this).attr('data-id'));
        filterDataChart(<?php echo $poll->id;?>,'<?php echo $poll->title;?>');
    });
</script>
