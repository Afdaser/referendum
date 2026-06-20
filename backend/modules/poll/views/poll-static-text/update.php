<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Language $language */
/** @var common\models\PollStaticText $model */
/** @var array $metaTokens */
/** @var array $infoTokens */
/** @var common\models\PollStaticFaq[] $faqModels */

$this->title = 'Статичний текст для опитувань: ' . $language->title;
$this->params['breadcrumbs'][] = ['label' => 'Статичний текст для опитувань', 'url' => ['index']];
$this->params['breadcrumbs'][] = $language->title;

// Локальний CSS без втручання в інші сторінки адмінки.
$this->registerCss(<<<CSS
.poll-static-token-list {
    column-count: 2;
    column-gap: 32px;
}
.poll-static-token-list li {
    break-inside: avoid;
}
@media (max-width: 767px) {
    .poll-static-token-list {
        column-count: 1;
    }
}
CSS
);
?>
<div class="poll-static-text-update">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Тут можна налаштувати статичний HTML-блок зі статистикою та мета-теги сторінок опитувань.
        Нижче наведено змінні, які можна використовувати у статичному тексті:
    </p>
    <ul class="poll-static-token-list">
        <?php // Розбиваємо довгий список змінних на два стовпці, щоб адмінка лишалася зручною. ?>
        <?php foreach ($infoTokens as $token => $description): ?>
            <li><code><?= Html::encode($token); ?></code> — <?= Html::encode($description); ?></li>
        <?php endforeach; ?>
    </ul>
    <p>
        Порожні поля видалять збережені значення та повернуть стандартну логіку формування текстів.
    </p>

    <?php $form = ActiveForm::begin(); ?>
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Статичний HTML-блок зі статистикою</strong></div>
            <div class="panel-body">
                <?php // Дозволяємо редагувати весь HTML-блок через змінні. ?>
                <?= $form->field($model, 'content')
                    ->textarea(['rows' => 12, 'class' => 'form-control'])
                    ->hint('Можна використовувати HTML і змінні з переліку вище.'); ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Мета-теги сторінки опитування</strong></div>
            <div class="panel-body">
                <p>
                    Доступні змінні для цих полів:
                </p>
                <ul class="poll-static-token-list">
                    <?php // Такий самий двоколонковий вигляд для змінних мета-тегів. ?>
                    <?php foreach ($metaTokens as $token => $description): ?>
                        <li><code><?= Html::encode($token); ?></code> — <?= Html::encode($description); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?= $form->field($model, 'heading')
                    ->textInput(['maxlength' => true])
                    ->hint('Значення потрапляє у головний H1. Можна вставляти змінні на кшталт @title.'); ?>
                <?= $form->field($model, 'meta_title')
                    ->textInput(['maxlength' => true])
                    ->hint('Використовується у &lt;title&gt;. Якщо залишити порожнім — застосовується стандартна формула.'); ?>
                <?= $form->field($model, 'meta_description')
                    ->textarea(['rows' => 4, 'class' => 'form-control'])
                    ->hint('Власний мета-опис. Порожнє значення повертає автоматичний опис.'); ?>
            </div>
        </div>

        
        <div class="panel panel-default">
            <div class="panel-heading"><strong>FAQ для сторінки опитування (статичний)</strong></div>
            <div class="panel-body">
                <p>Ці FAQ додаються для всіх опитувань обраної підмови. Доступні ті самі змінні, що і вище.</p>
                <div class="faq-items">
                    <?php foreach ($faqModels as $index => $faqModel): ?>
                        <div class="faq-item well">
                            <?= Html::hiddenInput("PollStaticFaq[$index][id]", $faqModel->id); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= Html::label('Питання', "poll-static-faq-question-$index"); ?>
                                    <?= Html::textarea("PollStaticFaq[$index][question]", $faqModel->question, ['id'=>"poll-static-faq-question-$index", 'class'=>'form-control', 'rows'=>3]); ?>
                                </div>
                                <div class="col-md-6">
                                    <?= Html::label('Відповідь', "poll-static-faq-answer-$index"); ?>
                                    <?= Html::textarea("PollStaticFaq[$index][answer]", $faqModel->answer, ['id'=>"poll-static-faq-answer-$index", 'class'=>'form-control', 'rows'=>3]); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']); ?>
            <?= Html::a('Скасувати', ['index'], ['class' => 'btn btn-default']); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
