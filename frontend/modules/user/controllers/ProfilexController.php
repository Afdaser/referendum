<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;
use common\helpers\MenuHelper;
use yii\web\Controller;
use DateTime;

use common\models\Profile;
use common\models\Poll;
use common\models\User;
use common\models\UserInterest;
use common\models\UserCareer;
use common\models\UserSecondaryEducation;
use common\models\UserHighEducation;

class ProfilexController extends Controller {

    public $menu = [];

    public function actionIndex() {
//        die(__METHOD__);
//        return $this->render('index-dev');

        return $this->render('index-dev');
    }

    /*
     * Change user profile by categories
     */

    public function actionProfileUpdate($category = null) {
        $category = Html::encode(Yii::$app->request->post('category', false));
//        echo "<h2>category  [{$category}]</h2>";
//        die(__METHOD__);
//        return $this->render('index-dev');
//        return $this->render('index-dev');
        //only for authorized users
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $user->oldEmail = $user->email;
            /// if(!Yii::app()->user->isGuest){
//            $attributes = Yii::$app->request->post('Profile');
            $attributes = $this->prepareAttributes();
            if (!empty($attributes)) {
                $error = NULL;

                switch ($category) {
                    case 'main':
                        $error = $this->main($user, $attributes);
                        //{
//                        $user = self::main($user,$attributes);
//                        if ($user->hasErrors()) {
//                            $error = json_encode(CHtml::errorSummary($user));
//                        }
                        break;
//                    }
                    case 'interests':
                        $error = $this->interests($user, $attributes);
                        break;
                    case 'career':
                        $error = $this->career($user, $attributes);
                        break;
                    case 'education':
                        $secError = null;
                        $highError = null;

                        if(isset($attributes['secEduc'])){
                            $secError = $this->secondaryEducation($user, $this->prepareAttributes($attributes['secEduc']));
                        }
                        if(isset($attributes['highEduc'])){
                            $highError = $this->highEducation($user, $this->prepareAttributes($attributes['highEduc']));
                        }
                        if($secError || $highError){
                            $error = json_encode($secError . $highError);
                        }
                        break;
                    case 'email': {
                            $category = 'settings';
//                            $user->scenario = 'email';
                            $user->oldEmail = $user->email;
                            $user->email = Html::encode($attributes['email']);
                            if (!$user->validate(null, false) || !$user->save(false)) {
                                $error = json_encode(Html::errorSummary($user));
                            } else {
                                $user->oldEmail = $user->email;
                            }
                            break;
                        }
                    default: {
                            
                        }
                }

                return self::renderProfile($user, $category, $error);
            } else {
                return self::renderProfile($user);
            }
        } else {
            $this->redirect('/');
        }
    }

    public function actionProfile() {
        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $user->oldEmail = $user->email;
            if ($attributes = Yii::$app->request->get('Profile')) {
                $category = Html::encode(Yii::$app->request->get('category', false));
                $error = NULL;
                return self::renderProfile($user, $category, $error);
            } else {

                return self::renderProfile($user);
            }
        } else {
            $this->redirect('/');
        }
    }

    public function actionNewAnswers() {
        die(__METHOD__);
//        return $this->render('index-dev');
        return $this->render('index-new-answers');
    }

//    public function actionNewComments() {
//        die(__METHOD__);
////        return $this->render('index-dev');
//        return $this->render('index-new-comments');
//    }

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
                'url' => Url::toRoute(['/user/new/new-comments']),
                'active' => MenuHelper::isActiveMenu(['user', 'profilex', 'new-comments'], $this->route),
            ],
            [
                'label' => Yii::t("main", "Нові відповіді") . Html::tag('span', $answersCount, ['class' => 'count_poll']),
//                'url' => ['user/newAnswers'],
                'url' => Url::toRoute(['/user/new/new-answers']),
                'active' => MenuHelper::isActiveMenu(['user', 'profilex', 'new-answers'], $this->route),
            ],
