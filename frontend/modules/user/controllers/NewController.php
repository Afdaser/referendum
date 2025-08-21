<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\helpers\Url;
use common\models\User;
use common\models\Poll;

class NewController extends \yii\web\Controller
{

    const SECTION_COMMENTS = 'new-comments';
    const SECTION_ANSWERS = 'new-answers';

    public $menu = [];

    public function actionNewComments()
    {
        if (!Yii::$app->user->isGuest) {
            $this->fetchMenu(self::SECTION_COMMENTS);

            $polls = User::getPollsWithNewComments();

            return $this->render('new-comments', [
                        'category' => 'newComments',
                        'polls' => $polls
            ]);
        } else {
            $this->redirect('/');
        }
    }

    public function actionNewAnswers()
    {
        if (!Yii::$app->user->isGuest) {
            $this->fetchMenu(self::SECTION_ANSWERS);

            $comments = User::getCommentsWithAnswers();

            return $this->render('new-answers', [
                        'category' => 'newAnswers',
                        'comments' => $comments
            ]);
        } else {
            $this->redirect('/');
        }
    }

    protected function fetchMenu($section = null)
    {
        $commentsCount = Poll::getNewCommentsCount(Yii::$app->user->identity->id);
        $answersCount = User::getNewAnswersCount();
        $this->menu = [
            [
                'label' => Yii::t('main', 'Головна'),
                'url' => ['/site/myPolls'],
                'linkOptions' => ['class' => 'back_to_main'],
            ],
            [
                'label' => Yii::t('main', 'Нові коментарі') . '<span class="count_poll">' . $commentsCount . '</span>',
                'url' => Url::toRoute(['/user/new/new-comments']),
                'active' => ($section == self::SECTION_COMMENTS),
            ],
            [
                'label' => Yii::t('main', 'Нові відповіді') . '<span class="count_poll">' . $answersCount . '</span>',
                'url' => Url::toRoute(['/user/new/new-answers']),
                'active' => ($section == self::SECTION_ANSWERS),
            ],
            [
                'label' => Yii::t('main', 'Мій профіль'),
                'url' => ['/user/profile'],
            ],
        ];
    }

    /*
     * Check all comments for user polls as read
     */

    public function actionReadAllComments()
    {
        if (Yii::$app->request->isAjax) {
            if (!Yii::$app->user->isGuest) {
                $polls = Poll::getPollsWithNewComments();
                foreach ($polls as $poll) {
                    $poll->readPollComments();
                }
                return 1;
            } else {
                return 0;
            }
        }
    }

    // TODO REMOVE DEPRICATED!
    private static function readPollComments($poll)
    {
        return $poll->readPollComments();
    }

    /*
     * Check all answer for user comment as read by comment author
     */

    public function actionReadAnswers()
    {
        if (Yii::$app->request->isAjax) {
            $result = 0;
            if (!Yii::$app->user->isGuest) {
//                $id = intval(Yii::$app->request->getPost('id'));
//                $comment = PollComment::model()->findByPk($id);
//                if($comment){
//                    if($comment->user_id == Yii::$app->user->id){
//                        self::readCommentAnswers($comment);
//                        $result = 1;
//                    }
//                }
            }
            echo $result;
        }
        die(__METHOD__);
    }

    /*
     * Check answers for all user comments as read by author
     */

    public function actionReadAllAnswers()
    {
        if (Yii::$app->request->isAjax) {
            if (!Yii::$app->user->isGuest) {
                $comments = User::getCommentsWithAnswers();
                foreach ($comments as $comment) {
                    $comment->readCommentAnswers();
                }
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /*
     * Set comment answers as read
     */

    private function readCommentAnswers($comment)
    {
        return $comment->readCommentAnswers();
//        if ($comments = $comment->commentChilds(array('condition' => 'read_by_parent = 0'))) {
//            foreach ($comments as $comment) {
//                $comment->read_by_parent = 1;
//                $comment->save();
//            }
//        }
    }

}
