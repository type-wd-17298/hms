<?php

namespace app\modules\office\models;

use Yii;
use app\modules\hr\models\Employee;

class ApprovalProcessList extends \yii\db\ActiveRecord {

    public $approval_tt; //cal time

    public static function tableName() {
        return 'paperless_approval_process_list';
    }

    public function rules() {
        return [
            [['approval_id'], 'required'],
            [['process_acknowledge_staff', 'process_receiver', 'employee_id'], 'integer'],
            [['processlist_id', 'process_create', 'process_update'], 'safe'],
            [['process_comment'], 'string'],
            [['approval_status_id'], 'string', 'max' => 4],
                //  [['approval_id'], 'integer'],
                //[['processlist_id'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'processlist_id' => 'Paperless Processlist ID',
            'approval_id' => 'approval ID',
            'employee_id' => 'Employee ID',
            'process_create' => 'Process Create',
            'process_update' => 'Process Update',
            'process_command' => 'Process Command',
            'process_comment' => 'Process Comment',
            'process_staffs' => 'Process Staffs',
            'process_deps' => 'Process Deps',
            'approval_status_id' => 'approval_status_id',
            'approval_tt' => 'เวลารอคอย',
            'process_acknowledge_staff' => 'staff',
        ];
    }

    public function getApproval() {
        return $this->hasOne(PaperlessApproval::className(), ['approval_id' => 'approval_id']);
    }

    public function getReceiver() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'process_receiver']);
    }

    public function getAcknowledge() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'process_acknowledge_staff']);
    }

    public function getStatus() {
        return $this->hasOne(ApprovalStatus::className(), ['approval_status_id' => 'approval_status_id']);
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
