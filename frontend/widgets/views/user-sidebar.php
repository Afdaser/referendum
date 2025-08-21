<?php

use yii\bootstrap\Html;
use frontend\helpers\Url;
//use common\helpers\StringHelper;
use common\models\User;
use common\models\Poll;
//use common\models\Country;
//use common\models\Language;

/*
 * // Yii1 remove:
    <div class="left_auth_name"><?php echo User::getUserName(Yii::app()->user->id); ?></div>
    <a href="<?php echo Yii::app()->createUrl('/Ulogin/logout/');?>" class="right_leave_btn">
    </a> 
 */

?>
<div class="title_auth">
    <div class="left_auth_name"><?= User::getUserName(Yii::$app->user->identity->id); ?></div>
    <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline']); ?>
    <?= Html::submitButton('', ['class' => 'btn btn-link right_leave_btn'] ); ?>
    <?= Html::endForm() ?>
</div>
<div class="inner_auth_b">
    <div class="text_authorized_b">
<?php /* * // Yii1 remove:
        <?php echo Yii::t("user", 'Мій рейтинг'); ?>: <?php echo User::getUserRating(Yii::app()->user->id);?><br>
        <?php echo Yii::t("user", 'Мої опитування'); ?>: <a href="<?php echo Yii::app()->createUrl('/site/myPolls/')?>"><?php echo User::getPollsCount(Yii::app()->user->id);?></a><br>
        <?php echo Yii::t("user", 'Нові коментарі'); ?>: <a href="<?php echo Yii::app()->createUrl('/user/newComments/')?>"><?php echo Poll::getNewCommentsCount(Yii::app()->user->id);?></a>
 */
?>
        <?php echo Yii::t("user", 'Мій рейтинг'); ?>: <?= User::getUserRating(Yii::$app->user->identity->id);?><br>
        <?php echo Yii::t("user", 'Мої опитування'); ?>: <a href="<?= Url::toRoute('/poll/site/myPolls/')?>"><?= User::getPollsCount(Yii::$app->user->identity->id);?></a><br>
        <?php echo Yii::t("user", 'Нові коментарі'); ?>: <a href="<?= Url::toRoute('/user/new/new-comments/')?>"><?= Poll::getNewCommentsCount(Yii::$app->user->identity->id);?></a>
    </div>
    <a href="<?= Url::toRoute('/user/profile');?>" class="my_profile" role="tablist">
        <?= Yii::t("user", 'Мій профіль'); ?>
    </a>
</div>
<?php /*
 * 
 * /var/www/vhosts_yii/referendum.social/referendum.social.local/frontend/widgets/views/create-poll-modal.php
 * <?= $this->render('create-poll-modal', ['user'=>Yii::$app->user->identity,]); ?>
 */ ?>
<?= $this->render('create-poll-modal', [ 'pollModel' => $pollModel,]); ?>
    
    
<?php if($refresh):?>
    <script>
        document.location.href = '<?= Url::home();?>';
    </script>
<?php endif;?>