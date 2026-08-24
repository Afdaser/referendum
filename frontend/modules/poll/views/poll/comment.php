<?php

use yii\helpers\Url;
use common\helpers\StringHelper;
use common\models\User;

/*
 * Yii1:
 * Url::toRoute(['/poll/site/user-profile', 'id'=>$comment->user_id] );
 *
 * /Profile
 */
?>
<div class="title_comment_item_b clearfix" itemprop="author" itemtype="https://schema.org/Person" itemscope>
    <?php if ($comment->isGuestComment): ?>
        <?php // Для гостей не будуємо посилання на профіль, бо акаунта не існує. ?>
        <span class="name_link" itemprop="name"><?= $comment->displayAuthorName; ?></span>
        <span class="comment-author-badge"><?= Yii::t('poll', 'незареєстрований'); ?></span>
    <?php else: ?>
        <a href="<?= Url::toRoute(['/poll/site/user-profile', 'id' => $comment->user_id]); ?>" class="name_link" itemprop="url"><span itemprop="name"><?= $comment->displayAuthorName; ?></span></a>
    <?php endif; ?>
    <span class="date_writing"><?= StringHelper::relative_date($comment->date_add); ?></span>
    <span class="right_b_rat" itemprop="userInteractionCount">
        <?php // Радіокнопки не створюють посилань, за якими міг би переходити пошуковий бот. ?>
        <input type="radio" class="comment-rating-input" id="comment-rating-up-<?= $comment->id; ?>" name="comment-rating-<?= $comment->id; ?>" value="1" data-id="<?= $comment->id; ?>">
        <label for="comment-rating-up-<?= $comment->id; ?>" class="rating_btn_up" aria-label="<?= Yii::t('poll', 'Підняти рейтинг коментаря'); ?>"></label>
        <span class="rating" data-id="<?= $comment->id; ?>">
            <?= $comment->rating; ?>
        </span>
        <input type="radio" class="comment-rating-input" id="comment-rating-down-<?= $comment->id; ?>" name="comment-rating-<?= $comment->id; ?>" value="-1" data-id="<?= $comment->id; ?>">
        <label for="comment-rating-down-<?= $comment->id; ?>" class="rating_btn_down" aria-label="<?= Yii::t('poll', 'Знизити рейтинг коментаря'); ?>"></label>
    </span>
</div>
<div class="inner_text_comment" itemprop="text">
    <?= $comment->content; ?>
</div>
<?php /* OLD: echo Yii::app()->createUrl('/poll/view',array('id'=>$comment->poll_id)) . '#reply-' . $comment->id; /* */ ?>
<?php // Формуємо URL з hash-фрагментом для відповіді, щоб не використовувати query-параметр reply. ?>
<a href="<?= Url::toRoute(['/poll/poll/view', 'id' => $comment->poll_id]); ?>#reply-<?= $comment->id; ?>" class="share_link">
    <i class="fa fa-share"></i>
    <?= Yii::t('poll', 'Відповісти'); ?>
</a>

<?php if (count($comment->commentChilds)) : ?>
    <div class="review_comment">
        <?= $this->render('/poll/comments', ['comments' => $comment->commentChilds]); ?>
    </div>
<?php endif; ?>
