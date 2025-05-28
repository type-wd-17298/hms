<?php

namespace app\modules\office\models;

use Yii;

/**
 * This is the model class for table "leave_signature".
 *
 * @property int $leave_signature_id
 * @property resource $leave_signature_data
 * @property string $leave_signature_date
 * @property int $leave_id
 */
class LeaveSignature extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'leave_signature';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['leave_signature_data'], 'string'],
            [['leave_signature_date'], 'safe'],
            [['leave_id'], 'required'],
            [['leave_id', 'employee_id'], 'integer'],
                //[['leave_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'leave_signature_id' => 'Leave Signature ID',
            'leave_signature_data' => 'Leave Signature Data',
            'leave_signature_date' => 'Leave Signature Date',
            'leave_id' => 'Leave ID',
        ];
    }

}
