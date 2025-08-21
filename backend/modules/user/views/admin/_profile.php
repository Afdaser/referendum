<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 * @var dektrium\user\models\Profile $profile
 */
?>

<?php $this->beginContent('@app/modules/user/views/admin/update.php', ['user' => $user]) ?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-9',
        ],
    ],
]); ?>

<?= $form->field($profile, 'name') ?>
<?= $form->field($profile, 'lastname') ?>
<?=
$form->field($profile, 'country_id')->widget(Select2::classname(), [
    'data' => common\models\Country::dropDownAllItems(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => Yii::t('app', 'Select country...')],
    'pluginOptions' => ['allowClear' => true,],
]);
?>

<?php /*
Select region...
Select country...
Select city...
/* */ ?>

<?= $form->field($profile, 'region_id')->widget(Select2::classname(), [
    'data' => common\models\CountryRegion::dropDownAllItems(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => Yii::t('app', 'Select region...')],
    'pluginOptions' => ['allowClear' => true,],
]);
?>
<?= $form->field($profile, 'city_id')->widget(Select2::classname(), [
    'data' => common\models\RegionCity::dropDownAllItems(),
    'language' => Yii::$app->language,
    'options' => ['placeholder' => Yii::t('app', 'Select city...')],
    'pluginOptions' => ['allowClear' => true,],
]);
?>

<?=
$form->field($profile, 'date_birthday')->widget(DatePicker::className(), [
    'options' => ['placeholder' => Yii::t('app', 'Select date...')],
    'pluginOptions' => [
        'todayHighlight' => true,
        'todayBtn' => true,
        'format' => 'yyyy-mm-dd',
        'autoclose' => true,
    ],
]);
?>
<?= $form->field($profile, 'public_email') ?>
<?= $form->field($profile, 'phone') ?>
<?= $form->field($profile, 'gender') ?>
<?= $form->field($profile, 'marital')->checkbox() ?>
<?= $form->field($profile, 'preferences')->textarea() ?>
<?php /*
//is_active
//identity
//network
//state
//date_add
//date_update
//is_admin
<?php /* */ ?>

<?php // = $form->field($profile, 'website') ?>
<?php // = $form->field($profile, 'location') ?>
<?php // = $form->field($profile, 'gravatar_email') ?>
<?php // = $form->field($profile, 'bio')->textarea() ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
