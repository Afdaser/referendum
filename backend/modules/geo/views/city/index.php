<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

use common\models\RegionCity;
use common\models\Country;
use common\models\CountryRegion;

/** @var yii\web\View $this */
/** @var common\models\search\RegionCitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Cities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-city-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create City'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'country_id',
                'filter' => Country::dropDownAllItems(),
                'value' => 'country.name',
            ],
            [
                'attribute' => 'region_id',
                'filter' => CountryRegion::dropDownAllItems(),
                'value' => 'region.name',
            ],
//            'region_id',
//            'country_id',
//            'country.name',
//            'region.name',
            'name',
//            'created_by',
            //'updated_by',
            //'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, RegionCity $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
