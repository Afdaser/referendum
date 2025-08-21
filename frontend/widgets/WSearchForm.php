<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap4\Widget;
use frontend\models\forms\SearchForm;



/**
 * Description of WSearchForm
 *
 * @author alex
 */
class WSearchForm extends Widget {

    public $data;
    public $model;
    protected $view = 'search-form';

    public function run() {

        if(empty($this->model)){
            $request = Yii::$app->request;
            $this->model = new SearchForm();
            $this->model->search_in_title = 1;
            $this->model->load($request->post());
        } 

        return $this->render($this->view, [
                    'model' => $this->model,
                    'data' => $this->data,
        ]);
    }

}
