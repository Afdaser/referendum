<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%language}}".
 *
 * @property int $id ID
 * @property string $name Name
 * @property string $locale Locale
 * @property string $title Title
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property Page[] $pages
 * @property Poll[] $polls
 * @property Tag[] $tags
 * @property UserLanguage[] $userLanguages
 */

class Language extends ActiveRecord
{
    public static $all = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%language}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'locale', 'title'], 'required'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'locale' => Yii::t('app', 'Locale'),
            'title' => Yii::t('app', 'Title'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[Pages]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PageQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::class, ['language_id' => 'id']);
    }

    /**
     * Gets query for [[Polls]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollQuery
     */
    public function getPolls()
    {
        return $this->hasMany(Poll::class, ['poll_language_id' => 'id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TagQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['language_id' => 'id']);
    }

    /**
     * Gets query for [[UserLanguages]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserLanguageQuery
     */
    public function getUserLanguages()
    {
        return $this->hasMany(UserLanguage::class, ['language_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\LanguageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\LanguageQuery(get_called_class());
    }

    /*
     * Returns a list of languages
     */
    public static function getLanguagesList(){
        $result = [];
        $languages = Language::find()->all();
        foreach($languages as $language){
            $result[$language->id] = $language->title;
        }

        return $result;
    }

    /*
     * Returns the array of language names
     */
    public static function getLanguageNames(){
        $result = [];
        $languages = Language::find()->all();
        foreach($languages as $i=>$language){
            $result[$i] = $language->name;
        }

        return $result;
    }

    public static function fetchLanguages(){
        if (empty(self::$all)) {
            $languages = Language::find()->all();
            foreach ($languages as $i => $language) {
                self::$all[$language->name] = [
                    'name' => $language->name,
                    'locale' => $language->locale,
                    'id' => $language->id,
                ];
            }
        }
        return self::$all;
    }

    public static function getLanguageByName($name){
        $result = 0;
        if($name == 'uk'){
            $name = 'ua';
        }
        $language = Language::find()->where(['name'=>$name])->one();
        if($language){
            $result = $language->id;
        }

        return $result;
    }

    public static function isRightLanguage($language){
        $result = false;
//        if(Language::model()->findByAttributes(array('name'=>CHtml::encode($language)))){
//      ['name'=> CHtml::encode($language)]
        if(self::find()->where(['name'=> $language])->count()){
            $result = true;
        }
        return $result;
    }

    public static function dropDownAllItems() {
        return self::find()->select(['title', 'id'])->indexBy('id')->column();
    }

}
