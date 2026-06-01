<?php
use yii\bootstrap\Html;
// $url = $this->createAbsoluteUrl("poll/view", array("id" => $poll->id));
// $url = $this->createAbsoluteUrl($poll->url);
$url = $poll->absoluteUrl;
$describe = $poll->getPollDescribe();

$pollDescribe = $poll->describe;
$pollDescribe = strip_tags($pollDescribe);
$pollDescribe = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $pollDescribe);
$pollDescribe = str_replace('  ',' ', $pollDescribe);
$pollDescribe = trim($pollDescribe);
$copyMessage = Yii::t('poll', 'Посилання скопійовано');
// Тексти aria-label пояснюють дію іконкових кнопок для скринрідерів.
$facebookLabel = Yii::t('poll', 'Поділитися у Facebook');
$vkLabel = Yii::t('poll', 'Поділитися у VK');
$twitterLabel = Yii::t('poll', 'Поділитися у Twitter');
$googleLabel = Yii::t('poll', 'Поділитися у Google Plus');
$copyLabel = Yii::t('poll', 'Скопіювати посилання');
// Формуємо URL логотипу для соцмереж з поточного домену/субдомену.
$socialLogoUrl = Yii::$app->request->hostInfo . '/img/layout/logo_social.png';

?>
<span class="share_icon">
    <i class="fa fa-share-alt"></i>
</span>
<span class="links_share">
    <span class="inner_b_share">
        <button type="button" class="facebook icon_share" aria-label="<?= Html::encode($facebookLabel); ?>"
           onclick="Share.facebook('<?php echo $url; ?>','<?= Html::encode ($poll->title); ?>','<?= Html::encode($socialLogoUrl); ?>','<?= Html::encode ($describe); ?>')">
            <i class="fa fa-facebook"></i>
        </button>
<?php /* * /?>
        <button type="button" class="vk icon_share" aria-label="<?= Html::encode($vkLabel); ?>"
           onclick="Share.vkontakte('<?php echo $url; ?>','<?= Html::encode ($poll->title); ?>','<?= Html::encode($socialLogoUrl); ?>','<?= Html::encode ($pollDescribe); ?>')">
            <i class="fa fa-vk"></i>
        </button>
<?php /* */?>
        <button type="button" class="twitter icon_share" aria-label="<?= Html::encode($twitterLabel); ?>"
           onclick="Share.twitter('<?php echo $url; ?>','<?= Html::encode ($poll->title); ?>')">
            <i class="fa fa-twitter"></i>
        </button>
<?php /* * /?>
        <button type="button" class="google icon_share" aria-label="<?= Html::encode($googleLabel); ?>" onclick="Share.gg('<?php echo $url; ?>')">
            <i class="fa fa-google-plus"></i>
        </button>
<?php /* */?>
        <button type="button" class="copy_link icon_share" aria-label="<?= Html::encode($copyLabel); ?>" data-url="<?php echo $url; ?>">
            <i class="fa fa-link"></i>
        </button>
    </span>
</span>
<span class="copy_link_message"><?= Html::encode($copyMessage); ?></span>
