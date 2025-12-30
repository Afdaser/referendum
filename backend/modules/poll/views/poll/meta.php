<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Poll $model */

$this->title = 'Мета-теги опитування: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="poll-meta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Мета-теги сторінки</strong>
        </div>
        <div class="panel-body">
            <?php // Поля мета-тегів зберігаються окремо від основного тексту опитування. ?>
            <?= $form->field($model, 'heading')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_description')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Повернутися до опитування', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
