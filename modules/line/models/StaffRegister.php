<?php

namespace app\modules\line\models;

use Yii;
use app\models\SocialAccount;

/**
 * This is the model class for table "staff_register".
 *
 * @property string $user_id
 * @property string $user_data
 * @property string $date_create
 * @property string $user_event
 * @property string $user_active
 */
class StaffRegister extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'staff_register';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id'], 'required'],
            [['user_data'], 'string'],
            [['date_create'], 'safe'],
            [['user_id'], 'string', 'max' => 100],
            [['user_event'], 'string', 'max' => 200],
            [['user_active'], 'string', 'max' => 10],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'user_id' => 'User ID',
            'user_data' => 'User Data',
            'date_create' => 'Date Create',
            'user_event' => 'User Event',
            'user_active' => 'User Active',
        ];
    }

    public function getStaff() {
        return $this->hasOne(SocialAccount::className(), ['client_id' => 'user_id']);
    }

}
