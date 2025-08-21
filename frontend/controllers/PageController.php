<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
//use frontend\models\ResendVerificationEmailForm;
//use frontend\models\VerifyEmailForm;
//use yii\base\InvalidArgumentException;
//use yii\web\BadRequestHttpException;
//use yii\filters\VerbFilter;
//use yii\filters\AccessControl;
//use common\models\form\LoginForm;
//use frontend\models\PasswordResetRequestForm;
//use frontend\models\ResetPasswordForm;
//use frontend\models\SignupForm;
//use frontend\models\ContactForm;
use common\models\Page;

/**
 * Description of PageController
 *
 * @author alex
 */
class PageController extends Controller {

    public $menu = [];

    /**
     * {@inheritdoc}
     */
    public function actionView($language = null, $page = 'x') {
        $pageData = $this->loadPage($language, $page);

        return $this->render('view', array(
                    'pageData' => $pageData,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function actionUpdates($language = null, $page = 'updates') {
        if (empty($language)) {
            $language = 'en';
        }
        $pageData = $this->loadPage($language, $page);
        return $this->render('view', array(
            'pageData' => $pageData,
        ));
    }

    protected function loadPage($language, $page) {
        $pageData = Page::find()->select('p.*')
                ->from('page p')
                ->innerJoin('language l', 'l.id=p.language_id')
                ->where('p.slug=:slug AND l.name=:lang ', array(':slug' => $page, ':lang' => $language))
                ->one();

        if (empty($pageData)) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $pageData;
    }

}
