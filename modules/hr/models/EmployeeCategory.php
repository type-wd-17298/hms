<?php

namespace app\modules\hr\models;

use Yii;

class EmployeeCategory extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'employee_category';
    }

    /*
      public function attributeLabels() {
      return [
      'employee_type_id' => 'Employee Type ID',
      'employee_type_name' => 'Employee Type Name',
      'employee_type_status' => 'Employee Type Status',
      'employee_type_sort' => 'Employee Type Sort',
      ];
      }
     */
}
