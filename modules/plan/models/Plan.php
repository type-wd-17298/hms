<?php

namespace app\modules\plan\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;
use app\components\Ccomponent;

/**
 * This is the model class for table "plan_list".
 *
 * @property int $plan_list_id
 * @property string $plan_list_title
 * @property string|null $plan_list_objective
 * @property string|null $plan_list_target
 * @property string|null $plan_list_activity
 * @property string|null $plan_list_budget
 * @property string|null $plan_list_kpi
 * @property string|null $plan_list_period
 * @property int $employee_id
 */
class Plan extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'plan_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['plan_list_title', 'employee_id', 'plan_list_budget', 'plan_budget_year'], 'required'],
            [['plan_list_objective', 'plan_list_target', 'plan_list_activity', 'plan_list_kpi', 'plan_list_costdetail'], 'string'],
            [['employee_id', 'plan_budget_year', 'department_id'], 'integer'],
            [['plan_list_title'], 'string', 'max' => 255],
            [['plan_list_period'], 'string', 'max' => 100],
            [['plan_list_budget'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'plan_list_id' => 'Plan List ID',
            'plan_list_title' => 'ชื่อแผนงาน/โครงการ',
            'plan_list_objective' => 'วัตุประสงค์',
            'plan_list_target' => 'กลุ่มเป้าหมาย',
            'plan_list_activity' => 'กิจกรรม',
            'plan_list_budget' => 'งบประมาณ',
            'plan_list_kpi' => 'ผลลัพธ์ (KPI)',
            'plan_list_period' => 'ระยะเวลา/หน่วยงานที่รับผิดชอบ',
            'employee_id' => 'เจ้าหน้าที่ผู้บันทึก',
            'department_id' => 'หน่วยงาน',
            'create_at' => 'create_at',
            'update_at' => 'update_at',
            'plan_list_costdetail' => 'รายละเอียดค่าใช้จ่าย',
            'plan_budget_year' => 'ปีงบประมาณ'
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'department_id']);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

}
