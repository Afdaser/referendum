<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%region_city}}".
 *
 * @property int $id ID
 * @property int $region_id Region
 * @property int $country_id Country
 * @property string $name Name
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property Country $country
 * @property CountryRegion $region
 * @property UserCareer[] $userCareers
 * @property UserHighEducation[] $userHighEducations
 * @property UserSecondaryEducation[] $userSecondaryEducations
 */
class RegionCity extends ActiveRecord
{

    use mixin\DropDownItems;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%region_city}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_id', 'country_id', 'name'], 'required'],
            [['region_id', 'country_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => CountryRegion::class, 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'region_id' => Yii::t('app', 'Region'),
            'region.name' => Yii::t('app', 'Region'),
            'country_id' => Yii::t('app', 'Country'),
            'country.name' => Yii::t('app', 'Country'),
            'name' => Yii::t('app', 'Name'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CountryQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * Gets query for [[Region]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CountryRegionQuery
     */
    public function getRegion()
    {
        return $this->hasOne(CountryRegion::class, ['id' => 'region_id']);
    }

    /**
     * Gets query for [[UserCareers]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserCareerQuery
     */
    public function getUserCareers()
    {
        return $this->hasMany(UserCareer::class, ['city_id' => 'id']);
    }

    /**
     * Gets query for [[UserHighEducations]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserHighEducationQuery
     */
    public function getUserHighEducations()
    {
        return $this->hasMany(UserHighEducation::class, ['city_id' => 'id']);
    }

    /**
     * Gets query for [[UserSecondaryEducations]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserSecondaryEducationQuery
     */
    public function getUserSecondaryEducations()
    {
        return $this->hasMany(UserSecondaryEducation::class, ['city_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\RegionCityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\RegionCityQuery(get_called_class());
    }
}
