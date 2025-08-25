<?php

use yii\web\View;
use frontend\helpers\Url;
use common\helpers\StringHelper;
use common\models\User;
use yii\helpers\Html;

/** @var yii\web\View $this */
/* @var Poll $poll */

if (!empty($poll->pollLanguage)) {
    Yii::$app->params['canonical'] = Yii::$app->urlManager->createPollLangUrl($poll->pollLanguage->name, '//poll/view', array('id' => $poll->id));
}

?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <div class="chart_b" style="margin-bottom: 5px;">
            <div class="top_b_chart">
                <a class="btn_prev_var" href="<?= Yii::$app->request->referrer; ?>"><?= Yii::t('poll', 'Назад'); ?></a>
            </div>
            <div class="inner_b_chart">
                <!-- Додаємо мікророзмітку для DiscussionForumPosting -->
                <div class="poll_block" itemscope itemtype="https://schema.org/DiscussionForumPosting">
                    <meta itemprop="mainEntityOfPage" content="<?= 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>" />
                    <meta itemprop="url" content="<?= 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>" />

                    <div class="top_poll_b clearfix">
                        <span class="right_block_share_icon">
                           <?= $this->render('//site/polls/_shareSocial', ['poll' => $poll]); ?>
                        </span>
                    </div>
                    <div class="middle_name_poll_b clearfix">
                        <div class="left_rating_b" itemprop="interactionStatistic" itemscope itemtype="https://schema.org/InteractionCounter">
                            <a href="javascript:void(0)" class="arrow_rating_top" data-id="<?= $poll->id; ?>"></a><br>
                            <span class="poll_rating" itemprop="userInteractionCount" data-id="<?= $poll->id; ?>"><?= $poll->rating; ?></span><br>
                            <span itemprop="interactionType" content="https://schema.org/LikeAction"></span>
                            <a href="javascript:void(0)" class="arrow_rating_down" data-id="<?= $poll->id; ?>"></a>
                        </div>
                        <div class="middle_title_b">
                            <div class="title_poll" itemprop="headline">
                                <h1><?= $poll->title; ?></h1>
                            </div>
                            <div class="desc_my_chart" itemprop="articleBody">
                                <?= $poll->getClearedDescribe() ?>
                            </div>
                            <?php $chartData = $poll->getChartData();?>
                            <?php $bar = StringHelper::formatForBar($chartData); ?>
                            <?php $pie = StringHelper::formatForPie($chartData); ?>
                            <?php if(!$poll->isShowResult()):?>
                                <div class="inner_block_chosen">
                                    <?= $this->render('/poll/options', ['poll' => $poll,'chartData'=>$chartData,'bar'=>$bar,'pie'=>$pie]); ?>
                                </div>
                            <?php endif;?>
                        </div>
                        <?php if($poll->isShowResult()):?>
                            <div class="clearfix"></div>
                            <div class="container_graph">
                                <?= $this->render('/poll/options', ['poll' => $poll,'chartData'=>$chartData,'bar'=>$bar,'pie'=>$pie]); ?>
                            </div>
                        <?php endif;?>
                    </div>
                    <div class="top_poll_b clearfix bottom_space_for_chart">
                        <?php foreach ($poll->tags as $pollTag) : ?>
                            <a href="<?= $pollTag->url ?>" class="link_poll">#<?= $pollTag->name; ?></a>
                        <?php endforeach; ?>

                        <span class="chosen_graph_b animated_b">
                            <span class="inner_chosen_graph">
                                <a href="javascript:void(0)" class="pie_chart" data-id="pie">
                                    <span class="pie_chart_img"></span>
                                    <span class="vertical_chart_img"></span>
                                    <span class="horizontal_chart_img"></span>
                                </a>
                                <a href="javascript:void(0)" class="horizontal_b_chart active" data-id="bar">
                                    <span class="pie_chart_img"></span>
                                    <span class="vertical_chart_img"></span>
                                    <span class="horizontal_chart_img"></span>
                                </a>
                                <a href="javascript:void(0)" class="vertical_b_chart" data-id="column">
                                    <span class="pie_chart_img"></span>
                                    <span class="vertical_chart_img"></span>
                                    <span class="horizontal_chart_img"></span>
                                </a>
                            </span>
                        </span>
                    </div>
                    <div class="show_voices_b">
                        <div class="title_show_voice clearfix">
                            <span class="toggle_b collapsed" data-toggle="collapse" data-target=".for_collapsing">
                                <?= Yii::t('poll', 'Показати голоси'); ?>
                                <i class="fa fa-angle-up"></i>
                            </span>
                            <span class="right_clear_btn">
                                <a href="javascript:void(0)" class="clear_btn">
                                    <i class="clear_btn_ico"></i>
                                    <span><?= Yii::t('poll', 'Очистити'); ?></span>
                                </a>
                            </span>
                        </div>
                        <div class="for_collapsing collapse">
                            <div class="select_blocks">
                                <span class="item_show">
                                    <?= Yii::t('poll', 'Стать'); ?><br>
                                    <select class="gender">
                                        <option value="0"><?= Yii::t('poll', 'Всі'); ?></option>
                                        <?php $sexes = User::getUserSexList();?>
                                        <?php foreach($sexes as $index=>$sex): ?>
                                            <option value="<?= $index; ?>"><?= $sex; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </span>
                                <span class="item_show">
                                    <?= Yii::t('poll', 'Вік'); ?><br>
                                    <select class="age">
                                        <option value="0"><?= Yii::t('poll', 'Всі'); ?></option>
                                        <?php $ages = User::getUserAgeIntervalList();?>
                                        <?php foreach($ages as $index=>$age): ?>
                                            <option value="<?= $index; ?>"><?= $age; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </span>
                                <span class="item_show">
                                    <?= Yii::t('poll', 'Країна'); ?><br>
                                    <select class="country">
                                        <option value="0"><?= Yii::t('poll', 'Всі'); ?></option>
                                        <?php foreach($poll->getVoteCountries() as $key => $country): ?>
                                            <option value="<?= $key; ?>"><?= $country; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </span>
                                <span class="item_show region">
                                    <?= Yii::t('poll', 'Регіон'); ?><br>
                                    <input type="text" id="region" style="display: none">
                                    <input type="text" class="autocomplete region" id="regionAC">
                                    <a href="#" class="del_btn"></a>
                                    <div class="autocomplete-suggestions" style="position: absolute; display: none; max-height: 300px; z-index: 9999;"></div>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bottom_poll_b clearfix">
                        <span class="item_bottom_poll author-block" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <?= Yii::t('poll', 'Користувач'); ?>:
                            <a href="<?= Url::toRoute(['/poll/site/user-profile','id'=>$poll->user_id ]);?>" itemprop="url">
                                <span itemprop="name"><?= $poll->author->getFullUserName();?></span>
                            </a>
                        </span>
                        <span class="item_bottom_poll"><?= Yii::t('poll', 'голосів'); ?>: <?= $poll->countPollOptionsVoters; ?></span>
                        <span class="item_bottom_poll" itemprop="datePublished" content="<?= $poll->date_add; ?>"><?= $poll->date_add; ?></span>
                        <?php if ($poll->isOpen()): ?>
                            <div class="right_poll_status open">
                                <?= Yii::t('poll', 'Відкрите голосування'); ?>
                                <span><i class="fa fa-unlock"></i></span>
                            </div>
                        <?php else: ?>
                            <div class="right_poll_status closed" title="<?= $poll->countPollOptionsVoters; ?>/<?= $poll->votes_count_close;?>">
                                <?= Yii::t('poll', 'Закрите голосування'); ?>
                                <span><i class="fa fa-lock"></i></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="comments_add-answer_b">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"> <!-- ТЕПЕР активна вкладка "Коментарі" -->
            <a href="#middle_comments_list_b" role="tab" data-toggle="tab">
                <?= Yii::t('poll', 'Коментарі'); ?>
                <span class="count_poll" itemprop="commentCount"><?= count($poll->pollComments); ?></span>
            </a>
        </li>
        <li>
            <a href="#inner_add-answer_b" role="tab" data-toggle="tab">
                <?= Yii::t('poll', 'Запропоновані відповіді'); ?>
                <span class="count_poll"><?= count($poll->pollAnswers); ?></span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="inner_add-answer_b" class="inner_add-answer_b tab-pane">
            <?= $this->render('/poll/answersBlock', ['answers' => $poll->pollAnswers]); ?>
        </div>
        <div id="middle_comments_list_b" class="middle_comments_list_b tab-pane active"> <!-- ТЕПЕР активний блок коментарів -->
            <?= $this->render('/poll/commentsBlock', ['comments' => $poll->pollCommentsRoot]); ?>
        </div>
    </div>
    <div class="comments_block_bottom">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#middle_text_input__comment_b" role="tab" data-toggle="tab"><?= Yii::t('poll', 'Додати коментар'); ?></a></li>
            <li><a href="#middle_text_input_b" role="tab" data-toggle="tab"><?= Yii::t('poll', 'Додати варіант відповіді'); ?></a></li>
        </ul>
        <div class="tab-content">
            <div id="middle_text_input_b" class="middle_text_input_b tab-pane">
                <form method="post" action="<?= Url::toRoute(['/poll/poll/add-answer']); ?>">
                    <?= yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken); ?>
                    <input name="Profile[answer][poll_id]" type="text" class="autocomplete" value="<?= $poll->id;?>" hidden>
                    <textarea id="answer_text" maxlength="60" name="Profile[answer][title]"><?= $answerModel->title;?></textarea>
                    <div class="count_symbols">
                        <?= Yii::t('poll', 'Залишилось'); ?>: <span id="textareaFeedback">60</span> <?= Yii::t('poll', 'символів'); ?>
                    </div>
                    <div class="bottom_btn_b">
                        <button type="submit" class="send_btn"><?= Yii::t('poll', 'Надіслати'); ?><i class="send_icon"></i></button>
                    </div>
                </form>
            </div>
            <div id="middle_text_input__comment_b" class="middle_text_input_b comments tab-pane active">
                <form method="post" action="<?= Url::toRoute(['/poll/poll/add-comment']); ?>">
                    <?= yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken); ?>
                    <input name="Profile[comment][poll_id]" type="text" class="autocomplete" value="<?= $poll->id;?>" hidden>
                    <input name="Profile[comment][parent_id]" type="text" class="autocomplete" value="<?= isset($commentModel->parent_id)?$commentModel->parent_id:'';?>" hidden>
                    <textarea name="Profile[comment][content]"><?= $commentModel->content;?></textarea>
                    <div class="bottom_btn_b">
                        <button type="submit" class="send_btn"><?= Yii::t('poll', 'Надіслати'); ?><i class="send_icon"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="info_block">
        <h2 itemprop="alternativeHeadline"><?= Yii::t('poll', 'Коротка статистика та результати опитування "{title}"', ['title' => $poll->title]); ?></h2>

        <p itemprop="text">
            <?= Yii::t('poll',
                'Це онлайн-опитування під назвою «{title}» було створено {date}. Наразі воно зібрало {votes} {votesWord} і {comments} {commentsWord}, відображаючи поточну громадську думку та результати голосування.',
                [
                    'title' => $poll->title,
                    'date' => date('d.m.Y', strtotime($poll->date_add)),
                    'votes' => $poll->countPollOptionsVoters,
                    'votesWord' => Yii::t('poll', 'голосів'),
                    'comments' => count($poll->pollComments),
                    'commentsWord' => Yii::t('poll', 'коментарів'),
                ]
            ); ?>
        </p>

        <?php if (!empty($poll->tags)): ?>
            <p><?= Yii::t('poll', 'Теги: {tags}', [
                    'tags' => implode(', ', array_map(function ($tag) {
                        return Html::encode($tag->name);
                    }, $poll->tags))
                ]); ?></p>
        <?php endif; ?>

        <?php
        $options = $poll->pollOptions ?? [];
        if (!empty($options)):
            $totalVotes = 0;
            $registeredVotes = 0;
            $guestVotes = 0;
            $mostPopular = null;
            $maxVotes = -1;
            foreach ($options as $opt) {
                $optionVotes = (int) $opt->optionVotesCount;
                $optionGuestVotes = (int) $opt->optionGuestVotesCount;
                $votes = $optionVotes + $optionGuestVotes;
                $totalVotes += $votes;
                $registeredVotes += $optionVotes;
                $guestVotes += $optionGuestVotes;
                if ($votes > $maxVotes) {
                    $maxVotes = $votes;
                    $mostPopular = $opt;
                }
            }
        ?>
            <p><?= Yii::t('poll', 'Проголосували: {reg} зареєстрованих користувачів і {guest} гостей.', [
                    'reg' => $registeredVotes,
                    'guest' => $guestVotes,
                ]); ?></p>
            <p><?= Yii::t('poll', 'В цьому публічному опитуванні та опитуванні громадської думки представлені такі варіанти відповідей:') ?></p>
            <ul class="poll-options-stats">
                <?php foreach ($options as $opt):
                    if ($mostPopular && $opt->id == $mostPopular->id) {
                        continue;
                    }
                    $votes = (int) ($opt->optionVotesCount + $opt->optionGuestVotesCount);
                    $percent = \common\models\PollOption::getPercentRating($totalVotes, $votes);
                ?>
                    <li itemprop="suggestedAnswer" itemscope itemtype="https://schema.org/Answer">
                        <span itemprop="text"><?= Html::encode($opt->title) ?></span>
                        <span class="dash">—</span>
                        <strong itemprop="upvoteCount"><?= $votes ?></strong> <?= Yii::t('poll', 'голосів') ?>
                        (<span itemprop="percentage"><?= number_format($percent, 1) ?>%</span>)
                    </li>
                <?php endforeach; ?>
                <?php if ($mostPopular):
                    $votes = (int) ($mostPopular->optionVotesCount + $mostPopular->optionGuestVotesCount);
                    $percent = \common\models\PollOption::getPercentRating($totalVotes, $votes);
                ?>
                    <li itemprop="suggestedAnswer" itemscope itemtype="https://schema.org/Answer" class="popular-option">
                        <span itemprop="text"><?= Html::encode($mostPopular->title) ?></span>
                        <span class="dash">—</span>
                        <strong itemprop="upvoteCount"><?= $votes ?></strong> <?= Yii::t('poll', 'голосів') ?>
                        (<span itemprop="percentage"><?= number_format($percent, 1) ?>%</span>)
                        <span class="dash">—</span>
                        <span class="popular-text"><?= Yii::t('poll', 'Цей варіант вибрали найбільше') ?></span>
                    </li>
                <?php endif; ?>
            </ul>
        <?php else: ?>
            <p class="muted"><?= Yii::t('poll', 'У цьому опитуванні ще немає варіантів відповіді.'); ?></p>
        <?php endif; ?>
    </div>
