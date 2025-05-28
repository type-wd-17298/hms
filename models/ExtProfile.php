<?php

namespace app\models;

use dektrium\user\models\Profile as BaseProfile;
use app\modules\hr\models\Employee;

class ExtProfile extends BaseProfile {

    public function rules() {
        $rules = parent::rules();
        // add some rules
        //$rules['depcodeRequired'] = ['depcode', 'required'];
        //$rules['depcodeLength'] = ['depcode', 'string', 'max' => 5];
        $rules['cidRequired'] = ['cid', 'required'];
        $rules['cidLength'] = ['cid', 'validateIdCard'];
        $rules['nameRequired'] = ['name', 'required'];
        $rules['nameLength'] = ['name', 'string'];
        $rules['cidUnique'] = ['cid', 'unique'];

        #$rules['position_nameRequired'] = ['position_name', 'required'];
        #$rules['position_nameLength'] = ['position_name', 'string'];
        #$rules['position_levelRequired'] = ['position_level', 'required'];
        #$rules['position_levelLength'] = ['position_level', 'string'];


        $rules['lnameRequired'] = ['lname', 'required'];
        $rules['lnameLength'] = ['lname', 'string'];
        //$rules['lastloginLength'] = ['lastlogin', 'date', 'format' => 'yyyy-M-d H:m:s'];

        return $rules;
    }

    public function attributeLabels() {
        $label = [
            //'depcode' => 'รหัสหน่วยงาน',
            'cid' => 'หมายเลขประจำตัวประชาชน',
            'name' => 'ชื่อ',
            'lname' => 'นามสกุล',
        ];
        return array_merge($label, parent::attributeLabels());
    }

    public function validateIdCard() {
        $id = str_split(str_replace('-', '', $this->cid)); //ตัดรูปแบบและเอา ตัวอักษร ไปแยกเป็น array $id
        $sum = 0;
        $total = 0;
        $digi = 13;

        for ($i = 0; $i < 12; $i++) {
            $sum = $sum + (intval($id[$i]) * $digi);
            $digi--;
        }
        $total = (11 - ($sum % 11)) % 10;

        if ($total != $id[12]) { //ตัวที่ 13 มีค่าไม่เท่ากับผลรวมจากการคำนวณ ให้ add error
            $this->addError('cid', 'หมายเลขบัตรประชาชนไม่ถูกต้อง');
        }
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_cid' => 'cid']);
    }

    public function getFullname() {
        return $this->name . ' ' . $this->lname;
    }

}
