<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Poll $poll */
/** @var common\models\PollStaticText $model */

$this->title = 'Статичний текст опитування: ' . $poll->title;
$this->params['breadcrumbs'][] = ['label' => 'Статичний текст опитувань', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="poll-static-text-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Основний статичний текст</strong>
        </div>
        <div class="panel-body">
            <?php // Статичний текст буде показано під основним блоком опитування. ?>
            <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Мета-теги сторінки</strong>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'heading')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_description')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Повернутися до списку', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
