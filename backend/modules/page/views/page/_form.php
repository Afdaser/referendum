<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use common\models\Language;

/** @var yii\web\View $this */
/** @var common\models\Page $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'language_id')->widget(Select2::classname(), [
                'data' => Language::dropDownAllItems(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select language...')],
                'pluginOptions' => ['allowClear' => true,],
            ]);
            ?>

    <?php // H1 для статичної сторінки на фронтенді. ?>
    <?= $form->field($model, 'name')->label('H1')->textInput(['maxlength' => true]) ?>

    <?php // SEO-заголовок для HTML <title>. ?>
    <?= $form->field($model, 'title')->label('Заголовок')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->widget(ashch\tinymce\TinyMce::class); ?>

    <?php // SEO-опис сторінки, буде використаний як meta-description. ?>
    <?= $form->field($model, 'describe')->label('meta-description')->textarea(['rows' => 6]) ?>

    <?php // Robots meta tag: контроль індексації та переходу за посиланнями. ?>
    <?= $form->field($model, 'robots_tag')->dropDownList(
        \common\models\Page::getRobotsTagItems(),
        ['prompt' => 'Оберіть Robots Tag']
    ) ?>

    <?php // Окремий блок для власних скриптів сторінки. ?>
    <?= $form->field($model, 'scripts')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
