<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%user_high_education}}".
 *
 * @property int $id ID
 * @property int $user_id User
 * @property int|null $country_id Country
 * @property int|null $region_id Region
 * @property int|null $city_id City
 * @property string|null $university University
 * @property string|null $faculty Faculty
 * @property string|null $speciality Speciality
 * @property string|null $status Status
 * @property string|null $year_begin Year begin
 * @property string|null $year_end Year end
 * @property string $date_add Date add
 * @property string $date_update Date update
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property RegionCity $city
 * @property Country $country
 * @property CountryRegion $region
 * @property User $user
 */
class UserHighEducation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_high_education}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'date_update'], 'required'],
            [['user_id', 'country_id', 'region_id', 'city_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['year_begin', 'year_end', 'date_add', 'date_update'], 'safe'],
            [['university', 'faculty', 'speciality', 'status'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => CountryRegion::class, 'targetAttribute' => ['region_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegionCity::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'country_id' => Yii::t('app', 'Country'),
            'region_id' => Yii::t('app', 'Region'),
            'city_id' => Yii::t('app', 'City'),
            'university' => Yii::t('app', 'University'),
            'faculty' => Yii::t('app', 'Faculty'),
            'speciality' => Yii::t('app', 'Speciality'),
            'status' => Yii::t('app', 'Status'),
            'year_begin' => Yii::t('app', 'Year begin'),
            'year_end' => Yii::t('app', 'Year end'),
            'date_add' => Yii::t('app', 'Date add'),
            'date_update' => Yii::t('app', 'Date update'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(RegionCity::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * Gets query for [[Region]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(CountryRegion::class, ['id' => 'region_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
