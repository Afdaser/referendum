<?php

namespace frontend\modules\poll\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\helpers\StringHelper;

use common\models\Country;
use common\models\Poll;
use common\models\PollOption;
//use common\models\PollComment;
use common\models\User;

/**
 * Description of AjaxController
 *
 * @author alex
 */
class AjaxController extends \yii\web\Controller
{
    public function init() {
        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return parent::init();
    }

    /*
     * Increments poll`s variant of answer rating
     */
    public function actionUpAnswerRating()
    {
        if (Yii::$app->request->isAjax && !Yii::$app->user->isGuest) {
            $id = intval(Yii::$app->request->post('id'));
            $answer = PollOption::find()->where(['id' => $id])->one();
            $isVoted = User::isVotedForAnswer($id);
            if ($answer && !$isVoted) {
                if ($answer->isUnpublished()) {
                    $newRating = $answer->upRating();
                    // echo json_encode($newRating);
                    return $newRating;
                }
            }
        }
    }

    /*
     * Return filtered data for chart building
     */
    public function actionGetChartData(){
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ( Yii::$app->request->isAjax){
            $id = intval( Yii::$app->request->post('id'));
            if($id){
                $poll = Poll::find()->where(['id' => $id])->one();
                if($poll){
                    $gender = intval( Yii::$app->request->post('gender'));
                    $ageInterval = intval( Yii::$app->request->post('age'));
                    $country = intval( Yii::$app->request->post('country'));
//                    if($country){
//                        $region = intval( Yii::$app->request->post('region'));
//                    } else {
//                        $region = 0;
//                    }
                    $registration = intval( Yii::$app->request->post('registration'));
                    $chartData = $poll->getChartData($gender,$ageInterval,$country,$registration);
                    $result['bar'] = StringHelper::formatForBarAjax($chartData);
                    $result['pie'] = StringHelper::formatForPieAjax($chartData);
                    echo json_encode($result);
                    die();
                    return $result;
                }
            }
        }
    }

    /*
     * Return region list for country
     */
    public static function actionGetRegions(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax){
            $id = intval( Yii::$app->request->post('country'));
            $regions = Country::getRegions($id);
//            echo json_encode($regions);
//            return ['regions' => $regions];
            return $regions;
        }
    }

    /*
     * Return city list for region
     */
    public static function actionGetCities(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ( Yii::$app->request->isAjax){
            $country = intval( Yii::$app->request->post('country'));
            $region = intval( Yii::$app->request->post('region'));
            $cities = Country::getCities($country, $region);
//            echo json_encode($cities);
            return $cities;
        }
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */

    public function actionGetChartDataRaw()
    {

//        $requestData = [
//            'id' => Yii::$app->request->post('id'),
//            'gender' => Yii::$app->request->post('gender'),
//            'age' => Yii::$app->request->post('age'),
//            'country' => Yii::$app->request->post('country'),
//            'region' => Yii::$app->request->post('region'),
//        ];
        $requestData = Yii::$app->request->post();

        return $requestData;

        $raw = [
            'bar' => [
                'series' => [
                    ['name' => 'Yes','data' => ['110']],
                    ['name' => 'No','data' => ['10']]
                    ]
                ],
            'pie' => [
                ['name' => 'Yes','y' => 1,'sliced' => true,'selected'=> true],
                ['No',1]
                ]
            ];

//        $raw = <<<RAW_JSON
//{"bar":{"series":[{"name":"Yes","data":["1"]},{"name":"No","data":["0"]}]},"pie":[{"name":"Yes","y":1,"sliced":true,"selected":true},["No",0]]}
//RAW_JSON;

        return $raw;
//        return $this->render('get-chart-data');
    }

    public function actionGetChartDataRawTwo()
    {

        $raw = <<<RAW_JSON
{"bar":{"series":[{"name":"Yes","data":["1"]},{"name":"No","data":["0"]}]},"pie":[{"name":"Yes","y":1,"sliced":true,"selected":true},["No",0]]}
RAW_JSON;

        return $raw;
//        return $this->render('get-chart-data');
    }
}
