<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $statistics */

$cards = [
    ['key' => 'users', 'label' => Yii::t('main', 'Зареєстровані користувачі'), 'icon' => 'users'],
    ['key' => 'polls', 'label' => Yii::t('main', 'Створені опитування'), 'icon' => 'list-alt'],
    ['key' => 'votes', 'label' => Yii::t('main', 'Віддані голоси'), 'icon' => 'check-square-o'],
    ['key' => 'comments', 'label' => Yii::t('poll', 'Коментарі'), 'icon' => 'comment-o'],
];
// Для чисел і текстових назв місяців використовуємо локаль поточного піддомену,
// а не глобальну локаль formatter, яка в конфігурації за замовчуванням українська.
$statisticsFormatter = clone Yii::$app->formatter;
$statisticsFormatter->locale = Yii::$app->language;
$updatedAtFormat = Yii::$app->language === 'en-US' ? 'MMM d, y, HH:mm' : 'dd MMM y, HH:mm';
?>
<section class="landing-statistics" aria-labelledby="landing-statistics-title">
    <div class="landing-statistics__intro">
        <span class="landing-statistics__eyebrow"><?= Html::encode(Yii::t('main', 'Referendum у цифрах')); ?></span>
        <h2 id="landing-statistics-title"><?= Html::encode(Yii::t('main', 'Спільнота, що висловлює свою думку')); ?></h2>
        <p><?= Html::encode(Yii::t('main', 'Долучайтеся до відкритих опитувань, створюйте власні та голосуйте за важливе.')); ?></p>
    </div>
    <div class="landing-statistics__grid">
        <?php foreach ($cards as $card): ?>
            <article class="landing-statistics__card landing-statistics__card--<?= Html::encode($card['key']); ?>">
                <span class="landing-statistics__icon-wrap">
                    <span class="landing-statistics__icon" aria-hidden="true">
                        <i class="fa fa-<?= Html::encode($card['icon']); ?>"></i>
                    </span>
                    <?php if (($statistics['daily'][$card['key']] ?? 0) > 0): ?>
                        <span class="landing-statistics__daily"><i class="fa fa-plus" aria-hidden="true"></i><span class="sr-only">+</span><?= $statisticsFormatter->asInteger($statistics['daily'][$card['key']]); ?></span>
                    <?php endif; ?>
                </span>
                <strong>
                    <span><?= $statisticsFormatter->asInteger($statistics[$card['key']] ?? 0); ?></span>
                </strong>
                <span class="landing-statistics__label"><?= Html::encode($card['label']); ?></span>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($statistics['updatedAt'])): ?>
        <p class="landing-statistics__updated">
            <?= Html::encode(Yii::t('main', 'Дані оновлено: {date}', [
                'date' => $statisticsFormatter->asDatetime($statistics['updatedAt'], $updatedAtFormat),
            ])); ?>
        </p>
    <?php endif; ?>
</section>
