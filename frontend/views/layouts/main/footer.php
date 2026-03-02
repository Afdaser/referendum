<!-- ~/frontend/views/layouts/main/footer.php -->
<?php

use common\models\FooterLink;
use frontend\helpers\Url;
use yii\helpers\Html;

/** @var $this Controller */
?>
<?php
$prefix =  ('https://online-statistics.org' !=  Yii::$app->request->hostinfo) ? '' : 'https://en.online-statistics.org';
// Отримуємо набір посилань для поточної мови + глобальні посилання для всіх мов.
$footerLinks = FooterLink::getForLanguage((string) Yii::$app->language);
?>
<!-- Footer
================================================== -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <span class="foot_links">
                <?php if (!empty($footerLinks)): ?>
                    <?php foreach ($footerLinks as $footerLink): ?>
                        <?php
                        // Не нашкодь: виводимо дані безпечно, а URL приводимо до валідного виду.
                        $url = preg_match('/^https?:\/\//i', (string) $footerLink->url)
                            ? $footerLink->url
                            : Url::to($footerLink->url);
                        ?>
                        <a href="<?= Html::encode($url); ?>"><?= Html::encode($footerLink->anchor); ?></a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php // Fallback на випадок, якщо посилання ще не налаштовані в адмінці. ?>
                    <a href="<?= Url::toRoute('/updates'); ?>"><?= Yii::t("main", 'Оновлення'); ?></a>
                    <a href="<?= Url::to(['/page/view', 'page' => 'about']); ?>"><?= Yii::t("main", 'Про нас'); ?></a>
                <?php endif; ?>
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
