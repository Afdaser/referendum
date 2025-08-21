<?php

use dektrium\user\widgets\Connect;
use dektrium\user\models\LoginForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

//use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model dektrium\user\models\LoginForm
 * @var $module dektrium\user\Module
 */
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

$this->title = Yii::t('app', 'Sign In');
$this->params['breadcrumbs'][] = $this->title;
$this->params['body-class'] = 'login-page';

$fieldLoginOptions = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>",
    'inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1'],
];
$fieldPasswordOptions = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>",
    'inputOptions' => ['class' => 'form-control', 'tabindex' => '2']
];
//= Html::encode($this->title);
// die(__FILE__.'#'.__LINE__);
/*        <a href="#"><b><?= Yii::$app->name; ?></b> ADMIN</a>
bailey_logo_black.png     bailey_logo_gray_150.png  bailey_logo_gray.png

 */
?>
<div class="login-box">
    <div class="login-logo">
        <a href="#"><?= Html::img('@web/images/logo.png', ['alt' => Yii::$app->name, 'width'=>'300']) ?></a>
    </div><!-- /.login-logo -->
    <div class="header"></div>
    <?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>    
    <div class="login-box-body">
        <h1 class="login-box-msg"><?= Yii::t('core', 'Welcome to Control Panel'); ?></h1>
        <?php
        $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                    'validateOnType' => false,
                    'validateOnChange' => false,
                ])
        ?>

        <?php if ($module->debug): ?>
            <?=
                    $form->field($model, 'login', $fieldLoginOptions)
                    ->label(false)
                    ->textInput(['placeholder' => $model->getAttributeLabel('login')]);


            //$form->field($model, 'login', $fieldOptions1
            /*
              $form->field($model, 'login', [
              'options' => ['class' => 'form-group has-feedback',],
              'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>",
              'inputOptions' => [
              'autofocus' => 'autofocus',
              //'class' => 'form-control form-group has-feedback',
              'tabindex' => '1']])->dropDownList(LoginForm::loginList());
             * 
             */
            ?>

        <?php else: ?>

            <?=
                    $form->field($model, 'login', $fieldLoginOptions)
                    ->label(false)
                    ->textInput(['placeholder' => $model->getAttributeLabel('login')]);

//                    $form->field($model, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]);
            ?>

        <?php endif ?>

            <?php if ($module->debug): ?>
            <div class="alert alert-warning">
            <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
            </div>
        <?php else: ?>
            <?=
                    $form->field(
                            $model, 'password', $fieldPasswordOptions)
                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
                    ->label(false);
            /*
              ->label(
              Yii::t('user', 'Password')
              . ($module->enablePasswordRecovery ?
              ' (' . Html::a(
              Yii::t('user', 'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5']
              )
              . ')' : '')
              ) /* */
            ?>
        <?php endif ?>

        <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '3']) ?>

        <?=
        Html::submitButton(
                Yii::t('user', 'Sign in'), ['class' => 'btn btn-primary btn-block', 'tabindex' => '4']
        )
        ?>


        <?php /*
          <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
          <div class="body">
          <?=
          $form
          ->field($model, 'username', $fieldOptions1)
          ->label(false)
          ->textInput(['placeholder' => $model->getAttributeLabel('username')])
          ?>

          <?=
          $form
          ->field($model, 'password', $fieldOptions2)
          ->label(false)
          ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
          ?>

          <?= $form->field($model, 'rememberMe')->checkbox(['class' => 'simple']) ?>
          </div>
          <div class="footer">
          <?= Html::submitButton(Yii::t('app', 'Sign me in'), [
          'class' => 'btn btn-primary btn-flat btn-block',
          'name' => 'login-button'
          ])
          ?>
          </div>
          <?php /* */ ?>
<?php ActiveForm::end(); ?>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

<?php /*
<div class="row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
<?= 'ActiveForm::begin ++ ActiveForm::end(); '; ?>
            </div>
        </div>
            <?php if ($module->enableConfirmation): ?>
            <p class="text-center">
            <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
            </p>
        <?php endif ?>
            <?php if ($module->enableRegistration): ?>
            <p class="text-center">
            <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
            </p>
        <?php endif ?>
        <?=
        Connect::widget([
            'baseAuthUrl' => ['/user/security/auth'],
        ])
        ?>
    </div>
</div>
<?php /* */ ?>
<?php /*
  <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
  <div class="body">
  <?=
  $form
  ->field($model, 'username', $fieldOptions1)
  ->label(false)
  ->textInput(['placeholder' => $model->getAttributeLabel('username')])
  ?>

  <?=
  $form
  ->field($model, 'password', $fieldOptions2)
  ->label(false)
  ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
  ?>

  <?= $form->field($model, 'rememberMe')->checkbox(['class' => 'simple']) ?>
  </div>
  <div class="footer">
  <?= Html::submitButton(Yii::t('app', 'Sign me in'), [
  'class' => 'btn btn-primary btn-flat btn-block',
  'name' => 'login-button'
  ])
  ?>
  </div>
  <?php ActiveForm::end(); ?>
 */ ?>