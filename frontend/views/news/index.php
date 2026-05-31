<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var common\models\News[] $items */
/** @var yii\data\Pagination $pagination */
?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <h1 class="tag-page__title"><?= Yii::t('poll', 'Новини'); ?></h1>

        <?php if (!empty($items)): ?>
            <div class="list_polls">
                <?php foreach ($items as $item): ?>
                    <?php
                    // Не нашкодити: один URL використовуємо для зображення, заголовка і кнопки, щоб усі кліки вели на ту саму новину.
                    $newsUrl = Url::to(['/news/view', 'slug' => $item->slug]);
                    $newsTitle = trim((string) $item->title);
                    $newsTitleLabel = Yii::t('poll', 'Перейти до новини «{title}»', ['title' => $newsTitle]);
                    ?>
                    <article class="item_list_poll">
                        <?php // Не нашкодити: зображення показуємо тільки якщо редактор уже додав image_url до новини. ?>
                        <?php if (!empty($item->image_url)): ?>
                            <a href="<?= $newsUrl; ?>" class="item_list_poll__image-link" aria-label="<?= Html::encode($newsTitleLabel); ?>">
                                <?= Html::img($item->image_url, [
                                    'class' => 'item_list_poll__image',
                                    'alt' => $item->h1 ?: $item->title,
                                ]); ?>
                            </a>
                        <?php endif; ?>

                        <h2 class="item_list_poll__title" style="margin: 0 0 8px 0;">
                            <?php // Не нашкодити: клікабельним робимо тільки текст заголовка, не всю картку. ?>
                            <a href="<?= $newsUrl; ?>" class="item_list_poll__title-link" aria-label="<?= Html::encode($newsTitleLabel); ?>">
                                <?= Html::encode($item->title); ?>
                            </a>
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

                        <div class="item_list_poll__read-more">
                            <?php // Окремий клас віддзеркалює лише фон кнопки, текст лишається читабельним. ?>
                            <a href="<?= $newsUrl; ?>" class="btn_prev_var btn_read_next_var" aria-label="<?= Html::encode($newsTitleLabel); ?>"><span><?= Yii::t('poll', 'Читати'); ?></span></a>
                        </div>
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
                <p style="margin: 0;"><?= Yii::t('poll', 'Поки що новин немає'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
