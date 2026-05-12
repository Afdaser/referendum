<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use common\models\Language;
use common\models\User;
use common\models\Tag;
use common\models\PollFaq;

/** @var yii\web\View $this */
/** @var common\models\Poll $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="poll-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Мета-теги сторінки опитування</strong></div>
        <div class="panel-body">
            <?php // Індивідуальні поля мають пріоритет над шаблоном, але можуть залишатись порожніми. ?>
            <?= $form->field($model, 'meta_h1')->textInput(['maxlength' => true])
                ->hint('Кастомний H1 для цього опитування. Якщо порожньо — використається шаблон з налаштувань мови.'); ?>
            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true])
                ->hint('Кастомний title для цього опитування. Якщо порожньо — використається шаблон з налаштувань мови.'); ?>
            <?= $form->field($model, 'meta_description')->textarea(['rows' => 4, 'class' => 'form-control'])
                ->hint('Кастомний description для цього опитування. Якщо порожньо — використається шаблон з налаштувань мови.'); ?>
        </div>
    </div>
    <?= $form->field($model, 'describe')->widget(ashch\tinymce\TinyMce::class); ?>
    <?php
    // Завантажуємо індивідуальні FAQ для цього опитування (або показуємо 2 порожні рядки для нового).
    $pollFaqItems = $model->isNewRecord ? [] : PollFaq::find()->where(['poll_id' => $model->id])->orderBy(['position' => SORT_ASC, 'id' => SORT_ASC])->all();
    $pollFaqItems[] = new PollFaq();
    $pollFaqItems[] = new PollFaq();
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Індивідуальний FAQ для цього опитування</strong></div>
        <div class="panel-body">
            <?php foreach ($pollFaqItems as $index => $faqItem): ?>
                <div class="well">
                    <?= Html::hiddenInput("PollFaq[$index][id]", $faqItem->id); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= Html::label('Питання', "poll-faq-question-$index"); ?>
                            <?= Html::textarea("PollFaq[$index][question]", $faqItem->question, ['id' => "poll-faq-question-$index", 'class' => 'form-control', 'rows' => 3]); ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::label('Відповідь', "poll-faq-answer-$index"); ?>
                            <?= Html::textarea("PollFaq[$index][answer]", $faqItem->answer, ['id' => "poll-faq-answer-$index", 'class' => 'form-control', 'rows' => 3]); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?= $form->field($model, 'poll_language_id')->widget(Select2::classname(), [
                'data' => Language::dropDownAllItems(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select language...')],
                'pluginOptions' => ['allowClear' => true,],
            ]);
            ?>

    <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
                'data' => User::dropDownAllItems(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('app', 'Select user...')],
                'pluginOptions' => ['allowClear' => true,],
            ]);
            ?>

    <?php
    // Показуємо лише теги поточної мови опитування, щоб не змішувати піддомени в адмінці.
    $tagItems = $model->poll_language_id !== null
        ? Tag::find()->select(['name'])->where(['language_id' => (int)$model->poll_language_id])->indexBy('name')->orderBy(['name' => SORT_ASC])->column()
        : [];
    ?>

    <?= $form->field($model, 'tagNames')->widget(Select2::classname(), [
                'data' => $tagItems,
                'language' => Yii::$app->language,
                'options' => [
                    'multiple' => true,
                    'placeholder' => Yii::t('app', 'Tags'),
                ],
                'pluginOptions' => [
                    // Вмикаємо режим «тегів», щоб можна було додавати нові значення вручну.
                    'tags' => true,
                    'tokenSeparators' => [','],
                    'allowClear' => true,
                ],
            ]);
            ?>

    <?= $form->field($model, 'rating')->textInput() ?>

    <?= $form->field($model, 'views')->textInput() ?>

    <?= $form->field($model, 'result_type')->textInput() ?>    

    <?= $form->field($model, 'show_for_all_languages')->textInput() ?>


    <?php // Перенесли службові поля вниз форми, щоб SEO-блок був одразу після заголовка. ?>
    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'votes_count_close')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'show_on_slider')->textInput() ?>

    <hr>
    

        


    <?= $form->field($model, 'poll_sex')->textInput() ?>

    <?php /* = $form->field($model, 'poll_country_id')->textInput() ?>
    <?= $form->field($model, 'poll_region_id')->textInput() ?>
    <?= $form->field($model, 'poll_city_id')->textInput() /* */ ?>
    <?=
    $form->field($model, 'poll_country_id')->widget(Select2::classname(), [
        'data' => common\models\Country::dropDownAllItems(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => Yii::t('app', 'Select country...')],
        'pluginOptions' => ['allowClear' => true,],
    ]);
    ?>

    <?= $form->field($model, 'poll_region_id')->widget(Select2::classname(), [
        'data' => common\models\CountryRegion::dropDownAllItems(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => Yii::t('app', 'Select region...')],
        'pluginOptions' => ['allowClear' => true,],
    ]);
    ?>
    <?= $form->field($model, 'poll_city_id')->widget(Select2::classname(), [
        'data' => common\models\RegionCity::dropDownAllItems(),
        'language' => Yii::$app->language,
        'options' => ['placeholder' => Yii::t('app', 'Select city...')],
        'pluginOptions' => ['allowClear' => true,],
    ]);
    ?>

    <?= $form->field($model, 'poll_min_age')->textInput() ?>

    <?= $form->field($model, 'poll_max_age')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
