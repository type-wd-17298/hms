<?php

namespace app\modules\office\models;

use Yii;
use app\modules\hr\models\Employee;

class WorkChangeGrid extends \yii\db\ActiveRecord {

    public $rankdate;
    public $pcheck;
    public $cc;

    public static function tableName() {
        return 'work_change_grid';
    }

    public function rules() {
        return [
            [['work_grid_type_id', 'emp_staff_a', 'emp_staff_b', 'work_change_id', 'work_grid_change_date_a', 'work_grid_change_detail'], 'required'],
            [['emp_staff_a', 'emp_staff_b', 'work_change_id', 'work_grid_type_id', 'work_grid_type_id2'], 'integer'],
            [['update_at', 'create_at', 'work_grid_change_date', 'work_grid_change_date_a', 'work_grid_change_date_b'], 'safe'],
            [['work_grid_change_detail'], 'string'],
            //[['work_change_id', 'emp_staff_a', 'emp_staff_b', 'work_grid_type_id'], 'unique', 'targetAttribute' => ['work_change_id', 'emp_staff_a', 'emp_staff_b', 'work_grid_type_id']],
            //[['work_change_id', 'emp_staff_a', 'emp_staff_b', 'work_grid_change_date_a', 'work_grid_change_date_b', 'work_grid_type_id', 'work_grid_type_id2'], 'unique', 'targetAttribute' => ['work_change_id', 'emp_staff_a', 'emp_staff_b', 'work_grid_change_date_a', 'work_grid_change_date_b', 'work_grid_type_id', 'work_grid_type_id2']],
            ['work_grid_change_date_b', 'required', 'when' => function ($model) {
                    return in_array($model->work_change_id, [1, 2]);
                }],
            ['work_change_id', 'required', 'when' => function ($model) {
                    return in_array($model->work_change_id, [1, 2]);
                }],
            ['work_grid_type_id2', 'required', 'when' => function ($model) {
                    return in_array($model->work_change_id, [1, 2]);
                }],
            ['emp_staff_b', 'validateUnique'],
        ];
    }

    public function validateUnique($attribute, $params, $validator) {
        if ($this->emp_staff_a == $this->emp_staff_b) {
            $this->addError($attribute, 'คุณเลือกรายการซ้ำซ้อน เนื่องจากผู้แลกและผู้รับแลกเวรต้องไม่เป็นคนเดียวกัน');
        }

//        if (in_array($this->work_change_id, [1, 2]) && strtotime($this->work_grid_change_date_a) > strtotime($this->work_grid_change_date_b)) {
//            $this->addError($attribute, 'คุณเลือกรายการไม่ถูกต้อง เนื่องจากวันที่แลกเวรกับวันที่คืนเวร ต้องไม่ถูกต้อง');
//        }

        if (in_array($this->work_change_id, [1, 2]) && $this->work_grid_change_date_a == $this->work_grid_change_date_b && $this->work_grid_type_id == $this->work_grid_type_id2) {
            $this->addError($attribute, 'คุณเลือกรายการไม่ถูกต้อง เนื่องจากวันที่แลกเวรกับวันที่คืนเวร ต้องไม่เป็นวันเดียวกัน');
        }
    }

    public function attributeLabels() {
        return [
            'work_grid_change_id' => 'Paperless View ID',
            'emp_staff_a' => 'เจ้าหน้าที่',
            'emp_staff_b' => 'ผู้รับอยู่เวรแทน',
            'work_change_id' => 'ประเภทการแลกเวร',
            'work_grid_change_date' => 'เจ้าหน้าที่',
            'work_grid_change_date_a' => 'วันที่แลก',
            'work_grid_change_date_b' => 'วันที่ใช้คืน',
            'work_grid_change_detail' => 'เนื่องจาก',
            'update_at' => 'Update At',
            'create_at' => 'Create At',
            'work_status_id' => 'สถานะ',
            'work_grid_type_id' => 'ประเภทเวร',
            'work_grid_type_id2' => 'ประเภทเวรใช้คืน',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->work_grid_change_date = new \yii\db\Expression('NOW()');
            $this->create_at = new \yii\db\Expression('NOW()');
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    public function getWorkStatus() {
        return $this->hasOne(WorkChangeStatus::className(), ['work_status_id' => 'work_status_id']);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'emp_staff_a']);
    }

    public function getEmps() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'emp_staff_a']);
    }

    public function getEmps2() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'emp_staff_b']);
    }

    public function getWorkType() {
        return $this->hasOne(WorkGridType::className(), ['work_grid_type_id' => 'work_grid_type_id']);
    }

    public function getWorkType2() {
        return $this->hasOne(WorkGridType::className(), ['work_grid_type_id' => 'work_grid_type_id2']);
    }

    public function getWorkChange() {
        return $this->hasOne(WorkChange::className(), ['work_change_id' => 'work_change_id']);
    }

    public function getLastProcess() {
        return @WorkProcessList::find()->where(['processlist_id' => $this->work_lastprocess_id])->one();
    }

    public function getProcessStaff() {
        return @WorkProcessList::find()->where(['work_grid_change_id' => $this->work_grid_change_id, 'work_status_id' => 'L04'])->one();
    }

    public function getProcessL1() {
        return @WorkProcessList::find()->where(['work_grid_change_id' => $this->work_grid_change_id, 'work_status_id' => 'L01'])->one();
    }

    public function getProcessL2() {
        return @WorkProcessList::find()->where(['work_grid_change_id' => $this->work_grid_change_id, 'work_status_id' => 'L02'])->one();
    }

    public function getProcessL3() {
        return @WorkProcessList::find()->where(['work_grid_change_id' => $this->work_grid_change_id, 'work_status_id' => 'L03'])->one();
    }

    public function getProcessExcutive() {
        return @WorkProcessList::find()->where(['work_grid_change_id' => $this->work_grid_change_id, 'work_status_id' => 'L10'])->one();
    }

    public function getProcessStaffExcutive() {
        return @WorkProcessList::find()->where(['work_grid_change_id' => $this->work_grid_change_id])->one();
    }

    public function getWorkAssign() {
        return Employee::findOne($this->emp_staff_b);
    }

}
