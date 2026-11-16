<?php /** @var $activePolls Poll[] */ ?>
<div class="container">
    <div class="row">
        <div class="slider_top_b">
            <div class="slider_controls">
                <a class="slider_next" href="#"><?= Yii::t('poll', 'Next') ?></a>
                <a class="slider_prev" href="#"><?= Yii::t('poll', 'Prev') ?></a>
            </div>
            <div class="slider">
<?php foreach ($activePolls as $poll): ?>
                <div class="slide">
                    <div class="inner_slide_b">
                        <div class="item_normal">
                            <a class="poll_title_link" href="<?= $poll->url ?>"><?= $poll->title ?></a>
                        </div>
                        <div class="item_hover clearfix">
                            <div class="left_info_b">
                                <span class="lock_item"></span>
                                <span class="rating_text_left_info"><span class="caret_top"></span><?= $poll->rating ?><span class="caret_down"></span></span>
                            </div>
                            <div class="middle_text_info"><?= Yii::t('poll', 'коментарів') ?>: <?= count($poll->pollComments) ?><br><?= Yii::t('poll', 'голосів') ?>: <?= $poll->countPollOptionsVoters ?></div>
                        </div>
                    </div>
                </div>
<?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
