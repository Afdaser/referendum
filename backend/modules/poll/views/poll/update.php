<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Poll $model */

$this->title = Yii::t('app', 'Update Poll: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="poll-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
