<?php

use common\models\News;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var News $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'desc')->textarea(['rows' => 2]) ?>
    <?php // URL або шлях до hero-зображення; файл не завантажуємо тут, щоб не змінювати існуючий медіа-процес. ?>
    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'excerpt')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'content')->textarea(['rows' => 8]) ?>
    <?= $form->field($model, 'published_at')->textInput(['type' => 'datetime-local']) ?>
    <?= $form->field($model, 'is_published')->dropDownList(News::getPublishedItems()) ?>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
