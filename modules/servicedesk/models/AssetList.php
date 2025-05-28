<?php

namespace app\modules\servicedesk\models;

use Yii;
use mdm\autonumber\AutoNumber;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

class AssetList extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_servicedesk;
    }

    public static function tableName() {
        return 'asset_list';
    }

    public function getFullname() {
        return $this->asset_list_number . ' ' . $this->asset_list_name;
    }

    /*
      public function rules() {
      return [
      [['service_list_issue', 'service_problem_id', 'employee_id', 'department_id',], 'required'],
      [['service_list_issue', 'service_list_solve', 'service_list_comment'], 'string'],
      [['service_status_id'], 'safe'],
      [['employee_id', 'employee_id_staff', 'employee_id_operation', 'department_id', 'department_id_operation', 'service_urgency_id', 'service_problem_id', 'service_list_date_finish', 'service_list_date_accept', 'service_list_date', 'service_list_code'], 'safe'],
      [['service_list_code'], 'unique'],
      ];
      }
     */
    /*
      public function attributeLabels() {
      return [
      'service_list_id' => 'เลขที่',
      'service_list_code' => 'CODE',
      'employee_id' => 'เจ้าหน้าที่',
      'employee_id_staff' => 'เจ้าหน้าที่',
      'employee_id_operation' => 'เจ้าหน้าที่',
      'department_id' => 'หน่วยงาน',
      'department_id_operation' => 'หน่วยงาน',
      'service_urgency_id' => 'ความสำคัญ',
      'service_problem_id' => 'ประเภทปัญหา',
      'service_status_id' => 'สถานะ',
      'service_list_date' => 'วันที่แจ้ง',
      'service_list_date_accept' => 'รับทราบ',
      'service_list_date_finish' => 'เสร็จ',
      'service_list_comment' => 'รายละเอียด',
      'service_list_issue' => 'ปัญหา/งานซ่อม',
      'service_list_solve' => 'แก้ปัญหา/งานซ่อม',
      ];
      }
     */
    /*
      public function beforeSave($insert) {
      if ($this->isNewRecord) {
      $this->service_list_creat_at = new \yii\db\Expression('NOW()');
      if ($this->employee_id == '')
      $this->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
      if ($this->department_id == '')
      $this->department_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
      $this->service_list_code = AutoNumber::generate('ITSD-?????');
      if ($this->service_list_date == '')
      $this->service_list_date = new \yii\db\Expression('NOW()');
      }



      return parent::beforeSave($insert);
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

      public function getServiceProblem() {
      return $this->hasOne(ServiceProblem::className(), ['service_problem_id' => 'service_problem_id']);
      }

      public function getServiceUrgency() {
      return $this->hasOne(ServiceUrgency::className(), ['service_urgency_id' => 'service_urgency_id']);
      }

      public function getServiceStatus() {
      return $this->hasOne(ServiceStatus::className(), ['service_status_id' => 'service_status_id']);
      }
     */
}
