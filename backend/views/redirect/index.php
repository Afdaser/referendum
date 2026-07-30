<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Redirect $model */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $domainOptions */

$this->title = 'Редіректи';
?>
<div class="redirect-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Новий редірект</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
                <!-- Простий блок з трьома полями: піддомен, звідки та куди. -->
                <!-- Поле вибору піддомену з активних доменів. -->
                <?= $form->field($model, 'subdomain')
                    ->dropDownList($domainOptions, ['prompt' => 'Оберіть домен'])
                    ->hint('Виберіть домен або залиште «Основний домен».') ?>
                <?= $form->field($model, 'from_path')->textInput(['maxlength' => true, 'placeholder' => '/stara-storinka']) ?>
                <?= $form->field($model, 'to_url')->textInput(['maxlength' => true, 'placeholder' => '/nova-storinka']) ?>

                <div class="form-group">
                    <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Список редіректів</h3>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'subdomain',
                        'value' => static function ($model) {
                            // Виводимо порожній піддомен як основний домен.
                            return $model->subdomain === '' ? 'Основний домен' : $model->subdomain;
                        },
                    ],
                    'from_path',
                    'to_url',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => static function ($url, $model) {
                                return Html::a('Видалити', $url, [
                                    'data-method' => 'post',
                                    'data-confirm' => 'Видалити цей редірект?',
                                ]);
                            },
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
