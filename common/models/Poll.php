<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;
use common\helpers\StringHelper;
use yii\bootstrap\Html;
use yii\db\Expression;

/**
 * This is the model class for table "{{%poll}}".
 *
 * @property int $id ID
 * @property string $title Title
 * @property string|null $describe Describe
 * @property int $user_id User
 * @property int $rating Rating
 * @property int $status Status
 * @property int $views Views
 * @property int $result_type Result type
 * @property int $poll_language_id Language
 * @property int $show_for_all_languages Show for all languages
 * @property int $poll_sex Sex
 * @property int $poll_country_id Country
 * @property int $poll_region_id Region
 * @property int $poll_city_id City
 * @property int $poll_min_age Poll min age
 * @property int $poll_max_age Poll max age
 * @property int $votes_count_close Votes count close
 * @property string $date_add Date add
 * @property string $date_update Date update
 * @property int $show_on_slider Show on slider
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property PollAnswer[] $pollAnswers
 * @property PollComment[] $pollComments
 * @property Language $pollLanguage
 * @property PollOption[] $pollOptions
 * @property PollRatingVote[] $pollRatingVotes
 * @property PollTag[] $pollTags
 * @property Tag[] $tags
 * @property User[] $users
 */
class Poll extends ActiveRecord
{
    public static $subdomen = null;
//    const MAIN_DOMAIN = 'https://online-statistics.org';
    # poll statuses
    const POLL_STATUS_ACTIVE = 0;
    const POLL_STATUS_CLOSED = 1;
    const POLL_STATUS_UNPUBLISHED = 2;

    # poll`s result types
    const POLL_TYPE_HORIZONTAL = 0;
    const POLL_TYPE_VERTICAL = 1;
    const POLL_TYPE_PIE = 2;

    #poll, witch will be shown on the holder page
    const HOLDER_PAGE_POLL_ID = -1;

    #time to edit own poll
    const TIME_TO_EDIT = 5;
    public $editable = false;


    public $showResultOnMainPage = NULL;

