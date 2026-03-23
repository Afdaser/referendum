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
<?php
// Додатковий захист: якщо сайдбар випадково рендериться для гостя, не падаємо на null identity.
$identity = Yii::$app->user->identity;
$successMessage = Yii::$app->session->getFlash('success');
?>
<?php if (!empty($successMessage)): ?>
    <div class="alert alert-success">
        <?= Html::encode($successMessage); ?>
    </div>
<?php endif; ?>
<div class="title_auth">
    <div class="left_auth_name"><?= $identity ? User::getUserName($identity->id) : Yii::t('main', 'Гість'); ?></div>
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
        <?php echo Yii::t("user", 'Мій рейтинг'); ?>: <?= $identity ? User::getUserRating($identity->id) : 0;?><br>
        <?php echo Yii::t("user", 'Мої опитування'); ?>: <a href="<?= Url::toRoute('/poll/site/myPolls/')?>"><?= $identity ? User::getPollsCount($identity->id) : 0;?></a><br>
        <?php echo Yii::t("user", 'Нові коментарі'); ?>: <a href="<?= Url::toRoute('/user/new/new-comments/')?>"><?= $identity ? Poll::getNewCommentsCount($identity->id) : 0;?></a>
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