//            ['label' => Yii::t("main", "Нові коментарі") . '<span class="count_poll">' . $commentsCount . '</span>', 'url' => ['user/newComments']],
//            ['label' => Yii::t("main", "Нові відповіді") . '<span class="count_poll">' . $answersCount . '</span>', 'url' => ['user/newAnswers']],
            [
                'label' => Yii::t("main", "Мій профіль"),
                'url' => Url::toRoute(['/user/profilex/profile']),
                'active' => MenuHelper::isActiveMenu(['user', 'profilex', 'profile'], $this->route),
//                'active' => MenuHelper::isActiveMenu(['user', 'profile'], $this->context->route),
            ],
        ];

        return $this->render('/profile/index', [
                    'category' => 'profile',
                    'profileCategory' => $category,
                    'user' => $user,
                    'error' => $error,
                    'other' => $other,
        ]);
    }

    /*
     * Save main data
     */
    private function main($user,$attributes){
        $error = null;
        $profile = $user->profile;
        if (empty($profile)) {
//            $profile = $user->newProfile();
            $profile = new Profile;
            $profile->user_id = $user->id;

        }

        $profile->load(['Profile' => $attributes]);
        if (!$profile->validate(null, false) || !$profile->save(false)) {
            $error = json_encode(Html::errorSummary($profile));
        }

        return $error;
    }

    /*
     * Save user interests
     */
    private function interests($user, $attributes) {
        $error = null;
        $interests = $user->userInterest;
        if (empty($interests)) {
            $interests = new UserInterest;
            $interests->date_add = date('Y-m-d H:i:s');
            $interests->date_update = date('Y-m-d H:i:s');
            $interests->user_id = $user->id;
        }

        $interests->load(['UserInterest' => $attributes]);
        if (!$interests->validate(null, false) || !$interests->save(false)) {
            $error = json_encode(Html::errorSummary($interests));
        }
        return $error;
    }

    /*
     * Save user secondary education
     */

    private function secondaryEducation($user, $attributes) {
        $error = null;
        $secondary = $user->userSecondaryEducation;
        if (empty($secondary)) {
            $secondary = new UserSecondaryEducation;
            $secondary->date_add = date('Y-m-d H:i:s');
            $secondary->date_update = date('Y-m-d H:i:s');
            $secondary->user_id = $user->id;
        }

//        $secondary->setEducation($attributes);
        $secondary->load(['UserSecondaryEducation' => $attributes]);
        if (!$secondary->validate(null, false) || !$secondary->save(false)) {
            $error = Html::errorSummary($secondary);
        }
        return $error;
    }

    /*
     * save user high education
     */

    private function highEducation($user, $attributes) {
        $error = null;
        $high = $user->userHighEducation;
        if (empty($high)) {
            $high = new UserHighEducation;
            $high->date_add = date('Y-m-d H:i:s');
            $high->date_update = date('Y-m-d H:i:s');
            $high->user_id = $user->id;
        }
        $high->load(['UserHighEducation' => $attributes]);
        if (!$high->validate(null, false) || !$high->save(false)) {
            $error = Html::errorSummary($high);
        }
        return $error;
    }

    /*
     * save user career information
     */

    private function career($user, $attributes) {
        $error = null;
        $error2 = null;
        $career = $user->userCareer;
        if (empty($career)) {
            $career = new UserCareer;
            $career->date_add = date('Y-m-d H:i:s');
            $career->date_update = date('Y-m-d H:i:s');
            $career->user_id = $user->id;
        }

        $career->load(['UserCareer' => $attributes]);
        if(!$career->validate(null, false) || !$career->save(false)){
            $error = json_encode(Html::errorSummary($career));
        }
        return $error;
    }

    protected function prepareAttributes($data = []) {
        $keyConverter = [
            'region' => 'region_id',
            'city' => 'city_id',
            'country' => 'country_id',
            'yearBegin' => 'year_begin',
            'yearEnd' => 'year_end',
            'sex' => 'gender',
        ];
        if(empty($data)){
            $attributes = Yii::$app->request->post('Profile');
        } else{
            $attributes = $data;
        }
        foreach ($attributes as $key => $value) {
            if (isset($keyConverter[$key])) {
                $attributes[$keyConverter[$key]] = $attributes[$key];
            }
        }
        if(!empty($attributes['birthday'])){
            $date = new DateTime();
            $date->setDate($attributes['birthday']['year'] ?? 2000, $attributes['birthday']['month'] ?? 1, $attributes['birthday']['day'] ?? 1);
            $attributes['date_birthday'] = $date->format('Y-m-d');
        }
        return $attributes;
    }

}