    private $tagIds = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%poll}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'user_id', 'status'], 'required'],
            [['describe'], 'string'],
            [['user_id', 'rating', 'status', 'views', 'result_type', 'poll_language_id', 'show_for_all_languages', 'poll_sex', 'poll_country_id', 'poll_region_id', 'poll_city_id', 'poll_min_age', 'poll_max_age', 'votes_count_close', 'show_on_slider', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
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

    /**
     * Gets query for [[PollAnswers]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollAnswerQuery
     */
    public function getPollAnswers()
    {
        // Yii1:
        // 'pollAnswers' => array(self::HAS_MANY, 'PollOption', 'poll_id', 'order' => 'rating DESC', 'condition' => 'status = ' . PollOption::OPTION_STATUS_UNPUBLISHED),
//        return $this->hasMany(PollAnswer::class, ['poll_id' => 'id']);
        return $this->hasMany(PollOption::class, ['poll_id' => 'id'])->onCondition(['status' => PollOption::OPTION_STATUS_UNPUBLISHED]);
    }

    /**
     * Gets query for [[PollComments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentQuery
     */
    public function getPollComments()
    {
        return $this->hasMany(PollComment::class, ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[PollComments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentQuery
     */
    public function getPollCommentsRoot()
    {
        // 'pollCommentsRoot' => array(self::HAS_MANY, 'PollComment', 'poll_id', 'order' => 'date_add', 'condition' => 'parent_id IS NULL'),
        // 'pollCommentsRootCount' => array(self::STAT, 'PollComment', 'poll_id', 'order' => 'date_add', 'condition' => 'parent_id IS NULL'),
        return $this->hasMany(PollComment::class, ['poll_id' => 'id'])->andOnCondition(['is', 'parent_id', NULL]);
    }

    /**
     * Gets query for [[PollComments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentQuery
     */
    public function getPollCommentsNew()
    {
        // 'pollCommentsRoot' => array(self::HAS_MANY, 'PollComment', 'poll_id', 'order' => 'date_add', 'condition' => 'parent_id IS NULL'),
        // 'pollCommentsRootCount' => array(self::STAT, 'PollComment', 'poll_id', 'order' => 'date_add', 'condition' => 'parent_id IS NULL'),
        return $this->hasMany(PollComment::class, ['poll_id' => 'id'])->andOnCondition(['is_new' => PollComment::NEW_COMMENT]);
    }

    public function getCountPollCommentsNew()
    {
        return $this->getPollCommentsNew()->count();
    }

    public function getPollCommentsRootCount()
    {
        return $this->getPollCommentsRoot()->count();
    }

    /**
     * Gets query for [[PollLanguage]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\LanguageQuery
    public function getPollLanguage()
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::class, ['id' => 'poll_language_id']);
    }

    /**
     * Gets query for [[PollVoteCount]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollVoteCountQuery
     */
    public function getPollVoteCount()
    {
        return $this->hasOne(PollVoteCount::class, ['poll_id' => 'id']);
    }

    public function getVoteCount()
    {
        $modelPollVoteCount = $this->pollVoteCount;
        if(empty($modelPollVoteCount)){
//            return 'NULL -0';
            return 0;
        } else {
            return $modelPollVoteCount->vote_count;
        }
    }

    /**
     * Gets query for [[PollOptions]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollOptionQuery
     */
    public function getPollOptions()
    {
        // Yii1:
        // 'pollOptions' => array(self::HAS_MANY, 'PollOption', 'poll_id', 'condition' => 'status = ' . PollOption::OPTION_STATUS_PUBLISHED),
        return $this->hasMany(PollOption::class, ['poll_id' => 'id'])->andOnCondition(['status' => PollOption::OPTION_STATUS_PUBLISHED]);
//        return $this->hasMany(PollOption::class, ['poll_id' => 'id', 'status' => PollOption::OPTION_STATUS_PUBLISHED]);
//        return $this->hasMany(PollOption::class, ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[PollRatingVotes]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollRatingVoteQuery
     */
    public function getPollRatingVotes()
    {
        return $this->hasMany(PollRatingVote::class, ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[PollTags]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollTagQuery
     */
    public function getPollTags()
    {
        return $this->hasMany(PollTag::class, ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TagQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('{{%poll_tag}}', ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('{{%poll_rating_vote}}', ['poll_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PollQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PollQuery(get_called_class());
    }

    public function getStatusName() {
        return StringHelper::formatPollStatus($this->status);
// statusName
//        return '+'.$this->status;
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']); // ->viaTable('{{%poll_rating_vote}}', ['poll_id' => 'id']);
    }

    /**
     * Gets query for [[OptionVote]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OptionVoteQuery
     */
    public function getPollOptionsVoters()
    {
       // 'pollOptionsVoters' => array(self::HAS_MANY, 'OptionVote', array('id' => 'option_id'), 'through' => 'pollOptions'),
//     '' => array(self::HAS_MANY, 'OptionVote', array('id' => 'option_id'), 'through' => 'pollOptions'),
        return $this->hasMany(OptionVote::class, ['option_id' => 'id'])->viaTable('{{%poll_option}}', ['poll_id' => 'id']);

    }

    /**
     * Gets query for [[OptionVote]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OptionVoteQuery
     */
    public function getPollOptionsGuestVoters()
    {
       // 'pollOptionsGuestVoters' => array(self::HAS_MANY, 'OptionGuestVote', array('id' => 'option_id'), 'through' => 'pollOptions'),
        return $this->hasMany(OptionGuestVote::class, ['option_id' => 'id'])->viaTable('{{%poll_option}}', ['poll_id' => 'id']);
    }


/* * /
    /**
     * @return array relational rules.
     * /
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'pollComments' => array(self::HAS_MANY, 'PollComment', 'poll_id', 'order' => 'date_add'),
            'pollCommentsRoot' => array(self::HAS_MANY, 'PollComment', 'poll_id', 'order' => 'date_add', 'condition' => 'parent_id IS NULL'),
            'pollCommentsRootCount' => array(self::STAT, 'PollComment', 'poll_id', 'order' => 'date_add', 'condition' => 'parent_id IS NULL'),
            'pollAnswers' => array(self::HAS_MANY, 'PollOption', 'poll_id', 'order' => 'rating DESC', 'condition' => 'status = ' . PollOption::OPTION_STATUS_UNPUBLISHED),
            'pollOptions' => array(self::HAS_MANY, 'PollOption', 'poll_id', 'condition' => 'status = ' . PollOption::OPTION_STATUS_PUBLISHED),
+'pollOptionsVoters' => array(self::HAS_MANY, 'OptionVote', array('id' => 'option_id'), 'through' => 'pollOptions'),
            'pollOptionsVotesCount' => array(self::STAT, 'PollOption', 'poll_id', 'join' => 'RIGHT JOIN option_votes ON t.id = option_votes.option_id'),
            // Guest:
//+            'pollOptionsGuestVoters' => array(self::HAS_MANY, 'OptionGuestVote', array('id' => 'option_id'), 'through' => 'pollOptions'),
            'pollOptionsGuestVotesCount' => array(self::STAT, 'PollOption', 'poll_id', 'join' => 'RIGHT JOIN option_guest_votes ON t.id = option_guest_votes.option_id'),
            'pollRatingVotes' => array(self::HAS_MANY, 'PollRatingVote', 'poll_id', 'together' => true),
            'pollTags' => array(self::HAS_MANY, 'PollTag', 'poll_id'),
            'Tags' => array(self::MANY_MANY, 'Tag', 'poll_tags(poll_id, tag_id)'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'language' => array(self::BELONGS_TO, 'Language', 'poll_language_id'),
            'pollCity' => array(self::BELONGS_TO, 'RegionCity', 'poll_city_id'),
            'pollRegion' => array(self::BELONGS_TO, 'CountryRegion', 'poll_region_id'),
            'pollLanguage' => array(self::BELONGS_TO, 'Language', 'poll_language_id'),
            'pollCountry' => array(self::BELONGS_TO, 'Countrie', 'poll_country_id'),
            'language' => array(self::BELONGS_TO, 'Language', 'poll_language_id'),
        );
    }
/* */
    /**
     * @return array relational rules.
     */
/* TODO: Remove_Yii1_code * / 
    private function yiiOneRelations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'pollComments' => array(self::HAS_MANY, 'PollComment', 'poll_id', 'order' => 'date_add'),
            'pollCommentsRoot' => array(self::HAS_MANY, 'PollComment', 'poll_id', 'order' => 'date_add', 'condition' => 'parent_id IS NULL'),
            'pollCommentsRootCount' => array(self::STAT, 'PollComment', 'poll_id', 'order' => 'date_add', 'condition' => 'parent_id IS NULL'),
            'pollAnswers' => array(self::HAS_MANY, 'PollOption', 'poll_id', 'order' => 'rating DESC', 'condition' => 'status = ' . PollOption::OPTION_STATUS_UNPUBLISHED),
            'pollOptions' => array(self::HAS_MANY, 'PollOption', 'poll_id', 'condition' => 'status = ' . PollOption::OPTION_STATUS_PUBLISHED),
            'pollOptionsVoters' => array(self::HAS_MANY, 'OptionVote', array('id' => 'option_id'), 'through' => 'pollOptions'),
            'pollOptionsVotesCount' => array(self::STAT, 'PollOption', 'poll_id', 'join' => 'RIGHT JOIN option_votes ON t.id = option_votes.option_id'),
            // Guest:
            'pollOptionsGuestVoters' => array(self::HAS_MANY, 'OptionGuestVote', array('id' => 'option_id'), 'through' => 'pollOptions'),
            'pollOptionsGuestVotesCount' => array(self::STAT, 'PollOption', 'poll_id', 'join' => 'RIGHT JOIN option_guest_votes ON t.id = option_guest_votes.option_id'),
            'pollRatingVotes' => array(self::HAS_MANY, 'PollRatingVote', 'poll_id', 'together' => true),
            'pollTags' => array(self::HAS_MANY, 'PollTag', 'poll_id'),
            'Tags' => array(self::MANY_MANY, 'Tag', 'poll_tags(poll_id, tag_id)'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'language' => array(self::BELONGS_TO, 'Language', 'poll_language_id'),
            'pollCity' => array(self::BELONGS_TO, 'RegionCity', 'poll_city_id'),
            'pollRegion' => array(self::BELONGS_TO, 'CountryRegion', 'poll_region_id'),
            'pollLanguage' => array(self::BELONGS_TO, 'Language', 'poll_language_id'),
            'pollCountry' => array(self::BELONGS_TO, 'Countrie', 'poll_country_id'),
            'language' => array(self::BELONGS_TO, 'Language', 'poll_language_id'),
        );
    }
    /* */



    /**
     * Голосовавшие страны
     *
     * @return array
     */
	public function getVoteCountries()
    {
        // получаем айдишники для option
        $optionIDs = [];
        foreach($this->pollOptions as $option) {
            $optionIDs[] = $option->id;
        }
/* TODO: Remove_Yii1_code */
//        $criteria = new CDbCriteria();
//        $criteria->addInCondition('option_id', $optionIDs);
//        $optionVotes = OptionVote::model()->with('user')->findAll($criteria);
//
        $optionVotes = OptionVote::find()->with('user')->where(['option_id' => $optionIDs])->all();

        // получаем айдишники стран, участвующих в опросе и формируем массив id => кол-во голосов
        $countryVotes = [];
        foreach ($optionVotes as $optionVote) {
            /** @var OptionVote $optionVote */
            $key = $optionVote->user->profile->country_id;
            if (!empty($key)) {
                if (array_key_exists($key, $countryVotes)) {
                    $countryVotes[$key]++;
                } else {
                    $countryVotes[$key] = 1;
                }
            }
        }

        // всего голосов
        $totalVotes = array_sum($countryVotes);

        $countries = Country::getCountriesList();
        $result = [];
        foreach ($countries as $country) {
            /** @var Country $country */
            $key = $country->id;
            if (array_key_exists($key, $countryVotes)) {
                $percent = ($countryVotes[$key] / $totalVotes) * 100;
                $result[$key] = $country->name . ' (' . round($percent, 2) . '%)';
            }
        }

        return $result;
    }

    /*
     * return TRUE if poll is open
     */
    public function isOpen(){
        $result = false;
        if ($this->status == Poll::POLL_STATUS_ACTIVE) {
            $result = true;
        }
        return $result;
    }

    /*
     * return all polls with statuses ACTIVE and CLOSED
     * @limit - limit of polls
     * @tag - hash-tag
     * @sort - order: asc,desc
     * @category - poll`s category
     */
    public static function getPublishedPolls($limit, $tag, $sort, $category, $user_id, $searchModel, $period, $languageCode = null){
//        $criteria=new CDbCriteria;

        //filter by category: hot,own,etc
        if($category){
            if($category == 'own' || $category == 'user'){
//                $criteria->addCondition('user_id = :user');
//                if($user_id){
//                    $criteria->params[':user'] = $user_id;
//                } else {
//                    $criteria->params[':user'] = Yii::$app->user->id;
//                }
            } elseif($category == 'actual'){
//                $criteria = self::getActualCriteria($criteria);
            } elseif($category == 'search'){
//                if($searchModel){
//                    if($searchModel->text){
//                        if(!$searchModel->searchInTags){
//                            $searchModel->searchInTitle = 1;
//                            $criteria->addCondition("title LIKE CONCAT('%', :text ,'%')");
//                        } else {
//                            $criteria->join = "LEFT JOIN poll_tags pt ON t.id = pt.poll_id
//                                    LEFT JOIN tags tg ON tg.id= pt.tag_id";
//                            if($searchModel->searchInTitle){
//                                $criteria->addCondition("title LIKE CONCAT('%', :text ,'%') OR
//                                    tg.name LIKE CONCAT('%', :text ,'%')");
//                            } else {
//                                $criteria->addCondition("tg.name LIKE CONCAT('%', :text ,'%')");
//                            }
//                        }
//                        $criteria->params[':text'] = $searchModel->text;
//                    }
//                    if($searchModel->country){
//                        $criteria->addCondition('poll_country_id = :country OR poll_country_id = 0');
//                        $criteria->params[':country'] = $searchModel->country;
//                    }
//                    if($searchModel->region){
//                        $criteria->addCondition('poll_region_id = :region OR poll_region_id = 0');
//                        $criteria->params[':region'] = $searchModel->region;
//                    }
//                }
            }elseif($category == 'hot'){
	           // $criteria->addCondition("DATE(date_add) = DATE(CURRENT_TIMESTAMP)");
            }
        }

        //filter by poll`s tag
        if($tag){
            if($tagId = Tag::getTagId($tag)){
                $criteria->with = array(
                    'pollTags'=>array(
                        'select'=>false,
                        'condition'=>"pollTags.tag_id = $tagId",
                    ),
                );

            }
        }
/*
        //filter by date_add
        if($category == 'actual' || $category == 'hot'){
            $criteria->addCondition('date_add >= :dateFrom');
            if($period == 'day') {
                $criteria->params[':dateFrom'] = date('Y-m-d H:i:s',strtotime('today 00:00'));
            } elseif($period == 'week') {
                $criteria->params[':dateFrom'] = date('Y-m-d H:i:s',strtotime('today -7 day 00:00'));
            } elseif($period == 'month') {
                $criteria->params[':dateFrom'] = date('Y-m-d H:i:s',strtotime('today -1 month 00:00'));
            } elseif($period == 'year') {
                $criteria->params[':dateFrom'] = date('Y-m-d H:i:s',strtotime('today -1 year 00:00'));
            }
        }
/* */
        if($category == 'hot'){
//                $SERVER = explode('.', $_SERVER['SERVER_NAME']);
//                if($SERVER[0] == 'en'){
//                        $criteria->addCondition('poll_language_id = :polllanguageid');
//                        $criteria->params[':polllanguageid'] = '3';
//                }
//                else if($SERVER[0] == 'ru'){
//                        $criteria->addCondition('poll_language_id = :polllanguageid');
//                        $criteria->params[':polllanguageid'] = '2';
//                }
//                else if($SERVER[0] == 'ua'){
//                        $criteria->addCondition('poll_language_id = :polllanguageid');
//                        $criteria->params[':polllanguageid'] = '1';
//                }
        }

//        $language = Language::getLanguageByName($languageCode);
//        if ($language) {
//            $criteria->addCondition('poll_language_id = :polllanguageid');
//            $criteria->params[':polllanguageid'] = $language;
//        }

        //filter by poll`s status
//        $criteria->addCondition('status <> :status');
//        $criteria->params[':status'] = Poll::POLL_STATUS_UNPUBLISHED;

//        $criteria->addCondition('t.id <> :holderPoll');
//        $criteria->params[':holderPoll'] = Poll::HOLDER_PAGE_POLL_ID;

        //order
        if($sort == 'default'){
//            $criteria->order = 'date_add DESC';
        } elseif($sort == 'asc') {
//            $criteria->order = 't.rating ASC';
        } else {
//            $criteria->order = 't.rating DESC';
        }

//        $pollsCount = Poll::model()->count($criteria);

        //pagination
        // Yii 1
//        $curPage = intval(Yii::$app->request->getQuery('page', 1) - 1);
        // Yii 2
        $curPage = intval(Yii::$app->request->get('page', 1) - 1);
//        $criteria->limit = $limit;
//        $criteria->offset = $curPage * $limit;
//        $criteria->together=true;

//        $totalItems=Poll::model()->count($criteria);
//        $polls=Poll::model()->findAll($criteria);
//        $pages = Pager::getPagination($totalItems, $limit, $criteria);
        $polls = Poll::find()->all();
        $pages = [];
        $pollsCount = Poll::find()->count();

        return array(
            'polls'=>$polls,
            'pages'=>$pages,
            'pollsCount'=>$pollsCount,
        );
    }

    /*
     * Return criteria ti find only actual polls
     * if user is guest - return @param criteria
     */
    private static function getActualCriteria($criteria){
        if(!Yii::$app->user->isGuest){
            $user = User::model()->findByPk(Yii::$app->user->id);
            if($user->is_active){
                $languages = implode(",", $user->getLanguages());
                $criteria->select = "t.*";
                $criteria->addCondition("(show_for_all_languages = 1 OR (poll_language_id IN ($languages)))
                    AND (poll_sex = 0 OR poll_sex = :sex) AND (poll_min_age = 0 OR poll_min_age < :minAge)
                    AND (poll_max_age = 0 OR poll_max_age > :maxAge) AND (poll_country_id = 0 OR poll_country_id = :country)
                    AND (poll_region_id = 0 OR poll_region_id = :region) AND (poll_city_id = 0 OR poll_city_id = :city)");

              /*  $criteria->join = "LEFT JOIN poll_tags pt ON t.id = pt.poll_id
                                    LEFT JOIN tags tg ON tg.id= pt.tag_id";

                $criteria->addCondition(":preferences LIKE CONCAT('%', tg.name ,'%')
                    OR :activity LIKE CONCAT('%', tg.name ,'%')
                    OR :interests LIKE CONCAT('%', tg.name ,'%')
                    OR :music LIKE CONCAT('%', tg.name ,'%')
                    OR :films LIKE CONCAT('%', tg.name ,'%')
                    OR :books LIKE CONCAT('%', tg.name ,'%')
                    OR :shows LIKE CONCAT('%', tg.name ,'%')
                    OR :games LIKE CONCAT('%', tg.name ,'%')");

                $criteria->group = 't.id';*/

                $criteria->params = array(
                    ':sex'=>$user->sex?$user->sex:0,
                    ':minAge'=>$user->getAge(),
                    ':maxAge'=>$user->getAge(),
                    ':country'=>$user->profile->country_id?$user->profile->country_id:0,
                    ':region'=>$user->profile->region_id?$user->profile->region_id:0,
                    ':city'=>$user->profile->city_id?$user->profile->city_id:0,
                    /*':preferences'=>$user->preferences,
                    ':activity'=>$user->interests?$user->interests->activity:'',
                    ':interests'=>$user->interests?$user->interests->interests:'',
                    ':music'=>$user->interests?$user->interests->music:'',
                    ':films'=>$user->interests?$user->interests->films:'',
                    ':books'=>$user->interests?$user->interests->books:'',
                    ':shows'=>$user->interests?$user->interests->shows:'',
                    ':games'=>$user->interests?$user->interests->games:'',*/
                );
            }
        }
        return $criteria;
    }

    /*
     * return only active polls
     * @limit - limit of polls
     */
    public static function getActivePolls($limit=NULL){
        /* TODO: Remove_Yii1_code */
        $criteria=new CDbCriteria;
        $criteria->addCondition('status = :status');
        $criteria->params[':status'] = Poll::POLL_STATUS_ACTIVE;
        if($limit){
            $criteria->limit = $limit;
        }
        $criteria->order = 't.date_add DESC';
        $polls=Poll::model()->findAll($criteria);

        return $polls;
    }

    /*
     * Return describe for the poll
     */
    public function getPollDescribe(){
        $result = Yii::t('poll', 'Варіанти відповіді: ');
      $options = $this->pollOptions;
//        $options = $this->getPollOptions()->all();
        foreach($options as $option){
            $result .= $option->title . " ";
        }
//        echo '<h1>'.__METHOD__.'</h1><pre>';
//        var_dump($option);
//        echo '<hr>';
//        var_dump($option);
//        echo '</pre>';
//        echo '<hr>';
//        echo '<div style="border:2px dotted red;">';
//        echo $result;
//        echo '</div>';
//        die(__FILE__.'#'.__LINE__."\n");
        return $result;
    }

    /**
     * Return SEO url from Pool
     * @return string
     */

    /**
     * Return SEO url from Pool
     * @return string
     */
    public function getUrl()
    {
        if(is_null(self::$subdomen)){
            $host = explode('.', $_SERVER['SERVER_NAME']);
            if(in_array($host[0], array('en', 'uk', 'ua' , 'ru', 'no'))){
                $subdomen = $host[0];
            }else{
                $subdomen = '';
            }
        }
        if($subdomen === ''){
            return SITE_PROTOCOL."{$this->language->name}.".SITE_DOMAIN."/poll/{$this->id}";
//            return "http s://{$this->language->name}.".SITE_DOMAIN."/poll/{$this->id}";
//            $prefix = str_replace('http s://on', "http s://{$this->language->name}.on", self::MAIN_DOMAIN);
//            return $prefix.'/poll/'.$this->id;
        }else{
            return '/poll/'.$this->id;
        }

//        return Yii::$app->createUrl('poll/view', array('id' => $this->id));
    }

    /**
     * Return SEO AbsoluteUrl from Pool
     * @return string
     */
    public function getAbsoluteUrl()
    {
        if(is_null(self::$subdomen)){
            $host = explode('.', $_SERVER['SERVER_NAME']);
            if(in_array($host[0], array('en', 'uk', 'ua' , 'ru', 'no'))){
                $subdomen = $host[0];
            }else{
                $subdomen = '';
            }
        }

        if($subdomen === ''){
            return SITE_PROTOCOL."{$this->language->name}.".SITE_DOMAIN."/poll/{$this->id}";
//            $prefix = str_replace('https://on', "https://{$this->language->name}.on", self::MAIN_DOMAIN);
//            return $prefix.'/poll/'.$this->id;
        }else{
//            return '/poll/'.$this->id;
            return SITE_PROTOCOL."{$this->language->name}.".SITE_DOMAIN."/poll/{$this->id}";
        }

//        return Yii::$app->createUrl('poll/view', array('id' => $this->id));
    }
/* TODO: Remove_Yii1_code * /
    public function yiiOnegetUrl()
    {
        if (is_null(self::$subdomen)) {
            $host = explode('.', $_SERVER['SERVER_NAME']);
            if(in_array($host[0], array('en', 'uk', 'ua' , 'ru', 'no'))){
                self::$subdomen = $host[0];
            }else{
                self::$subdomen = '';
            }
        }
        if(self::$subdomen === ''){
            return SITE_PROTOCOL."{$this->language->name}.".SITE_DOMAIN."/poll/{$this->id}"; // #P#
//            return "http s://{$this->language->name}.".SITE_DOMAIN."/poll/{$this->id}"; // #P#
//            $prefix = str_replace('https://on', "https://{$this->language->name}.on", self::MAIN_DOMAIN);
//            return $prefix.'/poll/'.$this->id;
        }else{
            return '#P#/poll/'.$this->id;
        }

//        return Yii::$app->createUrl('poll/view', array('id' => $this->id));
    }
/* /END_Yii1_code */

    /**
     * Return Count of Voters with Guests
     * count($poll->pollOptionsVoters) + count($poll->pollOptionsGuestVoters)
     * @return integer
     */
    public function getCountPollOptionsVoters()
    {
//        $countAuthVotes = count($this->getPollOptionsVoters()->where(['>', 'id', 0])->all());
//        $countGuestVotes = count($this->getPollOptionsGuestVoters()->all());
//        $countAuthVotes = $this->getPollOptionsVoters()->where(['>', 'id', 0])->count();
        $countAuthVotes = $this->getPollOptionsVoters()->count();
        $countGuestVotes = $this->getPollOptionsGuestVoters()->count();

/* TODO: Remove_Yii1_code */
        if (0 AND YII_ENV == 'dev') {
//            echo 'Dev2405[ ' . $countGuestVotes . ' ] + [ ' . $countAuthVotes . ' ]';
//            echo '<pre>';
//
//            echo '<h3>Raw SQL:</h3>';
//            $query = $this->getPollOptionsVotersDev();
//            $querySQL =  $query->createCommand()->sql;
//            echo $querySQL;
//            echo '<h3>Loop:</h3>';
//            foreach ($this->getPollOptionsVotersDev()->all() as $key => $item) {
//                if($key <1){
//                    echo "<hr><h2>Key: [{$key}]</h2><pre>";
//                    var_dump($item);
//                    echo '</pre>';
//                }else{
//                    echo "<p>Option: [{$item->id}] | `option_id` = [{$item->option_id}] |</p>"; // `option_id` = [{$item->poll_id}]</p>";
//// `option_id` IN (SELECT `id` FROM `poll_option` WHERE `poll_id` = 432 )
//                }
//            }
////            var_dump($this->getPollOptionsVoters());
////            var_dump($this->getPollOptionsVoters());
//            echo '</pre>';
//
//            die(__METHOD__);
            return 'Dev2405[ ' . $countGuestVotes . ' ] + [ ' . $countAuthVotes . ' ]';
        } else{
            return ($countGuestVotes + $countAuthVotes);
        }
/* Old code:
        $authVotes = $this->getPollOptionsVoters()->where(['>', 'id', 0])->all();
        $guestVotes = $this->getPollOptionsGuestVoters()->all();
        if (YII_ENV == 'dev') {
            return 'Dev2405[ '.count($guestVotes).' + '.count($authVotes).' ]';
        } else{
            return count($guestVotes) + count($authVotes);
        }

/* */
//        echo '<pre>';
//        var_dump($authVotes);
//        echo '</pre>';
//        die(__FILE__.'#'.__LINE__."\n\n");
//        return count($this->pollOptionsVoters) + count($this->pollOptionsGuestVoters);
//        return $this->getPollOptionsVoters()->count() + $this->getPollOptionsGuestVoters()->count();
//        return '#:'.$this->getPollOptionsVoters()->count().' + '.$this->getPollOptionsGuestVoters()->count();
    }

    /*
     * Change poll rating and return new value
     */
    public function changeRating($rating){
        $newRating = $this->rating;
        if($rating > 0){
            $change = 1;
            $this->rating++;
        } else {
            $change = -1;
            $this->rating--;
        }

        $changeLog = new PollRatingVote;
        $changeLog->poll_id = $this->id;
        $changeLog->user_id = Yii::$app->user->id;
        $changeLog->rating = $change;
        $changeLog->date_add = date('Y-m-d H:i:s');
        if($changeLog->save()){
            $this->date_update = date('Y-m-d H:i:s');
            $this->save();
            $newRating = $this->rating;
        }

        return $newRating;
    }

    /*
     * Return TRUE if Guest has already voted at this poll from its IP address
     */
    public function isVotedByGuest(){
        $result = false;
        $resultOfQuery = OptionGuestVote::find()
                ->joinWith('option')
                ->where(['user_ip' => ip2long(Yii::$app->request->getUserIP())])
                ->andWhere(['poll_option.poll_id' => $this->id])
                ->count();
        return boolval($resultOfQuery);

//        $criteria = new CDbCriteria;
//        $criteria->with = array('option');
//        $criteria->addCondition('option.poll_id = :poll');
//        $criteria->params[':poll'] = $this->id;
//
//        $criteria->addCondition('t.user_ip = :ip');
//        $criteria->params[':ip'] = ip2long(Yii::$app->request->userIP);
//
//        if($resultOfQuery = OptionGuestVote::model()->count($criteria)){
//            $result = true;
//        }
        return $result;
    }

    /*
     * Return TRUE if user has already voted at this poll
     */
    public function isVoted(){
        $resultOfQuery = OptionVote::find()->from(['ov' => OptionVote::tableName()])
                ->joinWith('option')->where(['poll_option.poll_id' => $this->id])
                ->andWhere(['ov.user_id' => Yii::$app->user->id])
                ->count();
        return boolval($resultOfQuery);
    }

    /*
     * Return TRUE if user must saw results, and return FALSE if user must saw vote variants
     */
    public function isShowResult(){
        $result = $this->showResultOnMainPage;
        if($result === NULL){
            if($this->status == self::POLL_STATUS_CLOSED && $this->pollOptionsVotesCount < $this->votes_count_close){
                $result = false;
            } else {
                $result = false;
                if(!Yii::$app->user->isGuest){
                    if($this->isActual()){
                        $result = $this->isVoted();
                    } else {
                        $result = true;
                    }
                }else{
                    $result = $this->isVotedByGuest();
                }
            }
        } elseif($result == false){
            $result = $this->isVoted();
        }

        return $result;
    }

    /*
     * Return true if poll is actual for current user
     */

    public function isActual()
    {
        $result = false;
        // Yii1 remove:
        // $user = User::model()->findByPk(Yii::$app->user->id);
        if (Yii::$app->user->isGuest) {
//            $result = true;
            $result = false;
        } else {
            $user = User::findOne(['id' => Yii::$app->user->identity->id]);
            if ($this->show_for_all_languages == 1 || in_array($this->poll_language_id, $user->getLanguages()))
                if ($this->poll_sex == 0 || $this->poll_sex == $user->sex)
                    if ($this->poll_country_id == 0 || $this->poll_country_id == $user->profile->country_id)
                        if ($this->poll_region_id == 0 || $this->poll_region_id == $user->profile->region_id)
                            if ($this->poll_city_id == 0 || $this->poll_city_id == $user->profile->city_id)
                                if ($this->poll_min_age == 0 || $this->poll_min_age < $user->getAge())
                                    if ($this->poll_max_age == 0 || $this->poll_max_age > $user->getAge())
                                        $result = true;
        }
        return $result;
    }

    /*
     * Sets the attributes of poll model
     * @attr - data array
     */
    public function setPollAttributes($attr){
        $this->title = isset($attr['title']) ? Html::encode($attr['title']) : '';
        $this->describe = isset($attr['describe']) ? Html::encode($attr['describe']) : '';
        $this->user_id = Yii::$app->user->id;
        $this->rating = 0;
        $this->status = isset($attr['status']) ? intval($attr['status']) : 0;
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

    /*
     * Creates options for current poll
     * @options - titles of options
     */
    public function createNewPollOptions($options){
        if(count($options) > 0){
            foreach($options as $option){
                $pollOption = new PollOption;
                $pollOption->poll_id = $this->id;
                $pollOption->user_id = Yii::$app->user->id;
                if(strlen(Html::encode($option)) < 1){
                    $pollOption->title = ' ';
                } else{
                    $pollOption->title = Html::encode($option);
                }
                $pollOption->status = PollOption::OPTION_STATUS_PUBLISHED;
                $pollOption->date_add = date('Y-m-d H:i:s');
                $pollOption->save();
            }
        } else {
            $pollOption = new PollOption;
            $pollOption->poll_id = $this->id;
            $pollOption->user_id = Yii::$app->user->id;
            $pollOption->title = Html::encode(' ');
            $pollOption->status = PollOption::OPTION_STATUS_PUBLISHED;
            $pollOption->date_add = date('Y-m-d H:i:s');
            $pollOption->save();
        }
    }

    /*
     * Creates tags for current poll
     * @tagString - string wih tags, delimited by comas
     */
    public function createNewPollTags($tagString){
        $tagString = Html::encode($tagString);
        $tags = explode(',',$tagString);
        foreach($tags as $tagName){
            if($tagId = Tag::createNewTag($tagName, $this->poll_language_id)){
                $pollTag = new PollTag;
                $pollTag->poll_id = $this->id;
                $pollTag->tag_id = $tagId;
                $pollTag->save();
            }
        }
    }

    /*
     * Return the array with poll`s data, required to chart builds
     */
    public function getChartData($sex = 0, $ageInterval = 0, $country = 0, $registration = 0){
        $data = array();
        $max = 0;
        $maxIndex = 0;
        $sum = 0;
        $options = $this->pollOptions;
        if($ageInterval){
            $age = User::getAgesByIntervalIndex($ageInterval);
        }
        $hasFilters = $sex || isset($age) || $country;

        foreach($options as $i=>$option){
            $data[$i]['option'] = $option->title;

            if(!$hasFilters && $registration == 0){
                $value = $option->optionVotesCount + $option->optionGuestVotesCount;
            } elseif($registration == 2){
                // лише незареєстровані голоси
                $value = $option->optionGuestVotesCount;
            } else {
                $query = OptionVote::find()->alias('ov')
                    ->leftJoin('{{%profile}} p', 'p.user_id = ov.user_id')
                    ->where(['ov.option_id' => $option->id])
                    ->andWhere(['not', ['ov.user_id' => null]]);

                // Додаткові фільтри застосовуються тільки до зареєстрованих користувачів
                if($country){
                    $query->andWhere(['p.country_id' => $country]);
                }

                if($sex){
                    $query->andWhere(['p.gender' => $sex]);
                }

                if(isset($age)){
                    $query->andWhere(new Expression('YEAR(CURDATE())-YEAR(p.date_birthday) BETWEEN :min AND :max', [
                        ':min' => $age['min'],
                        ':max' => $age['max'],
                    ]));
                }

                $value = (int)$query->count();
            }
            $data[$i]['value'] = $value;
            $sum += $data[$i]['value'];
            if($data[$i]['value'] > $max){
                $max = $data[$i]['value'];
                $maxIndex = $i;
            }
        }

        foreach($options as $i=>$option){
            $data[$i]['percent'] = PollOption::getPercentRating($sum,$data[$i]['value']);
        }

        $data[$maxIndex]['isMax'] = true;
        return $data;
    }

    public static function  empty_getPollForMainPage(){
//        $result = Poll::model()->findByPk(self::HOLDER_PAGE_POLL_ID);
//        return $result;
    }

    public function checkEditable(){
        if(time() - strtotime($this->date_add) < self::TIME_TO_EDIT * 60){
            $this->editable = true;
        }
    }

    public function isEditable(){
        $result = false;
        if(time() - strtotime($this->date_add) < self::TIME_TO_EDIT * 60){
            $result = true;
        }
        return $result;
    }

    /*
     * Edit options for current poll
     * @options - titles of options
     * @poll - poll
     */
    public function  empty_editPollOptions($options){
    }

    /*
     * Creates tags for current poll
     * @tagString - string wih tags, delimited by comas
     * @poll - poll
     */
    public function  empty_editPollTags($tagString){
    }



    /*
     * Creates tags for current poll
     * @tagString - string wih tags, delimited by comas
     * @poll - poll
     */
    public function editPollTags($tagString){
        foreach($this->pollTags as $tag){
            $tag->delete();
        }
        self::createNewPollTags($tagString);
    }

    public function getClearedDescribe()
    {
        $describe = $this->describe;
        return preg_replace_callback('/<a (.*?)>/', function($matches){
            $result = mb_substr($matches[0], 0, -1);
            if (strpos($result, 'rel="noopener noreferrer"') === false) {
                $result .= ' rel="noopener noreferrer"';
            }
            if (strpos($result, 'target="_blank"') === false) {
                $result .= ' target="_blank"';
            }
            $result.= '>';
            return $result;
        }, $describe);
    }

    public static function dropDownAllItems() {
        return self::find()->select(['title', 'id'])->orderBy(['title' => SORT_ASC])->indexBy('id')->column();
    }

    public function unsetAttributes() {
        //  model method unsetAttributes() like Yii1 #7742
        // samdark added the status:under discussion
    }

    public function readPollComments()
    {
//        echo "<h4>Coments of Poll [{$this->id}]</h4>";
//        return false;
        if ($comments = $this->getPollComments()->orWhere(['is_new' => 0, 'has_new' => 0])->all()){
// array('condition' => 'has_new = 1'))) {
            foreach ($comments as $comment) {
//                echo "<p>Coment [{$comment->id}] is_new:[{$comment->is_new}] has_new:[{$comment->has_new}] </p>";
                $comment->is_new = 0;
                $comment->has_new = 0;
                $comment->save();
            }
        }
    }

    /**
     * Видаляє всі пов'язані голоси перед видаленням опитування.
     *
     * Це усуває помилку цілісності та прибирає зайві записи.
     *
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $this->tagIds = $this->getPollTags()->select('tag_id')->column();
        $optionIds = $this->getPollOptions()->select('id')->column();
        if (!empty($optionIds)) {
            OptionGuestVote::deleteAll(['option_id' => $optionIds]);
            OptionVote::deleteAll(['option_id' => $optionIds]);
        }

        return true;
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if (!empty($this->tagIds)) {
            Tag::updateAllCounters(['polls_count' => -1], ['id' => $this->tagIds]);
        }
    }

    /*
     * Return user polls that has new comments
     */
    public static function getPollsWithNewComments(){
        $query = self::find()->joinWith('pollComments')
                ->where(['poll_comment.is_new' => 1]);
        if (!Yii::$app->user->isGuest) {
            $query->andWhere(['poll.user_id' => Yii::$app->user->identity->id]);
        }
        $query->groupBy(['poll.id',]);
        $polls = $query->all();
        return $polls;
    }

    /*
     * return count of new user comment
     * @userId
     */
    public static function getNewCommentsCount($userId)
    {
        $query = self::find()->joinWith('pollComments')
                ->where(['poll_comment.is_new' => 1])
                ->andWhere(['poll.user_id' => $userId]);
        return $query->count();
    }

}
