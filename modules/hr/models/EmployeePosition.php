<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "employee_position".
 *
 * @property int $employee_position_id
 * @property string $employee_position_name
 * @property string|null $employee_position_level
 * @property int|null $employee_position_sort
 * @property int|null $employee_position_active
 */
class EmployeePosition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_position_name'], 'required'],
            [['employee_position_sort', 'employee_position_active'], 'integer'],
            [['employee_position_name'], 'string', 'max' => 255],
            [['employee_position_level'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employee_position_id' => 'Employee Position ID',
            'employee_position_name' => 'Employee Position Name',
            'employee_position_level' => 'Employee Position Level',
            'employee_position_sort' => 'Employee Position Sort',
            'employee_position_active' => 'Employee Position Active',
        ];
    }
}
