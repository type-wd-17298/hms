<?php

namespace app\modules\office\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;
use mdm\autonumber\AutoNumber;
use app\components\Ccomponent;

class ApprovalDead extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'paperless_approval_dead';
    }

    public function rules() {
        return [
            [['dead_infomation'], 'required'],
            [['dead_id', 'employee_id', 'department_id'], 'integer'],
            [['staff_name', 'dead_id_number', 'dead_cid', 'dead_infomation'], 'string', 'max' => 255],
            [['dead_create', 'dead_update', 'dead_date', 'dead_time'], 'safe'],
            // [['dead_cid'], 'unique', 'skipOnError' => false, 'skipOnEmpty' => false],
            [['dead_infomation'], 'unique']
        ];
    }

    public function attributeLabels() {
        return [
            'dead_id' => 'dead_id',
            'dead_cid' => 'เลขที่บัตรประชาชนผู้เสียชีวิต',
            'dead_id_number' => 'เลขที่หนังสือการตาย',
            'employee_id' => 'เลขที่บัตรผู้แจ้ง',
            'staff_name' => 'ข้อมูลผู้แจ้ง ชื่อนามกุล - หน่วยงาน',
            'dead_date' => 'วันที่เสียชีวิต',
            'dead_time' => 'เวลาเสียชีวิต',
            'department_id' => '',
            'dead_infomation' => 'ข้อมูลผู้เสียชีวิต'
        ];
    }

    public function beforeValidate() {
        Yii::info('Validating: ' . json_encode($this->attributes), 'application');
        return parent::beforeValidate();
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            if ($this->dead_date == '')
                $this->dead_date = new \yii\db\Expression('NOW()');
            $this->dead_create = new \yii\db\Expression('NOW()');
            /*
              $this->dead_id_number = AutoNumber::generate('DEATH' . (date('Y') + 543) . '-?????');
              $this->staff_name = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_fullname;
              list($cid, $name) = explode(':', $this->dead_infomation);
              $this->dead_cid = $cid;
             *
             */
        } else {
            $this->dead_update = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'department_id']);
    }

}
