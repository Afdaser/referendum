<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Country $model */

$this->title = Yii::t('app', 'Update Country: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="country-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
