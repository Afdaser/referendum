<?php foreach($comments as $comment) : ?>
    <?php if(isset($isNew)) : ?>
        <?= $this->render('/poll/profile_comment', ['comment' => $comment, 'isNew'=>true]); ?>
    <?php else: ?>
        <?= $this->render('/poll/comment', ['comment' => $comment]); ?>
    <?php endif; ?>
<?php endforeach; ?>