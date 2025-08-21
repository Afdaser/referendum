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
            'description:raw',
            //'created_by',
            //'updated_by',
            //'created_at:datetime',
            'updated_at:datetime',
[
    'class' => ActionColumn::class,
    'header' => Yii::t('app', 'Actions'),
    'template' => '{view} {update} {delete}',
    'urlCreator' => function ($action, Tag $model, $key, $index, $column) {
        return Url::toRoute([$action, 'id' => $model->id]);
    },
    'buttons' => [
        'delete' => function ($url, $model, $key) {
            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                'title' => Yii::t('yii', 'Delete'),
                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'data-method' => 'post',
                'data-pjax' => '0',
            ]);
        },
    ],
],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
