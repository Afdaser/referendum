<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Language[] $languages */

$this->title = 'Статичний текст для опитувань';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-static-text-index">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Ця сторінка містить перелік підмов, для яких можна редагувати мета-теги сторінок опитувань.
        Оберіть підмову, щоб налаштувати H1, &lt;title&gt; та meta description.
    </p>

    <?php if (empty($languages)): ?>
        <div class="alert alert-info">
            Поки що немає мов із опитуваннями або збереженими мета-тегами. Додайте опитування, щоб розпочати роботу.
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($languages as $language): ?>
                <?= Html::a(
                    Html::encode($language->title),
                    ['update', 'languageId' => $language->id],
                    [
                        'class' => 'list-group-item list-group-item-action',
                        'data-pjax' => '0',
                    ]
                ); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
