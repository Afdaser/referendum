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
            //'date_update:datetime',

            //'created_by',
            //'updated_by',
            //'created_at:datetime',
            //'updated_at:datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Poll $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
