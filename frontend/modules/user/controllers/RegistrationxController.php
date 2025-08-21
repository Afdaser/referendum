<?php

namespace frontend\modules\user\controllers;
    
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;
use common\helpers\MenuHelper;

use yii\web\Controller;
use common\models\User;
use common\models\Poll;


class RegistrationxController extends Controller
{
    public $menu = [];

    public function actionIndex()
    {
//        die(__METHOD__);
//        return $this->render('index-dev');

        return $this->render('index-dev');
    }

    public function actionProfile()
    {
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $user->oldEmail = $user->email;
            if($attributes = Yii::$app->request->get('Profile')){
                $category = Html::encode(Yii::$app->request->get('category', false));
                $error = NULL;
//                $user = User::model()->findByPk(Yii::app()->user->id);
//                $user->oldEmail = $user->email;
//                switch($category){}


//        die(__METHOD__);
//        return $this->render('profile');
                return self::renderProfile($user, $category, $error);
            } else {
//                $user = User::model()->findByPk(Yii::app()->user->id);
                return self::renderProfile($user);
            }
        } else {
            $this->redirect('/');
        }
    }

    public function actionNewAnswers()
    {
        die(__METHOD__);
//        return $this->render('index-dev');
        return $this->render('index-new-answers');
    }

    public function actionNewComments()
    {
        die(__METHOD__);
//        return $this->render('index-dev');
        return $this->render('index-new-comments');
    }

    /*
     * Render user profile
     */
    private function renderProfile($user, $category = 'main', $error = NULL, $other = false) {
        $commentsCount = Poll::getNewCommentsCount(Yii::$app->user->identity->id);
        $answersCount = User::getNewAnswersCount();
        $this->menu = [
            [
                'label' => Yii::t("main", "Головна"),
//                'url' => ['site/myPolls'],
                'url' => Url::toRoute(['/poll/site/my-polls']),
                'linkOptions' => ['class' => 'back_to_main'],
            ],
            [
                'label' => Yii::t("main", "Нові коментарі") . Html::tag('span', $commentsCount, ['class' => 'count_poll']),
//                'url' => ['user/newComments'],
                'url' => Url::toRoute(['/user/registration/new-comments']),
//                'url' => Url::toRoute(['/user/profilex/new-comments']),
                'active' => MenuHelper::isActiveMenu(['user', 'user', 'new-comments'], $this->route),
//                'active' => MenuHelper::isActiveMenu(['user', 'profilex', 'new-comments'], $this->route),
            ],
            [
                'label' => Yii::t("main", "Нові відповіді") . Html::tag('span', $answersCount, ['class' => 'count_poll']),
//                'url' => ['user/newAnswers'],
                'url' => Url::toRoute(['/user/registration/new-answers']),
//                'url' => Url::toRoute(['/user/profilex/new-answers']),
                'active' => MenuHelper::isActiveMenu(['user', 'user', 'new-answers'], $this->route),
//                'active' => MenuHelper::isActiveMenu(['user', 'profilex', 'new-answers'], $this->route),
            ],
//            ['label' => Yii::t("main", "Нові коментарі") . '<span class="count_poll">' . $commentsCount . '</span>', 'url' => ['user/newComments']],
//            ['label' => Yii::t("main", "Нові відповіді") . '<span class="count_poll">' . $answersCount . '</span>', 'url' => ['user/newAnswers']],
            [
                'label' => Yii::t("main", "Мій профіль"),
                'url' => Url::toRoute(['/user/registration/profile']),
//                'url' => Url::toRoute(['/user/profilex/profile']),
                'active' => MenuHelper::isActiveMenu(['user', 'user', 'profile'], $this->route),
//                'active' => MenuHelper::isActiveMenu(['user', 'profilex', 'profile'], $this->route),
//                'active' => MenuHelper::isActiveMenu(['user', 'profile'], $this->context->route),
            ],
        ];

        return $this->render('/user/registration/registration', [
            'category' => 'profile',
            'profileCategory' => $category,
            'user' => $user,
            'error' => $error,
            'other' => $other,
        ]);
    }

}
