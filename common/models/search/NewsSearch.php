<?php

namespace common\models\search;

use common\models\News;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class NewsSearch extends News
{
    public function rules(): array
    {
        return [
            [['id', 'is_published', 'created_at', 'updated_at'], 'integer'],
            [['title', 'slug', 'excerpt', 'content', 'h1', 'desc', 'image', 'published_at'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search($params): ActiveDataProvider
    {
        $query = News::find()->orderBy(['published_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
        ]);

        // У списку адмінки залишаємо пошук по title як основний сценарій редактора.
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
