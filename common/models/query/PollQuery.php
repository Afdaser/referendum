<?php

namespace common\models\query;

use Yii;
use common\models\Poll;
use common\models\PollVoteCount;

/**
 * This is the ActiveQuery class for [[\common\models\Poll]].
 *
 * @see \common\models\Poll
 */
class PollQuery extends \yii\db\ActiveQuery
{

    public function published()
    {
        return $this->andWhere(['<>', 'status', Poll::POLL_STATUS_UNPUBLISHED]);
    }

    public function categoryOwn($userId = null)
    {
        if ($userId) {
            $criteriaParams[':user'] = $userId;
        } else {
            if (!Yii::$app->user->isGuest) {
                $user = Yii::$app->user->identity;
                $criteriaParams[':user'] = $user->id;
            }
        }
        if (!empty($criteriaParams[':user'])) {
            $this->andWhere(['user_id' => $criteriaParams[':user']]);
        }
    }

    public function category($category, $userId = null)
    {
        //filter by category: hot,own,etc
        if (!empty($category)) {
            if ($category == 'own' || $category == 'user') {
//                $criteria->addCondition('user_id = :user');
                if ($userId) {
                    $criteriaParams[':user'] = $user_id;
                } else {
                    if (!Yii::$app->user->isGuest) {
                        $user = Yii::$app->user->identity;
                        $criteriaParams[':user'] = $user->id;
                    }
//                  $criteriaParams[':user'] = Yii::app()->user->id;
                }
                if (!empty($criteriaParams[':user'])) {
                    $this->andWhere(['user_id', $criteriaParams[':user']]);
                }
// Yii::$app->user
            } elseif ($category == 'actual') {
                // $criteria = self::getActualCriteria($criteria);
            } elseif ($category == 'search') {
                if ($searchModel) {
                    if ($searchModel->text) {
                        if (!$searchModel->searchInTags) {
                            $searchModel->searchInTitle = 1;
                            $criteria->addCondition("title LIKE CONCAT('%', :text ,'%')");
                        } else {
                            $criteria->join = "LEFT JOIN poll_tags pt ON t.id = pt.poll_id
                                    LEFT JOIN tags tg ON tg.id= pt.tag_id";
                            if ($searchModel->searchInTitle) {
                                $criteria->addCondition("title LIKE CONCAT('%', :text ,'%') OR
                                    tg.name LIKE CONCAT('%', :text ,'%')");
                            } else {
                                $criteria->addCondition("tg.name LIKE CONCAT('%', :text ,'%')");
                            }
                        }
                        $criteria->params[':text'] = $searchModel->text;
                    }
                    if ($searchModel->country) {
                        $criteria->addCondition('poll_country_id = :country OR poll_country_id = 0');
                        $criteria->params[':country'] = $searchModel->country;
                    }
                    if ($searchModel->region) {
                        $criteria->addCondition('poll_region_id = :region OR poll_region_id = 0');
                        $criteria->params[':region'] = $searchModel->region;
                    }
                }
            } elseif ($category == 'hot') {
                // $criteria->addCondition("DATE(date_add) = DATE(CURRENT_TIMESTAMP)");
            }
        }
    }

    public function leftJoinPollVoteCount()
    {
        return $this->from(['p' => Poll::tableName()])
                        ->leftJoin(['pvc' => PollVoteCount::tableName()], ' p.id = pvc.poll_id ')
                        ->select(['p.*', 'pvc.vote_count', 'pvc.guest_vote_count', 'pvc.user_vote_count']);
    }

    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return \common\models\Poll[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Poll|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}
