<?php

namespace frontend\modules\poll\controllers;

use frontend\modules\poll\controllers\AbstractController as Controller;
use common\models\search\PollSearch;
use frontend\models\forms\SearchForm;

class SearchController extends Controller
{
    public function actionSearch()
    {
        $searchModel = new PollSearch();
        $searchForm = new SearchForm();
        $searchForm->load($this->request->post());

        $dataProvider = $searchModel->searchForm(
            $this->request->queryParams,
            $searchForm
        );

        return $this->render('search', [
            'searchForm'  => $searchForm,
            'searchModel' => $searchModel,
            'dataProvider'=> $dataProvider,
            'tag'         => '',
            'category'    => 'search',
        ]);
    }
}
