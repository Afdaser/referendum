<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Poll;
use common\models\PollVoteCount;

/**
 * PollSearch represents the model behind the search form of `common\models\Poll`.
 */
class PollSearch extends Poll
{
    public $fx = [];
    public $voteCount;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'rating', 'status', 'views', 'result_type', 'poll_language_id', 'show_for_all_languages', 'poll_sex', 'poll_country_id', 'poll_region_id', 'poll_city_id', 'poll_min_age', 'poll_max_age', 'votes_count_close', 'show_on_slider', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title', 'describe', 'date_add', 'date_update'], 'safe'],
            [['voteCount'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with query for getPublishedPolls() analog
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function publishedPolls($params)
    {
        if(empty($params['limit'])){
            $params['limit'] = Yii::$app->params['POLLS_LIMIT_MAIN_PAGE'];
        }
        if (empty($params['sort'])) {
            $sortOrder = [
                'user_vote_count' => SORT_DESC,
            ];
        } else {
            if ($params['sort'] == 'desc') {
                $sortOrder = [
                    'user_vote_count' => SORT_DESC,
                ];
            } elseif ($params['sort'] == 'asc') {
                $sortOrder = [
                    'user_vote_count' => SORT_ASC,
                ];
            }
        }

        $query = Poll::find()->leftJoinPollVoteCount();
        if(!empty($params['category'])){
            switch ($params['category']){
                case 'own' :
                    $query->categoryOwn();
                    break;
                case 'user' :
                    if(!empty($params['user'])) {
                        $query->categoryOwn($params['user']->id);
                    }
                    break;
            }
            // Yii 1
            //filter by date_add
			
            if($params['category'] == 'actual' || $params['category'] == 'hot'){
                // $criteria->addCondition('date_add >= :dateFrom');
                if($params['period'] == 'day') {
                    $dateFrom = date('Y-m-d H:i:s',strtotime('today 00:00'));
                } elseif($params['period'] == 'week') {
                    $dateFrom = date('Y-m-d H:i:s',strtotime('today -7 day 00:00'));
                } elseif($params['period'] == 'month') {
                    $dateFrom = date('Y-m-d H:i:s',strtotime('today -1 month 00:00'));
                } elseif($params['period'] == 'halfyear') {
                    $dateFrom = date('Y-m-d H:i:s',strtotime('today -6 month 00:00'));
                } elseif($params['period'] == 'year') {
                    $dateFrom = date('Y-m-d H:i:s',strtotime('today -1 year 00:00'));
                }
                if(empty($dateFrom)){
                    if($params['category'] == 'actual'){
                        $dateFrom = date('Y-m-d H:i:s',strtotime('today 00:00'));
                    }
                }
            }
        }

        if(!empty($dateFrom)){
            $query->andFilterWhere(['>', 'date_add',  $dateFrom]);
        }

        $query->andFilterWhere(['<>', 'status', Poll::POLL_STATUS_UNPUBLISHED]);
//        $query->andFilterWhere(['<>', 'poll.id', Poll::HOLDER_PAGE_POLL_ID]);

        if (!empty($params['language'])) {
            $query->andFilterWhere([
                'poll_language_id' => $params['language'],
            ]);
        }

//         add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['limit'],
				  'pageSizeParam' => false, // Запрещает `per-page` в URL
            ],
            'sort' => [
                'defaultOrder' => $sortOrder,
            ],
        ]);

//        if(isset($params['requestQueryParams'])){
//            $requestQueryParams = $params['requestQueryParams'];
//            $this->load($requestQueryParams);
//        }



        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'describe', $this->describe]);
        if(!empty($params['tag'])){
            $query->innerJoinWith('tags')->andFilterWhere(['tag.name' => $params['tag']]);

        }

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Poll::find()
                ->leftJoinPollVoteCount();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'title',
                    'poll_language_id',
                    'describe',
                    'user_id',
                    'rating',
                    'status',
                    'show_on_slider',
                    'date_add',
                    'voteCount' => [
                        'asc' => ['pvc.vote_count' => SORT_ASC],
                        'desc' => ['pvc.vote_count' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                ]
            ],

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'rating' => $this->rating,
            'status' => $this->status,
            'views' => $this->views,
            'result_type' => $this->result_type,
            'poll_language_id' => $this->poll_language_id,
            'show_for_all_languages' => $this->show_for_all_languages,
            'poll_sex' => $this->poll_sex,
            'poll_country_id' => $this->poll_country_id,
            'poll_region_id' => $this->poll_region_id,
            'poll_city_id' => $this->poll_city_id,
            'poll_min_age' => $this->poll_min_age,
            'poll_max_age' => $this->poll_max_age,
            'votes_count_close' => $this->votes_count_close,
            'date_add' => $this->date_add,
            'date_update' => $this->date_update,
            'show_on_slider' => $this->show_on_slider,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'describe', $this->describe]);

        if(!empty($params['tag'])){
            $query->innerJoinWith('tags')->andFilterWhere(['tag.name' => $params['tag']]);
        }

        if (!empty($this->voteCount)) {
            $query->andFilterWhere(['pvc.vote_count' => $this->voteCount]);
        }

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchTag($params, $searchForm, $tag)
    {
        if(!isset($params['limit'])){
            $params['limit'] = Yii::$app->params['POLLS_LIMIT_MAIN_PAGE'];
        }
		
        $query = Poll::find();
        // add conditions that should always apply here
		if(isset($params['page']) and $params['page']>1){
			$title = $params['page'].Yii::t('main', '{hashtag} title_pag', ['hashtag' => $tag]);
			$description = $params['page'].Yii::t('main', '{hashtag} description_pag', ['hashtag' => $tag]);
		}else{
			$title = Yii::t('main', '{hashtag} title', ['hashtag' => $tag]);
			$description = Yii::t('main', '{hashtag} description', ['hashtag' => $tag]);
		}

		$title = (mb_strlen($title) > 65) ? mb_substr($title, 0, 65) . '...' : $title;
		$description = (mb_strlen($description) > 151) ? mb_substr($description, 0, 151) . '...' : $description;
		
		Yii::$app->page->title = $title;
		Yii::$app->page->description = $description;

/*

Yii::$app->view->title = $title;*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['limit'],
				  'pageSizeParam' => false, // Запрещает `per-page` в URL
				  'forcePageParam' => false,
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->innerJoinWith('tags')->andFilterWhere(['tag.name' => $tag]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchForm($params, $searchForm)
    {
        $dataProvider = $this->search($params);
        $query = $dataProvider->query;

        if(!isset($params['limit'])){
            $params['limit'] = Yii::$app->params['POLLS_LIMIT_MAIN_PAGE'];
        }

        $query = Poll::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['limit'],
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
        return $dataProvider;
    }
        $searchText = $searchForm->text;

        if($searchForm->search_in_tags){
            $query->innerJoinWith('tags')->orFilterWhere(['tag.name' => $searchText]);
        }


        $query->orFilterWhere(['like', 'title', $searchText]);

        if(empty($searchForm->search_in_title)){
            $query->orFilterWhere(['like', 'describe', $searchText]);
        }
        if(!empty($searchForm->country)){
            $query->andFilterWhere(['poll_country_id' => $searchForm->country]);
            if(!empty($searchForm->region)){
                $query->andFilterWhere(['poll_region_id' => $searchForm->region ]);
            }
        }

        return $dataProvider;
    }
}
