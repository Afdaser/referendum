<?php

/**
 * Description of AdminController
 *
 * @author alex
 */
//namespace app\controllers\user;

namespace backend\modules\user\controllers;

use dektrium\user\controllers\AdminController as BaseAdminController;
use yii\helpers\Url;
//use dektrium\user\models\User;
use dektrium\user\Finder;
//#use backend\modules\user\Finder;
use common\models\User;
use common\models\Profile;
//use dektrium\user\models\Profile;
//use dektrium\user\models\Profile;
use dektrium\user\models\UserSearch;

class AdminController extends BaseAdminController
{

    /**
     * @param string  $id
     * @param Module2 $module
     * @param Finder  $finder
     * @param array   $config
     */
    public function __construct($id, $module, Finder $finder, $config = [])
    {
//        $debug = 0;
//        $die = 0;
//        if ($debug) {
//            echo '<h2>#/modules/user/controllers/AdminController.php::__construct($id, $module, Finder $finder, $config = [])</h2>';
//            //*
//            echo '<h3>$module</h3>';
//            var_dump($module->modelMap);
//        }
        $module->modelMap['User'] = 'common\models\User';
        $module->modelMap['Profile'] = 'common\models\Profile';
//        if ($debug) {
//            echo '<hr>';
//            var_dump($module->modelMap);
//            echo get_class($finder);
//            //var_dump($finder);
//        }
//        if ($die) {
//            die('/var/www/vhosts_yii/project.nstravel/dev.nstravel.co.ua.local/modules/user/controllers/AdminController.php:: __construct($id, $module, Finder $finder, $config = [])');
//        }
        /* */
        ///#1$this->finder = $finder;
        //parent::__construct($id, $module, $config);
        parent::__construct($id, $module, $finder, $config);
        ///$this->finder = $finder;
        ///Controller::__construct($id, $module, $config);
    }

    /** @inheritdoc */
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rules['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['switch'],
                'roles' => ['superadmin'],
            ],
            [
                'allow' => true,
                'roles' => ['superadmin'],
                //'roles' => ['@'],
            ],
        ];
        return $rules;
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');
        $searchModel = \Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
//die('/var/www/vhosts_yii/project.nstravel/dev.nstravel.co.ua.local/modules/user/controllers/AdminController.php::actionIndex()');
        /*
          return $this->render('@app/modules/user/views/admin/index.php', [
          'dataProvider' => $dataProvider,
          'searchModel'  => $searchModel,
          ]);

          /* */
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }
        
    public function actionAssignments($id)
    {
        ///parent::actionAssignments($id);

        $debug = 0;
        if (!isset(\Yii::$app->extensions['dektrium/yii2-rbac'])) {
            // throw new NotFoundHttpException();
        }
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        if ($debug) {
            echo 'User: [' . get_class($user) . ']<hr>';
            //echo 'Profile: ['.get_class($profile).']<br>';

            var_dump($this);
            die('#/modules/user/controllers/AdminController.php::actionAssignments($id)');
        }
        //return $this->render('@app/modules/user/views/admin/_assignments.php', ['user' => $user,]);
        return $this->render('_assignments', ['user' => $user,]);
        
        //return $this->render('@dektrium/user/views/admin/_assignments.php', ['user' => $user,]);
        
        //return $this->render('@app/modules/user/views/admin/_assignments2', ['user' => $user,]);
 
    }
       /* */
    public function actionUpdateProfile($id)
    {
        Url::remember('', 'actions-redirect');
//        $user = $this->findCommonModel($id);
        $user = $this->findModel($id);
        $profile = $user->profile;

//        echo 'User: ['.get_class($user).']<br>';
//        echo 'Profile: ['.get_class($profile).']<br>';
//
//        echo '<h2>File:'.__FILE__.'#'.__LINE__.'</h2>';
//        die('#/modules/user/controllers/AdminController.php::actionUpdateProfile($id)');
//        
        if ($profile == null) {
            $profile = \Yii::createObject(Profile::className());
            $profile->link('user', $user);
        }
        $event = $this->getProfileEvent($profile);

        $this->performAjaxValidation($profile);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);

        if ($profile->load(\Yii::$app->request->post()) && $profile->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Profile details have been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }
        //die('/modules/user/controllers/AdminController.php::actionUpdateProfile($id)');

        return $this->render('@app/modules/user/views/admin/_profile.php', [
                    'user' => $user,
                    'profile' => $profile,
        ]);
    }

    /**
     * Shows information about user.
     *
     * @param int $id
     *
     * @return string
     */
    public function actionInfo($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findCommonModel($id);

        return $this->render('_info', [
            'user' => $user,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($id == \Yii::$app->user->getId()) {
            \Yii::$app->getSession()->setFlash('danger', \Yii::t('user', 'You can not remove your own account'));
        } else {
            $model = $this->findModel($id);
            $event = $this->getUserEvent($model);
            $this->trigger(self::EVENT_BEFORE_DELETE, $event);
            try {
                if ($model->delete()) {
                    \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been deleted'));
                    $this->trigger(self::EVENT_AFTER_DELETE, $event);
                }
            } catch (\Exception $exc) {
                \Yii::$app->session->setFlash('danger', \Yii::t('app', 'Unable to delete the {ModelName}. Deletion will result in a violation of the integrity of the Database.', ['ModelName' => 'Пользователя']));
                \Yii::$app->session->setFlash('info', \Yii::t('app', 'To prevent the user from entering the system, it is sufficient to block the user.'));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    /* */
    protected function findCommonModel($id)
    {
//        $user = $this->finder->findUserById($id);
        $user = User::find()->where(['id' => $id])->one();
        if ($user === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
//echo get_class($this->finder);
//echo'<hr>';        
//echo get_class($user);
//echo'<hr>';
//die('/var/www/vhosts_yii/project.nstravel/dev.nstravel.co.ua.local/modules/user/controllers/AdminController.php:findCommonModel($id)');
        return $user;
    }

    /* */
}
