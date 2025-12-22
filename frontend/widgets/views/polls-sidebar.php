<?php
/* @var $this PollsSidebar */

use yii\helpers\Html;
?>
<div class="list_poll_b">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#list_polls_new" role="tab" data-toggle="tab"><?php echo Yii::t("poll", 'Найновіше'); ?></a></li>
        <li><a href="#list_polls_popular" role="tab" data-toggle="tab"><?php echo Yii::t("poll", 'Популярне'); ?></a></li>
    </ul>
    <div class="tab-content">
        <div id="list_polls_new" class="list_polls tab-pane active">
            <?php foreach ($pollsLast as $poll): ?>
                <div class="item_list_poll">
                    <a href="<?php echo $poll->url; ?>" class="item_list_poll__title">
                        <?php echo $poll->title; ?>
                    </a>
                    <?php $tags = $poll->tags; // Відображаємо теми опитування у вигляді тегів. ?>
                    <?php if (!empty($tags)): ?>
                        <div class="item_list_poll__tags">
                            <span class="item_list_poll__tags-label" aria-hidden="true">
                                <i class="fa fa-tags"></i>
                            </span>
                            <?php foreach ($tags as $tag): ?>
                                <a href="<?php echo $tag->url; ?>" class="item_list_poll__tag">
                                    <?php echo Html::encode($tag->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="bottom_item_list_poll">
                        <div class="bottom_item_list_poll__meta">
                            <span class="icon_lock <?php echo ($poll->isOpen() ? "" : "closed"); ?>">
                                <i class="fa <?php echo ($poll->isOpen() ? "fa-unlock" : "fa-lock"); ?>"></i>
                            </span>
                            <?php echo Yii::t("poll", 'голосів'); ?>: <?= $poll->countPollOptionsVoters; ?>
                        </div>
                        <div class="bottom_item_list_poll__meta bottom_item_list_poll__comments">
                            <i class="fa fa-comments"></i>
                            <?php echo Yii::t("poll", 'коментарів'); ?>: <?= $poll->commentsCount; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="list_polls_popular" class="list_polls tab-pane">
            <?php foreach ($pollsPopular as $poll): ?>
                <div class="item_list_poll">
                    <a href="<?php echo $poll->url; ?>" class="item_list_poll__title">
                        <?php echo $poll->title; ?>
                    </a>
                    <?php $tags = $poll->tags; // Відображаємо теми опитування у вигляді тегів. ?>
                    <?php if (!empty($tags)): ?>
                        <div class="item_list_poll__tags">
                            <span class="item_list_poll__tags-label" aria-hidden="true">
                                <i class="fa fa-tags"></i>
                            </span>
                            <?php foreach ($tags as $tag): ?>
                                <a href="<?php echo $tag->url; ?>" class="item_list_poll__tag">
                                    <?php echo Html::encode($tag->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="bottom_item_list_poll">
                        <div class="bottom_item_list_poll__meta">
                            <span class="icon_lock <?php echo ($poll->isOpen() ? "" : "closed"); ?>">
                                <i class="fa <?php echo ($poll->isOpen() ? "fa-unlock" : "fa-lock"); ?>"></i>
                            </span>
                            <?php echo Yii::t("poll", 'голосів'); ?>: <?= $poll->countPollOptionsVoters; ?>
                        </div>
                        <div class="bottom_item_list_poll__meta bottom_item_list_poll__comments">
                            <i class="fa fa-comments"></i>
                            <?php echo Yii::t("poll", 'коментарів'); ?>: <?= $poll->commentsCount; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
