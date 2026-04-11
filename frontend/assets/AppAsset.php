<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // Yii2:
        'css/site.css',
        // Referendum social
        '/css/normalize.min.css',
        '/css/bootstrap-theme.min.css',
        // Стилі плагіна outdatedBrowser більше не підключаємо, адже банер прибрано з макету.
        '/css/jquery.bxslider.css',
        '/css/font-awesome.css',
        '/css/main.css',
        '/css/custom.css',
    ];
    /**
     * Використовуємо завантаження через preload із подальшим перемиканням на stylesheet.
     * Це відповідає рекомендаціям Google PageSpeed, а також дає змогу скоротити блокування рендеру.
     * У layout передбачено noscript-фолбек, який дублює всі CSS як звичайні стилі для випадків без JavaScript.
     */
    public $cssOptions = [
        'rel' => 'preload',
        'as' => 'style',
        'data-rel' => 'stylesheet',
        // Використовуємо dataset, щоб уникнути HTML-екранізації лапок у JS-рядку та виглядати зрозуміліше у фінальному HTML.
        'onload' => 'this.onload=null;this.rel=this.dataset.rel',
    ];
    public $js = [
//        '/js/vendor/jquery-1.10.1.min.js',

        '/js/vendor/bootstrap.min.js',
        // Error:
//        '/js/vendor/outdatedBrowser.min.js',
        '/js/vendor/jquery.bxslider.min.js',
        '/js/vendor/jquery.autocomplete.js',
//        '/js/vendor/jquery.autocomplete.min.js',
        '/js/vendor/currency-autocomplete.js',
        '/js/main.js',
        '/js/custom.js',
        '/js/custom-modal.js',
        '/js/highCharts/highcharts.js',
        // Централізований рендер графіків опитувань через один цикл.
        '/js/poll-chart-queue.js',
//        '/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js',


    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap-4\BootstrapAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
