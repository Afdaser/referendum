<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PollOption $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="poll-option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // poll_id передається з контексту опитування і не редагується вручну. ?>
    <?= $form->field($model, 'poll_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
