<?php

namespace app\modules\hr\models;

use Yii;
use app\modules\office\models\Cdepartment;
use app\models\ExtUser;
use app\modules\office\models\LeaveAccumulate;
use yii\helpers\Url;

class Employee extends \yii\db\ActiveRecord {

    const UPLOAD_FOLDER = '../data/files';

    //public $empLeave;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'employee';
    }

    public function rules() {
        return [
            [['employee_cid', 'employee_fullname', 'employee_dep_id', 'employee_type_id', 'employee_position_id'], 'required'],
            [['employee_dep_id', 'employee_type_id', 'employee_position_id'], 'integer'],
            [['employee_birthdate', 'employee_address', 'employee_phone', 'employee_status', 'empLeave'], 'safe'],
            [['employee_cid'], 'unique'],
            [['employee_cid'], 'validateIdCard'],
        ];
    }

    public function attributeLabels() {
        return [
            'employee_id' => 'รหัส',
            'employee_cid' => 'เลขบัตรประชาชน',
            'employee_fullname' => 'ชื่อ-นามสกุล',
            'employee_dep_id' => 'หน่วยงาน',
            'employee_type_id' => 'ประเภทเจ้าหน้าที่',
            'employee_birthdate' => 'วันเดือนปีเกิด',
            'employee_position_id' => 'ตำแหน่ง',
            'employee_address' => 'ที่อยู่',
            'employee_phone' => 'เบอร์โทร',
            'employee_status' => 'สถานะการใช้งาน',
        ];
    }

    public function validateIdCard() {
        $id = str_split(str_replace('-', '', $this->employee_cid)); //ตัดรูปแบบและเอา ตัวอักษร ไปแยกเป็น array $id
        $sum = 0;
        $total = 0;
        $digi = 13;

        for ($i = 0; $i < 12; $i++) {
            $sum = $sum + (intval($id[$i]) * $digi);
            $digi--;
        }
        $total = (11 - ($sum % 11)) % 10;

        if ($total != $id[12]) { //ตัวที่ 13 มีค่าไม่เท่ากับผลรวมจากการคำนวณ ให้ add error
            $this->addError('employee_cid', 'หมายเลขบัตรประชาชนไม่ถูกต้อง');
        }
    }

    public function getEmpType() {
        return $this->hasOne(EmployeeType::className(), ['employee_type_id' => 'employee_type_id']);
    }

    public function getEmpLeave() {
        return $this->hasOne(LeaveAccumulate::className(), ['employee_id' => 'employee_id'])->orOnCondition(['budgetyear' => \Yii::$app->params['budgetYear'] + 543]);
    }

    public function getLeave() {
        return @$this->hasOne(\app\modules\office\models\LeaveMain::className(), ['employee_id' => 'employee_id']);
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'employee_dep_id']);
    }

    public function getPosition() {
        return $this->hasOne(EmployeePosition::className(), ['employee_position_id' => 'employee_position_id']);
    }

    public function getHead() {//ตำแหน่งทางบริหาร
        $model = @EmployeePositionHead::find()->joinWith('executive')->where(['employee_id' => $this->employee_id])->orderBy(['employee_executive_level' => SORT_DESC])->all();
        $return = [];
        foreach ($model as $value) {
            $return[] = ['executive' => @$value->executive->employee_executive_name, 'dep' => @$value->dep->employee_dep_label, 'level' => @$value->executive->employee_executive_sort];
        }
        return $return;
    }

    public function getHeadExcutive() {//ตำแหน่งทางบริหาร
        return $this->hasMany(EmployeePositionHead::className(), ['employee_id' => 'employee_id']);
    }

//    public function getProfile($cid) {
//        return $this->hasOne(ExtUser::className(), ['ExtProfile' => 'employee_cid']);
//    }

    public function getFullname() {
        return $this->employee_fullname;
    }

    public function upinfo($cid) {
        $qury = "select birthday from patient where cid = '{$cid}' limit 1";
    }

    public static function getUploadPath() {
        return Yii::getAlias('@webroot') . '/' . self::UPLOAD_FOLDER . '/';
    }

    public static function getUploadUrl() {
        return Url::base(true) . '/' . self::UPLOAD_FOLDER . '/';
    }

}
