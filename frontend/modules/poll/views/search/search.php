<?php

use frontend\widgets\WPollList;

//use Yii;
//use yii\widgets\BaseListView AS BaseWidget;
//use yii\helpers\Html;
//use yii\helpers\Url;
//use app\models\User;


/** @var yii\web\View $this */
$qty = $dataProvider->count;
?>
<div class="col-md-8">
    <div class="row right_cut_row">
    <?=
    WPollList::widget([
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        'searchForm' => $searchForm,
//                    'itemOptions' => ['class' => 'item'],
    ])
    ?>

    </div>
</div>

