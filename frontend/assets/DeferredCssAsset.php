<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle для некритичних CSS, що завантажуються асинхронно.
 */
class DeferredCssAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/outdatedBrowser.min.css',
        '/css/jquery.bxslider.css',
        '/css/font-awesome.css',
    ];
    public $cssOptions = [
        'media' => 'print',
        'onload' => "this.media='all'",
    ];
    public $js = [];
    public $depends = [
        frontend\assets\AppAsset::class,
    ];
}
