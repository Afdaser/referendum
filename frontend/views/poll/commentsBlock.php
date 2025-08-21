<div class="middle_comments_list_b">
    <?php foreach($comments as $comment):?>
        <div class="comment_item">
            <?php if(isset($isNew)):?>
                <?php $this->renderPartial('/poll/profile_comment',array('comment'=>$comment,'isNew'=>true)); ?>
            <?php else:?>
                <?php $this->renderPartial('/poll/comment',array('comment'=>$comment)); ?>
            <?php endif;?>
        </div>
    <?php endforeach;?>
</div>