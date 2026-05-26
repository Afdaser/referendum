<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\News $model */
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <article class="chart_b">
            <h1 class="tag-page__title"><?= Html::encode($model->h1 ?: $model->title); ?></h1>
            <div class="small text-muted" style="margin-bottom: 12px;">
                <?= Yii::$app->formatter->asDate($model->published_at, 'php:d.m.Y H:i'); ?>
            </div>
            <div class="news-content">
                <?= $model->content; ?>
            </div>
        </article>
    </div>
</div>
