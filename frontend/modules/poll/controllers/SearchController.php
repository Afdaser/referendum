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
        // Keep searches in the URL so pagination and reloads retain all filters.
        if ($this->request->isPost && $searchForm->load($this->request->post())) {
            $params = $this->request->queryParams;
            unset($params['page']);
            $params[0] = '/poll/search/search';
            $params[$searchForm->formName()] = $searchForm->getAttributes();

            return $this->redirect($params, 303);
        }
        $searchForm->load($this->request->queryParams);

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
