<div class="tab-pane" id="new_answers" style="display: block">
    <div class="sort_b clearfix">
        <span class="right_btn_b">
            <a href="javascript:void(0)" class="my_profile clear_answers clearAllAnswers" data-toggle="modal" data-target="#new_poll"><?= Yii::t('poll', 'Очистити'); ?></a>
        </span>
    </div>
    <div class="bottom_content_tabs">
        <?php foreach($comments as $comment):?>
            <div class="item_answer comment<?php echo $comment->id;?>">
                <div class="comment_item">
                    <?php $this->renderPartial('/poll/profile_comment',array('comment'=>$comment,'isNew'=>false)); ?>
                </div>
                <div class="panel-group" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-collapse collapse comments<?php echo $comment->id;?>" role="tabpanel">
                            <div class="panel-body">
                                <?php if($childs = $comment->commentChilds(array('condition'=>'read_by_parent = 0'))):?>
                                    <?php foreach($childs as $child):?>
                                        <?php $this->renderPartial('/poll/profile_comment',array('comment'=>$child)); ?>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" href=".comments<?php echo $comment->id;?>" aria-expanded="true" aria-controls="collapseOne">
                                    <?= Yii::t('poll', 'Нові відповіді'); ?> (<?php echo count($comment->commentChilds(array('condition'=>'read_by_parent = 0')));?>)
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
                                    <?= Yii::t('poll', 'Переглянути всі коментарі'); ?> (<?php echo count($comment->poll->pollComments); ?>)
                                    <span class="right_caret">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                </a>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider_answer comment<?php echo $comment->id;?>"></div>
        <?php endforeach;?>
    </div>
</div>