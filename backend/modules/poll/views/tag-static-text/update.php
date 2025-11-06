<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TagStaticText $model */
/** @var array<string,string> $placeholders */

$this->title = 'Редагування тексту: ' . ($model->tag ? $model->tag->name : $model->id);
$this->params['breadcrumbs'][] = ['label' => 'Статичний текст на тегах', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tag ? $model->tag->name : $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редагування';
?>
<div class="tag-static-text-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'placeholders' => $placeholders,
    ]) ?>
</div>
