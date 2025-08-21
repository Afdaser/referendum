<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var common\models\CountryRegion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="country-region-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'country_id')->widget(Select2::classname(), [
                'data' => common\models\Country::dropDownAllItems(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select country...')],
                'pluginOptions' => ['allowClear' => true,],
            ]);
            ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
