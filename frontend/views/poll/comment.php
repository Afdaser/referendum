<div class="title_comment_item_b clearfix">
    <a href="<?php echo Yii::app()->createUrl('/site/userProfile',array('id'=>$comment->user_id));?>" class="name_link"><?php echo User::getUserName($comment->user_id); ?></a>
    <span class="date_writing"><?php echo StringHelper::relative_date($comment->date_add); ?></span>
    <span class="right_b_rat">
        <a href="javascript:void(0)" class="rating_btn_up" data-id="<?php echo $comment->id; ?>"></a>
        <span class="rating" data-id="<?php echo $comment->id; ?>">
            <?php echo $comment->rating; ?>
        </span>
        <a href="javascript:void(0)" class="rating_btn_down" data-id="<?php echo $comment->id; ?>"></a>
    </span>
</div>
<div class="inner_text_comment">
    <?php echo $comment->content; ?>
</div>
<?php /* OLD: echo Yii::app()->createUrl('/poll/view',array('id'=>$comment->poll_id,'reply'=>$comment->id)); /* */ ?>
<a href="<?php echo Yii::app()->createUrl("/poll/{$comment->poll_id}?reply={$comment->id}"); ?>" class="share_link">
    <i class="fa fa-share"></i>
    <?php echo Yii::t("poll", 'Відповісти'); ?>
</a>

<?php if(count($comment->commentChilds)):?>
    <div class="review_comment">
        <?php $this->renderPartial('/poll/comments',array('comments'=>$comment->commentChilds)); ?>
    </div>
<?php endif;?>

