<?php

namespace app\modules\survey\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;
use app\components\Ccomponent;

class SurveyComputerList extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'survey_computer_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['survey_list_problem', 'survey_list_desc', 'survey_list_compare', 'item_id', 'employee_id', 'survey_list_reuest', 'survey_type'], 'required'],
            [['item_id', 'employee_id', 'survey_budget_year', 'department_id', 'survey_list_reuest', 'survey_list_approve'], 'integer'],
            [['create_at', 'update_at', 'survey_list_approve_date'], 'safe'],
            [['survey_list_comment'], 'string'],
            [['survey_list_problem', 'survey_list_desc', 'survey_list_compare', 'survey_list_partnumber', 'survey_type'], 'string', 'max' => 255],
            [['survey_list_partnumber'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels() {
        return [
            'survey_list_id' => 'Survey List ID',
            'item_id' => 'รายการครุภัณฑ์คอมพิวเตอร์',
            'employee_id' => 'ผู้บันทึกรายการ',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'survey_budget_year' => 'ปีงบประมาณ',
            'department_id' => 'หน่วยงาน',
            'survey_list_reuest' => 'จำนวนที่ต้องการ',
            'survey_list_approve' => 'จำนวนที่อนุมัติ',
            'survey_list_approve_date' => 'วันที่อนุมัติ',
            'survey_list_comment' => 'หมายเหตุ',
            'survey_type' => 'ทดแทน/เพิ่มเติม',
            'survey_list_problem' => 'ปัญหา/อุปสรรค',
            'survey_list_desc' => 'ลักษณะงาน',
            'survey_list_compare' => 'เปรียบเทียบกับปริมาณงาน',
            'survey_list_partnumber' => 'เลขที่ขอทดแทน',
        ];
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'department_id']);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getItem() {
        return $this->hasOne(SurveyComputer::className(), ['id' => 'item_id']);
    }

}
