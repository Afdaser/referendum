<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\RegionCity $model */

$this->title = Yii::t('app', 'Update Region City: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Region Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="region-city-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
