<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use dektrium\user\models\User AS BaseModelUser;

/**
 * User ActiveRecord model.
 *
 * @property bool    $isAdmin
 * @property bool    $isBlocked
 * @property bool    $isConfirmed
 *
 * Database fields:
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 *
 * @property string $registration_ip
 * @property integer $confirmed_at
 * @property integer $blocked_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property integer $last_login_at
 * @property integer $flags
 *
 * Defined relations:
 * @property Account[] $accounts
 * @property Profile   $profile
 *
 * Dependencies:
 * @property-read Finder $finder
 * @property-read Module $module
 * @property-read Mailer $mailer
 */
//class User extends ActiveRecord implements IdentityInterface
class User extends BaseModelUser
{
    #User`s sex

    const SEX_MAN = 1;
    const SEX_WOMAN = 2;

    #User`s age
    const AGE_18_25 = 0;
    const AGE_25_40 = 1;
    const AGE_40_65 = 2;

    #User marital status
    const SINGLE = 0;
    const MARRIED = 1;

    #demo user for guest votes
    const DEMO_USER_ID = -1;
//    public $oldPassword;
//    public $passwordRepeat;
//    public $oldEmail;

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public $oldEmail;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
                    'verification_token' => $token,
                    'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /*     * * PROFILE fields: */

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return !empty($this->profile) ? $this->profile->name : $this->username;
    }

    /**
     *
     */
    public function getSex()
    {
        return !empty($this->profile) ? $this->profile->gender : null;
    }

    /**
     *
     */
    public function getDate_birthday()
    {
        return !empty($this->profile) ? $this->profile->date_birthday : null;
    }

    /**
     *
     */
    public function getCountry_id()
    {
        return !empty($this->profile) ? $this->profile->country_id : null;
    }

    /**
     *
     */
    public function getRegion_id()
    {
        return !empty($this->profile) ? $this->profile->region_id : null;
    }

    /**
     *
     */
    public function getIsActive()
    {
        return !empty($this->profile) ? $this->profile->is_active : null;
    }

    /**
     * TODO: Remove
     */
    public function getIs_active()
    {
        return !empty($this->profile) ? $this->profile->is_active : null;
    }

    /**
     *
     */
    public function getMarital()
    {
        return !empty($this->profile) ? $this->profile->marital : null;
    }

    /**
     *
     */
    public function getPreferences()
    {
        return !empty($this->profile) ? $this->profile->preferences : null;
    }

    /**
     *
     */
    public function getRegionName()
    {
        if (!empty($this->profile) && $this->profile->region_id) {
            $region = $this->profile->region;
            if (!empty($region)) {
                return $region->name;
            }
        }
    }

    /**
     *
     */
    public function getCity_id()
    {
        return !empty($this->profile) ? $this->profile->city_id : null;
    }

    /**
     *
     */
    public function getCommentsRatingSum()
    {
        // Yii1:
        // 'commentsRatingSum'=>array(self::STAT,  'PollComment', 'user_id', 'select' => 'SUM(rating)'),
        $rowRating = $this->getPollComments()->select(['SUM(rating) AS rating'])->asArray()->one();
        if (!empty($rowRating['rating'])) {
            return intval($rowRating['rating']);
        } else {
            return (YII_ENV == 'dev') ? 2024011 : 0;
        }
    }

    /**
     * pollsRatingSum
     */
    public function getPollsRatingSum()
    {
        // Yii1:
        // 'pollsRatingSum'=>array(self::STAT,  'Poll', 'user_id', 'select' => 'SUM(rating)'),
        $rowRating = $this->getPolls()->select(['SUM(rating) AS rating'])->asArray()->one();
        if (!empty($rowRating['rating'])) {
            return intval($rowRating['rating']);
        } else {
            return (YII_ENV == 'dev') ? 2024012 : 0;
        }
    }

    public function getLastname()
    {
        return !empty($this->profile) ? $this->profile->lastname : null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Gets query for [[PollCommentRatings]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentRatingQuery
     */
    public function getPollCommentRatings()
    {
        return $this->hasMany(PollCommentRating::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[PollComments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollCommentQuery
     */
    public function getPollComments()
    {
        return $this->hasMany(PollComment::class, ['user_id' => 'id']);
    }

    public function getCommentsCount()
    {
        return $this->getPollComments()->count();
    }

    /**
     * Gets query for [[PollOptions]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollOptionQuery
     */
    public function getPollOptions()
    {
        return $this->hasMany(PollOption::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[PollRatingVotes]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollRatingVoteQuery
     */
    public function getPollRatingVotes()
    {
        return $this->hasMany(PollRatingVote::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Polls]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PollQuery
     */
    public function getPolls()
    {
        return $this->hasMany(Poll::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Profile]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProfileQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[SocialAccounts]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\SocialAccountQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccount::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tokens]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TokenQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserCareer]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserCareerQuery
     */
    public function getUserCareer()
    {
        return $this->hasOne(UserCareer::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserHighEducation]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserHighEducationQuery
     */
    public function getUserHighEducation()
    {
        return $this->hasOne(UserHighEducation::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserInterest]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserInterestQuery
     */
    public function getUserInterest()
    {
        return $this->hasOne(UserInterest::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserLanguages]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserLanguageQuery
     */
    public function getUserLanguages()
    {
        return $this->hasMany(UserLanguage::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserSecondaryEducation]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserSecondaryEducationQuery
     */
    public function getUserSecondaryEducation()
    {
        return $this->hasOne(UserSecondaryEducation::class, ['user_id' => 'id']);
    }

    public static function dropDownAllItems()
    {
        return self::find()->select(['username', 'id'])->indexBy('id')->column();
    }

    /*
     * return count of new user comment
     * @userId
     */

    public static function getNewCommentsCount($userId)
    {
        return Poll::getNewCommentsCount($userId);

        $query = Poll::find()->joinWith('pollComments')
                ->where(['poll_comment.is_new' => 1]);
        if (!Yii::$app->user->isGuest) {
            $query->andWhere(['poll.user_id' => Yii::$app->user->identity->id]);
        }
        $query->groupBy(['poll.id',]);
        $polls = $query->all();
        return $polls;

        return (YII_ENV == 'dev') ? 1013 : 0;
        // #TODO #DEV202: Yii1-toYi2:
        $criteria = new CDbCriteria;
        $criteria->with = array('poll');
        $criteria->addCondition('is_new = :status');
        $criteria->addCondition('poll.user_id = :user');
        $criteria->params[':status'] = PollComment::NEW_COMMENT;
        $criteria->params[':user'] = intval($userId);

        $count = PollComment::model()->count($criteria);
        return $count;
    }

    /*
     * return count of user`s polls
     * @userId
     */

    public static function getPollsCount($userId)
    {
//        $count = $this->polls->count();
        $count = Poll::find()->where(['user_id' => intval($userId)])->count();
        return $count;
    }

    /*
     * return full user name
     * @id - user id
     */

    public static function getUserName($userId)
    {
        $result = '';
//        $user = User::model()->findByPK(intval($userId));
        $user = User::find()->where(['id' => intval($userId)])->one();
        if ($user) {
            $result = $user->username; // $user->name . ' ' . $user->lastname;
//            $result = $user->login;// $user->name . ' ' . $user->lastname;
        }
        return $result;
    }

    /**
     * @return string
     * return full user name
     */
    public function getFullUserName()
    {
        return $this->username;
//		return 	$this->login;//    $this->name . ' ' . $this->lastname;
    }

    /*
     * Return true if user already voted by comment
     * @commentId
     */

    public static function isVotedForComment($commentId)
    {
        $result = false;
        if (PollCommentRating::model()->countByAttributes(array('user_id' => Yii::$app->user->identity->id, 'poll_comment_id' => $commentId))) {
            $result = true;
        }

        return $result;
    }

    /*
     * Return true if user already voted by answer
     * @answerId
     */
    public static function isVotedForAnswer($answerId)
    {
        $result = false;
        if (PollOptionRating::find()->countByAttributes(['user_id' => Yii::$app->user->identity->id, 'poll_option_id' => $answerId])) {
            $result = true;
        }

        return $result;
    }

    /*
     * Return true if user already voted vy poll
     * @pollId
     */
    public static function isVotedForPoll($pollId)
    {
        $result = false;
        if (PollRatingVote::find()->countByAttributes(['user_id' => Yii::$app->user->identity->id, 'poll_id' => $pollId])) {
            $result = true;
        }
        return $result;
    }

    /*
     * Return sex list (man/woman)
     */

    public static function getUserSexList()
    {
        $result = array();
        $result[self::SEX_MAN] = Yii::t("user", 'чоловіча');
        $result[self::SEX_WOMAN] = Yii::t("user", 'жіноча');

        return $result;
    }

    /*
     * Return age list
     */

    public static function getAgeList()
    {
        $result = [
            10 => 10,
            15 => 15,
            25 => 25,
            60 => 60
        ];

        return $result;
    }

    /*
     * Return list of age`s intervals
     */

    public static function getUserAgeIntervalList()
    {
        $result = [
            1 => '10-15',
            2 => '15-25',
            3 => '25-60'
        ];

        return $result;
    }

    /*
     * Return min and max age for interval
     * @index
     */

    public static function getAgesByIntervalIndex($index)
    {
        $array['min'] = [1 => 10, 2 => 15, 3 => 25];
        $array['max'] = [1 => 15, 2 => 25, 3 => 60];
        if (($index > 0) && ($index < count($array['min'])) && ($index < count($array['max']))) {
            $result['min'] = $array['min'][$index];
            $result['max'] = $array['max'][$index];
        } else {
            $result['min'] = $array['min'][1];
            $result['max'] = $array['max'][count($array['max']) - 2];
        }

        return $result;
    }

    /*
     * Set user main profile data
     */

    public function setMain($attributes)
    {
        $this->sex = intval($attributes['sex']);
        $this->date_birthday = StringHelper::formatDate(intval($attributes['birthday']['day']), intval($attributes['birthday']['month']), intval($attributes['birthday']['year']));
        if (intval($attributes['country']) > 0) {
            $this->country_id = intval($attributes['country']);
        } else {
            $this->country_id = NULL;
        }

        if (intval($attributes['region']) > 0) {
            $this->region_id = intval($attributes['region']);
        } else {
            $this->region_id = NULL;
        }

        if (intval($attributes['city']) > 0) {
            $this->city_id = intval($attributes['city']);
        } else {
            $this->city_id = NULL;
        }

        foreach ($this->languages as $language) {
            $language->delete();
        }
        if (isset($attributes['languages'])) {
            foreach ($attributes['languages'] as $language) {
                $userLanguage = new UserLanguage;
                $userLanguage->user_id = Yii::$app->user->identity->id;
                $userLanguage->language_id = intval($language);
                $userLanguage->save();
            }
        } else {
            $this->addError('languages', Yii::t("user", 'Оберіть хоча б одну мову!'));
        }
        $this->marital = intval($attributes['marital']);
        $this->preferences = CHtml::encode($attributes['preferences']);
        $this->date_update = date('Y-m-d H:i:s');
    }

    /*
     * Return TRUE if user checked this language in own profile
     * @language - language_id
     */

    public function useLanguage($languageId)
    {
        $result = false;
        $languageId = intval($languageId);
        $languages = $this->getUserLanguages()->all();
//        $languages = $this->userLanguages;
//        $languages = Language::getLanguagesList();
//        echo '<div style="border: 3px dotted red;">'. __FILE__.'<hr><ul>';
//        echo '<li>COUNT:[' . count($languages) .']</li>';
        /*
          <?php foreach ($languages as $i => $language): ?>
          <li> value= <?php echo $i; ?> <?php if ($user->useLanguage($i)): ?>checked<?php endif; ?>><?php echo $language; ?></li>
          <?php endforeach; ?>


          <?php
          //  var_dump()
          ?>
          /* */

        foreach ($languages AS $key => $language) {
//            echo '<li>[' . $key .'] '. $language->language_id .' = ';
            if ($language->language_id == $languageId) {
                $result = true;
            }
//            echo '</li>';
        }
//        echo '</ul></div>';
        return $result;
    }

    /*
     * Set password data
     */

    public function setPasswordYiiOne($attributes)
    {
        $this->password = CHtml::encode($attributes['password']);
        if (isset($attributes['oldPassword'])) {
            $this->oldPassword = CHtml::encode($attributes['oldPassword']);
        }
        $this->passwordRepeat = CHtml::encode($attributes['passwordRepeat']);
        $this->date_update = date('Y-m-d H:i:s');
    }

    /*
     * Return user polls that has new comments
     */

    public static function getPollsWithNewComments()
    {
        return Poll::getPollsWithNewComments();

        $query = Poll::find()->joinWith('pollComments')
                ->where(['poll_comment.is_new' => 1]);
        if (!Yii::$app->user->isGuest) {
            $query->andWhere(['poll.user_id' => Yii::$app->user->identity->id]);
        }
        $query->groupBy(['poll.id',]);
        $polls = $query->all();
        return $polls;

        /*
          if (!Yii::$app->user->isGuest) {
          $userId = Yii::$app->user->identity->id;
          } else {
          $userId = null;
          }
          $query = Poll::find()->joinWith('pollComments')
          ->where(['poll_comment.is_new' => 1]);
          if (!empty($userId)) {
          $query->andWhere(['poll.user_id' => $userId]);
          }
          $query->groupBy(['poll.id',]);
          $polls = $query->all();
          return $polls;
          /* */

        echo $query->createCommand()->sql;
        /*
          SELECT `poll`.* FROM `poll` LEFT JOIN `poll_comment` ON `poll`.`id` = `poll_comment`.`poll_id`
          WHERE (`poll_comment`.`is_new`=1) AND (`user_id`=1186) GROUP BY `poll`.`id`
         */
        die(__METHOD__);

        // or to get the SQL with all parameters included try:
        // $query->createCommand()->getRawSql()


        return $polls;
        /*
          SELECT `t`.`id`, `t`.`title`, `t`.`describe`, `t`.`user_id`, `t`.`rating`,
          `t`.`status`, `t`.`views`, `t`.`result_type`, `t`.`poll_language_id`,
          `t`.`show_for_all_languages`, `t`.`poll_sex`, `t`.`poll_country_id`,
          `t`.`poll_region_id`, `t`.`poll_city_id`, `t`.`poll_min_age`, `t`.`poll_max_age`,
          `t`.`votes_count_close`, `t`.`date_add`, `t`.`date_update`, `t`.`show_on_slider`
          FROM `polls` `t`
          LEFT JOIN poll_comments ON t.id = poll_comments.poll_id
          WHERE (t.user_id = 1186) AND (poll_comments.is_new = 1)
          GROUP BY poll_id

         */

        $criteria = new CDbCriteria();
        $criteria->addCondition("t.user_id = " . Yii::app()->user->id);
        $criteria->addCondition("poll_comments.is_new = 1");
        $criteria->join = "LEFT JOIN poll_comments ON t.id = poll_comments.poll_id";
        $criteria->group = "poll_id";
        $polls = Poll::model()->findAll($criteria);
        /* */
        return $polls;
    }

    /*
     * Return user comments that has new answers
     */

    public static function getCommentsWithAnswers()
    {
        $userId = Yii::$app->user->identity->id;
        $comments = PollComment::find()->commentsWithAnswers($userId)->all();
        return $comments;
        /* TODO Remove old Yii1 code * /
          $criteria = new CDbCriteria();
          $criteria->addCondition("t.user_id = ".Yii::app()->user->id);
          $criteria->addCondition("poll_comments.read_by_parent = 0");
          $criteria->join = "LEFT JOIN poll_comments ON t.id = poll_comments.parent_id";
          $criteria->group = "poll_comments.parent_id";
          $comments = PollComment::model()->findAll($criteria);
          return $comments;
          /* */
    }

    /*
     * Return count of new answers fo all user comments
     */

    public static function getNewAnswersCount()
    {
        $userId = Yii::$app->user->identity->id;
        $commentsCount = PollComment::find()->commentsWithAnswers($userId)->count();
        return $commentsCount;
        /* TODO Remove old Yii1 code * /
          return (YII_ENV == 'dev') ? 1014 : 0;
          // #TODO #DEV201: Yii1-toYi2:
          $criteria = new CDbCriteria();
          $criteria->addCondition("t.user_id = ".Yii::app()->user->id);
          $criteria->addCondition("poll_comments.read_by_parent = 0");
          $criteria->join = "LEFT JOIN poll_comments ON t.id = poll_comments.parent_id";
          $result = PollComment::model()->count($criteria);
          return $result;
          /* */
    }

    /*
     * Return user age by date_birthday
     */

    public function getAge()
    {
        if ($this->date_birthday) {
            return floor((time() - strtotime($this->date_birthday)) / (60 * 60 * 24 * 365.25));
        } else {
            return 0;
        }
    }

    /*
     * Return array of user languages
     */

    public function getLanguages()
    {
        $result = array();
        // Yii1 remove
        // if($languages = UserLanguage::model()->findAllByAttributes(array('user_id'=>$this->id))){
        if ($languages = UserLanguage::find(['user_id' => $this->id])->all()) {
            foreach ($languages as $language) {
                $result[] = $language->language_id;
            }
        }

        return $result;
    }

    /**
     * @param $userId
     * @return integer
     * return user rating
     */
    public static function getUserRating($userId)
    {
        // $user = User::find()->where(['id' => intval($userId)])->one();
        $user = User::findByPk($userId);
        $result = 0;
        // Yii1:
        // $user = User::model()->findByPK(intval($userId));
        if ($user) {
            if (YII_ENV == 'dev') {
                $result = 'getUserRating() = pollsRatingSum [#pollsRatingSum:' . $user->pollsRatingSum . ' + #commentsRatingSum:' . $user->commentsRatingSum . ']';
            } else {
                $result = $user->pollsRatingSum + $user->commentsRatingSum;
            }
        }
        return $result;
    }

    /**
     * @param $userId
     * @return integer
     * return user rating
     */
    public static function findByPk($userId)
    {
        $user = User::find()->where(['id' => intval($userId)])->one();
        return $user;
    }

    /**
     * Rules email unique
     * @param $attribute
     * @param $params
     */
    public function emailUnique($attribute, $params)
    {
        if ($this->$attribute) {
            $user = User::model()->findByAttributes(array('email' => CHtml::encode($this->$attribute)));
            if ($user) {
                $this->addError($attribute, Yii::t("main", 'Оберіть інший email!'));
            }
        }
    }

    public function newProfile($data = [])
    {
        $profile = new Profile;
        $profile->user_id = $this->id;
        $profile->public_email = $this->email;
        if (isset($data['Profile'])) {
            $profile->load($data);
        } else {
            $profile->load(['Profile' => $data]);
        }
        return $profile;
    }

}
