<?php

/**
 * Description of SecurityController
 *
 * @author alex
 */
//namespace backend\controllers\user;

namespace backend\modules\user\controllers;

use dektrium\user\controllers\SecurityController as BaseSecurityController;
use dektrium\user\models\LoginForm;

//
//use yii\helpers\Url;
////use dektrium\user\models\User;
//use dektrium\user\Finder;
////#use app\modules\user\Finder;
//use app\models\User;
//use app\models\Profile;
//use dektrium\user\models\UserSearch;

class SecurityController extends BaseSecurityController
{
    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        $this->layout = '@backend/views/layouts/main-login.php';
        $this->viewPath = '@backend/modules/user/views/security';
        return parent::actionLogin();
    }
}
