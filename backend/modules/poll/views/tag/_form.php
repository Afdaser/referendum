<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use common\models\Language;

/** @var yii\web\View $this */
/** @var common\models\Tag $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tag-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language_id')->widget(Select2::classname(), [
                'data' => Language::dropDownAllItems(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select language...')],
                'pluginOptions' => ['allowClear' => true,],
            ]);
            ?>

    <?= $form->field($model, 'description')->widget(ashch\tinymce\TinyMce::class); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
