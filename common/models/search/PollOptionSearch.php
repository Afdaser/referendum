<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PollOption;

/**
 * PollOptionSearch represents the model behind the search form of `common\models\PollOption`.
 */
class PollOptionSearch extends PollOption
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'poll_id', 'user_id', 'status', 'rating', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title', 'date_add', 'date_update'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PollOption::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'poll_id' => $this->poll_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'rating' => $this->rating,
            'date_add' => $this->date_add,
            'date_update' => $this->date_update,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
