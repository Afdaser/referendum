<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\TagFaq;

/** @var ActiveForm $form */
/** @var TagFaq $faqModel */
/** @var int|string $index */

// Компонент для редагування одного елементу FAQ.
// Виносимо в окремий шаблон, щоб використовувати як для наявних записів, так і для прототипу нових.
?>
<div class="panel panel-default faq-item">
    <div class="panel-heading">
        <strong>FAQ</strong>
        <button type="button" class="close faq-remove" title="Видалити блок" aria-label="Видалити">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="panel-body">
        <?= Html::hiddenInput("TagFaq[{$index}][id]", $faqModel->id); ?>
        <?= $form->field($faqModel, "[{$index}]question")
            ->textarea(['rows' => 2])
            ->hint('Питання може містити HTML та змінні. Використовуйте, наприклад, @tag.'); ?>
        <?= $form->field($faqModel, "[{$index}]answer")
            ->textarea(['rows' => 4])
            ->hint('Відповідь дозволяє HTML. Порожній блок буде видалено під час збереження.'); ?>
    </div>
</div>
