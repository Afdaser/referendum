<?php

namespace frontend\modules\poll\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use frontend\modules\poll\controllers\AbstractController as Controller;
use common\models\search\PollSearch;
use frontend\models\forms\SearchForm;
use common\models\Tag;

class TagController extends Controller
{
    public function actionIndex($tag)
    {
        $tagModel = Tag::find()->where(['name' => $tag])->one();
        if (empty($tagModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        // === Canonical та редірект на канонічний піддомен ===
        $langDomains = Yii::$app->params['langDomains'] ?? [];
        $canonicalDomain = $langDomains[$tagModel->language_id] ?? 'en.referendum.social';

        // Підтримка пагінації в canonical (якщо є /page/N)
        $page = (int) Yii::$app->request->get('page', 1);
        $path = '/tag/' . rawurlencode($tag);
        if ($page > 1) {
            $path .= '/page/' . $page;
        }

        $canonicalUrl = 'https://' . $canonicalDomain . $path;
        if (Yii::$app->request->hostName !== $canonicalDomain) {
            return $this->redirect($canonicalUrl, 301);
        }

        Yii::$app->view->registerLinkTag([
            'rel' => 'canonical',
            'href' => $canonicalUrl,
        ]);
        // === /Canonical та редірект ===

        $searchModel = new PollSearch();
        $searchForm = new SearchForm();
        $searchForm->load($this->request->queryParams);

        $dataProvider = $searchModel->searchTag(
            $this->request->queryParams,
            $searchForm,
            $tag
        );

        return $this->render('index', [
            'searchForm'  => $searchForm,
            'searchModel' => $searchModel,
            'dataProvider'=> $dataProvider,
            'tag'         => $tag,
            'tagModel'    => $tagModel,
            'category'    => 'search',
            'page'        => $page,
        ]);
    }
}
