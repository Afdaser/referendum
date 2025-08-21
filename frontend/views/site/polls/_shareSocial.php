<?php 
use yii\bootstrap\Html;
?>
<?php // $url = $this->createAbsoluteUrl("poll/view", array("id" => $poll->id)); ?>
<?php // $url = $this->createAbsoluteUrl($poll->url); ?>
<?php $url = $poll->absoluteUrl; ?>
<?php $describe = $poll->getPollDescribe(); ?>
<?php

$pollDescribe = $poll->describe;
$pollDescribe = strip_tags($pollDescribe);
$pollDescribe = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $pollDescribe);
$pollDescribe = str_replace('  ',' ', $pollDescribe);
$pollDescribe = trim($pollDescribe);

?>
<span class="share_icon">
    <i class="fa fa-share-alt"></i>
</span>
<span class="links_share">
    <span class="inner_b_share">
        <a href="javascript:void(0)" class="facebook icon_share"
           onclick="Share.facebook('<?php echo $url; ?>','<?= Html::encode ($poll->title); ?>','http://online-statistic.com/img/layout/logo_social.png','<?= Html::encode ($describe); ?>')">
            <i class="fa fa-facebook"></i>
        </a>
<?php /* * /?>
        <a href="javascript:void(0)" class="vk icon_share"
           onclick="Share.vkontakte('<?php echo $url; ?>','<?= Html::encode ($poll->title); ?>','http://online-statistic.com/img/layout/logo_social.png','<?= Html::encode ($pollDescribe); ?>')">
            <i class="fa fa-vk"></i>
        </a>
<?php /* */?>
        <a href="javascript:void(0)" class="twitter icon_share"
           onclick="Share.twitter('<?php echo $url; ?>','<?= Html::encode ($poll->title); ?>')">
            <i class="fa fa-twitter"></i>
        </a>
<?php /* * /?>
        <a href="javascript:void(0)" class="google icon_share" onclick="Share.gg('<?php echo $url; ?>')">
            <i class="fa fa-google-plus"></i>
        </a>
<?php /* */?>
    </span>
</span>