<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\RegionCity $model */

$this->title = Yii::t('app', 'Create Region City');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Region Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="region-city-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
