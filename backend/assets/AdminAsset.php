<?php
// EQ AppAsset
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@backend'; // '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/admin.css',
//        'css/_all-skins.css',
        'css/skin-referendum.css',
        'css/style-override.css',
    ];
    public $js = [
        'js/adminlte.min.js',
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset',
//        'dmstr\adminlte\web\AdminLteAsset',
    ];
}
