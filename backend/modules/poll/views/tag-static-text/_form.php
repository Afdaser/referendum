<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Language;
use common\models\Tag;

/** @var yii\web\View $this */
/** @var common\models\TagStaticText $model */
/** @var array<string,string> $placeholders */

$tagItems = Tag::find()->select('name')->orderBy('name')->indexBy('id')->column();
$languageItems = Language::dropDownAllItems();
?>

<div class="tag-static-text-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tag_id')->dropDownList($tagItems, [
        'prompt' => 'Оберіть тег…',
    ]) ?>

    <?= $form->field($model, 'language_id')->dropDownList($languageItems, [
        'prompt' => 'Виберіть мову…',
    ]) ?>

    <?= $form->field($model, 'content')->textarea([
        'rows' => 10,
        'placeholder' => 'Введіть текст з HTML-розміткою або простим текстом.',
    ])->hint('Використовуйте плейсхолдери зі списку нижче. HTML не обрізається, тож можна одразу вставляти готову верстку.') ?>

    <div class="alert alert-info">
        <p><strong>Доступні змінні:</strong></p>
        <ul class="list-unstyled">
            <?php foreach ($placeholders as $code => $description): ?>
                <li><code><?= Html::encode($code) ?></code> — <?= Html::encode($description) ?></li>
            <?php endforeach; ?>
        </ul>
        <p>Якщо для змінної немає значення, вона прибирається автоматично.</p>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
