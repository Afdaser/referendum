<?php

use common\models\User;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Poll $poll */
?>
<section class="guest-sample" aria-label="<?= Html::encode(Yii::t('poll', 'Стать при народженні')); ?>">
    <p class="guest-sample__saved"><?= Html::encode(Yii::t('poll', 'Ваш голос зараховано')); ?></p>
    <h2 tabindex="-1"><?= Html::encode(Yii::t('poll', 'Будь ласка, доповніть вибірку')); ?></h2>
    <?php if ($error = Yii::$app->session->getFlash('guestSampleError')): ?>
        <p class="guest-sample__error" role="alert"><?= Html::encode($error); ?></p>
    <?php endif; ?>
    <?= Html::beginForm(['guest-sample', 'id' => $poll->id], 'post'); ?>
        <fieldset class="guest-sample__choices">
            <legend><?= Html::encode(Yii::t('poll', 'Стать при народженні')); ?></legend>
            <?= Html::submitButton(Html::encode(Yii::t('poll', 'Народжений чоловіком')), [
                'name' => 'sex_at_birth', 'value' => User::SEX_MAN, 'class' => 'guest-sample__choice',
            ]); ?>
            <?= Html::submitButton(Html::encode(Yii::t('poll', 'Народжена жінкою')), [
                'name' => 'sex_at_birth', 'value' => User::SEX_WOMAN, 'class' => 'guest-sample__choice',
            ]); ?>
        </fieldset>
        <?= Html::submitButton(Html::encode(Yii::t('poll', 'Показати результати (пропустити)')), [
            'name' => 'skip', 'value' => '1', 'class' => 'guest-sample__skip',
        ]); ?>
    <?= Html::endForm(); ?>
</section>
