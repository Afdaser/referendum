<?php

namespace common\models;

use Yii;
use common\components\ActiveRecord;

/**
 * This is the model class for table "{{%user_interest}}".
 *
 * @property int $id
 * @property int $user_id User
 * @property string|null $activity Activity
 * @property string|null $interests Interests
 * @property string|null $music Music
 * @property string|null $films Films
 * @property string|null $shows Shows
 * @property string|null $books Books
 * @property string|null $games Games
 * @property string $date_add Date add
 * @property string $date_update Date update
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 *
 * @property User $user
 */
class UserInterest extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_interest}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'date_update'], 'required'],
            [['user_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['date_add', 'date_update'], 'safe'],
            [['activity', 'interests', 'music', 'films', 'shows', 'books', 'games'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'activity' => Yii::t('app', 'Activity'),
            'interests' => Yii::t('app', 'Interests'),
            'music' => Yii::t('app', 'Music'),
            'films' => Yii::t('app', 'Films'),
            'shows' => Yii::t('app', 'Shows'),
            'books' => Yii::t('app', 'Books'),
            'games' => Yii::t('app', 'Games'),
            'date_add' => Yii::t('app', 'Date add'),
            'date_update' => Yii::t('app', 'Date update'),
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
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
