<!-- ~/frontend/views/layouts/main/footer.php -->
<?php

use frontend\helpers\Url;

/** @var $this Controller */
?>
<?php
$prefix =  ('https://online-statistics.org' !=  Yii::$app->request->hostinfo) ? '' : 'https://en.online-statistics.org';
// 'https:'.Yii::$app->urlManager->createLangUrl(Yii::$app->language,"/")
?>
<!-- Footer
================================================== -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <span class="foot_links">
                <a href="<?= Url::toRoute('/site/authors'); ?>"><?= Yii::t("main", 'Автори'); ?></a>
                <a href="<?= Url::toRoute('/updates'); ?>"><?= Yii::t("main", 'Оновлення'); ?></a>
                <a href="<?= Url::toRoute('/site/sponsors'); ?>"><?= Yii::t("main", 'Допомога'); ?></a>
                <?php /*                 * / ?>
                  <a href="<?php echo Yii::$app->createUrl('/site/authors');?>"><?php echo Yii::t("main", 'Автори'); ?></a>
                  <a href="<?php echo $prefix.Yii::$app->createUrl('/updates');?>"><?php echo Yii::t("main", 'Оновлення'); ?></a>
                  <a href="<?php echo Yii::$app->createUrl('/site/sponsors');?>"><?php echo Yii::t("main", 'Допомога'); ?></a>
                  <?php /* */ ?>
            </span>
            <span class="copyright">All Rights Reserved | 2021</span>
        </div>
<?php /*                 * / ?>
        <div class="row" style="border:1px dashed red;">
            <div class="col-md-2" style="border:1px dashed green;">
                <?= Yii::$app->language; ?>
            </div>
            <div class="col-md-2" style="border:1px dashed green;">
                <?= Yii::$app->request->baseurl; ?>
            </div>
            <div class="col-md-2" style="border:1px dashed green;">
                <?= Yii::$app->request->hostinfo; ?>
            </div>
            <div class="col-md-2" style="border:1px dashed green;">
                <?= $prefix; ?>
            </div>
        </div>
<?php /* */ ?>
    </div>
</footer>
