<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CountryRegion $model */

$this->title = Yii::t('app', 'Create Country Region');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Country Regions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-region-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
