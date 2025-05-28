<?php

namespace app\models;

use app\models\ExtProfile;
use dektrium\user\models\RegistrationForm as BaseRegistrationForm;
use dektrium\user\models\User;

class ExtRegistrationForm extends BaseRegistrationForm {

    /**
     * Add a new field
     * @var string
     */
    public $name;
    public $lname;
    public $passwordconfirm;
    public $emailconfirm;
    public $cid;
    public $captcha;

    #public $status;

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = parent::rules();

        $rules['passwordconfirmRequired'] = ['passwordconfirm', 'required'];
        $rules['passwordCompare'] = ['passwordconfirm', 'compare', 'compareAttribute' => 'password'];

        $rules['nameRequired'] = ['name', 'required'];
        $rules['nameLength'] = ['name', 'string', 'max' => 255];
        $rules['lnameRequired'] = ['lname', 'required'];
        $rules['lnameLength'] = ['lname', 'string', 'max' => 255];

        $rules['cidRequired'] = ['cid', 'required'];
        $rules['cidLength'] = ['cid', 'validateIdCard'];

        $rules['emailconfirmRequired'] = ['emailconfirm', 'required'];
        $rules['emailCompare'] = ['emailconfirm', 'compare', 'compareAttribute' => 'email'];

        //$rules['captchaRequired'] = ['captcha', 'required'];
        //$rules['captcha'] = ['captcha', 'captcha'];


        $rules['usernameRequired'] = ['username', 'required'];
        #$rules['usernameLength'] = ['username', 'validateFormatUsername'];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $labels = parent::attributeLabels();

        $labels['depcode'] = \Yii::t('user', 'หน่วยงาน(ส่วนกลางระบุ 00000)');
        $labels['passwordconfirm'] = \Yii::t('user', 'ยืนยันรหัสผ่านอีกครั้ง');

        $labels['username'] = \Yii::t('user', 'Username(ชื่อผู้ใช้งาน)(ภาษาอังกฤษ)');
        $labels['password'] = \Yii::t('user', 'Password(รหัสผ่าน)');
        $labels['email'] = \Yii::t('user', 'อีเมลล์');
        $labels['emailconfirm'] = \Yii::t('user', 'ยืนยันอีเมลล์อีกครั้ง');

        #$labels['pname'] = \Yii::t('user', 'คำนำหน้า(เช่น นาย,นาง,นส.)');
        $labels['name'] = \Yii::t('user', 'ชื่อ(ภาษาไทย)');
        $labels['lname'] = \Yii::t('user', 'นามสกุล(ภาษาไทย)');
        #$labels['position_name'] = \Yii::t('user', 'ตำแหน่ง(เช่น นักวิชาการคอม)');
        #$labels['position_level'] = \Yii::t('user', 'ระดับ(เช่น ชำนาญการ)');
        #$labels['telephone'] = \Yii::t('user', 'เบอร์โทรศัพท์หน่วยงาน');
        #$labels['mobilephone'] = \Yii::t('user', 'เบอร์โทรศัพท์มือถือ(ส่วนตัว)');

        $labels['cid'] = \Yii::t('user', 'หมายเลขบัตรประชาชน');

        return $labels;
    }

    /**
     * @inheritdoc
     */
    public function loadAttributes(User $user) {
// here is the magic happens
        $user->setAttributes([
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
        ]);
        /** @var Profile $profile */
        $profile = \Yii::createObject(ExtProfile::className());

        $profile->setAttributes([
            #'depcode' => $this->depcode,
            #'pname' => $this->name,
            'name' => $this->name,
            'lname' => $this->lname,
            'cid' => $this->cid,
                #'position_name' => $this->position_name,
                #'telephone' => $this->telephone,
                #'mobilephone' => $this->mobilephone,
        ]);

        $user->setProfile($profile);
    }

    public function validateFormatUsername() {
        $val = @explode('@', $this->username);
        @list($username, $depcode) = $val;

        if (count($val) != 2) {
            $this->addError('username', 'รูปแบบ Username ไม่ถูกต้อง ที่ถูกต้อง username@depcode เช่น sila.kl@00056');
        }

        if ($depcode != $this->depcode) { //ตรวจสอบการเลือกหน่วยบริการ ตรงกับ user ที่กำหนดเองหรือไม่
            $this->addError('username', 'คุณใส่รหัสสถานบริการไม่ตรงกับที่เลือกไว้ด้านล่างคือ ' . $this->depcode);
        }

        if (empty($username) && empty($depcode)) {
            $this->addError('username', 'รูปแบบ Username ไม่ถูกต้อง ที่ถูกต้อง เช่น sila.kl@00056');
        }
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

}
