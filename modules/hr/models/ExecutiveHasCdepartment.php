<?php

namespace app\modules\hr\models;

use Yii;
use app\modules\office\models\Cdepartment;

/**
 * This is the model class for table "executive_has_cdepartment".
 *
 * @property int $employee_executive_id
 * @property int $department_id
 * @property string|null $create_at
 */
class ExecutiveHasCdepartment extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'executive_has_cdepartment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['employee_executive_id', 'employee_dep_id'], 'required'],
            [['employee_executive_id', 'employee_dep_id'], 'integer'],
            [['create_at'], 'safe'],
            [['employee_executive_id', 'employee_dep_id'], 'unique', 'targetAttribute' => ['employee_executive_id', 'employee_dep_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'employee_executive_id' => 'Employee Executive ID',
            'employee_dep_id' => 'Department ID',
            'create_at' => 'Create At',
        ];
    }

    public function getDepartment() {
        return $this->hasOne(EmployeeDep::class, ['employee_dep_id' => 'employee_dep_id']);
    }

    public function getExecutiveHead() {
        return EmployeePositionHead::find()->where(['employee_executive_id' => $this->employee_executive_id])->all();
        // return $this->hasMany(EmployeePositionHead::class, ['employee_executive_id' => 'employee_executive_id']);
    }

}
