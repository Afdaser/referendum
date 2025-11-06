<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TagStaticText;

/**
 * Пошукова модель для фільтрації статичних текстів тегів.
 */
class TagStaticTextSearch extends TagStaticText
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tag_id', 'language_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // Обходимо сценарії базової моделі, достатньо стандартного набору.
        return Model::scenarios();
    }

    /**
     * Створює провайдер даних із накладеними фільтрами.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TagStaticText::find()->with(['tag', 'language']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['updated_at' => SORT_DESC],
                'attributes' => [
                    'id',
                    'updated_at',
                    'tag_id' => [
                        'asc' => ['tag.name' => SORT_ASC],
                        'desc' => ['tag.name' => SORT_DESC],
                    ],
                    'language_id' => [
                        'asc' => ['language.title' => SORT_ASC],
                        'desc' => ['language.title' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'tag_id' => $this->tag_id,
            'language_id' => $this->language_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', '{{%tag_static_text}}.content', $this->content]);

        return $dataProvider;
    }
}
