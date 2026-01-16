<?php

namespace common\models;

use common\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%redirect}}".
 *
 * @property int $id ID
 * @property string $subdomain Subdomain
 * @property string $from_path From path
 * @property string $to_url To url
 * @property int|null $created_by Created by:
 * @property int|null $updated_by Updated by:
 * @property int|null $created_at Created at:
 * @property int|null $updated_at Updated at:
 */
class Redirect extends ActiveRecord
{
    /**
     * Готуємо шлях і піддомен перед валідацією.
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            // Нормалізуємо піддомен та шлях редіректу.
            $this->subdomain = trim((string) $this->subdomain);
            $this->from_path = '/' . ltrim((string) $this->from_path, '/');

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%redirect}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subdomain', 'from_path', 'to_url'], 'trim'],
            [['from_path', 'to_url'], 'required'],
            [['subdomain'], 'string', 'max' => 128],
            [['from_path', 'to_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subdomain' => 'Піддомен',
            'from_path' => 'Звідки',
            'to_url' => 'Куди',
            'created_by' => Yii::t('app', 'Created by:'),
            'updated_by' => Yii::t('app', 'Updated by:'),
            'created_at' => Yii::t('app', 'Created at:'),
            'updated_at' => Yii::t('app', 'Updated at:'),
        ];
    }
}
