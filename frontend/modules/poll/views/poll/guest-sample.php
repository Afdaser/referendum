<?php

use common\models\User;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Poll $poll */
?>
<div class="chart_b guest-sample" aria-labelledby="guest-sample-title">
    <p class="guest-sample__saved"><?= Html::encode(Yii::t('poll', 'Ваш голос зараховано')); ?></p>
    <h1 id="guest-sample-title"><?= Html::encode(Yii::t('poll', 'Будь ласка, доповніть вибірку')); ?></h1>
    <p class="guest-sample__poll"><?= Html::encode($poll->title); ?></p>
    <p id="guest-sample-description"><?= Html::encode(Yii::t('poll', 'За бажанням вкажіть стать при народженні. Відповідь доповнить ваш голос у статистиці опитування.')); ?></p>
    <?php if ($error = Yii::$app->session->getFlash('guestSampleError')): ?>
        <p class="guest-sample__error" role="alert"><?= Html::encode($error); ?></p>
    <?php endif; ?>
    <?= Html::beginForm(['guest-sample', 'id' => $poll->id], 'post', ['aria-describedby' => 'guest-sample-description']); ?>
        <fieldset class="guest-sample__choices">
            <legend><?= Html::encode(Yii::t('poll', 'Стать при народженні')); ?></legend>
            <?= Html::submitButton(Html::encode(Yii::t('poll', 'Народжений чоловіком')), [
                'name' => 'sex_at_birth', 'value' => User::SEX_MAN, 'class' => 'guest-sample__choice',
            ]); ?>
            <?= Html::submitButton(Html::encode(Yii::t('poll', 'Народжена жінкою')), [
                'name' => 'sex_at_birth', 'value' => User::SEX_WOMAN, 'class' => 'guest-sample__choice',
            ]); ?>
        </fieldset>
        <p class="guest-sample__optional"><?= Html::encode(Yii::t('poll', 'Це необов’язково — результати доступні й без відповіді.')); ?></p>
        <?= Html::submitButton(Html::encode(Yii::t('poll', 'Показати результати (пропустити)')), [
            'name' => 'skip', 'value' => '1', 'class' => 'guest-sample__skip',
        ]); ?>
    <?= Html::endForm(); ?>
</div>
