<?php

use common\models\News;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var News $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'desc')->textarea(['rows' => 2]) ?>
    <?= $form->field($model, 'excerpt')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'content')->widget(ashch\tinymce\TinyMce::class); ?>

    <?php if (!empty($model->image_url)): ?>
        <?php // Показуємо поточне зображення перед вибором нового файлу, щоб редактор не замінив його випадково. ?>
        <div class="form-group">
            <label class="control-label">Поточне зображення</label>
            <div>
                <?= Html::img($model->image_url, [
                    'alt' => $model->title,
                    'style' => 'max-width: 320px; width: 100%; height: auto;',
                ]) ?>
            </div>
            <p class="help-block">Новий файл замінить URL зображення після збереження.</p>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'imageFile')->fileInput(['accept' => 'image/jpeg,image/png']) ?>
    <?= $form->field($model, 'published_at')->textInput(['type' => 'datetime-local']) ?>
    <?= $form->field($model, 'is_published')->dropDownList(News::getPublishedItems()) ?>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
