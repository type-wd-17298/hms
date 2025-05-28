<?php

namespace app\modules\office\models;

use Yii;
use app\modules\hr\models\Employee;

class WorkProcessList extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'work_process_list';
    }

    public function getLeave() {
        return $this->hasOne(LeaveMain::className(), ['work_grid_change_id' => 'work_grid_change_id']);
    }

    public function getReceiver() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'process_receiver']);
    }

    public function getStatus() {
        return $this->hasOne(WorkChangeStatus::className(), ['work_status_id' => 'work_status_id']);
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
