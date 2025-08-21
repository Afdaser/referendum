<?php

use yii\helpers\Url;
use common\helpers\StringHelper;
use common\models\User;

?>

<div class="bottom_content_tabs">
    <?php foreach($polls as $index=>$poll): ?>
        <?php if(($index == 2) || ($index == 4)):?>
            <div class="inner_banner_b">
                <img src="/img/banner_inner_blocks.png" alt="">
            </div>
        <?php endif;?>
        <div class="poll_block">
            <div class="top_poll_b clearfix">
                <?php foreach($poll->tags as $pollTag) :?>
                    <a href="<?= Url::toRoute(['/poll/search/tag', 'tag' => $pollTag->name ]); ?>" class="link_poll">#<?= $pollTag->name; ?></a>
                <?php endforeach; ?>

                <span class="right_block_share_icon">
                    <?= $this->render('//site/polls/_shareSocial',array('poll'=>$poll));?>
                </span>

                <?php if($poll->editable):?>
                    <a data-target="#new_poll<?= $poll->id?>" data-toggle="modal" class="update_btn" href="#"></a>
                <?php endif;?>
            </div>
            <div class="middle_name_poll_b clearfix">
                <div class="left_rating_b">
                    <a href="javascript:void(0)" class="arrow_rating_top" data-id="<?= $poll->id; ?>"></a><br>
                    <span class="poll_rating" data-id="<?= $poll->id; ?>"><?= $poll->rating; ?></span><br>
                    <a href="javascript:void(0)" class="arrow_rating_down" data-id="<?= $poll->id; ?>"></a>
                </div>
                <div class="middle_title_b">
                    <div class="title_poll">
                        <a href="<?= $poll->url ?>"><?= $poll->title; ?></a>
                    </div>
                    <!-- h3>#DEV1</h3 -->
                    <?php $chartData = $poll->getChartData();?>
                    <?php $bar = StringHelper::formatForBar($chartData); ?>
                    <?php $pie = StringHelper::formatForPie($chartData); ?>
                    <?php if(!$poll->isShowResult()):?>
                        <div class="inner_block_chosen">
                            <?= $this->render('//poll/options', ['poll' => $poll, 'chartData'=>$chartData, 'bar'=>$bar, 'pie'=>$pie]); ?>
                        </div>
                    <?php /* TODO REmove debug * / else: ?>
                        <div class="inner_block_chosen">
                            $poll->isShowResult():
                            <?= $poll->isShowResult(); ?>
                        </div>
                    <?php /* */ ?>
                    <?php endif; ?>
                </div>
                <?php if($poll->isShowResult()):?>
                    <div class="clearfix"></div>
                    <div class="container_graph">
                        <?= $this->render('//poll/options', ['poll' => $poll, 'chartData'=>$chartData, 'bar'=>$bar, 'pie'=>$pie]); ?>
                    </div>
                <?php endif;?>
            </div>
            <div class="bottom_poll_b clearfix">
                <span class="item_bottom_poll"><?= Yii::t('poll', 'Користувач'); ?>: <?= User::getUserName($poll->user_id); ?></span>
                <span class="item_bottom_poll"><?= Yii::t('poll', 'коментарів'); ?>: <?= count($poll->pollComments); ?></span>
                <span class="item_bottom_poll"><?= Yii::t('poll', 'голосів'); ?>: <?= $poll->countPollOptionsVoters; ?><?= (YII_ENV == 'dev') ? '[#DEV2404b]' : ''; ?></span>
               <!--<?php if($poll->isOpen()): ?>
                    <div class="right_poll_status open">
                        <?= Yii::t('poll', 'Відкрите голосування'); ?>
                        <span><i class="fa fa-unlock"></i></span>
                    </div>
                <?php else: ?>
                    <div class="right_poll_status closed" title="<?= $poll->countPollOptionsVoters; ?>/<?= $poll->votes_count_close;?>">
                        <?= Yii::t('poll', 'Закрите голосування'); ?>
                        <span><i class="fa fa-lock"></i></span>
                    </div>
                <?php endif;?> 
                Це функціонал закритих і відкритих голосувань.
                -->
            </div>
        </div>
    <?php endforeach?>
</div>

<?php foreach($polls as $poll): ?>
    <?php if($poll->editable):?>
        <?php $this->render('//poll/_newPoll', array('model' => $poll)); ?>
    <?php endif;?>
<?php endforeach?>