<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use common\models\Language;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\Poll $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="poll-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'votes_count_close')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'show_on_slider')->textInput() ?>
    
    <?= $form->field($model, 'describe')->widget(ashch\tinymce\TinyMce::class); ?>

    <?= $form->field($model, 'poll_language_id')->widget(Select2::classname(), [
                'data' => Language::dropDownAllItems(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select language...')],
                'pluginOptions' => ['allowClear' => true,],
            ]);
            ?>

    <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
                'data' => User::dropDownAllItems(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select user...')],
                'pluginOptions' => ['allowClear' => true,],
            ]);
            ?>

    <?= $form->field($model, 'rating')->textInput() ?>

    <?= $form->field($model, 'views')->textInput() ?>

    <?= $form->field($model, 'result_type')->textInput() ?>    

    <?= $form->field($model, 'show_for_all_languages')->textInput() ?>

    <hr>
    

        


    <?= $form->field($model, 'poll_sex')->textInput() ?>

    <?php /* = $form->field($model, 'poll_country_id')->textInput() ?>
    <?= $form->field($model, 'poll_region_id')->textInput() ?>
    <?= $form->field($model, 'poll_city_id')->textInput() /* */ ?>
    <?=
    $form->field($model, 'poll_country_id')->widget(Select2::classname(), [
        'data' => common\models\Country::dropDownAllItems(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => Yii::t('app', 'Select country...')],
        'pluginOptions' => ['allowClear' => true,],
    ]);
    ?>

    <?= $form->field($model, 'poll_region_id')->widget(Select2::classname(), [
        'data' => common\models\CountryRegion::dropDownAllItems(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => Yii::t('app', 'Select region...')],
        'pluginOptions' => ['allowClear' => true,],
    ]);
    ?>
    <?= $form->field($model, 'poll_city_id')->widget(Select2::classname(), [
        'data' => common\models\RegionCity::dropDownAllItems(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => Yii::t('app', 'Select city...')],
        'pluginOptions' => ['allowClear' => true,],
    ]);
    ?>

    <?= $form->field($model, 'poll_min_age')->textInput() ?>

    <?= $form->field($model, 'poll_max_age')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
