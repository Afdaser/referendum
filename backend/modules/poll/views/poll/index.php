<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

use common\models\Poll;
use common\models\Language;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\search\PollSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Polls');
$this->params['breadcrumbs'][] = $this->title;

// Додаємо стилі, щоб кнопки дій були компактними й акуратно вирівняними.
$this->registerCss(<<<CSS
.poll-index .action-buttons-cell .btn-group {
    display: inline-flex;
    gap: 6px;
}

.poll-index .action-buttons-cell .btn {
    padding: 4px 8px;
}
CSS);
?>
<div class="poll-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Poll'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute' => 'poll_language_id',
                'filter' => Language::dropDownAllItems(),
                'value' => 'language.name',
            ],
            //'describe:raw',
            [
                'attribute' => 'describe',
                'label' => Yii::t('app', 'Describe length'),
                'value' => function ($model) {
                    return strlen( strip_tags($model->describe));
                }
            ],
            [
                'attribute' => 'user_id',
                'filter' => User::dropDownAllItems(),
                'value' => 'author.username',
            ],
            [
                'attribute' => 'voteCount',
                'value' => 'voteCount',
                'label' => Yii::t('app', 'Votes count'),
            ],
            'rating',
            [
                'attribute' => 'status',
//		'name' => 'status', // Yii1
//		'type' => 'raw', // Yii1
//		'value' => 'StringHelper::formatPollStatus($data->status)',
		'value' => 'statusName',
            ],
            [
                'attribute' => 'show_on_slider',

            ],
            //'views',
            //'result_type',
//            'poll_language_id',

            //'show_for_all_languages',
            //'poll_sex',
            //'poll_country_id',
            //'poll_region_id',
            //'poll_city_id',
            //'poll_min_age',
            //'poll_max_age',
            //'votes_count_close',
            
            'date_add:datetime',
            [
                'attribute' => 'updated_at',
                'label' => $searchModel->getAttributeLabel('date_update'),
                'format' => 'datetime',
                // Беремо Unix-час `updated_at`, бо sitemap рахує оновлення опису опитувань саме з цього поля.
                'value' => 'updated_at',
            ],

            //'created_by',
            //'updated_by',
            //'created_at:datetime',
            //'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'header' => Yii::t('app', 'Actions'),
                // Оновлюємо шаблон, щоб додати кнопку відкриття опитування на сайті та зберегти компактний вигляд.
                'template' => '<div class="btn-group btn-group-xs" role="group">{view}{update}{delete}{public}</div>',
                'contentOptions' => ['class' => 'action-buttons-cell text-center'],
                'urlCreator' => function ($action, Poll $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'buttons' => [
                    'view' => function ($url, Poll $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'class' => 'btn btn-default',
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, Poll $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'class' => 'btn btn-default',
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url, Poll $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'class' => 'btn btn-default',
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    },
                    'public' => function ($url, Poll $model) {
                        return Html::a('<span class="glyphicon glyphicon-new-window"></span>', $model->url, [
                            'class' => 'btn btn-default',
                            'title' => Yii::t('app', 'Перехід на опитування'),
                            'aria-label' => Yii::t('app', 'Перехід на опитування'),
                            'data-pjax' => '0',
                            'target' => '_blank',
                            'rel' => 'noopener noreferrer',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
