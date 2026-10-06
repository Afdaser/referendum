<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $statistics */

$cards = [
    ['key' => 'users', 'label' => Yii::t('main', 'Зареєстровані користувачі'), 'icon' => 'users'],
    ['key' => 'polls', 'label' => Yii::t('main', 'Створені опитування'), 'icon' => 'polls'],
    ['key' => 'votes', 'label' => Yii::t('main', 'Віддані голоси'), 'icon' => 'votes'],
];
?>
<section class="landing-statistics" aria-labelledby="landing-statistics-title">
    <div class="landing-statistics__intro">
        <span class="landing-statistics__eyebrow"><?= Html::encode(Yii::t('main', 'Referendum у цифрах')); ?></span>
        <h2 id="landing-statistics-title"><?= Html::encode(Yii::t('main', 'Спільнота, що висловлює свою думку')); ?></h2>
        <p><?= Html::encode(Yii::t('main', 'Долучайтеся до відкритих опитувань, створюйте власні та голосуйте за важливе.')); ?></p>
    </div>
    <div class="landing-statistics__grid">
        <?php foreach ($cards as $card): ?>
            <article class="landing-statistics__card landing-statistics__card--<?= Html::encode($card['icon']); ?>">
                <span class="landing-statistics__icon" aria-hidden="true">
                    <?php if ($card['icon'] === 'users'): ?>
                        <svg viewBox="0 0 48 48"><circle cx="19" cy="16" r="7"/><path d="M6 38c1-8 6-12 13-12s12 4 13 12"/><circle cx="34" cy="19" r="5"/><path d="M32 29c6-1 10 3 11 9"/></svg>
                    <?php elseif ($card['icon'] === 'polls'): ?>
                        <svg viewBox="0 0 48 48"><rect x="9" y="6" width="30" height="36" rx="5"/><path d="M16 17h16M16 24h16M16 31h10"/><path d="m29 32 3 3 6-7"/></svg>
                    <?php else: ?>
                        <svg viewBox="0 0 48 48"><path d="M7 23h34v19H7zM12 23l7-17h18l4 17"/><path d="m18 15 5 5 10-10M16 32h16"/></svg>
                    <?php endif; ?>
                </span>
                <strong><?= Yii::$app->formatter->asInteger($statistics[$card['key']]); ?></strong>
                <span><?= Html::encode($card['label']); ?></span>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($statistics['updatedAt'])): ?>
        <p class="landing-statistics__updated">
            <?= Html::encode(Yii::t('main', 'Дані оновлено: {date}', [
                'date' => Yii::$app->formatter->asDatetime($statistics['updatedAt'], 'short'),
            ])); ?>
        </p>
    <?php endif; ?>
</section>
