<?php

namespace app\modules\office\models;

//use Yii;
//use mdm\autonumber\AutoNumber;
//use app\models\ExtProfile;
use app\modules\hr\models\Employee;

//use app\components\Ccomponent;

class LicenseList extends \yii\db\ActiveRecord {

    public static function getDb() {
// use the "db_project" application component
        return \Yii::$app->db_erp;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'traffic_license_data';
    }

    public function rules() {
        return [
            [['traffic_number', 'traffic_status', 'employee_id'], 'required'],
            [['id', 'employee_id'], 'integer'],
            [['traffic_number'], 'trim'], // ตัดช่องว่างหน้า-หลัง
            [['traffic_number'], 'match', 'pattern' => '/^\S+$/', 'message' => 'ห้ามเว้นวรรค'], // ไม่ให้มีช่องว่าง
            [['cid_hash', 'traffic_type', 'traffic_number', 'traffic_owner', 'traffic_status'], 'string', 'max' => 200],
            [['comments'], 'string', 'max' => 255],
            [['create_at'], 'save'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'cid_hash' => 'CID',
            'traffic_number' => 'ทะเบียนรถ',
            'traffic_owner' => 'ชื่อที่ลงเบียน',
            'traffic_status' => 'สถานะการใช้งาน',
            'create_at' => 'ลงทะเบียนเมื่อ',
            'comments' => 'หมายเหตุ',
            'employee_id' => 'เจ้าของรถ'
        ];
    }

    public function getWorkAssign() {
        return Employee::findOne(['employee_cid' => $this->cid_hash]);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_cid' => 'cid_hash']);
    }

}
