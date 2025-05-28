<?php

namespace app\modules\office\models;

use Yii;
use app\modules\hr\models\Employee;

class LeaveProcessList extends \yii\db\ActiveRecord {

    public $leave_tt; //cal time

    public static function tableName() {
        return 'leave_process_list';
    }

    public function rules() {
        return [
            [['leave_id'], 'required'],
            [['process_acknowledge_staff', 'process_receiver', 'employee_id'], 'integer'],
            [['processlist_id', 'process_create', 'process_update'], 'safe'],
            [['process_comment'], 'string'],
            [['leave_status_id'], 'string', 'max' => 4],
            [['leave_id'], 'integer'],
                //[['processlist_id'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'processlist_id' => 'Paperless Processlist ID',
            'leave_id' => 'leave ID',
            'employee_id' => 'Employee ID',
            'process_create' => 'Process Create',
            'process_update' => 'Process Update',
            'process_command' => 'Process Command',
            'process_comment' => 'Process Comment',
            'process_staffs' => 'Process Staffs',
            'process_deps' => 'Process Deps',
            'leave_status_id' => 'leave_status_id',
            'leave_tt' => 'เวลารอคอย',
            'process_acknowledge_staff' => 'staff',
        ];
    }

    public function getLeave() {
        return $this->hasOne(LeaveMain::className(), ['leave_id' => 'leave_id']);
    }

    public function getReceiver() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'process_receiver']);
    }

    public function getStatus() {
        return $this->hasOne(LeaveStatus::className(), ['leave_status_id' => 'leave_status_id']);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getOwner() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_owner_id']);
    }

//    public function getPaperless() {
//        return $this->hasOne(Paperless::className(), ['paperless_id' => 'paperless_id']);
//    }

    public function getStaff() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'process_acknowledge_staff']);
    }

}
