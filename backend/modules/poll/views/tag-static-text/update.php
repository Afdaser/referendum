<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Language $language */
/** @var common\models\TagStaticText $model */
/** @var array $tokens */
/** @var common\models\TagStaticFaq[] $faqModels */

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
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Основний статичний текст</strong></div>
            <div class="panel-body">
                <?= $form->field($model, 'content')
                    ->textarea(['rows' => 10, 'class' => 'form-control'])
                    ->hint('Можна використовувати HTML і змінні з переліку вище.'); ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Мета-теги сторінки</strong></div>
            <div class="panel-body">
                <p>
                    Ці поля керують заголовком H1, тегом &lt;title&gt; та мета-описом на сторінці тегу.
                    Дозволено використовувати ті самі змінні, що й у статичному тексті.
                </p>
                <?= $form->field($model, 'heading')
                    ->textInput(['maxlength' => true])
                    ->hint('Значення потрапляє у головний H1. Можна вставляти змінні на кшталт @tag.'); ?>
                <?= $form->field($model, 'meta_title')
                    ->textInput(['maxlength' => true])
                    ->hint('Використовується у &lt;title&gt;. Якщо залишити порожнім — застосовується стандартна формула.'); ?>
                <?= $form->field($model, 'meta_description')
                    ->textarea(['rows' => 4, 'class' => 'form-control'])
                    ->hint('Власний мета-опис. Порожнє значення повертає автоматичний опис.'); ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>FAQ для сторінки тегу</strong></div>
            <div class="panel-body">
                <p>
                    У кожному блоці нижче введіть запитання та відповідь. Змінні працюють так само, як і в основному тексті.
                    Щоб видалити пункт, очистіть обидва поля та натисніть «Зберегти».
                </p>
                <div class="faq-items">
                    <?php foreach ($faqModels as $index => $faqModel): ?>
                        <div class="faq-item well">
                            <?= Html::hiddenInput("TagStaticFaq[$index][id]", $faqModel->id); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= Html::label('Питання', "faq-question-$index"); ?>
                                    <?= Html::textarea(
                                        "TagStaticFaq[$index][question]",
                                        $faqModel->question,
                                        [
                                            'class' => 'form-control',
                                            'rows' => 3,
                                            'id' => "faq-question-$index",
                                            'placeholder' => 'Наприклад: Які опитування по @tag найпопулярніші?',
                                        ]
                                    ); ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::label('Відповідь', "faq-answer-$index"); ?>
                                    <?= Html::textarea(
                                        "TagStaticFaq[$index][answer]",
                                        $faqModel->answer,
                                        [
                                            'class' => 'form-control',
                                            'rows' => 3,
                                            'id' => "faq-answer-$index",
                                            'placeholder' => 'Відповідь можна форматувати HTML та змінними (@latestlink тощо).',
                                        ]
                                    ); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="help-block">
                    Порожні блоки можна залишати — система автоматично їх пропустить, але завжди показуємо мінімум два, щоб зручно додавати нові.
                </p>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Блок «Latest activity in {TAG} polls»</strong></div>
            <div class="panel-body">
                <p>
                    Цей шаблон керує окремим блоком активності на сторінці тегу.
                    Якщо лишити поле порожнім, фронтенд покаже стандартний варіант блоку.
                </p>
                <?= $form->field($model, 'latest_activity_template')
                    ->textarea(['rows' => 8, 'class' => 'form-control'])
                    ->hint('Можна використовувати HTML і змінні @tag, @votes90d, @polls30d, @weeklypoll (також підтримується @votes7d для сумісності).'); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']); ?>
            <?= Html::a('Скасувати', ['index'], ['class' => 'btn btn-default']); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
