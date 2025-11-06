<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TagStaticText $model */
/** @var array<string,string> $placeholders */

$this->title = 'Додати статичний текст';
$this->params['breadcrumbs'][] = ['label' => 'Статичний текст на тегах', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-static-text-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'placeholders' => $placeholders,
    ]) ?>
</div>
