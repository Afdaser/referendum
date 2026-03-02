<?php

use common\models\FooterLink;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var FooterLink $model */
/** @var array $languageOptions */

$this->title = 'Редагування посилання футера';
$this->params['breadcrumbs'][] = ['label' => 'Футер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="footer-link-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="box box-primary">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <!-- Селектор з існуючих локалей, щоб не вводити невалідні коди вручну. -->
            <?= $form->field($model, 'language_code')->dropDownList($languageOptions, ['prompt' => 'Усі мови/країни'])
                ->hint('Оберіть локаль або залиште порожньо для відображення в усіх мовах/країнах.') ?>
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
