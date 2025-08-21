<div class="middle_comments_list_b">
    <?php foreach ($comments as $comment): ?>
        <div class="comment_item">
            <?php if (isset($isNew)): ?>
                <?php // $this->render('//poll/profile_comment',array('comment'=>$comment,'isNew'=>true)); ?>
                <?= $this->render('_poll_profile_comment', ['comment' => $comment, 'isNew' => true]); ?>
            <?php else: ?>
                <?php // $this->render('//poll/comment',array('comment'=>$comment)); ?>
                <?= $this->render('_poll_comment', ['comment' => $comment]); ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>