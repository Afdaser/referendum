<?php

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
                    <a href="<?= $pollTag->url ?>" class="link_poll">#<?= $pollTag->name; ?></a>
                <?php endforeach; ?>

                <span class="right_block_share_icon">
                    <?= $this->render('//site/polls/_shareSocial',array('poll'=>$poll));?>
                </span>

                <?php if($poll->editable):?>
                    <a data-target="#new_poll<?= $poll->id?>" data-toggle="modal" class="update_btn" href="#"></a>
                <?php endif;?>
            </div>
            <?php // Готуємо дані для графіків, щоб їх можна було використати нижче. ?>
            <?php $chartData = $poll->getChartData();?>
            <?php $bar = StringHelper::formatForBar($chartData); ?>
            <?php $pie = StringHelper::formatForPie($chartData); ?>
            <div class="poll_heading_b clearfix">
                <div class="left_rating_b">
                    <?php // Кнопки рейтингу лишають класи та data-id для наявних jQuery-обробників. ?>
                    <button type="button" class="arrow_rating_top" data-id="<?= $poll->id; ?>" aria-label="<?= Yii::t('poll', 'Підняти рейтинг опитування'); ?>"></button><br>
                    <span class="poll_rating" data-id="<?= $poll->id; ?>"><?= $poll->rating; ?></span><br>
                    <button type="button" class="arrow_rating_down" data-id="<?= $poll->id; ?>" aria-label="<?= Yii::t('poll', 'Знизити рейтинг опитування'); ?>"></button>
                </div>
                <div class="middle_title_b">
                    <div class="title_poll">
                        <a href="<?= $poll->url ?>"><?= $poll->title; ?></a>
                    </div>
                </div>
            </div>
            <div class="middle_name_poll_b clearfix">
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
            </div>
        </div>
    <?php endforeach?>
</div>

<?php foreach($polls as $poll): ?>
    <?php if($poll->editable):?>
        <?php $this->render('//poll/_newPoll', array('model' => $poll)); ?>
    <?php endif;?>
<?php endforeach?>
