<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Language $language */
/** @var common\models\PollStaticText $model */
/** @var array $tokens */

$this->title = 'Статичний текст для: ' . $language->title;
$this->params['breadcrumbs'][] = ['label' => 'Статичний текст на опитуваннях', 'url' => ['index']];
$this->params['breadcrumbs'][] = $language->title;
?>
<div class="poll-static-text-update">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Тут можна задати шаблони тексту для блоку статистики на сторінці опитування.
        Нижче наведено перелік змінних, які можна вставляти у шаблони:
    </p>
    <ul>
        <?php foreach ($tokens as $token => $description): ?>
            <li><code><?= Html::encode($token); ?></code> — <?= Html::encode($description); ?></li>
        <?php endforeach; ?>
    </ul>
    <p>
        Якщо поле порожнє, буде використано стандартний текст сайту.
    </p>

    <?php $form = ActiveForm::begin(); ?>
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Шаблони блоку статистики</strong></div>
            <div class="panel-body">
                <?= $form->field($model, 'heading')
                    ->textInput(['maxlength' => true])
                    ->hint('Заголовок блоку (H2). Можна використовувати змінні.'); ?>
                <?= $form->field($model, 'summary')
                    ->textarea(['rows' => 5, 'class' => 'form-control'])
                    ->hint('Основний текст із підстановками.'); ?>
                <?= $form->field($model, 'options_intro')
                    ->textarea(['rows' => 3, 'class' => 'form-control'])
                    ->hint('Фраза перед переліком варіантів відповіді.'); ?>
                <?= $form->field($model, 'most_popular')
                    ->textarea(['rows' => 3, 'class' => 'form-control'])
                    ->hint('Пояснення про найпопулярнішу відповідь.'); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']); ?>
            <?= Html::a('Скасувати', ['index'], ['class' => 'btn btn-default']); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
