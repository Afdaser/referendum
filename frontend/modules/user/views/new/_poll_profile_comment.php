<?php

use yii\helpers\Url;
use common\helpers\StringHelper;
use common\models\User;

/*
  <div class="title_comment_item_b clearfix">
  <a href="<?= Url::toRoute(['/poll/site/user-profile', 'id'=>$comment->user_id] );?>" class="name_link"><?= User::getUserName($comment->user_id); ?></a>
  <span class="date_writing"><?= StringHelper::relative_date($comment->date_add); ?></span>

  /* */
?>
<div class="title_comment_item_b clearfix">
    <a href="<?= Url::toRoute(['/poll/site/user-profile', 'id' => $comment->user_id]); ?>" class="name_link"><?= User::getUserName($comment->user_id); ?></a>
    <span class="date_writing"><?= StringHelper::relative_date($comment->date_add); ?></span>
    <?php if (isset($isNew)): ?>
        <?php if (!$isNew): ?>
            <a href="javascript:void(0)" class="close_item_answer clearAnswers" data-id="<?= $comment->id; ?>"></a>
        <?php endif; ?>
    <?php endif; ?>
</div>
<div class="inner_text_comment">
    <?php echo $comment->content; ?>
</div>

<?php if (isset($isNew)): ?>
    <?php if (!$isNew): ?>
        <a href="<?= Url::to(['/poll/poll/view', 'id' => $comment->poll_id, 'reply' => $comment->id]); ?>" class="share_link">
            <i class="fa fa-angle-right"></i>
            <?php echo Yii::t("poll", 'Відповісти'); ?>
        </a>
    <?php endif; ?>

    <?php if ($isNew): ?>
        <?php if (count($comment->commentChilds(['has_new' => 1]))): ?>
            <div class="review_comment">
                <?php $this->renderPartial('/poll/comments', array('comments' => $comment->commentChilds(['has_new' => 1]), 'isNew' => isset($isNew) ? true : false)); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
