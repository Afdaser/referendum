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
    <?php // Повертаємо редагування статусу у формі, щоб адміністратор міг керувати видимістю відповіді. ?>
    <?= $form->field($model, 'status')->dropDownList([
        \common\models\PollOption::OPTION_STATUS_PUBLISHED => 'Опубліковане',
        \common\models\PollOption::OPTION_STATUS_UNPUBLISHED => 'Неопубліковане',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
