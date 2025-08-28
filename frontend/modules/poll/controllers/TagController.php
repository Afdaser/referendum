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

        // === Canonical за мовою ТЕГУ (аналогічно до PollController) ===
        $langDomains = [
            1 => 'ua.referendum.social', // українська
            2 => 'ru.referendum.social', // російська
            3 => 'en.referendum.social', // англійська
            4 => 'no.referendum.social', // норвезька
        ];

        $canonicalDomain = $langDomains[$tagModel->language_id] ?? 'en.referendum.social';

        // Підтримка пагінації в canonical (якщо є /page/N)
        $page = (int) Yii::$app->request->get('page', 1);
        $path = '/tag/' . rawurlencode($tag);
        if ($page > 1) {
            $path .= '/page/' . $page;
        }

        $canonicalUrl = 'https://' . $canonicalDomain . $path;

        Yii::$app->view->registerLinkTag([
            'rel' => 'canonical',
            'href' => $canonicalUrl,
        ]);
        // === /Canonical ===

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
        ]);
    }
}