</div><!-- end .comments_add-answer_b -->
                </div><!-- end .poll_block -->
            </div>
        </div>
        <?php if(0 && Yii::$app->user->isGuest){ ?>
            <a data-target="#registrtion_step_1" data-toggle="modal" href="#" class="item_list_poll" style="text-align:center;padding-left:70px;padding-right:70px;">
                <?= Yii::t('poll', 'Для того щоб прийняти участь в опитуванні, або побачити результати необхідно зареєструватись.'); ?>
            </a>
        <?php } ?>
        <?php if($poll->isShowResult() && !$poll->isVoted()){?>
            <?php /* Можна додати повідомлення для користувача, якщо треба */ ?>
        <?php } ?>
    </div>
</div>

<script>
    $(function()
    {
        var maxLength = $('#answer_text').attr('maxlength');        
        $('#answer_text').keyup(function()
        {
            var curLength = $('#answer_text').val().length;         
            $(this).val($(this).val().substr(0, maxLength));     
            var remaning = maxLength - curLength;
            if (remaning < 0) remaning = 0;
            $('#textareaFeedback').html(remaning);
            if (remaning < 10)
            {
                $('#textareaFeedback').addClass('warning')
            }
            else
            {
                $('#textareaFeedback').removeClass('warning')
            }
        })
    })

    $(function() {
        <?php if(isset($error) && $error):?>
            alert(<?= strip_tags($error);?>);
        <?php endif;?>
    });

    $(document).on('change','.gender, .age, .country',function(){
        filterDataChart(<?= $poll->id;?>,'<?= $poll->title;?>');
    });

    $(document).on('click','.clear_btn',function(){
        clearChartFilters(<?= $poll->id;?>,'<?= $poll->title;?>');
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
        filterDataChart(<?= $poll->id;?>,'<?= $poll->title;?>');
    });

    $(document).on('click','.pie_chart,.horizontal_b_chart,.vertical_b_chart',function(){
        $('#container<?= $poll->id; ?>').removeClass('pie').removeClass('bar').removeClass('column');
        $('#container<?= $poll->id; ?>').addClass($(this).attr('data-id'));
        filterDataChart(<?= $poll->id;?>,'<?= $poll->title;?>');
    });
</script>
