<?php

namespace app\modules\servicedesk\models;

use Yii;
use mdm\autonumber\AutoNumber;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

class SoftwareList extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_servicedesk;
    }

    public static function tableName() {
        return 'service_software_list';
    }

    public function rules() {
        return [
            [['software_list_name', 'employee_id', 'department_id', 'software_list_regisdate'], 'required'],
            [['software_list_name', 'software_list_detail', 'software_list_id'], 'string'],
            [['software_status_id'], 'safe'],
            [['software_list_license_amount', 'software_list_license', 'software_list_ma', 'employee_id', 'software_list_vender', 'employee_id_staff', 'department_id', 'software_type_id', 'software_list_date_expire', 'software_list_regisdate', 'software_list_name'], 'safe'],
            [['software_list_id'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'software_list_id' => 'เลขที่',
            'software_list_name' => 'ชื่อซอฟต์แวร์',
            'employee_id' => 'เจ้าหน้าที่',
            'employee_id_staff' => 'เจ้าหน้าที่',
            'department_id' => 'หน่วยงาน',
            'department_id_operation' => 'หน่วยงาน',
            'software_type_id' => 'ประเภทซอฟต์แวร์',
            'software_status_id' => 'สถานะ',
            'software_list_regisdate' => 'วันที่เริ่มใช้งาน',
            'software_list_date_expire' => 'วันหมดอายุ',
            'software_list_detail' => 'รายละเอียด',
            'software_list_license' => 'ราคา License',
            'software_list_ma' => 'ราคา MA (บาท/ปี)',
            'software_list_license_amount' => 'จำนวน License',
            'software_list_vender' => 'ผู้พัฒนาระบบ',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->software_list_creat_at = new \yii\db\Expression('NOW()');
            if ($this->employee_id == '')
                $this->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
            if ($this->department_id == '')
                $this->department_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
            $this->software_list_id = AutoNumber::generate('ITSR-???');
            if ($this->software_list_regisdate == '')
                $this->software_list_regisdate = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getEmpStaff() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id_staff']);
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'department_id']);
    }

    public function getSoftwareType() {
        return $this->hasOne(SoftwareType::className(), ['software_type_id' => 'software_type_id']);
    }

    public function getSoftwareStatus() {
        return $this->hasOne(SoftwareStatus::className(), ['software_status_id' => 'software_status_id']);
    }

}
