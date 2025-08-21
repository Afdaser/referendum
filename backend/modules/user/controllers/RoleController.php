<?php

/**
 * Description of RoleController
 *
 * @author alex
 */

namespace backend\modules\user\controllers;

use yii\filters\AccessControl;
use dektrium\rbac\controllers\RoleController as BaseRoleController;

//use yii\helpers\Url;
////use dektrium\user\models\User;
//use dektrium\user\Finder;
////#use app\modules\user\Finder;
//use app\models\User;
//use app\models\Profile;
//use dektrium\user\models\UserSearch;

class RoleController extends BaseRoleController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['superadmin'],
                    ],
                ],
            ],
        ];
    }
}
