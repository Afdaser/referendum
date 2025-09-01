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
        '/css/bootstrap.min.css',
        '/css/bootstrap-theme.min.css',
        '/css/outdatedBrowser.min.css',
        '/css/jquery.bxslider.css',
        '/css/font-awesome.css',
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
        '/js/vendor/currency-autocomplete.js',
        '/js/main.js',
        '/js/custom.js',
        '/js/custom-modal.js',
        '/js/highCharts/highcharts.js',
//        '/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js',


    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap-4\BootstrapAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
