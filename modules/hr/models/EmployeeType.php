<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "employee_type".
 *
 * @property int $employee_type_id
 * @property string $employee_type_name
 * @property string $employee_type_status
 * @property int $employee_type_sort
 */
class EmployeeType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_type_name', 'employee_type_status'], 'required'],
            [['employee_type_sort'], 'integer'],
            [['employee_type_name'], 'string', 'max' => 100],
            [['employee_type_status'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employee_type_id' => 'Employee Type ID',
            'employee_type_name' => 'Employee Type Name',
            'employee_type_status' => 'Employee Type Status',
            'employee_type_sort' => 'Employee Type Sort',
        ];
    }
}
