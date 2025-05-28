<?php

namespace app\modules\office\models;

use Yii;

/**
 * This is the model class for table "leave_status".
 *
 * @property int $leave_status_id
 * @property string $leave_status_name
 * @property int $leave_status_active
 */
class LeaveStatus extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'leave_status';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['leave_status_active'], 'integer'],
            [['leave_status_name', 'leave_status_color'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'leave_status_id' => 'Leave Status ID',
            'leave_status_name' => 'สถานะดำเนินการ',
            'leave_status_active' => 'Leave Status Active',
        ];
    }

}
