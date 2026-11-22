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
 * @property string|null $social_facebook
 * @property string|null $social_linkedin
 * @property string|null $social_instagram
 * @property string|null $social_reddit
 * @property string|null $social_x
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
    public static function socialNetworks(): array
    {
        return [
            'social_facebook' => ['label' => 'Facebook', 'prefix' => 'https://www.facebook.com/', 'displayPrefix' => 'facebook.com/', 'icon' => 'facebook', 'maxLength' => 60, 'pattern' => '/^[A-Za-z0-9.]{1,60}$/D'],
            'social_linkedin' => ['label' => 'LinkedIn', 'prefix' => 'https://www.linkedin.com/in/', 'displayPrefix' => 'linkedin.com/in/', 'icon' => 'linkedin', 'maxLength' => 100, 'pattern' => '/^[A-Za-z0-9-]{1,100}$/D'],
            'social_instagram' => ['label' => 'Instagram', 'prefix' => 'https://www.instagram.com/', 'displayPrefix' => 'instagram.com/', 'icon' => 'instagram', 'maxLength' => 30, 'pattern' => '/^[A-Za-z0-9._]{1,30}$/D'],
            'social_reddit' => ['label' => 'Reddit', 'prefix' => 'https://www.reddit.com/user/', 'displayPrefix' => 'reddit.com/user/', 'icon' => 'reddit', 'maxLength' => 20, 'pattern' => '/^[A-Za-z0-9_-]{1,20}$/D'],
            'social_x' => ['label' => 'X.com', 'prefix' => 'https://x.com/', 'displayPrefix' => 'x.com/', 'icon' => 'x', 'maxLength' => 15, 'pattern' => '/^[A-Za-z0-9_]{1,15}$/D'],
        ];
    }

    /** Повертає адреси лише з імен акаунтів, збережених у профілі. */
    public function getSocialLinks(): array
    {
        $links = [];
        foreach (self::socialNetworks() as $attribute => $network) {
            $handle = trim((string) $this->$attribute);
            if ($handle !== '' && preg_match($network['pattern'], $handle)) {
                $links[] = [
                    'label' => $network['label'],
                    'icon' => $network['icon'],
                    'handle' => $handle,
                    'url' => $attribute === 'social_facebook' && ctype_digit($handle)
                        ? 'https://www.facebook.com/profile.php?id=' . $handle
                        : $network['prefix'] . rawurlencode($handle),
                ];
            }
        }
        return $links;
    }

    /** Перевіряє лише форму імені акаунта; зовнішні сторінки не запитує. */
    public function validateSocialHandle($attribute): void
    {
        if (!is_string($this->$attribute)) {
            return;
        }
        if (trim($this->$attribute) === '') {
            $this->$attribute = null;
            return;
        }
        $handle = ltrim(trim($this->$attribute), '@');
        if (!preg_match(self::socialNetworks()[$attribute]['pattern'], $handle)) {
            $this->addError($attribute, Yii::t('user', 'Вкажіть лише ім’я акаунта або ID без посилання.'));
            return;
        }
        $this->$attribute = $handle;
    }

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
            [['social_facebook'], 'string', 'max' => 60],
            [['social_linkedin'], 'string', 'max' => 100],
            [['social_instagram'], 'string', 'max' => 30],
            [['social_reddit'], 'string', 'max' => 20],
            [['social_x'], 'string', 'max' => 15],
            [['social_facebook', 'social_linkedin', 'social_instagram', 'social_reddit', 'social_x'], 'validateSocialHandle', 'skipOnError' => true],
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
            'social_facebook' => 'Facebook',
            'social_linkedin' => 'LinkedIn',
            'social_instagram' => 'Instagram',
            'social_reddit' => 'Reddit',
            'social_x' => 'X.com',
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
