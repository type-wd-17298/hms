<?php

namespace app\modules\office\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\office\components\Ccomponent;

class PaperlessProcessList extends \yii\db\ActiveRecord {

    public $paperless_tt; //cal time

    public static function tableName() {
        return 'paperless_process_list';
    }

    public function rules() {
        return [
            [['paperless_id'], 'required'],
            [['process_acknowledge_staff', 'process_receiver', 'operation_id', 'employee_id'], 'integer'],
            [['processlist_id', 'process_create', 'process_update', 'process_staffs', 'process_deps'], 'safe'],
            [['process_comment'], 'string'],
            [['paperless_status_id'], 'string', 'max' => 4],
            [['paperless_id'], 'string', 'max' => 50],
            [['process_command'], 'string', 'max' => 255],
            [['processlist_id'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'processlist_id' => 'Paperless Processlist ID',
            'paperless_id' => 'Paperless ID',
            'operation_id' => 'Paperless Operation ID',
            'employee_id' => 'Employee ID',
            'process_create' => 'Process Create',
            'process_update' => 'Process Update',
            'process_command' => 'Process Command',
            'process_comment' => 'Process Comment',
            'process_staffs' => 'Process Staffs',
            'process_deps' => 'Process Deps',
            'paperless_status_id' => 'paperless_status_id',
            'paperless_tt' => 'เวลารอคอย',
            'process_acknowledge_staff' => 'staff',
        ];
    }

    public function getReceiver() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'process_receiver']);
    }

    public function getStatus() {
        return $this->hasOne(PaperlessStatus::className(), ['paperless_status_id' => 'paperless_status_id']);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getOwner() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_owner_id']);
    }

    public function getPaperless() {
        return $this->hasOne(Paperless::className(), ['paperless_id' => 'paperless_id']);
    }

    public function getStaff() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'process_acknowledge_staff']);
    }

    public function getLinkView() {
        return $this->hasOne(@PaperlessView::className(), ['paperless_paper_ref' => 'paperless_id']);
    }

//    public function getPaper() {
//        return $this->hasOne(Paperless::className(), ['paperless_id' => 'paperless_id']);
//    }
    public function getPaper() {
        if (substr($this->paperless_id, 0, 3) == 'BNN') {
            return $this->hasOne(Paperless::className(), ['paperless_id' => 'paperless_id']);
        } else if (substr($this->paperless_id, 0, 3) == 'BRN') {
            return $this->hasOne(PaperlessOfficial::className(), ['paperless_id' => 'paperless_id']);
        }
    }

    /*
      public function beforeSave($insert) {
      if (in_array($this->paperless_status_id, ['F18', 'F19'])) {
      Ccomponent::OperationUpdate(); //update สถานะการดำเนินการให้ผู้บริหาร
      }
      }
     */
}
