<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

use common\models\Country;
use common\models\CountryRegion;

/** @var yii\web\View $this */
/** @var common\models\search\CountryRegionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Country Regions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-region-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Country Region'), ['create'], ['class' => 'btn btn-success']) ?>
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
//            'country_id',
//            'country.name',
            'name',
//            'created_by',
//            'updated_by',
//            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CountryRegion $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
