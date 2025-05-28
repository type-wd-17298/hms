<?php

namespace app\modules\servicedesk\models;

use Yii;
use mdm\autonumber\AutoNumber;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

class StaffWorkList extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_servicedesk;
    }

    public static function tableName() {
        return 'staff_worklist';
    }

    public function rules() {
        return [
            [['staff_worklist_issue', 'worklist_group_id', 'employee_id', 'department_id',], 'required'],
            [['staff_worklist_issue', 'staff_worklist_solve', 'staff_worklist_comment'], 'string'],
            [['service_status_id'], 'safe'],
            [['employee_id', 'employee_id_staff', 'employee_id_operation', 'department_id', 'department_id_operation', 'service_urgency_id', 'worklist_group_id', 'staff_worklist_date_finish', 'staff_worklist_date_accept', 'staff_worklist_date', 'staff_worklist_code'], 'safe'],
            [['staff_worklist_code'], 'unique'],
                // ['asset_list_number', 'required', 'when' => function ($model) {
                //return true;
                // return ($model->worklist_group_id <> 1);
                // }],
//            ['staff_worklist_date_finish', 'required', 'when' => function ($model) {
//                    return ($model->service_status_id == 5);
//                }],
        ];
    }

    public function attributeLabels() {
        return [
            'staff_worklist_id' => 'เลขที่',
            'staff_worklist_code' => 'CODE',
            'employee_id' => 'เจ้าหน้าที่',
            'employee_id_staff' => 'เจ้าหน้าที่',
            'employee_id_operation' => 'เจ้าหน้าที่',
            'department_id' => 'หน่วยงาน',
            'department_id_operation' => 'หน่วยงาน',
            'service_urgency_id' => 'ความสำคัญ',
            'worklist_group_id' => 'ประเภทงาน',
            'service_status_id' => 'สถานะ',
            'staff_worklist_date' => 'วันที่เริ่มดำเนินงาน',
            'staff_worklist_date_accept' => 'รับทราบ',
            'staff_worklist_date_finish' => 'วันที่ดำเนินการสำเร็จ',
            'staff_worklist_comment' => 'รายละเอียด',
            'staff_worklist_issue' => 'กิจกรรมการทำงาน',
            'staff_worklist_solve' => 'แก้ปัญหา/งานซ่อม',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->staff_worklist_create_at = new \yii\db\Expression('NOW()');
            if ($this->employee_id == '')
                $this->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
            if ($this->department_id == '')
                $this->department_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
            $this->staff_worklist_code = AutoNumber::generate('ITAT-?????');
            if ($this->staff_worklist_date == '')
                $this->staff_worklist_date = new \yii\db\Expression('NOW()');
        }

        if ($this->service_status_id == 5) {
            if ($this->staff_worklist_date_finish == '')
                $this->staff_worklist_date_finish = new \yii\db\Expression('NOW()');
        }
        if ($this->service_status_id == 3) {
            if ($this->staff_worklist_date_accept == '')
                $this->staff_worklist_date_accept = new \yii\db\Expression('NOW()');
        }


        return parent::beforeSave($insert);
    }

    public function getAsset() {
        return $this->hasOne(AssetList::className(), ['asset_list_number' => 'asset_list_number']);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getEmpStaff() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id_staff']);
    }

    public function getEmpOper() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id_operation']);
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'department_id']);
    }

    public function getStaffWorklistGroup() {
        return $this->hasOne(StaffWorklistGroup::className(), ['worklist_group_id' => 'worklist_group_id']);
    }

    public function getServiceUrgency() {
        return $this->hasOne(ServiceUrgency::className(), ['service_urgency_id' => 'service_urgency_id']);
    }

    public function getServiceStatus() {
        return $this->hasOne(ServiceStatus::className(), ['service_status_id' => 'service_status_id']);
    }

}
