<?php

use common\models\FooterLink;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var FooterLink $model */

$this->title = 'Редагування посилання футера';
$this->params['breadcrumbs'][] = ['label' => 'Футер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="footer-link-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="box box-primary">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <!-- Даємо можливість вказати довільний код мови для масштабування. -->
            <?= $form->field($model, 'language_code')->textInput(['maxlength' => true])->hint('Напр.: uk, en-US. Порожньо = для всіх мов.') ?>
            <?= $form->field($model, 'anchor')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'sort_order')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Назад', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
