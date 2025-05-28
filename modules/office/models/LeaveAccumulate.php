<?php

namespace app\modules\office\models;

use Yii;

class LeaveAccumulate extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'leave_accumulate';
    }

    public function getLeave() {
        return $this->hasOne(\app\modules\office\models\LeaveMain::className(), ['employee_id' => 'employee_id']);
    }

    public function getAccrued() {
        return $this->vacation_accrued - $this->leave->vacationSS;
    }

    public function getVacationCC() {
        return $this->vacation_leave + @($this->leave->accruedCC);
    }

    public function rules() {
        return [
            [['budgetyear', 'employee_id', 'update_at', 'create_at', 'staff'], 'safe'],
            [['cumulative', 'claim', 'sick_leave', 'personal_leave', 'maternity_leave', 'vacation_leave', 'cancel_leave', 'vacation_accrued'], 'integer', 'integerOnly' => false],
            [['cumulative'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/', 'min' => 0, 'max' => 30],
            [['claim'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/', 'min' => 0.5, 'max' => 10],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'id',
            'budgetyear' => 'ปีงบประมาณ',
            'employee_id' => 'เจ้าหน้าที่',
            'category_name' => 'เจ้าหน้าที่',
            'cumulative' => 'เหลือสะสม',
            'claim' => 'วันลาปีนี้',
            'sick_leave' => 'ลาป่วย',
            'personal_leave' => 'ลากิจ',
            'maternity_leave' => 'ลาคลอดบุตร',
            'vacation_leave' => 'ลาพักผ่อน',
            'cancel_leave' => 'ยกเลิกวันลา',
            'vacation_accrued' => 'วันหยุดสะสม',
            'update_at' => 'แก้ไขล่าสุด',
            'create_at' => 'สร้างเมื่อ',
            'staff' => 'เจ้าหน้าที่'
        ];
    }

    public function beforeSave($insert) {
        $sum = $this->cumulative + $this->claim;
        if ($sum <= 30) {
            $this->vacation_accrued = $sum;
        } else {
            $this->vacation_accrued = 30;
        }
        $this->staff = Yii::$app->user->identity->profile->fullname;
        return parent::beforeSave($insert);
    }

}
