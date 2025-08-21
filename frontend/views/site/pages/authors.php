<?php

use common\helpers\StringHelper;

?>
<div class="col-md-8">
    <div class="row right_cut_row">
        <?php if (count($this->context->menu)): ?>
            <?php if (count($this->context->menu) > 3): ?>
                <div class="my_account_tabs">
                <?php endif; ?>
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'items' => $this->context->menu,
                    'encodeLabel' => false,
                    'htmlOptions' => array('class' => 'nav nav-tabs', "role" => "tablist"),
                ));
                ?>
                <?php if (count($this->context->menu) > 3): ?></div><?php endif; ?>
        <?php endif; ?>
        <div class="chart_b user_page_b">
            <div class="top_b_chart">
                <a class="btn_prev_var"
                   href="<?= Yii::$app->request->referrer; ?>"><?= Yii::t("poll", 'Назад'); ?></a>
            </div>
            <div class="my_profile_b">
                <div class="profile_name">
                    <?php echo Yii::t("main", 'Автори'); ?>
                </div>
                <?php echo Yii::t("static", StringHelper::AUTHORS); ?>
            </div>
        </div>
    </div>
</div>

<?php
/*

<hr>
<h2><?= __DIR__ ?></h2>

<?php

/ ** @var yii\web\View $this * /
/ ** @var yii\bootstrap\ActiveForm $form * /
/ ** @var \common\models\form\LoginForm $model * /

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    <br>
                    Need new verification email? <?= Html::a('Resend', ['site/resend-verification-email']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

/* */
