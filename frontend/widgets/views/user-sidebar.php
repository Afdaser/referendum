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
?>
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
        <?php echo Yii::t("user", 'Нові коментарі'); ?>: <a href="<?php echo Yii::app()->createUrl('/user/newComments/')?>"><?php echo Poll::getNewCommentsCount(Yii::app()->user->id);?></a>
 */
?>
        <?php echo Yii::t("user", 'Мій рейтинг'); ?>: <?= $identity ? User::getUserRating($identity->id) : 0;?><br>
        <?php // Нова логіка URL: користувацьке посилання має вести тільки на чистий публічний шлях /my-polls. ?>
        <?php echo Yii::t("user", 'Мої опитування'); ?>: <a href="/my-polls"><?= $identity ? User::getPollsCount($identity->id) : 0;?></a><br>
        <?php echo Yii::t("user", 'Нові коментарі'); ?>: <a href="<?= Url::toRoute('/user/new/new-comments/')?>"><?= $identity ? Poll::getNewCommentsCount($identity->id) : 0;?></a>
    </div>
    <a href="<?= Url::toRoute('/user/profile');?>" class="my_profile" role="tablist">
        <?= Yii::t("user", 'Мій профіль'); ?>
    </a>
</div>
<?php /*
 * Модалка створення опитування більше НЕ рендериться глобально в сайдбарі.
 * Причина: цей шаблон створення не потрібен на всіх сторінках для залогіненого користувача.
 * Безпечний сценарій: рендерити модалку лише у профільних/цільових в'ю створення опитування.
 *
 * Історичне посилання на старий рендер:
 * /var/www/vhosts_yii/referendum.social/referendum.social.local/frontend/widgets/views/create-poll-modal.php
 * <?= $this->render('create-poll-modal', ['pollModel' => $pollModel]); ?>
 */ ?>
<?php
// Регресійний захист: кнопка "Створити нове" на /my-polls має відкривати модалку.
// Тому повертаємо рендер create-poll-modal лише на сторінці "Мої опитування",
// а не глобально на всіх сторінках авторизованого користувача.
$requestedRoute = Yii::$app->requestedRoute;
$controllerRoute = Yii::$app->controller ? Yii::$app->controller->route : '';
$pathInfo = trim(Yii::$app->request->pathInfo, '/');
$isMyPollsPage = in_array($requestedRoute, ['poll/site/my-polls', 'site/my-polls'], true)
    || in_array($controllerRoute, ['poll/site/my-polls', 'site/my-polls'], true)
    || strpos($pathInfo, 'my-polls') === 0
    || strpos($pathInfo, 'poll/site/my-polls') === 0;
?>
<?php if ($isMyPollsPage): ?>
    <?= $this->render('create-poll-modal', ['pollModel' => $pollModel]); ?>
<?php endif; ?>
    
    
<?php if($refresh):?>
    <script>
        document.location.href = '<?= Url::home();?>';
    </script>
<?php endif;?>
