<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

use common\models\Tag;
use common\models\Language;

/** @var yii\web\View $this */
/** @var common\models\search\TagSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tags');
$this->params['breadcrumbs'][] = $this->title;

// Додаємо стилі для кнопок дій, щоб вони виглядали охайно й не виходили за межі стовпчика.
$this->registerCss(<<<CSS
.tag-index .action-buttons-cell .btn-group {
    display: inline-flex;
    gap: 6px;
}

.tag-index .action-buttons-cell .btn {
    padding: 4px 8px;
}
CSS);
?>
<div class="tag-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Tag'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
//            'language_id',
            [
                'attribute' => 'language_id',
                'filter' => Language::dropDownAllItems(),
                'value' => 'language.name',
            ],
            [
                'attribute' => 'description',
                'value' => function (Tag $model) {
                    return preg_split("/\r\n|\r|\n/", $model->description)[0] ?? '';
                },
            ],
            'polls_count',
            //'created_by',
            //'updated_by',
            //'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'header' => Yii::t('app', 'Actions'),
                // Кастомізуємо шаблон, щоб додати кнопку переходу на сайт і зберегти охайне вирівнювання.
                'template' => '<div class="btn-group btn-group-xs" role="group">{view}{update}{delete}{public}</div>',
                'contentOptions' => ['class' => 'action-buttons-cell text-center'],
                'urlCreator' => function ($action, Tag $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'buttons' => [
                    'view' => function ($url, Tag $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'class' => 'btn btn-default',
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, Tag $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'class' => 'btn btn-default',
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url, Tag $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'class' => 'btn btn-default',
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                    },
                    'public' => function ($url, Tag $model) {
                        return Html::a('<span class="glyphicon glyphicon-new-window"></span>', $model->url, [
                            'class' => 'btn btn-default',
                            'title' => Yii::t('app', 'Відкрити на сайті'),
                            'aria-label' => Yii::t('app', 'Відкрити на сайті'),
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