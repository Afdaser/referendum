<?php

use common\models\FooterLink;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var FooterLink $model */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $languageOptions */

$this->title = 'Футер';
?>
<div class="footer-link-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Нове посилання футера</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <!-- Використовуємо селектор тільки з доступних у БД локалей (мова + країна). -->
            <?= $form->field($model, 'language_code')->dropDownList($languageOptions, ['prompt' => 'Усі мови/країни'])
                ->hint('Оберіть конкретну локаль (напр. en-US або en-NZ), або залиште порожньо для всіх.') ?>
            <?= $form->field($model, 'anchor')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'url')->textInput(['maxlength' => true])->hint('Приклад: /about або https://example.com/page') ?>
            <?= $form->field($model, 'sort_order')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Список посилань</h3>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'language_code',
                        'value' => static function (FooterLink $row) {
                            // Порожній код мови означає глобальне посилання для всіх мов.
                            return FooterLink::getLanguageLabel($row->language_code);
                        },
                    ],
                    'anchor',
                    'url',
                    'sort_order',
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
                                    'data-confirm' => 'Видалити це посилання?',
                                ]);
                            },
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
