<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Language $language */
/** @var common\models\PollStaticText $model */
/** @var array $tokens */

$this->title = 'Мета-теги для опитувань: ' . $language->title;
$this->params['breadcrumbs'][] = ['label' => 'Статичний текст для опитувань', 'url' => ['index']];
$this->params['breadcrumbs'][] = $language->title;
?>
<div class="poll-static-text-update">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Тут можна задати H1, &lt;title&gt; та meta description для сторінок опитувань.
        Нижче наведено змінні, які можна використовувати у цих полях:
    </p>
    <ul>
        <?php foreach ($tokens as $token => $description): ?>
            <li><code><?= Html::encode($token); ?></code> — <?= Html::encode($description); ?></li>
        <?php endforeach; ?>
    </ul>
    <p>
        Порожні поля видалять збережені значення та повернуть стандартну логіку формування мета-тегів.
    </p>

    <?php $form = ActiveForm::begin(); ?>
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Мета-теги сторінки опитування</strong></div>
            <div class="panel-body">
                <?= $form->field($model, 'heading')
                    ->textInput(['maxlength' => true])
                    ->hint('Значення потрапляє у головний H1. Можна вставляти змінні на кшталт @title.'); ?>
                <?= $form->field($model, 'meta_title')
                    ->textInput(['maxlength' => true])
                    ->hint('Використовується у &lt;title&gt;. Якщо залишити порожнім — застосовується стандартна формула.'); ?>
                <?= $form->field($model, 'meta_description')
                    ->textarea(['rows' => 4, 'class' => 'form-control'])
                    ->hint('Власний мета-опис. Порожнє значення повертає автоматичний опис.'); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']); ?>
            <?= Html::a('Скасувати', ['index'], ['class' => 'btn btn-default']); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
