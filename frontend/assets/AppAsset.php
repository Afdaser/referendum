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
        // Стилі плагіна outdatedBrowser більше не підключаємо, адже банер прибрано з макету.
        '/css/jquery.bxslider.css',
        '/css/main.css',
        '/css/custom.css',
    ];
    public $js = [
//        '/js/vendor/jquery-1.10.1.min.js',

        '/js/vendor/bootstrap.min.js',
        // Error:
//        '/js/vendor/outdatedBrowser.min.js',
        '/js/vendor/jquery.bxslider.min.js',
        '/js/vendor/jquery.autocomplete.js',
//        '/js/vendor/jquery.autocomplete.min.js',
        // Прибрано неактуальний currency-autocomplete: цільові селектори відсутні у поточних шаблонах.
        '/js/main.js',
        '/js/custom.js',
        // Скрипт реєстраційної модалки лишається глобальним, бо використовується на різних сторінках.
        '/js/registration-modal.js',
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
