<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Poll $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$qtyOptions = count($model->pollOptions);
?>
<div class="poll-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Poll options'). ($qtyOptions ? ' ['.$qtyOptions.']' : ''),
                ['/poll/poll-option/index', 'PollOptionSearch' => ['poll_id' =>  $model->id]], ['class' => 'btn btn-primary']
                ) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title:ntext',
            'describe:raw',
//            'user_id',
            'author.name',
            'rating',
            'statusName',
            'show_on_slider:boolean',
            'votes_count_close',
            'pollVoteCount.vote_count',
            'pollVoteCount.guest_vote_count',
            'pollVoteCount.user_vote_count',
/*
            'views',
            'result_type',
            'poll_language_id',
            'show_for_all_languages',
            'poll_sex',
            'poll_country_id',
            'poll_region_id',
            'poll_city_id',
            'poll_min_age',
            'poll_max_age',
            'date_add:datetime',
            'date_update:datetime',
//            'created_by',
//            'updated_by',
*  */
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
