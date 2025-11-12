<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Language[] $languages */

$this->title = 'Статичний текст на тегах';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-static-text-index">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Ця сторінка зібрала всі підмови, для яких у нас є перекладені теги або вже збережений статичний текст.
        Тут же редагується додатковий FAQ для тегів, який виводиться як Schema.org microdata.
        Оберіть потрібну підмову, щоб перейти на окрему сторінку редагування.
    </p>

    <?php if (empty($languages)): ?>
        <div class="alert alert-info">
            Поки що немає мов із перекладами тегів або статичними текстами. Додайте переклади, щоб розпочати роботу.
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
