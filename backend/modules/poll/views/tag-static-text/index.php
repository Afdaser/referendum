<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Language[] $languages */
/** @var common\models\TagStaticText[] $models */
/** @var array $tokens */

$this->title = 'Статичний текст на тегах';
?>
<div class="tag-static-text-index">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Тут можна вказати власний HTML-текст для блоку на сторінці тегу. Використовуйте змінні, щоб підставити актуальні дані:
    </p>
    <ul>
        <?php foreach ($tokens as $token => $description): ?>
            <li><code><?= Html::encode($token); ?></code> — <?= Html::encode($description); ?></li>
        <?php endforeach; ?>
    </ul>
    <p>
        Порожнє поле вилучає текст для відповідної мови та вмикає стандартний автоматичний опис.
    </p>

    <?php $form = ActiveForm::begin(); ?>

    <?php foreach ($languages as $language): ?>
        <?php $model = $models[$language->id]; ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($language->title); ?></h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, "[{$language->id}]language_id")->hiddenInput()->label(false); ?>
                <?= $form->field($model, "[{$language->id}]content")
                    ->textarea(['rows' => 8, 'class' => 'form-control'])
                    ->hint('Можна використовувати HTML та змінні з переліку вище.'); ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
