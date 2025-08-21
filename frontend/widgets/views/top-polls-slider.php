<?php /** @var $activePolls Poll[] */ ?>
<div class="container">
    <div class="row">
        <div class="slider_top_b">
            <div class="slider_controls">
                <a class="slider_next" href="#"><?php echo Yii::t("poll", 'Next'); ?></a>
                <a class="slider_prev" href="#"><?php echo Yii::t("poll", 'Prev'); ?></a>
            </div>
            <div class="slider">
                <?php foreach ($activePolls as $poll): ?>
                    <div class="slide">
                        <a href="<?php echo $poll->url; ?>" class="inner_slide_b">
                            <div class="item_normal">
                                <?php echo $poll->title; ?>
                            </div>
                            <div class="item_hover clearfix">
                                <div class="left_info_b">
                                    <span class="lock_item"></span>
                                    <span class="rating_text_left_info">
                                        <span class="caret_top"></span>
                                        <?php echo $poll->rating; ?>
                                        <span class="caret_down"></span>
                                    </span>
                                </div>
                                <div class="middle_text_info">
                                    <?php echo Yii::t("poll", 'коментарів'); ?>: <?php echo count($poll->pollComments); ?><br>
                                    <?php echo Yii::t("poll", 'голосів'); ?>: <?php echo count($poll->pollOptionsVoters); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
