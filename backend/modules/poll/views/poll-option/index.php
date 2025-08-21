<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

use common\models\Poll;
use common\models\PollOption;

/** @var yii\web\View $this */
/** @var common\models\search\PollOptionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Poll options');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-option-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Poll option'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
//            'poll_id',
            [
                'attribute' => 'poll_id',
                'filter' => Poll::dropDownAllItems(),
                'value' => 'poll.title',
            ],
            'user_id',
            'title',
            'status',
            //'rating',
            'date_add:datetime',
            //'date_update',
            //'created_by',
            //'updated_by',
            //'created_at:datetime',
            //'updated_at:datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PollOption $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
