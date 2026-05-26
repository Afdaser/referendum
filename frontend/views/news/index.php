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
            <div class="list_polls">
                <?php foreach ($items as $item): ?>
                    <article class="item_list_poll">
                        <h2 class="item_list_poll__title" style="margin: 0 0 8px 0;">
                            <?= Html::encode($item->title); ?>
                        </h2>
                        <div class="bottom_item_list_poll">
                            <div class="bottom_item_list_poll__meta">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <?= Yii::$app->formatter->asDate($item->published_at, 'php:d.m.Y H:i'); ?>
                            </div>
                        </div>

                        <?php
                        // Якщо excerpt порожній, робимо безпечний короткий витяг із контенту.
                        $excerpt = trim((string) $item->excerpt);
                        if ($excerpt === '') {
                            $excerpt = StringHelper::truncateWords(strip_tags((string) $item->content), 40);
                        }
                        ?>
                        <p style="margin: 8px 0 10px 0; color: #3d3d3d;"><?= Html::encode($excerpt); ?></p>

                        <a href="<?= \yii\helpers\Url::to(['/news/view', 'slug' => $item->slug]); ?>" class="btn_prev_var">Читати</a>
                    </article>
                <?php endforeach; ?>
            </div>

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
