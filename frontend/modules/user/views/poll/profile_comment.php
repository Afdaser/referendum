<div class="title_comment_item_b clearfix">
    <a href="<?php echo Yii::app()->createUrl('/site/userProfile',array('id'=>$comment->user_id));?>" class="name_link"><?php echo User::getUserName($comment->user_id); ?></a>
    <span class="date_writing"><?php echo StringHelper::relative_date($comment->date_add); ?></span>
    <?php if(isset($isNew)):?>
        <?php if(!$isNew):?>
            <a href="javascript:void(0)" class="close_item_answer clearAnswers" data-id="<?php echo $comment->id;?>"></a>
        <?php endif;?>
    <?php endif;?>
</div>
<div class="inner_text_comment">
    <?php echo $comment->content; ?>
</div>

<?php if(isset($isNew)):?>
    <?php if(!$isNew):?>
        <a href="<?php echo Yii::app()->createUrl('/poll/view',array('id'=>$comment->poll_id,'reply'=>$comment->id)); ?>" class="share_link">
            <i class="fa fa-angle-right"></i>
            <?php echo Yii::t("poll", 'Відповісти'); ?>
        </a>
    <?php endif;?>

    <?php if($isNew):?>
        <?php if(count($comment->commentChilds(array('condition'=>'has_new = 1')))):?>
            <div class="review_comment">
                <?php $this->renderPartial('/poll/comments',array('comments'=>$comment->commentChilds(array('condition'=>'has_new = 1')),'isNew'=>isset($isNew)?true:false)); ?>
            </div>
        <?php endif;?>
    <?php endif;?>
<?php endif;?>
