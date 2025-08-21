<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Country $model */

$this->title = Yii::t('app', 'Create Country');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
