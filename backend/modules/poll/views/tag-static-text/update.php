<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\TagFaq;

/** @var yii\web\View $this */
/** @var common\models\Language $language */
/** @var common\models\TagStaticText $model */
/** @var array $tokens */
/** @var TagFaq[] $faqModels */

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
        <?= $form->field($model, 'content')
            ->textarea(['rows' => 10, 'class' => 'form-control'])
            ->hint('Можна використовувати HTML і змінні з переліку вище.'); ?>

        <h2>FAQ для сторінки тегу</h2>
        <p>
            Додайте питання та відповіді, які будуть показані в блоці Schema FAQ. У тексті також працюють змінні з переліку вище,
            тому можна автоматично підставляти статистику. Порожній блок буде проігноровано під час збереження.
        </p>

        <div id="faq-items" data-index="<?= count($faqModels); ?>">
            <?php foreach ($faqModels as $index => $faqModel): ?>
                <?= $this->render('_faq-item', [
                    'form' => $form,
                    'faqModel' => $faqModel,
                    'index' => $index,
                ]); ?>
            <?php endforeach; ?>
        </div>

        <p>
            <?= Html::button('Додати питання', ['class' => 'btn btn-default', 'id' => 'faq-add']); ?>
        </p>

        <template id="faq-template">
            <?= $this->render('_faq-item', [
                'form' => $form,
                'faqModel' => new TagFaq(['language_id' => $language->id]),
                'index' => '__index__',
            ]); ?>
        </template>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-success']); ?>
            <?= Html::a('Скасувати', ['index'], ['class' => 'btn btn-default']); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<JS
(function () {
    const container = document.getElementById('faq-items');
    const addButton = document.getElementById('faq-add');
    const template = document.getElementById('faq-template');

    if (!container || !addButton || !template) {
        return;
    }

    let index = Number(container.dataset.index) || 0;

    addButton.addEventListener('click', function (event) {
        event.preventDefault();
        const html = template.innerHTML.replace(/__index__/g, index);
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
        const element = wrapper.firstElementChild;
        if (element) {
            container.appendChild(element);
            index += 1;
            container.dataset.index = String(index);
        }
    });

    container.addEventListener('click', function (event) {
        const removeButton = event.target.closest('.faq-remove');
        if (!removeButton) {
            return;
        }

        event.preventDefault();
        const item = removeButton.closest('.faq-item');
        if (item) {
            item.remove();
        }
    });
})();
JS;
$this->registerJs($script);
</div>
