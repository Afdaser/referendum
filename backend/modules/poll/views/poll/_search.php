<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\search\PollSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="poll-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'describe') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'rating') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'views') ?>

    <?php // echo $form->field($model, 'result_type') ?>

    <?php // echo $form->field($model, 'poll_language_id') ?>

    <?php // echo $form->field($model, 'show_for_all_languages') ?>

    <?php // echo $form->field($model, 'poll_sex') ?>

    <?php // echo $form->field($model, 'poll_country_id') ?>

    <?php // echo $form->field($model, 'poll_region_id') ?>

    <?php // echo $form->field($model, 'poll_city_id') ?>

    <?php // echo $form->field($model, 'poll_min_age') ?>

    <?php // echo $form->field($model, 'poll_max_age') ?>

    <?php // echo $form->field($model, 'votes_count_close') ?>

    <?php // echo $form->field($model, 'date_add') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <?php // echo $form->field($model, 'show_on_slider') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
