<?php

use common\helpers\MenuHelper;
use \yii\helpers\Url;
use common\models\AuthItem;

//use dektrium\rbac\models\AuthItem;
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <!-- Sidebar user panel -->
        <?php /* */ ?>
        <?php
        $menuItems = [];
        if (Yii::$app->user->isGuest) {
            //$menuItems[] = '<li><a href="' . Url::toRoute("/site/login"). '" class="waves-effect"><i class="mdi mdi-login"></i><span> Войти </span></a></li>';
        } else {
            $menuItems = [
                ['label' => Yii::t('app', 'REFERENDUM'), 'options' => ['class' => 'header']],
                [
                    'label' => Yii::t('app', 'Polls'), // 'Orders',
                    'icon' => 'cubes',
                    'url' => Url::toRoute(['/poll/default/index']),
                    'active' => MenuHelper::isActiveMenu(['poll'], $this->context->route),
//                    'visible' => Yii::$app->user->can(AuthItem::P_CONTRACT_ENTER),
                    'items' => [
                        [
                            'label' => Yii::t('app', 'Polls'),
                            'icon' => 'archive',
                            'url' => Url::toRoute(['/poll/poll/index']),
                            'active' => MenuHelper::isActiveMenu(['poll', 'poll'], $this->context->route),
//                            'visible' => Yii::$app->user->can(AuthItem::G_ADMIN) OR Yii::$app->user->can(AuthItem::G_MANAGER),
                        ],
                        [
                            'label' => Yii::t('app', 'Poll options'),
                            'icon' => 'archive',
                            'url' => Url::toRoute(['/poll/poll-option/index']),
                            'active' => MenuHelper::isActiveMenu(['poll', 'poll-option'], $this->context->route),
//                            'visible' => Yii::$app->user->can(AuthItem::G_ADMIN) OR Yii::$app->user->can(AuthItem::G_MANAGER),
                        ],
                        [
                            'label' => Yii::t('app', 'Poll comment'),
                            'icon' => 'archive',
                            'url' => Url::toRoute(['/poll/poll-comment/index']),
                            'active' => MenuHelper::isActiveMenu(['poll', 'poll-comment'], $this->context->route),
//                            'visible' => Yii::$app->user->can(AuthItem::G_ADMIN) OR Yii::$app->user->can(AuthItem::G_MANAGER),
                        ],
                        [
                            'label' => Yii::t('app', 'Tags'),
                            'icon' => 'test',
                            'url' => Url::toRoute(['/poll/tag/index']),
                            'active' => MenuHelper::isActiveMenu(['poll', 'tag'], $this->context->route),
//                            'visible' => Yii::$app->user->can(AuthItem::G_ADMIN) OR Yii::$app->user->can(AuthItem::G_MANAGER),
                        ],
                    ],
                    
                ],
                [
                    'label' => Yii::t('app', 'Pages'), // 'Orders',
                    'icon' => 'file-code-o ',
                    'url' => Url::toRoute(['/page/page/index']), // => Url::toRoute(['/page/default/index']),
                    'active' => MenuHelper::isActiveMenu(['page'], $this->context->route),
//                    'visible' => Yii::$app->user->can(AuthItem::P_CONTRACT_ENTER),
                    'items' => [],
                ],
                [
                    'label' => Yii::t('app', 'Geo'),
                    'icon' => 'globe',
                    'url' => Url::toRoute(['/geo/default/index']),
                    'active' => MenuHelper::isActiveMenu(['geo'], $this->context->route),
                    'items' => [
                        [
                            'label' => Yii::t('app', 'Countries'),
                            'icon' => 'flag',
                            'url' => Url::toRoute(['/geo/country/index']),
                            'active' => MenuHelper::isActiveMenu(['geo', 'country'], $this->context->route),
                        ],
                        [
                            'label' => Yii::t('app', 'Regions'),
                            'icon' => 'globe',
                            'url' => Url::toRoute(['/geo/region/index']),
                            'active' => MenuHelper::isActiveMenu(['geo', 'region'], $this->context->route),
                        ],
                        [
                            'label' => Yii::t('app', 'Cities'),
                            'icon' => 'building',
                            'url' => Url::toRoute(['/geo/city/index']),
                            'active' => MenuHelper::isActiveMenu(['geo', 'city'], $this->context->route),
                        ],
                    ],
                ],
                [
                    'label' => Yii::t('app', 'Users'),
                    'icon' => 'users',
                    'url' => Url::toRoute(['/user/admin/index']), // (['/user/default/index']),
                    'active' => MenuHelper::isActiveMenu(['user'], $this->context->route),
//                    'items' => [
//                        [
//                            'label' => Yii::t('app', 'Users'),
//                            'icon' => 'users',
//                            'url' => Url::toRoute(['/user/admin/index']),
//                            'active' => MenuHelper::isActiveMenu(['user', 'admin'], $this->context->route),
//                        ],
//                        [
//                            'label' => Yii::t('app', 'Profile'),
//                            'icon' => 'user',
//                            'url' => Url::toRoute(['/user/profile/index']),
//                            'active' => MenuHelper::isActiveMenu(['user', 'profile', ], $this->context->route),
//                        ],
//                    ],
                ],
            ];
        }
        ?>

        <?=
        dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                    'encodeLabels' => false,
                    'items' => $menuItems,
                ]
        )
        ?>

    </section>

</aside>
