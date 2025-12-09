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
                <?php // Навігаційні посилання футера залишаємо лише для актуальних сторінок. ?>
                <a href="<?= Url::toRoute('/updates'); ?>"><?= Yii::t("main", 'Оновлення'); ?></a>
                <?php // Використовуємо слаг /about, щоб відповідати кінцевій URL-адресі без /site. ?>
                <a href="<?= Url::to(['/page/view', 'page' => 'about']); ?>"><?= Yii::t("main", 'Про нас'); ?></a>
            </span>
            <span class="copyright">All Rights Reserved | <?= date('Y'); ?></span>
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
