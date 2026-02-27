<?php

namespace frontend\controllers;

use Yii;
use common\models\Language;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
        $this->prepareMeta($pageData);

        return $this->render('view', array(
                    'pageData' => $pageData,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function actionUpdates($language = null, $page = 'updates') {
        $pageData = $this->loadPage($language, $page);
        $this->prepareMeta($pageData);
        return $this->render('view', array(
            'pageData' => $pageData,
        ));
    }

    protected function loadPage($language, $page) {
        $language = $this->resolveLanguage($language);
        $pageData = Page::find()->select('p.*')
                ->from('page p')
                ->innerJoin('language l', 'l.id=p.language_id')
                ->where('p.slug=:slug AND l.name=:lang ', array(':slug' => $page, ':lang' => $language))
                ->one();

        if (empty($pageData)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $pageData;
    }

    protected function resolveLanguage($language): string {
        if (empty($language)) {
            $language = Yii::$app->language;
        }

        $rawLanguage = trim((string) $language);

        // Спершу намагаємось знайти мову за locale (наприклад, en-NZ -> nz).
        if ($rawLanguage !== '') {
            $languageModel = Language::find()->where(['locale' => $rawLanguage])->one();
            if ($languageModel !== null) {
                return (string) $languageModel->name;
            }
        }

        $language = strtolower(substr($rawLanguage, 0, 2));
        if ($language === 'uk') {
            $language = 'ua';
        }

        return $language ?: 'en';
    }

    protected function prepareMeta(Page $page): void {
        // Налаштовуємо метадані сторінки з назви та опису.
        $pageTitle = !empty($page->title) ? $page->title : $page->name;
        Yii::$app->page->setTitle($pageTitle);
        Yii::$app->page->setDescription($page->describe ?? '');
        Yii::$app->page->setKeywords($page->name ?? '');
        $this->registerCanonicalLink($page);
    }

    protected function registerCanonicalLink(Page $page): void {
        $langDomains = Yii::$app->params['langDomains'] ?? [];
        $canonicalDomain = $langDomains[$page->language_id] ?? Yii::$app->request->hostName;
        $slug = trim($page->slug ?? '', '/');

        // Не нашкодити: додаємо canonical лише коли є валідний слаг.
        if ($slug === '') {
            return;
        }

        $canonicalUrl = 'https://' . $canonicalDomain . '/' . $slug;
        Yii::$app->view->registerLinkTag([
            'rel' => 'canonical',
            'href' => $canonicalUrl,
        ]);
    }

}
