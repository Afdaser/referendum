<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PollComment $model */

$this->title = Yii::t('app', 'Create Poll comment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Poll comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-comment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
