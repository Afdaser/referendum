<?php

use frontend\widgets\WPollList;
use Yii;

/** @var yii\web\View $this */

$qty = $dataProvider->count;
$title = Yii::t('tag', 'Останні опитування {tag} та думка громадськості | Referendum', [
    'tag' => $tagModel->name,
]);
Yii::$app->page->title = $title;
$this->title = $title;
Yii::$app->page->description = Yii::t('tag', 'Опитування та думки щодо {tag}: беріть участь у {count} захопливих опитуваннях, висловлюйте свої думки, коментуйте актуальні питання, спілкуйтеся з іншими та досліджуйте результати.', [
    'count' => $tagModel->polls_count,
    'tag' => $tagModel->name,
]);
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <h1>Latest <?= $tag; ?> public opinion polls</h1>
<?php if ($qty) : ?>
    <?= WPollList::widget([
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'searchForm' => $searchForm,
    ]) ?>
<?php else: ?>
        <div class="chart_b" style="margin-bottom: 5px;">
            <h1>tag/index</h1>
            <h4><?= Yii::t('app', 'No matching results'); ?></h4>
            <p>
                You may change the content of this page by modifying
                the file <code><?= __FILE__; ?></code>.
            </p>
        </div>
<?php endif; ?>

<?php if ($page <= 1) : ?>
        <div class="info_block">
            <h2><?= Yii::t('tag', 'Найцікавіші опитування на тему "{tag}"', ['tag' => $tagModel->name]); ?></h2>
            <div class="tag-info-text">
                <?= $tagModel->getInfoText(); ?>
            </div>
        </div>
<?php endif; ?>

    </div>
</div>

