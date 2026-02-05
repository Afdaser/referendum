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
    <?php // Явно уточнюємо зміст поля для адмінів, щоб уникнути двозначності з заголовком опитування. ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Назва варіанту відповіді') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
