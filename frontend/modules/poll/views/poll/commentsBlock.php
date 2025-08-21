<div class="middle_comments_list_b">
    <?php foreach ($comments as $comment): ?>
        <div class="comment_item" itemprop="comment" itemtype="https://schema.org/Comment" itemscope>
            <?php if (isset($isNew)): ?>
                <?= $this->render('/poll/profile_comment', ['comment' => $comment, 'isNew' => true]); ?>
            <?php else: ?>
                <?= $this->render('/poll/comment', ['comment' => $comment]); ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>