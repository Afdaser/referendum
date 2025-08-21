<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PollComment $model */

$this->title = Yii::t('app', 'Update Poll comment: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Poll comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="poll-comment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
