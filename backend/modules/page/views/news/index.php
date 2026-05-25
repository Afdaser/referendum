<?php

use common\models\News;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\search\NewsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Новини';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="news-index">
    <p>
        <?= Html::a('Додати новину', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'title',
                'label' => 'Заголовок',
            ],
            [
                'attribute' => 'is_published',
                'label' => 'Статус',
                'filter' => News::getPublishedItems(),
                'value' => static fn(News $model): string => News::getPublishedItems()[$model->is_published] ?? '—',
            ],
            [
                'attribute' => 'published_at',
                'label' => 'Дата',
                'format' => 'datetime',
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => static fn($action, News $model): string => Url::toRoute([$action, 'id' => $model->id]),
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
