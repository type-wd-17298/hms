<?php

namespace app\modules\hr\models;

use Yii;

class EmployeePositionHead extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'employee_position_head';
    }

    public function rules() {
        return [
            [['employee_id', 'employee_executive_id'], 'required'],
            [['employee_executive_id', 'employee_dep_id'], 'number'],
            [['create'], 'safe'],
            [['employee_id', 'employee_executive_id', 'employee_dep_id'], 'unique', 'targetAttribute' => ['employee_id', 'employee_executive_id', 'employee_dep_id']],
        ];
    }

    public function attributeLabels() {
        return [
            'employee_id' => 'เจ้าหน้าที่',
            'employee_dep_id' => 'ฝ่าย/กลุ่มงาน/กลุ่มภารกิจ',
            'employee_executive_id' => 'ดำรงตำแหน่ง',
        ];
    }

    public function getExecutive() {
        return $this->hasOne(Executive::className(), ['employee_executive_id' => 'employee_executive_id']);
    }

    public function getExecutives() {
        return Executive::find()->where(['employee_executive_id' => $this->employee_executive_id])->all();
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'employee_dep_id']);
    }

    public function getDepartment() {
        return $this->hasMany(EmployeeDep::class, ['employee_dep_id' => 'employee_dep_id']);
    }

    public function getExecutiveDep() {
        return ExecutiveHasCdepartment::find()->where(['employee_executive_id' => $this->employee_executive_id])->all();
    }

}
