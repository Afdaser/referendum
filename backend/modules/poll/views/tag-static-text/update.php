<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Language $language */
/** @var common\models\TagStaticText $model */
/** @var array $tokens */

$this->title = 'Статичний текст для: ' . $language->title;
$this->params['breadcrumbs'][] = ['label' => 'Статичний текст на тегах', 'url' => ['index']];
$this->params['breadcrumbs'][] = $language->title;
?>
<div class="tag-static-text-update">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Тут можна задати власний HTML-текст для блоку на сторінці тегу. Нижче наведено перелік змінних, які можна вставляти у текст:
    </p>
    <ul>
        <?php foreach ($tokens as $token => $description): ?>
            <li><code><?= Html::encode($token); ?></code> — <?= Html::encode($description); ?></li>
        <?php endforeach; ?>
    </ul>
    <p>
        Порожнє поле вилучить статичний текст для цієї підмови та поверне стандартний автоматичний опис.
    </p>

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'content')
            ->textarea(['rows' => 10, 'class' => 'form-control'])
            ->hint('Можна використовувати HTML і змінні з переліку вище.'); ?>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']); ?>
            <?= Html::a('Скасувати', ['index'], ['class' => 'btn btn-default']); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
