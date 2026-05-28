<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\News $model */

$imageUrl = $model->getImageUrl();
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <article class="chart_b user_page_b news-detail-page">
            <div class="top_b_chart">
                <a class="btn_prev_var" href="<?= Url::to(['/news/index']); ?>">Назад до новин</a>
            </div>
            <div class="my_profile_b">
                <?php if ($imageUrl !== null): ?>
                    <?php // Зображення тримаємо над заголовком, щоб детальна новина виглядала як статична сторінка з hero-візуалом. ?>
                    <div class="news-detail-page__image-wrap">
                        <?= Html::img($imageUrl, [
                            'class' => 'news-detail-page__image',
                            'alt' => $model->getImageAlt(),
                            'loading' => 'lazy',
                        ]); ?>
                    </div>
                <?php endif; ?>

                <h1 class="profile_name news-detail-page__title">
                    <?= Html::encode($model->h1 ?: $model->title); ?>
                </h1>
                <div class="text_my_profile_data news-detail-page__date">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <?= Yii::$app->formatter->asDate($model->published_at, 'php:d.m.Y H:i'); ?>
                </div>
                <div class="news-content news-detail-page__content">
                    <?= $model->content; ?>
                </div>
            </div>
        </article>
    </div>
</div>
