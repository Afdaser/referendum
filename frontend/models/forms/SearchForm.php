<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;

/**
 * Description of SearchForm
 *
 * @author alex
 */
class SearchForm extends Model {

    public $text;
    public $search_in_title;
    public $search_in_tags;
    public $country;
    public $region;

    /**
     * Declares the validation rules.
     *
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['poll_language_id', 'show_for_all_languages', 'poll_sex', 'poll_country_id', 'poll_region_id', 'poll_city_id', 'poll_min_age', 'poll_max_age',], 'integer'],
            // text is required
//            [
//                'text',
//                'required',
//                'message' => Yii::t("main", 'Введіть') . ' {attribute}.'
//            ],
            [['text',], 'string',],
            [['country', 'region', ], 'string',],
            [['search_in_title', 'search_in_tags'], 'boolean',],
        ];
        // username must have email format
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return [
            'text' => Yii::t("main", 'Search:'),
        ];
    }

    public function getSearchInTitle() {
        return $this->search_in_title;
    }
    public function getSearchInTags() {
        return $this->search_in_tags;
    }
}
