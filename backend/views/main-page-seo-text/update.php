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
        Заповніть H1, мета-теги та SEO-текст для домену <strong><?= Html::encode($domainLabel); ?></strong>.
        Порожні поля видалять збережені значення та повернуть стандартну логіку показу текстів.
    </p>

    <?php $form = ActiveForm::begin(); ?>
        <?php
        // Поля для мета-тегів залишаємо у звичайному текстовому вигляді, як і H1.
        ?>
        <?= $form->field($model, 'meta_title')
            ->textInput(['maxlength' => true])
            ->hint('Значення потрапляє у мета-тег title для головної сторінки.'); ?>

        <?= $form->field($model, 'meta_description')
            ->textInput(['maxlength' => true])
            ->hint('Значення потрапляє у мета-тег description для головної сторінки.'); ?>

        <?= $form->field($model, 'paginated_meta_title')
            ->textInput(['maxlength' => true])
            ->hint('Використовується на сторінках пагінації головної (/page/N). Доступна змінна @page.'); ?>

        <?= $form->field($model, 'paginated_meta_description')
            ->textInput(['maxlength' => true])
            ->hint('Окремий description для сторінок пагінації. Можна вказати @page для номера поточної сторінки.'); ?>

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
