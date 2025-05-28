<?php

namespace app\models;

use dektrium\user\models\User as BaseUser;

class ExtUser extends BaseUser {

    public $cid;
    public $depcode;
    public $name;
    public $lname;

    public function scenarios() {
        $scenarios = parent::scenarios();
        // add field to scenarios
        $scenarios['create'][] = 'depcode';
        $scenarios['create'][] = 'cid';
        $scenarios['create'][] = 'name';
        $scenarios['create'][] = 'lname';
        $scenarios['update'][] = 'depcode';
        $scenarios['update'][] = 'cid';
        $scenarios['update'][] = 'name';
        $scenarios['update'][] = 'lname';
        $scenarios['register'][] = 'depcode';
        $scenarios['register'][] = 'cid';
        $scenarios['register'][] = 'name';
        $scenarios['register'][] = 'lname';
        $scenarios['connect'][] = 'depcode';
        $scenarios['connect'][] = 'cid';
        $scenarios['connect'][] = 'name';
        $scenarios['connect'][] = 'lname';

        return $scenarios;
    }

    public function rules() {
        $rules = parent::rules();
        // add some rules
        #$rules['depcodeRequired'] = ['depcode', 'required'];
        #$rules['cidRequired'] = ['cid', 'required'];
        $rules['nameRequired'] = ['name', 'required'];
        $rules['lnameRequired'] = ['lname', 'required'];

        #$rules['depcodeLength'] = ['depcode', 'string', 'max' => 5];

        return $rules;
    }

    /*
      public function loadAttributes(User $user) {


      $profile = \Yii::createObject(ExtProfile::className());
      $profile->setAttributes([
      'depcode' => $this->depcode,
      'name' => $this->name,
      'lname' => $this->lname,
      'cid' => $this->cid,
      ]);
      $user->setProfile($profile);
      }
     */

    public function attributeLabels() {
        $label = [
            'depcode' => 'รหัสสถานบริการ',
            'cid' => 'หมายเลขประจำตัวประชาชน',
            'name' => 'ชื่อ',
            'lname' => 'นามสกุล',
        ];
        return array_merge($label, parent::attributeLabels());
    }

}
