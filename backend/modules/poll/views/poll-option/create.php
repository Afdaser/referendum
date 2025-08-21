<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PollOption $model */

$this->title = Yii::t('app', 'Create Poll option');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Poll options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-option-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
