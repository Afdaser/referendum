<?php

use frontend\widgets\WPollsSidebar;
use frontend\widgets\WUserSidebar;
use yii\helpers\Html;

/** @var $this Controller */
$socialLinks = [
    'Facebook' => ['url' => 'https://www.facebook.com/online.statistic', 'icon' => 'facebook'],
    'LinkedIn' => ['url' => 'https://www.linkedin.com/company/referendum-social/', 'icon' => 'linkedin'],
    'Instagram' => ['url' => 'https://www.instagram.com/referendum.social/', 'icon' => 'instagram'],
    'Reddit' => ['url' => 'https://www.reddit.com/r/Surveys_and_statistic/', 'icon' => 'reddit'],
    'X.com' => ['url' => 'https://x.com/O_Statistics', 'icon' => 'x'],
];
?>
<div class="col-md-4">
    <div class="row left_cut_row">
        <div class="auth_b">
            <?= trim((string) WUserSidebar::widget()) . "\n" ?>
        </div>
        <div class="right_banner_b">
<?php if (YII_ENV === 'prod'): ?>
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-3234808971320300" data-ad-slot="2085995871" data-ad-format="auto"></ins>
<?php else: ?>
            <div<?= YII_ENV === 'dev' ? ' style="border:2px dotted red;"' : '' ?>>
                <h4>adsbygoogle</h4>
                data-ad-client="ca-pub-3234808971320300"<br>
                data-ad-slot="2085995871"
            </div>
<?php endif; ?>
        </div>
<?= '        ' . str_replace("\n", "\n        ", trim((string) WPollsSidebar::widget())) . "\n" ?>
        <div class="social_grey_b" aria-label="Social media">
<?php foreach ($socialLinks as $label => $socialLink): ?>
<?php
if ($socialLink['url'] === '') {
    continue;
}
$icon = $socialLink['icon'] === 'x'
    ? Html::tag('span', 'X', ['class' => 'social-x-icon', 'aria-hidden' => 'true'])
    : Html::tag('i', '', ['class' => 'fa fa-' . $socialLink['icon'], 'aria-hidden' => 'true']);
?>
<?= '            ' . Html::a($icon, $socialLink['url'], [
    'class' => 'social-grey-link',
    'title' => $label,
    'aria-label' => $label,
    'target' => '_blank',
    'rel' => 'noopener noreferrer',
]) . "\n" ?>
<?php endforeach; ?>
        </div>
    </div>
</div>
