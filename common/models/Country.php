<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%country}}".
 *
 * @property int $id ID
 * @property string $name Name
 * @property int $sorting_uk
 * @property int $sorting_ru
 * @property int $sorting_en
 * @property int $sorting_no
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property CountryRegion[] $countryRegions
 * @property RegionCity[] $regionCities
 * @property UserCareer[] $userCareers
 * @property UserHighEducation[] $userHighEducations
 * @property UserSecondaryEducation[] $userSecondaryEducations
 */
class Country extends ActiveRecord
{

    use mixin\DropDownItems;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%country}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sorting_uk', 'sorting_ru', 'sorting_en', 'sorting_no', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'sorting_uk' => Yii::t('app', 'Sorting Uk'),
            'sorting_ru' => Yii::t('app', 'Sorting Ru'),
            'sorting_en' => Yii::t('app', 'Sorting En'),
            'sorting_no' => Yii::t('app', 'Sorting No'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[CountryRegions]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CountryRegionQuery
     */
    public function getCountryRegions()
    {
        return $this->hasMany(CountryRegion::class, ['country_id' => 'id']);
    }

    /**
     * Gets query for [[RegionCities]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RegionCityQuery
     */
    public function getRegionCities()
    {
        return $this->hasMany(RegionCity::class, ['country_id' => 'id']);
    }

    /**
     * Gets query for [[UserCareers]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserCareerQuery
     */
    public function getUserCareers()
    {
        return $this->hasMany(UserCareer::class, ['country_id' => 'id']);
    }

    /**
     * Gets query for [[UserHighEducations]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserHighEducationQuery
     */
    public function getUserHighEducations()
    {
        return $this->hasMany(UserHighEducation::class, ['country_id' => 'id']);
    }

    /**
     * Gets query for [[UserSecondaryEducations]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserSecondaryEducationQuery
     */
    public function getUserSecondaryEducations()
    {
        return $this->hasMany(UserSecondaryEducation::class, ['country_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CountryQuery(get_called_class());
    }

    /*
     * Return list of countries
     */
    public static function getCountriesList(){
        /* TODO:Yii1.remove */
//        $criteria = new CDbCriteria;
//        $criteria->order = 'sorting_'.Yii::app()->language.' DESC';
//        $result = Country::model()->findAll($criteria);
        $fieldForSort = 'sorting_'.substr(Yii::$app->language, 0, 2);
        $result = self::find()->orderBy([$fieldForSort => SORT_DESC])->all();
        return $result;
    }

    /*
     * Return list of Regions in country
     */
    public static function getRegions($id){
        /* TODO:Yii1.remove * /
        $result = array();
        $regions = array();
        if($id){
            $regions = CountryRegion::model()->findAllByAttributes(array('country_id'=>$id));
        } else {
            //$regions = CountryRegion::model()->findAll();
        }

        foreach($regions as $i=>$region){
            $result[$i]['data'] = $region->id;
            $result[$i]['value'] = $region->name;
        }

        return $result;

        /* */
        $result = [];
        $regions = [];
        if($id){
            $regions = CountryRegion::find()->where(['country_id'=>$id])->all();
        } else {
            $regions = CountryRegion::find()->all();
        }
        foreach($regions as $i => $region){
            $result[$i]['data'] = $region->id;
            $result[$i]['value'] = $region->name;
        }

        return $result;
    }

    public static function getCities($country, $region){
        $result = [];
        $cities = [];
        $query = RegionCity::find();
        if($country){
            $query = $query->where(['country_id' => intval($country)]);
        }
        if($region){
            $query = $query->where(['region_id' => intval($region)]);
        }
        $cities = $query->all();
        foreach($cities as $i => $city){
            $result[$i]['data'] = $city->id;
            $result[$i]['value'] = $city->name;
        }
        return $result;
    }
}
