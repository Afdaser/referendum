<?php foreach($polls as $poll):?>
    <div class="item_list_poll">
        <a href="#" class="item_list_poll__title">
            <?php echo $poll->title; ?>
        </a>
        <div class="bottom_item_list_poll">
            <div class="bottom_item_list_poll__meta">
                <span class="icon_lock <?php if(!$poll->isOpen()): ?>closed<?php endif;?>">
                    <?php if($poll->isOpen()): ?>
                        <i class="fa fa-unlock"></i>
                    <?php else: ?>
                        <i class="fa fa-lock"></i>
                    <?php endif;?>
                </span>
                <?php echo Yii::t("poll", 'голосів'); ?>: <?= $poll->countPollOptionsVoters; ?>
            </div>
            <div class="bottom_item_list_poll__meta bottom_item_list_poll__comments">
                <i class="fa fa-comments"></i>
                <?php echo Yii::t("poll", 'коментарів'); ?>: <?= $poll->commentsCount; ?>
            </div>
        </div>
    </div>
<?php endforeach;?>
