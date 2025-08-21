<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

use common\models\Page;
use common\models\Language;

/** @var yii\web\View $this */
/** @var common\models\search\PageSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Page'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'slug',
//            'language_id',
            [
                'attribute' => 'language_id',
                'filter' => Language::dropDownAllItems(),
                'value' => 'language.name',
            ],
            'name',
            'title',
            //'content:ntext',
            //'describe:ntext',
            //'date_add:datetime',
            //'date_update:datetime',
            //'created_by',
            //'updated_by',
            //'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Page $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
