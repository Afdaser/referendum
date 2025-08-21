<div class="new_comments_b tab-pane" id="new_comments" style="display: block">
    <div class="sort_b clearfix">
        <span class="right_btn_b">
            <a href="javascript:void(0)" class="my_profile clear_answers clearAllComments" data-toggle="modal" data-target="#new_poll"><?= Yii::t('poll', 'Очистити'); ?></a>
        </span>
    </div>
    <div class="bottom_content_tabs">
        <?php foreach($polls as $poll):?>
            <div class="item_answer poll<?php echo $poll->id;?>">
                <div class="top_poll_b_answers clearfix">
                    <span class="item_bottom_poll">
                        <?php if($poll->isOpen()):?>
                            <span class="green_font"><i class="fa fa-unlock"></i></span>
                        <?php else:?>
                            <span class="red_font"><i class="fa fa-lock"></i></span>
                        <?php endif;?>
                    </span>
                    <span class="item_bottom_poll">
                        <span class="right_b_rat">
                            <a href="javascript:void(0)" class="rating_btn_up" data-id="<?php echo $poll->id; ?>"></a>
							<span class="poll_rating" data-id="<?php echo $poll->id; ?>"><?php echo $poll->rating; ?></span>
							<a href="javascript:void(0)" class="rating_btn_down" data-id="<?php echo $poll->id; ?>"></a>
                        </span>
                    </span>
                    <span class="item_bottom_poll right_border_del"><?= Yii::t('poll', 'голосів'); ?>: <?= $poll->countPollOptionsVoters; ?></span>
                    <a href="javascript:void(0)" class="close_item_answer clearComments" data-id="<?php echo $poll->id;?>"></a>
                </div>
                <div class="title_name_comment">
                    <a href="<?= $poll->url ?>"><?= $poll->title; ?></a>
                </div>
                <div class="panel-group" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-collapse collapse comments<?php echo $poll->id;?>" role="tabpanel">
                            <div class="panel-body">
                                <?php $this->renderPartial('//poll/commentsBlock', array('comments' => $poll->pollCommentsRoot(array('condition'=>'has_new = 1')),'isNew'=>true)); ?>
                            </div>
                        </div>
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" href=".comments<?php echo $poll->id;?>" aria-expanded="true" aria-controls="collapseOne">
                                    <?= Yii::t('poll', 'Нові коментарі'); ?> (<?php echo count($poll->pollComments(array('condition'=>'is_new='.PollComment::NEW_COMMENT)));?>)
                                    <span class="right_caret">
                                        <i class="fa fa-angle-up"></i>
                                     </span>
                                </a>
                            </h4>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a href="<?= $poll->url ?>">
                                    <?= Yii::t('poll', 'Переглянути всі коментарі'); ?> (<?php echo count($poll->pollComments); ?>)
                                    <span class="right_caret">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                </a>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider_answer poll<?php echo $poll->id;?>"></div>
        <?php endforeach;?>
    </div>
</div>
