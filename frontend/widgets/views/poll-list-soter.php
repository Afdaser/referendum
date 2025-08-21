<?php

use yii\helpers\Url;

if (empty($sort)) {
    $sort = '#';
}
if (empty($limit)) {
    $limit = Yii::$app->params['POLLS_LIMIT_MAIN_PAGE'];
}
?>
<?php if (YII_DEBUG || !empty($debug)) : ?>
    <div style="border:2px dashed red;"><?= '#DEV03:block04 [~/frontend/widgets/views/poll-list-soter.php]'; ?></div>
<?php endif; ?>
<?php
// <select class="count_article" onchange="document.location.href = &quot;/site/hotPolls/desc/month/&quot;+$(this).val()">
//$uriPrefix = '#';

if (Yii::$app->controller->action->id == 'index' && $category == 'hot') {
    $route = '/poll/site/hot-polls';
    $uriPrefix = Url::toRoute([$route]);
    $uriPrefix = '/site/hotPolls';
} elseif($category == 'search' && !empty($tag)) {
    $route = '/poll/search/tag'; // . Yii::$app->controller->action->id;
    $uriPrefix = Url::toRoute([$route, 'tag' => $tag]);
} else {
    $route = '/poll/site/' . Yii::$app->controller->action->id;
    $uriPrefix = Url::toRoute([$route]);
}
?>
<div class="right_count_select">
    <?= Yii::t('filter', 'Опитувань на сторінку'); ?>:
    <?php // select class="count_article" onchange="document.location.href = &quot;/site/hotPolls/desc/month/&quot;+$(this).val()"> //  ?>
    <select class="count_article" onchange='document.location.href = "<?= "{$uriPrefix}/{$sort}/{$period}/"; ?>" + $(this).val() + "<?= "?click=true"; ?>"'>
        <?php foreach ([10, 5, 2] as $value): ?>
            <option value="<?= $value; ?>" <?= ($value == $limit ) ? 'selected' : ''; ?>><?= $value; ?></option>
        <?php endforeach; ?>
    </select>
</div>
