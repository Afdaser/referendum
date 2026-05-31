<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\News $model */
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <article class="chart_b user_page_b news-detail">
            <?php if (!empty($model->image_url)): ?>
                <?php // Не нашкодити: показуємо одне завантажене зображення адаптивно, без генерації різних розмірів. ?>
                <div class="news-detail__image-wrap" style="margin: 0 0 18px 0;">
                    <?= Html::img($model->image_url, [
                        'class' => 'news-detail__image',
                        'alt' => $model->h1 ?: $model->title,
                        'style' => 'display:block;width:100%;height:auto;border-radius:4px;',
                    ]); ?>
                </div>
            <?php endif; ?>

            <div class="my_profile_b">
                <h1 class="profile_name">
                    <?= Html::encode($model->h1 ?: $model->title); ?>
                </h1>

                <div class="small text-muted" style="margin-bottom: 12px;">
                    <?= Yii::$app->formatter->asDate($model->published_at, 'php:d.m.Y H:i'); ?>
                </div>

                <div class="news-content">
                    <?= $model->content; ?>
                </div>

                <div class="news-detail__back" style="margin-top: 24px;">
                    <a class="btn_prev_var" href="<?= Url::to(['/news/index']); ?>">
                        <?= Yii::t('poll', 'До новин'); ?>
                    </a>
                </div>
            </div>
        </article>
    </div>
</div>
