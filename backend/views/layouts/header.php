<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
    <?php
// Yii::$app->name
    ?>
    <?=
    Html::a('<span class="logo-mini">' . 
            Html::img('@web/images/referendum_logo_sq_50.png', ['alt' => Yii::$app->name, 'width' => '50']).
            //Yii::$app->name .
            '</span><span class="logo-lg">' .
            Html::img('@web/images/referendum_logo.png', ['alt' => Yii::$app->name, 'width' => '145']).
            '</span>', Yii::$app->homeUrl, ['class' => 'logo'])
    ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"><?= Yii::t('app', 'Toggle navigation'); ?></span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->username ?></span>
                    </a>
                    <ul class="dropdown-menu" style="width: auto;">
                        <li class="user-footer">
                            <div class="pull-right">
                                <?=
                                Html::a(
                                        Yii::t('app', 'Log out'), ['/user/security/logout'], ['data-method' => 'post', 'class' => 'btn btn-danger']
                                )
                                ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
