<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Poll $model */

$this->title = Yii::t('app', 'Create Poll');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
