<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_data}}".
 *
 * @property int $id ID
 * @property string|null $login Login
 * @property string|null $name Name
 * @property string|null $lastname Lastname
 * @property string|null $password Password
 * @property int|null $city_id City
 * @property int|null $region_id Region
 * @property int|null $country_id Country
 * @property string|null $date_birthday Date birthday
 * @property string $email Email
 * @property string|null $phone Phone
 * @property int|null $sex Sex
 * @property int|null $marital Marital
 * @property string|null $preferences Preferences
 * @property int $is_active Is active
 * @property string|null $identity Identity
 * @property string|null $network Network
 * @property int|null $state State
 * @property string $date_add Date add
 * @property string $date_update Date update
 * @property int $is_admin Is admin
 */
class UserDatum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'region_id', 'country_id', 'sex', 'marital', 'is_active', 'state', 'is_admin'], 'integer'],
            [['date_birthday', 'date_add', 'date_update'], 'safe'],
            [['email', 'date_add', 'date_update'], 'required'],
            [['login', 'name', 'lastname', 'password', 'email', 'preferences', 'identity', 'network'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 35],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'login' => Yii::t('app', 'Login'),
            'name' => Yii::t('app', 'Name'),
            'lastname' => Yii::t('app', 'Lastname'),
            'password' => Yii::t('app', 'Password'),
            'city_id' => Yii::t('app', 'City'),
            'region_id' => Yii::t('app', 'Region'),
            'country_id' => Yii::t('app', 'Country'),
            'date_birthday' => Yii::t('app', 'Date birthday'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'sex' => Yii::t('app', 'Sex'),
            'marital' => Yii::t('app', 'Marital'),
            'preferences' => Yii::t('app', 'Preferences'),
            'is_active' => Yii::t('app', 'Is active'),
            'identity' => Yii::t('app', 'Identity'),
            'network' => Yii::t('app', 'Network'),
            'state' => Yii::t('app', 'State'),
            'date_add' => Yii::t('app', 'Date add'),
            'date_update' => Yii::t('app', 'Date update'),
            'is_admin' => Yii::t('app', 'Is admin'),
        ];
    }
}
