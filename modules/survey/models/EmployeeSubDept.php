<?php

namespace app\modules\survey\models;

use Yii;
use yii\db\ActiveRecord;

class EmployeeSubDept  extends ActiveRecord
{
    public static function tableName()
    {
        return 'employee_subdept';
    }

    public function rules()
    {
        return [
            [
                [
                    'employee_subDept_id',
                    'employee_subDept_label',
                ],
                'required'
            ],
            [
                [
                    'employee_subDept_id',
                    'employee_mainDept_id',
                ],
                'integer'
            ],
            [['employee_subDept_label'], 'string', 'max' => 255],
        ];
    }

    public static function getDb()
    {
        return Yii::$app->db;
    }
}
