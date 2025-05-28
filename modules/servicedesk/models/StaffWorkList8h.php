<?php

namespace app\modules\servicedesk\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

/**
 * This is the model class for table "staff_worklist8h".
 *
 * @property int $staff_worklist8h_id
 * @property string $staff_worklist8h_code
 * @property int $employee_id เจ้าหน้าที่
 * @property string|null $staff_worklist8h_date วันที่
 * @property string|null $staff_worklist8h_hour8 ชั่วโมงที่ 8
 * @property string|null $staff_worklist8h_hour9 ชั่วโมงที่ 9
 * @property string|null $staff_worklist8h_hour10 ชั่วโมงที่ 10
 * @property string|null $staff_worklist8h_hour11 ชั่วโมงที่ 11
 * @property string|null $staff_worklist8h_hour13 ชั่วโมงที่ 13
 * @property string|null $staff_worklist8h_hour14 ชั่วโมงที่ 14
 * @property string|null $staff_worklist8h_hour15 ชั่วโมงที่ 15
 * @property string|null $staff_worklist8h_hour16 ชั่วโมงที่ 16
 * @property string|null $staff_worklist8h_create_at วันที่สร้าง
 * @property string|null $staff_worklist8h_update_at วันที่แก้ไข
 * @property string|null $staff_worklist8h_detail รายละเอียด
 * @property string|null $staff_worklist8h_number
 */
class StaffWorkList8h extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'staff_worklist8h';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_servicedesk');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['staff_worklist8h_date'], 'required'],
            [['employee_id'], 'integer'],
            [['staff_worklist8h_date', 'staff_worklist8h_create_at', 'staff_worklist8h_update_at'], 'safe'],
            [['staff_worklist8h_hour8', 'staff_worklist8h_hour9', 'staff_worklist8h_hour10', 'staff_worklist8h_hour11', 'staff_worklist8h_hour13', 'staff_worklist8h_hour14', 'staff_worklist8h_hour15', 'staff_worklist8h_hour16', 'staff_worklist8h_detail'], 'string'],
            [['staff_worklist8h_code'], 'string', 'max' => 10],
            [['staff_worklist8h_number'], 'string', 'max' => 20],
            [['staff_worklist8h_date', 'employee_id'], 'unique', 'targetAttribute' => ['staff_worklist8h_date', 'employee_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'staff_worklist8h_id' => 'Staff Worklist8h ID',
            'staff_worklist8h_code' => 'Staff Worklist8h Code',
            'employee_id' => 'เจ้าหน้าที่',
            'staff_worklist8h_date' => 'วันที่',
            'staff_worklist8h_hour8' => 'เวลา 8.30-8.59',
            'staff_worklist8h_hour9' => 'เวลา 9.00-9.59',
            'staff_worklist8h_hour10' => 'เวลา 10.00-10.59',
            'staff_worklist8h_hour11' => 'เวลา 11.00-11.59',
            'staff_worklist8h_hour13' => 'เวลา 13.00-13.59',
            'staff_worklist8h_hour14' => 'เวลา 14.00-14.59',
            'staff_worklist8h_hour15' => 'เวลา 15.00-15.59',
            'staff_worklist8h_hour16' => 'เวลา 16.00-16.30',
            'staff_worklist8h_create_at' => 'วันที่สร้าง',
            'staff_worklist8h_update_at' => 'วันที่แก้ไข',
            'staff_worklist8h_detail' => 'รายละเอียด',
            'staff_worklist8h_number' => 'Staff Worklist8h Number',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->staff_worklist8h_create_at = new \yii\db\Expression('NOW()');
        }
        return parent::beforeSave($insert);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

}
