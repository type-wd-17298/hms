<?php

namespace app\modules\hr\models;

use Yii;
use app\modules\office\models\Cdepartment;

class Executive extends \yii\db\ActiveRecord {

    public $dep_code;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'employee_executive';
    }

    public function rules() {
        return [
            [['employee_executive_name'], 'required'],
            [['employee_executive_id'], 'integer'],
            [['dep_code', 'employee_executive_level', 'employee_executive_sort'], 'safe'],
            [['employee_executive_name', 'employee_executive_comment'], 'string'],
            [['employee_executive_name'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'employee_executive_id' => 'รหัส',
            'employee_executive_name' => 'ชื่อตำแหน่งทางบริหาร',
            'employee_executive_sort' => 'จัดลำดับ',
            'employee_executive_code' => 'รหัสตำแหน่ง',
            'employee_executive_level' => 'ลำดับชั้น',
            'dep_code' => 'หน่วยงานที่กำกับดูแล',
            'employee_executive_comment' => 'หมายเหตุ'
        ];
    }

    public function getDep() {//ตำแหน่งทางบริหาร
        $model = ExecutiveHasCdepartment::find()->where(['employee_executive_id' => $this->employee_executive_id])->all();
//        $return = [];
//        foreach ($model as $value) {
//            $return[$value->department_id] = $value->department->department_name;
//        }
        return $model;
    }

}
