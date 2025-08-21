<?php foreach($polls as $poll):?>
    <a href="#" class="item_list_poll">
        <?php echo $poll->title; ?>
        <div class="bottom_item_list_poll">
                            <span class="icon_lock <?php if(!$poll->isOpen()): ?>closed<?php endif;?>">
                                <?php if($poll->isOpen()): ?>
                                    <i class="fa fa-unlock"></i>
                                <?php else: ?>
                                    <i class="fa fa-lock"></i>
                                <?php endif;?>
                            </span>
        <?php echo Yii::t("poll", 'голосів'); ?>: <?= $poll->countPollOptionsVoters; ?>
        </div>
    </a>
<?php endforeach;?>