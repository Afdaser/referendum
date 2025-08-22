<?php

namespace frontend\modules\poll\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;

use common\models\Poll;
use common\models\PollOption;
use common\models\PollComment;
use common\models\User;

class PollController extends \yii\web\Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('changeCommentRating','upAnswerRating','changePollRating','view','getChartData','getRegions','getCities', 'vote','captcha'),
                'users'=>array('*'),
            ),
            array('allow',
                'actions'=>array('addComment','addAnswer','createPoll','editPoll'),
                'users'=>array('@'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
/*
    public function actionChangeCommentRating()
    {
        return $this->render('change-comment-rating');
    }

    public function actionChangePollRating()
    {
        return $this->render('change-poll-rating');
    }

    public function actionGetChartData()
    {
        return $this->render('get-chart-data');
    }

    public function actionUpAnswerRating()
    {
        return $this->render('up-answer-rating');
    }
/* */
    /*
     * Action view poll
     */
    public function actionView($language = null){
        if (!$this->checkRedirect()) {
            $id = intval(Yii::$app->request->get('id',0));
//            return $this->render('view');
            return self::renderView($id);
        }
    }


    /*
    * Render poll`s view
    */
    private function renderView($id,$comment=null,$answer=null){
//        $poll = Poll::model()->findByPk($id,'status<>'.Poll::POLL_STATUS_UNPUBLISHED);
        $poll = Poll::find()->where(['id' => $id])->published()->one();
        if(empty($poll)){
            throw new NotFoundHttpException(Yii::t('poll', 'Сторінку не знайдено'));
//            throw new CHttpException(404, Yii::t('poll', 'Сторінку не знайдено'));
        }
		$tags = [];
		foreach ($poll->tags as $pollTag){
			$tags[] = $pollTag->name;
		}

//		$this->pageTitle = $poll->title . ' ' . Yii::t('main', 'опрос');
                Yii::$app->page->setTitle($poll->title . ' ' . Yii::t('main', 'опрос'));

//		$this->pageKeywords = implode(',', $tags);
                Yii::$app->page->setKeywords(implode(',', $tags));

		//$this->pageDescription = $poll->describe;

                $locale = Yii::$app->urlManager->getCurrentLocale();

                $suffix = empty( Yii::$app->params['descriptionSuffix'][$locale] ) ? (' #' . Yii::t('main', 'опрос')) : Yii::$app->params['descriptionSuffix'][$locale];
                $prefix = empty( Yii::$app->params['descriptionPrefix'][$locale] ) ? '' : Yii::$app->params['descriptionPrefix'][$locale];
                $suffixLimit = !empty(Yii::$app->params['descriptionMaxLenth']) ? Yii::$app->params['descriptionMaxLenth'] : 156;
		$pageDescription = $prefix . $poll->title . $suffix;
                if(mb_strlen($pageDescription) > $suffixLimit){
//                    $this->pageDescription = mb_substr($pageDescription, 0 , ($suffixLimit-3)).'...';
                    Yii::$app->page->setDescription(mb_substr($pageDescription, 0 , ($suffixLimit-3)).'...');
                }else{
//                    $this->pageDescription = $pageDescription;
                    Yii::$app->page->setDescription($pageDescription);
                }

        $error = null;
        if(!isset($poll)){
            throw new NotFoundHttpException(Yii::t('poll', 'Сторінку не знайдено'));
//            throw new CHttpException(404, Yii::t('poll', 'Сторінку не знайдено'));
        }

        if(!$comment){
            $comment = new PollComment;
            $comment->poll_id = $id;
            $reply = intval(Yii::$app->request->get('reply'));

            if(PollComment::find()->where(['id' => $reply])->count()){
                $comment->parent_id = $reply;
            }
        } else {
            $error = Html::errorSummary($comment)?json_encode(Html::errorSummary($comment)):null;
        }

        if(!$answer){
            $answer = new PollOption;
            $answer->poll_id = $id;
        } else {
            $error = Html::errorSummary($answer)?json_encode(Html::errorSummary($answer)):null;
        }
        
// ✅ Додаємо canonical



// Відповідність мов до піддоменів
$langDomains = [
    1 => 'ua.referendum.social',
    2 => 'ru.referendum.social',
    3 => 'en.referendum.social',
    4 => 'no.referendum.social',
    // додай ще, якщо з’являться інші мови
];

// Визначаємо потрібний домен по мові опитування
$canonicalDomain = $langDomains[$poll->poll_language_id] ?? 'en.referendum.social';

// Правильний canonical URL у форматі /poll/ID
$canonicalUrl = 'https://' . $canonicalDomain . '/poll/' . $poll->id;

// Реєструємо тег canonical незалежно від поточного піддомену
Yii::$app->view->registerLinkTag([
    'rel' => 'canonical',
    'href' => $canonicalUrl,
]);

//		var_dump($poll->describe);
        return $this->render('view', [
            'poll'=>$poll,
            'commentModel'=>$comment,
            'answerModel'=>$answer,
            'error'=>$error,
        ]);
    }

    /*
     * Save poll`s comment
     */
    public function actionAddComment()
    {
        $postData = Yii::$app->request->post('Profile');
        $comment = new PollComment;
        if(!empty($postData['comment'])) {
            $comment->setCommentAttributes($postData['comment']);
        }

        if (!$comment->validate() || !$comment->save()) {
//            echo '<h2>Error~</h2><pre>';
//            var_dump($comment->getErrors());
//            echo '</pre>';
//            die(__METHOD__);
            return self::renderView($comment->poll_id, $comment);
        } elseif ($comment->parent_id) {
            $tmp = $comment;
            do {
                $tmp = $tmp->parent;
                $tmp->has_new = 1;
                $tmp->save();
            } while ($tmp->parent_id);
        }

        $this->redirect(array('view', 'id' => $comment->poll_id));
    }

    /*
     * Save poll`s variant of answer
     */
    public function actionAddAnswer()
    {
        $postData = Yii::$app->request->post('Profile');

        $answer = new PollOption();
        $answer->setUnpublishedOptionAttributes($postData['answer']);

        if (!$answer->validate() || !$answer->save()) {
//            echo '<h2>Error~</h2><pre>';
//            var_dump($answer->getErrors());
//            echo '</pre>';
//            die(__METHOD__);
            return self::renderView($answer->poll_id, null, $answer);
        }

        $this->redirect(array('view', 'id' => $answer->poll_id));
    }

    /*
     * Change poll`s comment rating
     */
    public function actionChangeCommentRating(){
        if (Yii::$app->request->isAjax && !Yii::$app->user->isGuest) {
            $id = intval(Yii::$app->request->post('id'));
            $rating = intval(Yii::$app->request->post('rating'));
            $comment = PollComment::find()->where(['id' => $id])->one();
            $isVoted = User::isVotedForComment($id);
            if($comment && !$isVoted && $rating){
               $newRating = $comment->changeRating($rating);
                echo json_encode($newRating);
            }
            /* Yii1 coe: * / 
            $comment = PollComment::model()->findByPk($id);
            $isVoted = User::isVotedForComment($id);
            if($comment && !$isVoted && $rating){
               $newRating = $comment->changeRating($rating);
                echo json_encode($newRating);
            }
            /* */
        }
    }


    /*
     * Change poll rating
     */
    public function actionChangePollRating(){
        if (Yii::$app->request->isAjax && !Yii::$app->user->isGuest) {
            $id = intval(Yii::$app->request->post('id'));
            $rating = intval(Yii::$app->request->post('rating'));
            $poll = Poll::find()->where(['id' => $id])->one();
            $isVoted = User::isVotedForPoll($id);
            if($poll && !$isVoted && $rating){
                $newRating = $poll->changeRating($rating);
                echo json_encode($newRating);
            }
        }
    }

    /*
     * Vote for poll`s option
     */
    public function actionVote($option)
    {
        if(!empty($option)){
            $id = (int) $option;
        }else{
            $id = (int) Yii::$app->request->get('option');
        }

//        echo '<h1>'.__METHOD__.'</h1>';
//        echo "<h2>{$id}</h2>";
//        die(__FILE__);

        if (!$id) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.').'#dev01');

        $option = PollOption::findOne(['id' => $id]);

        if (!$option) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.').'#dev02');
        }

/* / #TASK:3.1 Gues can vote old code
        if($option->poll->id == Poll::HOLDER_PAGE_POLL_ID || !Yii::$app->user->isGuest){
            if(!$option->poll->isVoted() && $option->poll->isActual()){
                if(!$option->vote()){
                    $error = Html::errorSummary($option);
                }
            }
         }
/* */
        if(Yii::$app->user->isGuest){
            if(!$option->poll->isVotedByGuest()){
                if(!$option->vote()){
                    $error = Html::errorSummary($option);
                }
            }
        }else{
            if (YII_ENV == 'dev') {
                echo '<h1>$option->poll->isVoted():</h1>';
                var_dump($option->poll->isVoted());
                echo '<hr>';
                echo '<h2>$option->poll:</h2>';
                echo '<pre>';
                var_dump($option->poll);
                echo '</pre>';
            }
            if(!$option->poll->isVoted() && $option->poll->isActual()){
                if(!$option->vote()){
                    $error = Html::errorSummary($option);
                }
            }
        }
//        if (YII_ENV == 'dev') {
//            die(__METHOD__);
//        }
        $this->redirect(['view','id'=>$option->poll_id]);
//        return $this->render('vote');
    }
    /*
     * Create new poll
     */
    public function actionCreatePoll(){
        $postData = Yii::$app->request->post();
        if($postData){
            $poll = new Poll;
            $poll->load($postData);
            

            if(isset($postData['Poll']['options'])){
                $options = $postData['Poll']['options'];
            }else{
                $options = [];
            }

            if($poll->validate() && $poll->save()){
                $poll->createNewPollOptions($options);
                if(isset($postData['Poll']['tags'])){
                    $poll->createNewPollTags($postData['Poll']['tags']);
                }
            }
        }
        $this->redirect('poll/site/my-polls');
//        $this->redirect(array('/site/myPolls'));

        /*
        $postData = Yii::$app->request->post('Poll');
        if($postData){
            $poll = new Poll;
            $poll->setPollAttributes($postData);
            $options = array();

            if(isset($postData['options'])){
                $options = $postData['options'];
            }

            if($poll->validate() && $poll->save()){
                $poll->createNewPollOptions($options);
                $poll->createNewPollTags($postData['tags']);
            }
        }

        $this->redirect(array('/site/myPolls'));
        /* */
    }

    /*
     * Return filtered data for chart building
     */
    public function actionGetChartData(){
        if (Yii::$app->request->isAjaxRequest){
            $id = intval(Yii::$app->request->post('id'));
            if($id){
                $poll = Poll::model()->findByPk($id);
                if($poll){
                    $gender = intval(Yii::$app->request->post('gender'));
                    $ageInterval = intval(Yii::$app->request->post('age'));
                    $country = intval(Yii::$app->request->post('country'));
                    if($country){
                        $region = intval(Yii::$app->request->post('region'));
                    } else {
                        $region = 0;
                    }
                    $chartData = $poll->getChartData($gender,$ageInterval,$country,$region);
                    $result['bar'] = StringHelper::formatForBarAjax($chartData);
                    $result['pie'] = StringHelper::formatForPieAjax($chartData);

                    echo json_encode($result);
                }
            }
        }
    }

    /*
     * Return region list for country
     */
    public static function actionGetRegions(){
        if (Yii::$app->request->isAjaxRequest){
            $id = intval(Yii::$app->request->post('country'));
            $regions = Country::getRegions($id);

            echo json_encode($regions);
        }
    }

    /*
     * Return city list for region
     */
    public static function actionGetCities(){
        if (Yii::$app->request->isAjaxRequest){
            $country = intval(Yii::$app->request->post('country'));
            $region = intval(Yii::$app->request->post('region'));
            $cities = Country::getCities($country,$region);

            echo json_encode($cities);
        }
    }

    public function actionEditPoll(){
        $postData = Yii::$app->request->get('Poll');
        if($postData){
            if(isset($postData['id'])){
                $id = intval($postData['id']);
                if($poll = Poll::model()->findByPk($id)){
                    if($poll->user_id == Yii::$app->user->identity->id && $poll->isEditable()){
                        $poll->setPollAttributes($postData);
                        if(isset($postData['options'])){
                            if($poll->validate() && $poll->save()){
                                $poll->editPollOptions($postData['options']);
                                $poll->editPollTags($postData['tags']);
                            }
                        }
                    }
                }
            }
        }

        $this->redirect(array('/site/myPolls'));
    }

    /*
    * Captcha
    */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'height' => 80,
                'width' => 160,
                'offset' => 5,
            ],
        ];
    }

    protected function checkRedirect() {
        $uri = Yii::$app->request->pathInfo;
        $segements = explode('/', Yii::$app->request->pathInfo);
        if ($segements[0] == 'poll') {
            if (isset($segements[1]) && $segements[1] == 'view') {
                if (isset($segements[2])) {
                    $pollId = $segements[2];
                    if ($poll = Poll::model()->findByPk($pollId)) {
                        $newUri = "/poll/{$pollId}";
                        $this->redirect($newUri);
                    }
                }
            }
        }
        return false;
    }
    /**
     * Finds the Poll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Poll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Poll::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
