<?php
/* Real host:  */
defined('SITE_DOMAIN') or define('SITE_DOMAIN','referendum.social');
defined('SITE_PROTOCOL') or define('SITE_PROTOCOL','https://');
/*  V1 * /
defined('SITE_DOMAIN') or define('SITE_DOMAIN','referendum.social.local');
defined('SITE_PROTOCOL') or define('SITE_PROTOCOL','HTTP://');
/* */
//* V2 * /
//defined('SITE_DOMAIN') or define('SITE_DOMAIN','forgood.website');
//defined('SITE_PROTOCOL') or define('SITE_PROTOCOL','https://');
/* * /
if (SITE_PROTOCOL == 'https://') {
    echo '<h1>'. SITE_PROTOCOL . '</h1>';
    die(__FILE__);
} else{
    echo '<h2 style="color:red;">'. SITE_PROTOCOL . '</h2>';
    die(__FILE__);
}
/* */
return [
    'name' => 'Referendum Social',
    'bootstrap' => [
        'dektrium\user\Bootstrap',
        'dektrium\rbac\Bootstrap',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'authManager' => [
            'class' => \dektrium\rbac\components\DbManager::class,
            'itemTable' => '{{%auth_item}}',
            'itemChildTable' => '{{%auth_item_child}}',
            'assignmentTable' => '{{%auth_assignment}}',
            'ruleTable' => '{{%auth_rule}}'
        ],
        'formatter' => [
            'dateFormat' => 'dd/MM/YYYY',
            'timeFormat' => 'php:H:i:s',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'defaultTimeZone' => 'UTC',
            'timeZone' => 'Europe/Kiev', // Europe/Kyiv
            'locale' => 'uk-UA'
        ],
    ],
];
