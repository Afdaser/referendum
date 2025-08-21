<?php

namespace common\models;

use Yii;
use dektrium\user\models\Profile AS BaseProfile;

use common\components\CreatorEditorBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property int $user_id
 * @property string|null $name
 * @property string|null $lastname Lastname
 * @property int|null $city_id City
 * @property int|null $region_id Region
 * @property int|null $country_id Country
 * @property string|null $date_birthday Date birthday
 * @property string|null $public_email
 * @property string|null $phone Phone
 * @property int|null $gender Gender
 * @property int|null $marital Marital
 * @property string|null $preferences Preferences
 * @property int|null $is_active Is active
 * @property string|null $identity Identity
 * @property string|null $network Network
 * @property int|null $state State
 * @property string|null $date_add Date add
 * @property string|null $date_update Date update
 * @property int|null $is_admin Is admin
 * @property string|null $gravatar_email
 * @property string|null $gravatar_id
 * @property string|null $location
 * @property string|null $website
 * @property string|null $bio
 * @property string|null $timezone
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

class Profile extends BaseProfile
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                TimestampBehavior::className(),
                CreatorEditorBehavior::className(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'city_id', 'region_id', 'country_id', 'gender', 'marital', 'is_active', 'state', 'is_admin', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['date_birthday', 'date_add', 'date_update'], 'safe'],
            [['bio'], 'string'],
            [['name', 'public_email', 'preferences', 'identity', 'network', 'gravatar_email', 'location', 'website'], 'string', 'max' => 255],
            [['lastname', 'phone'], 'string', 'max' => 128],
            [['gravatar_id'], 'string', 'max' => 32],
            [['timezone'], 'string', 'max' => 40],
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
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),

            'city_id' => Yii::t('app', 'City'),
            'city.name' => Yii::t('app', 'City'),
            'region_id' => Yii::t('app', 'Region'),
            'region.name' => Yii::t('app', 'Region'),
            'country_id' => Yii::t('app', 'Country'),
            'country.name' => Yii::t('app', 'Country'),

            'date_birthday' => Yii::t('app', 'Date birthday'),
            'public_email' => Yii::t('app', 'Public Email'),
            'phone' => Yii::t('app', 'Phone'),
            'gender' => Yii::t('app', 'Gender'),
            'marital' => Yii::t('app', 'Marital'),
            'preferences' => Yii::t('app', 'Preferences'),
            'is_active' => Yii::t('app', 'Is active'),
            'identity' => Yii::t('app', 'Identity'),
            'network' => Yii::t('app', 'Network'),
            'state' => Yii::t('app', 'State'),
            'date_add' => Yii::t('app', 'Date add'),
            'date_update' => Yii::t('app', 'Date update'),
            'is_admin' => Yii::t('app', 'Is admin'),
            'gravatar_email' => Yii::t('app', 'Gravatar Email'),
            'gravatar_id' => Yii::t('app', 'Gravatar ID'),
            'location' => Yii::t('app', 'Location'),
            'website' => Yii::t('app', 'Website'),
            'bio' => Yii::t('app', 'Bio'),
            'timezone' => Yii::t('app', 'Timezone'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RegionCityQuery
     */
    public function getCity()
    {
        return $this->hasOne(RegionCity::class, ['id' => 'city_id']);
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProfileQuery(get_called_class());
    }
}
