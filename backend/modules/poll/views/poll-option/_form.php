<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\PollOption $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="poll-option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'poll_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
                'data' => User::dropDownAllItems(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select user...')],
                'pluginOptions' => ['allowClear' => true,],
            ]);
            ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'rating')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
