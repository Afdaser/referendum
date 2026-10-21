<?php

use common\models\CookieConsentSetting;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var CookieConsentSetting $model */
/** @var array $languageOptions */

$this->title = 'Редагування правила cookie consent';
$this->params['breadcrumbs'][] = ['label' => 'Cookie consent', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cookie-consent-setting-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="box box-primary">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'host')->textInput(['maxlength' => true])
                ->hint('Без мовного піддомену: referendum.social (також для www.referendum.social, якщо немає окремого правила). Для мовної версії: en.referendum.social. * — для всіх хостів.') ?>
            <?= $form->field($model, 'language_code')->dropDownList($languageOptions, ['prompt' => CookieConsentSetting::getLanguageLabel('')])
                ->hint('Порожня локаль — усі мови лише в межах указаного хоста. Правило для referendum.social не поширюється на мовні піддомени.') ?>
            <?= $form->field($model, 'banner_text')->textarea(['rows' => 4]) ?>
            <?= $form->field($model, 'policy_url')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'essential_button_label')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'accept_button_label')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'is_active')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Назад', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

