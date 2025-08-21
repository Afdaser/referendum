<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Country $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="country-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'sorting_uk')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'sorting_ru')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'sorting_en')->textInput(['type' => 'number']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'sorting_no')->textInput(['type' => 'number']) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
