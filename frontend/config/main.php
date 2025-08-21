<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'referendum-frontend',
    'name' => 'Referendum Social',
    'homeUrl' => '/',
    'language' => 'en-US',
//    'language' => 'uk-UA',
//    'language' => 'ru-RU',
//    'language' => 'no-NO',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'class' => 'frontend\components\LanguageRequest',
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'forceTranslation' => true,
//                    'sourceLanguage' => 'en-TT',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
                'user' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'forceTranslation' => true,
//                    'sourceLanguage' => 'en-TT',
                    'fileMap' => [
                        'app' => 'user.php',
                    ],
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'forceTranslation' => true,
//                    'sourceLanguage' => 'en-TT',
                    'fileMap' => [
                        'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'user' => [
//            'identityClass' => 'common\models\SiteUser',
//            'identityClass' => 'frontend\models\SiteUser',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
//        'authManager' => [
//            'class' => \dektrium\rbac\components\DbManager::class,
//            'itemTable' => '{{%auth_item}}',
//            'itemChildTable' => '{{%auth_item_child}}',
//            'assignmentTable' => '{{%auth_assignment}}',
//            'ruleTable' => '{{%auth_rule}}',
////            'itemTable' => '{{%client_auth_item}}',
////            'itemChildTable' => '{{%client_auth_item_child}}',
////            'assignmentTable' => '{{%client_auth_assignment}}',
////            'ruleTable' => '{{%client_auth_rule}}'
//        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'page' => [
            'class' => 'frontend\components\PageMetaData',
            'imageDefault' => '/images/logo_gbs.png',
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
//            'enableStrictParsing' => true,
            'class' => \frontend\components\LanguageUrlManager::class,
            'rules' => [
                // Ajax validate:
                [
                    'pattern' => '/ajax/validate/login',
                    'route' => '/ajax/validate/login',
                    'suffix' => '.html',
                ],
                // Ajax:
                [
                    'pattern' => '/ajax/dev/index',
                    'route' => '/ajax/dev/index',
                    'suffix' => '.html',
                ],
                [
                    'pattern' => '/ajax/dev/login',
                    'route' => '/ajax/dev/login',
                    'suffix' => '.html',
                ],
                [
                    'pattern' => '/ajax/dev/submit',
                    'route' => '/ajax/dev/submit',
                ],
                [
                    'pattern' => '/site/checkLogin',
                    'route' => '/ajax/check/login',
                ],
                // User profile (common/public):
                [
                    'pattern' => '/site/userProfile/<id:\d+>',
                    'route' => '/poll/site/user-profile',
                ],

                // UserProfile
                'POST user/profile' => 'user/profilex/profile-update',
                [
                    'pattern' => 'user/profile',
//                    'route' => '/user/registrationx/profile'
                    'route' => 'user/profilex/profile'
//                    'route' => 'user/profile/profile'
                ],
                [
                    'pattern' => '/user/newAnswers',
                    'route' => '/user/new/new-answers',
//                    'route' => '/user/registrationx/new-answers'
//                    'route' => '/user/profilex/new-answers'
                ],
                [
                    'pattern' => '/user/newComments',
                    'route' => '/user/new/new-comments',
//                    'route' => '/user/registrationx/new-comments'
//                    'route' => '/user/profilex/new-comments'
                ],
                [
                    'pattern' => '/user/readAllComments',
                    'route' => '/user/new/read-all-comments',
                ],
                [
                    'pattern' => 'POST /user/readAllAnswers',
                    'route' => '/user/new/read-all-answers',
                ],

//                [
//                    'pattern' => '/user/profile',
//                    'route' => 'user/profile/index'
//                ],
//                [
//                    'pattern' => '/user/profilex',
//                    'route' => 'user/profile/index'
//                ],

                'updates' => 'page/updates',
                [
                    'pattern' => 'poll/<id:\d+>',
                    'route' => 'poll/poll/view',
                ],
                [
                    'pattern' => '/poll/vote',
//                    'pattern' => '/poll/vote/option=<option:\d+>',
//                    'pattern' => '/poll/vote?option=<option:\d+>',
                    'route' => '/poll/poll/vote',
                ],
                // Default Controllers/Actions
                    '' => 'poll/site/index',
                // Poll list with options:
                [
                    'pattern' => 'site/actualPolls',
                    'route' => 'poll/site/actual-polls',
                ],
                [
                    'pattern' => 'site/hotPolls',
                    'route' => 'poll/site/hot-polls',
                ],
                [
                    'pattern' => 'site/hotPolls/<sorting:(desc|asc|default)>/',
                    'route' => 'poll/site/hot-polls',
                ],
                'site/hotPolls/<sorting:(desc|asc|default)>/<period:\w+>/<limit:(2|5|10)>' => 'poll/site/hot-polls',
                [
                    'pattern' => '/poll/createPoll',
                    'route' => 'poll/poll/create-poll',
                ],
                [
                    'pattern' => '/poll/addComment',
                    'route' => 'poll/poll/add-comment'
                ],                
                [
                    'pattern' => '/poll/addAnswer',
                    'route' => 'poll/poll/add-answer'
                ],                
                [
                    'pattern' => '/poll/ChangePollRating',
                    'route' => 'poll/poll/change-poll-rating'
                ],
                [
                    'pattern' => '/poll/UpAnswerRating',
                    'route' => 'poll/ajax/up-answer-rating'
                ],
                // For debug:
                    'search/hot-polls' => 'poll/site/hot-polls',

                                    'updates' => 'page/updates',
                // Search
				[
    'pattern' => 'tag/<tag:\w+>/page/<page:\d+>',
    'route' => 'poll/tag/index',
    'defaults' => ['page' => 1], // По умолчанию 1-я страница
],
[
    'pattern' => 'tag/<tag:\w+>',
    'route' => 'poll/tag/index',
  
],
  /*              [
                    'pattern' => 'tag/<tag:\w+>',
                    'route' => 'poll/tag/index'
                ],*/
                // poll/ajax/*
                [
                    'pattern' => 'poll/ajax/get-regions',
                    'route' => 'poll/ajax/get-regions'
                ],
                // Search form:
                [
                // 'site/search/10/desc'
                    'pattern' => '/site/search/<limit:(2|5|10)>/<sorting:(desc|asc|default)>',
                    'route' => '/poll/search/search',
                ],
                // Search form in header:
                // 
                // http://uk.online-statistics.org.local/site/search/10/desc
                [
                // 'site/search/10/desc'
                    'pattern' => 'site/search',
                    'route' => 'poll/search/search',
                ],

//                    '/tag/<tag:\w+>' => '/poll/tag/index',
                    'site/actualPolls' => 'poll/site/actual-polls',
                    'site/myPolls' => 'poll/site/my-polls',
                    'site/myPolls/<sorting:(desc|asc|default)>/<period:\w+>/<limit:(2|5|10)>' => 'poll/site/my-polls',
                // TODO
                // DEV:
                [
                    'pattern' => 'login',
                    'route' => 'site/login',
                    'suffix' => '.html',
                ],
                [
                    'pattern' => 'logout',
                    'route' => 'site/logout',
                    'suffix' => '.html',
                ],

//              AJAX
//             	http://en.referendum.social.local/poll/ChangePollRating
/*
 * POST RAW: id=407&rating=1
 * POST RAW: id=407&rating=-1
 * POST 
 * 
 * {
	"id": "407",
	"rating": "1"
}
 */
                // Default Controllers/Actions
//                SITE_PROTOCOL.'<language:(ua|ru|en|no)>.<domain>/' => 'poll/site/hot-polls',
//                SITE_PROTOCOL.'<domain>/' => 'poll/site/hot-polls',
                //'http://<domain>/' => 'site/hotPolls',
//		SITE_PROTOCOL.'<language:(ua|ru|en|no)>.<domain>/poll/<id:\d+>' => 'poll/poll/view',
		// RSS
//		'http://<language:(ua|ru|en|no)>.<domain>/rss' => 'rss/feed',
//		SITE_PROTOCOL.'<language:(ua|ru|en|no)>.<domain>/rss.xml' => 'poll/rss/feed',
                // tags
//		SITE_PROTOCOL.'<language:(ua|ru|en|no)>.<domain>/tag/<tag:\w+>' => 'site/search',
//                '/tag/<tag:\w+>' => 'site/search',


                // Task #06 old:
//				'http://<language:(ua|ru|en|no)>.<domain>/search/tag/<tag:\w+>' => 'site/search',
//				'/search/tag/<tag:\w+>' => 'site/search',
                // Task #06 new:
//                'http://<language:(ua|ru|en|no)>.<domain>/tag/<tag:\w+>' => 'site/search',
//                '/tag/<tag:\w+>' => 'site/search',

/*
[
    'posts' => 'post/index',
    'post/<id:\d+>' => 'post/view',
]
 */
//                'updates' => 'page/view?page=updates',

/*
				// RSS
//				'http://<language:(ua|ru|en|no)>.<domain>/rss' => 'rss/feed',
				'http://<language:(ua|ru|en|no)>.<domain>/rss.xml' => 'rss/feed',

				// Default Controllers/Actions
				'http://<language:(ua|ru|en|no)>.<domain>/' => 'site/hotPolls',
				'http://<domain>/' => 'site/hotPolls',
				//'http://<domain>/' => 'site/hotPolls',

				// tags
                                // Task #06 old:
//				'http://<language:(ua|ru|en|no)>.<domain>/search/tag/<tag:\w+>' => 'site/search',
//				'/search/tag/<tag:\w+>' => 'site/search',
                                // Task #06 new:
				'http://<language:(ua|ru|en|no)>.<domain>/tag/<tag:\w+>' => 'site/search',
				'/tag/<tag:\w+>' => 'site/search',

				// pollgs
                                // Task #05 old: -= None =-
                                // Task #06 new:
				'http://<language:(ua|ru|en|no)>.<domain>/poll/<id:\d+>' => 'poll/view',
//				'http://<language:(ua|ru|en|no)>.<domain>/poll/<id:\d+>/<sort:\w+>/<limit:\d+>' => 'poll/view',


				'http://<language:(ua|ru|en|no)>.<domain>/<controller:\w+>/<action:\w+>/<id:\d+>/<sort:\w+>/<limit:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>/<sort:\w+>/<limit:\d+>' => '<controller>/<action>',

				'http://<language:(ua|ru|en|no)>.<domain>/<controller:\w+>/<action:\w+>/<sort:\w+>/<limit:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<sort:\w+>/<limit:\d+>' => '<controller>/<action>',

                'http://<language:(ua|ru|en|no)>.<domain>/<controller:\w+>/<action:\w+>/<sort:\w+>/<period:\w+>/<limit:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<sort:\w+>/<period:\w+>/<limit:\d+>' => '<controller>/<action>',

                'http://<language:(ua|ru|en|no)>.<domain>/<controller:\w+>/<action:\w+>/<sort:\w+>/<period:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<sort:\w+>/<period:\w+>' => '<controller>/<action>',

				'http://<language:(ua|ru|en|no)>.<domain>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',

				'http://<language:(ua|ru|en|no)>.<domain>/<controller:\w+>/<action:\w+>/<category:\w+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<category:\w+>' => '<controller>/<action>',

				'http://<language:(ua|ru|en|no)>.<domain>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',

				'http://<language:(ua|ru|en|no)>.<domain>/<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
                                // The Page
				'http://<language:(ua|ru|en|no)>.<domain>/<page:[\w-]+>' => 'page/view',
                                    'updates' => 'page/updates'
/* */
            ],
        ],
        /* */
    ],
    'modules' => [
        'poll' => [
            'class' => 'frontend\modules\poll\Module',
        ],
        'user' => [
            'class' => 'frontend\modules\user\Module',
            'controllerMap' => [
//                'security' => 'frontend\modules\user\controllers\SecurityController',
//                'admin' => 'frontend\modules\user\controllers\AdminController',
                'profile' => 'frontend\modules\user\controllers\ProfilexController',
//                'profilex' => 'frontend\modules\user\controllers\ProfilexController',
//                'default' => 'frontend\modules\user\controllers\DefaultController',
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
        'ajax' => [
            'class' => 'app\modules\ajax\Module',
        ],
    ],
    'params' => $params,
];


/*

    public function accessRules()
    public function actionView($language = null){
    public function actionAddComment(){
    public function actionAddAnswer(){
    public function actionChangeCommentRating(){
    public function actionUpAnswerRating(){
    public function actionChangePollRating(){
    public function actionVote()
    public function actionCreatePoll(){
    public function actionGetChartData(){
    public static function actionGetRegions(){
    public static function actionGetCities(){
    public function actionEditPoll(){


 */