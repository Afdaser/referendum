<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'xadmin-backend',
    'homeUrl' => '/xadmin',
    'basePath' => dirname(__DIR__),
    'language' => 'uk-UA',
//    'language' => 'en-US',
//    'language' => 'ru-RU',
//    'language' => 'no-NO',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'user' => [
            'class' => 'backend\modules\user\Module',
            'controllerMap' => [
                'security' => 'backend\modules\user\controllers\SecurityController',
                'admin' => 'backend\modules\user\controllers\AdminController',
            //'profile' => 'backend\modules\user\controllers\ProfileController',
            ],
            'as backend' => 'dektrium\user\filters\BackendFilter',
            'admins' => [
                'administrator', 'alex'
            ],
            'enableUnconfirmedLogin' => true,
            'enableRegistration' => false,
            'enableConfirmation' => false,
            'enableFlashMessages' => false,
//            'enableGeneratingPassword' => true,
//            'enableUnconfirmedLogin' => true,
//            'enablePasswordRecovery' => true,
//            'enableAccountDelete' => false,
        ],
        'poll' => [
            'class' => 'backend\modules\poll\Module',
        ],
        'page' => [
            'class' => 'backend\modules\page\Module',
        ],
        'geo' => [
            'class' => 'backend\modules\geo\Module',
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => '/xadmin',
            'csrfParam' => '_csrf-backend',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@backend/messages',
                    'basePath' => '@common/messages',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'en-TT',
                    'fileMap' => [
                        'back' => 'back.php',
                        'base' => 'base.php',
                        'core' => 'core.php',
                        'app' => 'app.php',
                    ],
                ],
/*
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'en-TT',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
                'back' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'en-TT',
                    'fileMap' => [
                        'back' => 'back.php',
                    ],
                ],
                'front' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'forceTranslation' => true,
                    'sourceLanguage' => 'en-TT',
                    'fileMap' => [
                        'front' => 'front.php',
                    ],
                ],
/* */
            ],
        ],
        /*
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
         */
        /* */
        'user' => [
//            'identityClass' => 'dektrium\user\models\User',
//            'identityClass' => \common\models\User::class,
            'identityClass' => 'common\models\User',
            'identityCookie' => [
                'name' => '_backendIdentity',
                'path' => '/backend/web',
                'httpOnly' => true,
            ],
            'loginUrl' => ['/login.html'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => '/poll/default/index',
//                [
//                    'pattern' => '', // '/' 
//                    'route' => '/site/index',
//                    'encodeParams' => false,
//                    'suffix' => '/',
//                ],
                '/login.html' => '/user/security/login',
            ],
        ],
    ],
    'params' => $params,
];
