<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;
/**
 * Description of PollForm
 *
 * @author alex
 */
class PollForm extends Model {

    public $id;
    public $title;
    public $describe;
    public $status;
    public $result_type;
    public $votes_count_close;
    public $poll_language_id;
    public $show_for_all_languages;
    public $poll_sex;
    public $poll_country_id;
    public $poll_region_id;
    public $poll_city_id;
    public $poll_min_age;
    public $poll_max_age;
//    public $text;
//    public $search_in_title;
//    public $search_in_tags;
//    public $country;
//    public $region;

    /**
     * Declares the validation rules.
     *
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', ], 'required'],
//            [['title', 'user_id', 'status'], 'required'],
            [['describe'], 'string'],
            [[
                'id',
//                'user_id', 'rating', 'views',
                'status', 'result_type', 'votes_count_close', 'poll_language_id', 'show_for_all_languages', 'poll_sex', 
                'poll_country_id', 'poll_region_id', 'poll_city_id', 'poll_min_age', 'poll_max_age',
//                'show_on_slider', 'created_by', 'updated_by', 'created_at', 'updated_at'
                ], 'integer'
            ],
//            [['user_id', 'rating', 'status', 'views', 'result_type', 'poll_language_id', 'show_for_all_languages', 'poll_sex', 'poll_country_id', 'poll_region_id', 'poll_city_id', 'poll_min_age', 'poll_max_age', 'votes_count_close', 'show_on_slider', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['date_add', 'date_update'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['poll_language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['poll_language_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'describe' => Yii::t('app', 'Describe'),
            'user_id' => Yii::t('app', 'User'),
            'author.name' => Yii::t('app', 'Author'),
            'rating' => Yii::t('app', 'Rating'),
            'status' => Yii::t('app', 'Status'),
            'status' => Yii::t('app', 'Status'),
            'views' => Yii::t('app', 'Views'),
            'result_type' => Yii::t('app', 'Result type'),
            'poll_language_id' => Yii::t('app', 'Language'),
            'show_for_all_languages' => Yii::t('app', 'Show for all languages'),
            'poll_sex' => Yii::t('app', 'Sex'),
            'poll_country_id' => Yii::t('app', 'Country'),
            'poll_region_id' => Yii::t('app', 'Region'),
            'poll_city_id' => Yii::t('app', 'City'),
            'poll_min_age' => Yii::t('app', 'Poll min age'),
            'poll_max_age' => Yii::t('app', 'Poll max age'),
            'votes_count_close' => Yii::t('app', 'Votes count close'),
            'date_add' => Yii::t('app', 'Date add'),
            'date_update' => Yii::t('app', 'Date update'),
            'show_on_slider' => Yii::t('app', 'Show on slider'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    public function presetAttributes($params = [])
    {
        
    }

    public function unsetAttributes() {
        //  model method unsetAttributes() like Yii1 #7742
        // samdark added the status:under discussion
    }

    /*
     * Sets the attributes of poll model
     * @attr - data array
     */
    public function setPollAttributes($attr){
        $this->title = Html::encode($attr['title']);
        $this->describe = Html::encode($attr['describe']);
        $this->user_id = Yii::$app->user->id;
        $this->rating = 0;
        $this->status = isset($attr['status'])?intval($attr['status']):0;
        $this->views = 0;
        $this->result_type = intval($attr['type']);
        $this->poll_language_id = intval($attr['language']);
        if(isset($attr['all_language'])){
            $this->show_for_all_languages = 1;
        } else {
            $this->show_for_all_languages = 0;
        }
        $this->poll_sex = intval($attr['sex']);
        $this->poll_country_id = intval($attr['country']);
        $this->poll_region_id = intval($attr['region']);
        $this->poll_city_id = intval($attr['city']);
        $this->poll_min_age = intval($attr['min_age']);
        $this->poll_max_age = intval($attr['max_age']);
        if($this->status == self::POLL_STATUS_CLOSED){
            $this->votes_count_close = intval($attr['votes_count_close']);
        }
        if($this->id){
            $this->date_update = date('Y-m-d H:i:s');
        } else {
            $this->date_add = date('Y-m-d H:i:s');
        }
    }
}
