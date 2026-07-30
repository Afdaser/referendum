<?php

use common\models\CookieConsentSetting;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var CookieConsentSetting $model */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $languageOptions */

$this->title = 'Cookie consent';
?>
<div class="cookie-consent-setting-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Нове правило</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'host')->textInput(['maxlength' => true])
                ->hint('Наприклад: en.referendum.social або * для дефолтного правила.') ?>
            <?= $form->field($model, 'language_code')->dropDownList($languageOptions, ['prompt' => 'Усі локалі'])
                ->hint('Якщо не вибирати локаль — правило спрацює для всіх мов на цьому хості.') ?>
            <?= $form->field($model, 'banner_text')->textarea(['rows' => 4])
                ->hint('Текст банера, який відобразиться користувачу.') ?>
            <?= $form->field($model, 'policy_url')->textInput(['maxlength' => true])
                ->hint('Відносний шлях (/cookie-policy) або повний URL (https://...).') ?>
            <?= $form->field($model, 'essential_button_label')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'accept_button_label')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'is_active')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Список правил</h3>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'host',
                    [
                        'attribute' => 'language_code',
                        'value' => static function (CookieConsentSetting $row) {
                            return CookieConsentSetting::getLanguageLabel((string) $row->language_code);
                        },
                    ],
                    'policy_url',
                    'is_active:boolean',
                    [
                        'class' => 'yii\\grid\\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => static function ($url) {
                                return Html::a('Редагувати', $url);
                            },
                            'delete' => static function ($url) {
                                return Html::a('Видалити', $url, [
                                    'data-method' => 'post',
                                    'data-confirm' => 'Видалити це правило?',
                                ]);
                            },
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
