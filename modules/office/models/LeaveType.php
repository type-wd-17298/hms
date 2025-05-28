<?php

namespace app\modules\office\models;

use Yii;

class LeaveType extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'leave_type';
    }

    public function rules() {
        return [
            [['leave_type_detail'], 'string'],
            [['leave_type_active'], 'integer'],
            [['leave_type_name'], 'string', 'max' => 100],
            [['leave_type_form'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels() {
        return [
            'leave_type_id' => 'Leave Type ID',
            'leave_type_name' => 'ประเภทการลา',
            'leave_type_detail' => 'Leave Type Detail',
            'leave_type_form' => 'Leave Type Form',
            'leave_type_active' => 'Leave Type Active',
        ];
    }

}
