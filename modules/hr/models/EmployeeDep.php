<?php

namespace app\modules\hr\models;

use Yii;
use app\modules\office\models\Cdepartment;

class EmployeeDep extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'employee_dep';
    }

    public function rules() {
        return [
            [['employee_dep_label'], 'required'],
            [['employee_dep_id'], 'integer'],
            [['category_id', 'employee_dep_hospcode', 'employee_dep_code', 'employee_dep_label', 'employee_dep_level', 'employee_dep_sort', 'employee_dep_parent', 'employee_dep_status'], 'safe'],
            [['employee_dep_label'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'employee_dep_id' => 'ID',
            'employee_dep_hospcode' => 'hospcode',
            'employee_dep_code' => 'รหัสหน่วยงาน',
            'employee_dep_label' => 'ชื่อหน่วยงาน',
            'employee_dep_level' => 'ระดับ',
            'employee_dep_sort' => 'เรียงลำดับ',
            'employee_dep_parent' => 'หน่วยงานหลัก',
            'employee_dep_status' => 'สถานะการใช้งาน',
            'category_id' => 'ประเภทหน่วยงาน',
        ];
    }

    public function getType() {
        return $this->hasOne(EmployeeCategory::className(), ['category_id' => 'category_id']);
    }

    public function getParent() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'employee_dep_parent']);
    }

}
