<?php

namespace app\modules\nurse\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

class RSDiaryGG extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'report_shift_diary_gg';
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'employee_dep_id']);
    }

    public function getType() {
        return $this->hasOne(ShiftType::className(), ['report_type_shift_id' => 'report_type_shift_id']);
    }

    public function getSumNurse() {
        return $this->rps_s2 + $this->rps_s3 + $this->rps_s4;
    }

    public function getNurse() {
        return ($this->rps_p1 * 1.5) + ($this->rps_p2 * 3.5) + ($this->rps_p3 * 5.5) + ($this->rps_p4 * 7.5) + ($this->rps_p5 * 12);
    }

    public function getProductivity() {
        return (($this->nurse / 3) * 100) / ($this->sumNurse * 7);
    }

}
