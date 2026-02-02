<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var string $domain */
/** @var string $domainLabel */
/** @var common\models\MainPageSeoText $model */

$this->title = 'SEO-текст головної сторінки: ' . $domainLabel;
$this->params['breadcrumbs'][] = ['label' => 'SEO-текст головної сторінки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $domainLabel;
?>
<div class="main-page-seo-text-update">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Заповніть H1 та SEO-текст для домену <strong><?= Html::encode($domainLabel); ?></strong>.
        Порожні поля видалять збережені значення та повернуть стандартну логіку показу текстів.
    </p>

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'heading')
            ->textInput(['maxlength' => true])
            ->hint('Значення потрапляє у головний H1 на головній сторінці.'); ?>

        <?php
        // Використовуємо внутрішній HTML-редактор TinyMCE для SEO-тексту.
        ?>
        <?= $form->field($model, 'content')->widget(ashch\tinymce\TinyMce::class); ?>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']); ?>
            <?= Html::a('Скасувати', ['index'], ['class' => 'btn btn-default']); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
