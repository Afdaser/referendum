<?php

/** @var yii\web\View $this */
/** @var common\models\News $model */

$this->title = 'Редагувати новину: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новини', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="news-update">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
