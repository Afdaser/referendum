<?php
/* @var $this PollsSidebar */
?>
<div class="list_poll_b">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#list_polls_new" role="tab" data-toggle="tab"><?php echo Yii::t("poll", 'Найновіше'); ?></a></li>
        <li><a href="#list_polls_popular" role="tab" data-toggle="tab"><?php echo Yii::t("poll", 'Популярне'); ?></a></li>
    </ul>
    <div class="tab-content">
        <div id="list_polls_new" class="list_polls tab-pane active">
            <?php foreach ($pollsLast as $poll): ?>
                <a href="<?php echo $poll->url; ?>" class="item_list_poll">
                    <?php echo $poll->title; ?>
                    <div class="bottom_item_list_poll">
                        <span class="icon_lock <?php echo ($poll->isOpen() ? "" : "closed"); ?>">
                            <i class="fa <?php echo ($poll->isOpen() ? "fa-unlock" : "fa-lock"); ?>"></i>
                        </span>
                        <?php echo Yii::t("poll", 'голосів'); ?>:  <?= $poll->countPollOptionsVoters; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div id="list_polls_popular" class="list_polls tab-pane">
            <?php foreach ($pollsPopular as $poll): ?>
                <a href="<?php echo $poll->url; ?>" class="item_list_poll">
                    <?php echo $poll->title; ?>
                    <div class="bottom_item_list_poll">
                        <span class="icon_lock <?php echo ($poll->isOpen() ? "" : "closed"); ?>">
                            <i class="fa <?php echo ($poll->isOpen() ? "fa-unlock" : "fa-lock"); ?>"></i>
                        </span>
                        <?php echo Yii::t("poll", 'голосів'); ?>:  <?= $poll->countPollOptionsVoters; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
