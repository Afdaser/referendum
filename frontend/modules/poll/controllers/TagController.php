<?php

namespace frontend\modules\poll\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use frontend\modules\poll\controllers\AbstractController as Controller;
use common\models\search\PollSearch;
use frontend\models\forms\SearchForm;
use common\models\Tag;
use yii\db\Expression;

class TagController extends Controller
{
    public function actionIndex($tag)
    {
        $hostParts = explode('.', Yii::$app->request->hostName);
        $subdomain = strtolower($hostParts[0] ?? '');
        $languageId = Yii::$app->request->languageId ?? null;
        $isNzSubdomain = ($subdomain === 'nz');
        $normalizedTag = Tag::normalizeTagSlug((string) $tag);

        // Для nz-піддомена обмежуємо вибірку мовою, а далі шукаємо за канонічним slug.
        $slugExpression = new Expression("LOWER(REPLACE(REPLACE(TRIM(name), '_', '-'), ' ', '-'))");
        $tagQuery = Tag::find()->where([$slugExpression => $normalizedTag]);
        if ($isNzSubdomain && $languageId) {
            $tagQuery->andWhere(['language_id' => $languageId]);
        }

        $tagModel = $tagQuery->one();

        if (empty($tagModel)) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $canonicalTag = $tagModel->getSlug();

        // === Canonical та редірект на канонічний піддомен ===
        $langDomains = Yii::$app->params['langDomains'] ?? [];
        $canonicalDomain = $langDomains[$tagModel->language_id] ?? 'en.referendum.social';

        // Підтримка пагінації в canonical (якщо є /page/N)
        $page = (int) Yii::$app->request->get('page', 1);
        $path = '/tag/' . rawurlencode($canonicalTag);
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

        // Передаємо у пошук контекст мови, щоб nz показував лише свої опитування.
        $queryParams = $this->request->queryParams;
        $queryParams['languageId'] = $languageId;
        $queryParams['restrictLanguage'] = $isNzSubdomain;
        $queryParams['tagId'] = $tagModel->id;

        $dataProvider = $searchModel->searchTag(
            $queryParams,
            $searchForm,
            $canonicalTag
        );

        return $this->render('index', [
            'searchForm'  => $searchForm,
            'searchModel' => $searchModel,
            'dataProvider'=> $dataProvider,
            'tag'         => $canonicalTag,
            'tagModel'    => $tagModel,
            'category'    => 'search',
            'page'        => $page,
        ]);
    }
}
