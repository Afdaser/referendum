<?php
use yii\bootstrap\Html;
use yii\helpers\Json;

$url = $poll->absoluteUrl;
$copyMessage = Yii::t('poll', 'Посилання скопійовано');
// Тексти aria-label пояснюють дію іконкових кнопок для скринрідерів.
$facebookLabel = Yii::t('poll', 'Поділитися у Facebook');
$twitterLabel = Yii::t('poll', 'Поділитися у Twitter');
$copyLabel = Yii::t('poll', 'Скопіювати посилання');

?>
<span class="share_icon">
    <i class="fa fa-share-alt"></i>
</span>
<span class="links_share">
    <span class="inner_b_share">
        <button type="button" class="facebook icon_share" aria-label="<?= Html::encode($facebookLabel); ?>"
           onclick="<?= Html::encode('Share.facebook(' . Json::htmlEncode($url) . ')'); ?>">
            <i class="fa fa-facebook"></i>
        </button>
        <button type="button" class="twitter icon_share" aria-label="<?= Html::encode($twitterLabel); ?>"
           onclick="<?= Html::encode('Share.twitter(' . Json::htmlEncode($url) . ',' . Json::htmlEncode($poll->title) . ')'); ?>">
            <i class="fa fa-twitter"></i>
        </button>
        <button type="button" class="copy_link icon_share" aria-label="<?= Html::encode($copyLabel); ?>" data-url="<?= Html::encode($url); ?>">
            <i class="fa fa-link"></i>
        </button>
    </span>
</span>
<span class="copy_link_message"><?= Html::encode($copyMessage); ?></span>
