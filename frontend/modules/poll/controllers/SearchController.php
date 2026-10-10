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
        $fields = [
            'q' => 'text', 'title' => 'search_in_title', 'tags' => 'search_in_tags',
            'country' => 'country', 'region' => 'region',
        ];
        $queryParams = $this->request->queryParams;
        // Keep searches in the URL so pagination and reloads retain all filters.
        if ($this->request->isPost && $searchForm->load($this->request->post())) {
            $params = $this->request->queryParams;
            unset($params['page']);
            $params[0] = '/poll/search/search';
            unset($params[$searchForm->formName()]);
            foreach ($fields as $param => $attribute) {
                unset($params[$param]);
                if ($searchForm->$attribute !== null && $searchForm->$attribute !== '' && $searchForm->$attribute !== '0' && $searchForm->$attribute !== 0) {
                    $params[$param] = $searchForm->$attribute;
                }
            }

            return $this->redirect($params, 303);
        }
        if (isset($queryParams[$searchForm->formName()])) {
            $searchForm->load($queryParams);
            $params = $queryParams;
            unset($params[$searchForm->formName()]);
            $params[0] = '/poll/search/search';
            foreach ($fields as $param => $attribute) {
                if ($searchForm->$attribute !== null && $searchForm->$attribute !== '' && $searchForm->$attribute !== '0' && $searchForm->$attribute !== 0) {
                    $params[$param] = $searchForm->$attribute;
                }
            }
            return $this->redirect($params, 302);
        }
        $attributes = [];
        foreach ($fields as $param => $attribute) {
            if (isset($queryParams[$param]) && is_scalar($queryParams[$param])) {
                $attributes[$attribute] = $queryParams[$param];
            }
        }
        $searchForm->load($attributes, '');

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
