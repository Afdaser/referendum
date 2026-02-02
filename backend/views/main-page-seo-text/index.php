<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array<string, string> $domains */
/** @var array<string, common\models\MainPageSeoText> $records */

$this->title = 'SEO-текст головної сторінки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-page-seo-text-index">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Тут можна налаштувати заголовок H1 та SEO-текст для головних сторінок доменів і піддоменів.
        Оберіть потрібний домен, щоб відкрити форму редагування.
    </p>

    <?php if (empty($domains)): ?>
        <div class="alert alert-info">
            Поки що немає доступних доменів. Перевірте налаштування конфігурації.
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($domains as $domain => $label): ?>
                <?php
                // Підказуємо, чи вже збережено текст для домену.
                $record = $records[$domain] ?? null;
                $hasContent = $record ? !$record->isEmpty() : false;
                $statusLabel = $hasContent ? 'налаштовано' : 'порожньо';
                ?>
                <?= Html::a(
                    Html::encode($label) . ' <span class="pull-right text-muted">' . Html::encode($statusLabel) . '</span>',
                    ['update', 'domain' => $domain],
                    [
                        'class' => 'list-group-item list-group-item-action',
                        'data-pjax' => '0',
                    ]
                ); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
