<?php

use common\models\User;
use common\helpers\VoteLocations;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Poll $poll */
$step = $step ?? 1;
$totalSteps = $totalSteps ?? 2;
$countryCode = VoteLocations::countryForHost(Yii::$app->request->hostName);
$isNz = $countryCode === 'NZ';
$isUa = $countryCode === 'UA';
$labels = [1 => 'Стать при народженні', 2 => 'Ваша вікова група',
    3 => $isUa ? 'У якій області або місті ви проживаєте?' : ($isNz ? 'У якому регіоні ви проживаєте?' : 'У якому штаті ви проживаєте?')];
$countryLabel = $isUa ? 'Країна: Україна' : ($isNz ? 'Країна: Нова Зеландія' : 'Країна: США');
$regionLabel = $isUa ? 'Область або місто' : ($isNz ? 'Регіон' : 'Штат');
$regionPrompt = $isUa ? 'Оберіть область або місто' : ($isNz ? 'Оберіть регіон' : 'Оберіть штат');
$label = $labels[$step];
?>
<section class="guest-sample" aria-label="<?= Html::encode(Yii::t('poll', $label)); ?>">
    <p class="guest-sample__saved"><?= Html::encode(Yii::t('poll', 'Ваш голос зараховано')); ?></p>
    <p class="guest-sample__progress"><?= Html::encode(Yii::t('poll', 'Крок {step} з {total}', ['step' => $step, 'total' => $totalSteps])); ?></p>
    <h2 tabindex="-1"><?= Html::encode(Yii::t('poll', 'Будь ласка, доповніть вибірку')); ?></h2>
    <p class="guest-sample__registration-hint"><?= Html::encode(Yii::t('poll', 'Зареєструйтеся, щоб не повторювати ці кроки.')); ?></p>
    <?php if ($error = Yii::$app->session->getFlash('guestSampleError')): ?>
        <p class="guest-sample__error" role="alert"><?= Html::encode($error); ?></p>
    <?php endif; ?>
    <?= Html::beginForm(['guest-sample', 'id' => $poll->id], 'post'); ?>
        <?= Html::hiddenInput('step', $step); ?>
        <fieldset class="guest-sample__choices">
            <legend><?= Html::encode(Yii::t('poll', $label)); ?></legend>
            <?php if ($step === 1): ?>
            <?= Html::submitButton(Html::encode(Yii::t('poll', 'Народжений чоловіком')), [
                'name' => 'sex_at_birth', 'value' => User::SEX_MAN, 'class' => 'guest-sample__choice',
            ]); ?>
            <?= Html::submitButton(Html::encode(Yii::t('poll', 'Народжена жінкою')), [
                'name' => 'sex_at_birth', 'value' => User::SEX_WOMAN, 'class' => 'guest-sample__choice',
            ]); ?>
            <?php elseif ($step === 2): ?>
                <?php foreach (User::getUserAgeIntervalList() as $value => $label): ?>
                    <?= Html::submitButton(Html::encode($label), [
                        'name' => 'age_interval', 'value' => $value, 'class' => 'guest-sample__choice',
                    ]); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="guest-sample__country"><?= Html::encode(Yii::t('poll', $countryLabel)); ?></p>
                <?= Html::label(Html::encode(Yii::t('poll', $regionLabel)), 'guest-state-' . $poll->id); ?>
                <?= Html::dropDownList('state_code', null, VoteLocations::regions($countryCode), [
                    'id' => 'guest-state-' . $poll->id,
                    'class' => 'guest-sample__state',
                    'prompt' => Yii::t('poll', $regionPrompt),
                    'required' => true,
                ]); ?>
                <?= Html::submitButton(Html::encode(Yii::t('poll', 'Зберегти та показати результати')), [
                    'class' => 'guest-sample__choice',
                ]); ?>
            <?php endif; ?>
        </fieldset>
        <?= Html::submitButton(Html::encode(Yii::t('poll', 'Показати результати (пропустити)')), [
            'name' => 'skip', 'value' => '1', 'class' => 'guest-sample__skip', 'formnovalidate' => true,
        ]); ?>
    <?= Html::endForm(); ?>
</section>
