<?php

use backend\assets\AdminAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

dmstr\web\AdminLteAsset::register($this);
AdminAsset::register($this);

//$background = '/xadmin/images/background/bg_login.jpg';
$background = '/xadmin/images/background/depositphotos_114835532-stock-photo-voting-hand-detail.jpg';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html  class="wide wow-animation" lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="login-page">

        <?php $this->beginBody() ?>
        <div class="page animated">
            <section class="section section-single box-transform-wrap novi-background novi-background context-dark">

                <?= $content ?>
                <div class="box-transform" style="background-image: url(<?= $background; ?>);"></div>
            </section>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
