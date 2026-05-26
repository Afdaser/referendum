<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var common\models\News[] $items */
/** @var yii\data\Pagination $pagination */
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <h1 class="tag-page__title">Новини</h1>

        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
                <article class="chart_b" style="margin-bottom: 12px;">
                    <h2 style="margin-top: 0;"><?= Html::encode($item->title); ?></h2>
                    <div class="small text-muted" style="margin-bottom: 10px;">
                        <?= Yii::$app->formatter->asDate($item->published_at, 'php:d.m.Y H:i'); ?>
                    </div>

                    <?php
                    // Якщо excerpt порожній, робимо безпечний короткий витяг із контенту.
                    $excerpt = trim((string) $item->excerpt);
                    if ($excerpt === '') {
                        $excerpt = StringHelper::truncateWords(strip_tags((string) $item->content), 40);
                    }
                    ?>
                    <p><?= Html::encode($excerpt); ?></p>

                    <a href="<?= \yii\helpers\Url::to(['/news/view', 'slug' => $item->slug]); ?>" class="btn btn-default">Читати</a>
                </article>
            <?php endforeach; ?>

            <div class="text-center">
                <?= LinkPager::widget([
                    'pagination' => $pagination,
                    // Не нашкодити: route задається у Pagination, тому не дублюємо тут.
                ]); ?>
            </div>
        <?php else: ?>
            <div class="chart_b">
                <p style="margin: 0;">Поки що новин немає</p>
            </div>
        <?php endif; ?>
    </div>
</div>
