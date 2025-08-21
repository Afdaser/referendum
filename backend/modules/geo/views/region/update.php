<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CountryRegion $model */

$this->title = Yii::t('app', 'Update Country Region: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Country Regions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="country-region-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
