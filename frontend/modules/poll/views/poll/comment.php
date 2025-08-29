<?php

use yii\helpers\Url;
use common\helpers\StringHelper;
use common\models\User;

/*
 * Yii1:
 * Url::toRoute(['/site/userProfile', 'id'=>$comment->user_id] );
 * 
 * /site/userProfile
 */
?>
<div class="title_comment_item_b clearfix" itemprop="author" itemtype="https://schema.org/Person" itemscope>
    <a href="<?= Url::toRoute(['/poll/site/user-profile', 'id' => $comment->user_id]); ?>" class="name_link" itemprop="url"><span itemprop="name"><?= User::getUserName($comment->user_id); ?></span></a>
    <?php $commentPublishedAt = new \DateTime($comment->date_add, new \DateTimeZone('America/Chicago')); ?>
    <span class="date_writing" itemprop="datePublished" content="<?= $commentPublishedAt->format(DATE_ATOM); ?>"><?= StringHelper::relative_date($comment->date_add); ?></span>
    <span class="right_b_rat" itemprop="userInteractionCount">
        <a href="javascript:void(0)" class="rating_btn_up" data-id="<?= $comment->id; ?>"></a>
        <span class="rating" data-id="<?= $comment->id; ?>">
            <?= $comment->rating; ?>
        </span>
        <a href="javascript:void(0)" class="rating_btn_down" data-id="<?= $comment->id; ?>"></a>
    </span>
</div>
<div class="inner_text_comment" itemprop="text">
    <?= $comment->content; ?>
</div>
<?php /* OLD: echo Yii::app()->createUrl('/poll/view',array('id'=>$comment->poll_id,'reply'=>$comment->id)); /* */ ?>
<a href="<?= Url::toRoute(['/poll/poll/view', 'id' => $comment->poll_id, 'reply' => $comment->id]); ?>" class="share_link">
    <i class="fa fa-share"></i>
    <?= Yii::t('poll', 'Відповісти'); ?>
</a>

<?php if (count($comment->commentChilds)) : ?>
    <div class="review_comment">
        <?= $this->render('/poll/comments', ['comments' => $comment->commentChilds]); ?>
    </div>
<?php endif; ?>

