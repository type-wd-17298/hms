<?php

namespace app\modules\office\models;

use Yii;
use yii\helpers\Url;
use mdm\autonumber\AutoNumber;
use app\components\Ccomponent;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

class PaperlessOfficial extends \yii\db\ActiveRecord {

    const UPLOAD_FOLDER = '../data/files'; //path files data

    public $photo_upload;
    public $pcheck; //จัดเรียงความสำคัญใน gridview

    public static function tableName() {
        return 'paperless_official';
    }

    public function rules() {
        return [
            [['paperless_topic', 'employee_dep_id'], 'required'],
            [['paperless_official_date', 'create_at', 'update_at', 'paperless_official_order'], 'safe'],
            [['paperless_status_id', 'paperless_official_detail', 'paperless_lastprocess_id',], 'string'],
            [['paperless_official_status', 'paperless_level_id', 'paperless_command_id', 'employee_owner_id'], 'integer'],
            [['paperless_id', 'employee_dep_id', 'employee_id'], 'string', 'max' => 20],
            [['paperless_official_uuid'], 'string', 'max' => 128],
            [['paperless_official_from'], 'string', 'max' => 100],
            [['paperless_topic', 'paperless_official_qrcode'], 'string', 'max' => 255],
            [['budgetyear'], 'string', 'max' => 4],
            [['paperless_official_type'], 'string', 'max' => 3],
            [['paperless_official_number', 'paperless_official_booknumber'], 'string', 'max' => 50],
            [['paperless_id'], 'unique'],
            [['paperless_official_booknumber', 'paperless_official_from', 'paperless_official_date', 'paperless_topic', 'employee_dep_id'], 'required', 'on' => 'BRN'],
            [['paperless_official_from', 'paperless_topic', 'employee_dep_id'], 'required', 'on' => 'BSN'],
        ];
    }

    public function scenarios() {
        $sn = parent::scenarios();
        $sn['BRN'] = ['paperless_lastprocess_id', 'paperless_official_qrcode', 'paperless_official_detail', 'paperless_official_from', 'paperless_official_number', 'paperless_official_booknumber', 'paperless_official_type', 'paperless_topic', 'employee_dep_id', 'employee_id', 'paperless_official_date', 'create_at', 'update_at'];
        $sn['BSN'] = ['paperless_lastprocess_id', 'paperless_official_qrcode', 'paperless_official_detail', 'paperless_official_from', 'paperless_official_number', 'paperless_official_booknumber', 'paperless_official_type', 'paperless_topic', 'employee_dep_id', 'employee_id', 'paperless_official_date', 'create_at', 'update_at'];
        $sn['BAN'] = ['paperless_lastprocess_id', 'paperless_official_qrcode', 'paperless_official_detail', 'paperless_official_from', 'paperless_official_number', 'paperless_official_booknumber', 'paperless_official_type', 'paperless_topic', 'employee_dep_id', 'employee_id', 'paperless_official_date', 'create_at', 'update_at'];
        $sn['BON'] = ['paperless_lastprocess_id', 'paperless_official_order', 'paperless_official_qrcode', 'paperless_official_detail', 'paperless_official_from', 'paperless_official_number', 'paperless_official_booknumber', 'paperless_official_type', 'paperless_topic', 'employee_dep_id', 'employee_id', 'paperless_official_date', 'create_at', 'update_at'];
        $sn['BCN'] = ['paperless_lastprocess_id', 'paperless_official_qrcode', 'paperless_official_detail', 'paperless_official_from', 'paperless_official_number', 'paperless_official_booknumber', 'paperless_official_type', 'paperless_topic', 'employee_dep_id', 'employee_id', 'paperless_official_date', 'create_at', 'update_at'];

        return $sn;
    }

    public function attributeLabels() {
        return [
            'paperless_id' => 'Paperless Official ID',
            'paperless_official_uuid' => 'UUID',
            'paperless_official_date' => 'วันที่หนังสือ',
            'paperless_official_from' => 'จากหน่วยงาน',
            'paperless_topic' => 'เรื่อง',
            'budgetyear' => 'ปีงบประมาณ',
            'paperless_official_number' => 'เลขที่หนังสือลงรับ',
            'paperless_official_booknumber' => 'เลขที่หนังสือ',
            'paperless_official_detail' => 'รายละเอียด',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'paperless_official_status' => 'สถานะการใช้งาน',
            'employee_dep_id' => 'ถึงกลุ่มงาน',
            'employee_id' => 'เจ้าหน้าที่ผู้บันทึก',
            'paperless_level_id' => 'ประเภทความเร่งด่วน',
            'paperless_status_id' => 'สถานะเอกสาร',
            'paperless_command_id' => 'ความมุ่งหมาย',
            'employee_owner_id' => 'เจ้าของเรื่อง',
            'paperless_official_qrcode' => 'เอกสารแนบ QRCODE',
            'paperless_official_type' => 'ประเภทหนังสือ', // BRN ทะเบียนรับ   BSN ทะเบียนส่ง
            'paperless_official_order' => 'วันที่ออกคำสั่ง'
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
            $this->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
            $this->employee_owner_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
            if ($this->paperless_official_type == 'BRN')
                $this->paperless_official_number = AutoNumber::generate($this->paperless_official_type . (date('Y') + 543) . '-?????');

            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $this->paperless_id = $this->paperless_official_type . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    public static function getUploadPath() {
        return Yii::getAlias('@webroot') . '/' . self::UPLOAD_FOLDER . '/';
    }

    public static function getUploadUrl() {
        return Url::base(true) . '/' . self::UPLOAD_FOLDER . '/';
    }

    public function getThumbnails($ref, $event_name = '') {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $preview = [];
        foreach ($uploadFiles as $file) {
            $preview[] = [
                'url' => self::getUploadUrl(true) . $ref . '/' . $file->real_filename,
                'src' => self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename,
                'options' => ['title' => $event_name, 'class' => 'img-responsive']
            ];
        }
        return $preview;
    }

    public function getUrlView($ref) {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $preview = '';
        foreach ($uploadFiles as $file) {
            $preview = self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename;
        }
        return $preview;
    }

    public function getThumbnailsView($ref) {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $preview = '';
        foreach ($uploadFiles as $file) {
            $preview .= '<div class="pull-left">' . Html::a(Html::img(self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename, ['width' => 50, 'class' => 'img-thumbnail']), self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename, ['data-fancybox' => true]) . '</div>';
        }
        return $preview;
    }

    public function getThumbnailsViewOne() {
        $ref = $this->product_code;
        $file = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->one();
        $preview = '';
        if ($file) {
#foreach ($uploadFiles as $file) {
            $preview .= '<div class="pull-right">' . Html::img(self::getUploadUrl() . $ref . '/thumbnail/' . $file->real_filename, ['width' => 50, 'class' => 'img-thumbnail', 'data-fancybox' => true]) . '</div>';
#}
        }
        return $preview;
    }

    public function getPhoto($ref) {
        $file = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->one();
        return $file;
    }

    public function getUrlPdf($ref, $mod = 'p') {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $fileSrc = [];
        $path = self::getUploadUrl();
        if ($mod = 'u')
            $path = self::getUploadPath();

        foreach ($uploadFiles as $file) {
            if ($file->type == 'pdf')
                $fileSrc[] = $path . $ref . '/' . $file->real_filename;
        }
        return $fileSrc;
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getOwner() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_owner_id']);
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'employee_dep_id']);
    }

    public function getDepFrom() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'paperless_from']);
    }

    public function getLevel() {
        return $this->hasOne(PaperlessLevel::className(), ['paperless_level_id' => 'paperless_level_id']);
    }

    public function getStatus() {
        return $this->hasOne(PaperlessStatus::className(), ['paperless_status_id' => 'paperless_status_id']);
    }

    public function getCommand() {
        return $this->hasOne(PaperlessCommand::className(), ['paperless_command_id' => 'paperless_command_id']);
    }

    public function getView() {
        return $this->hasOne(PaperlessView::className(), ['paperless_paper_ref' => 'paperless_id']);
    }

    public function getLastProcess() {
        return @PaperlessProcessList::find()->where(['paperless_id' => $this->paperless_id])->orderBy(['processlist_id' => SORT_DESC])->limit(1)->one();
    }

    public function getLinkView() {
        return $this->hasOne(@PaperlessView::className(), ['paperless_paper_ref' => 'paperless_id']);
    }

    public function getBn() {//เลขที่หนังสือ
        return $this->paperless_official_booknumber;
    }

    public function getFm() {//เลขที่หนังสือ
        return $this->paperless_official_from;
    }

    public function getFullPaper() {//เลขที่หนังสือ
        return $this->paperless_official_booknumber . ' ' . $this->paperless_topic;
    }

    public function getFullPaper2() {//เลขที่หนังสือ
        return $this->paperless_official_booknumber . ' ลงวันที่ ' . Ccomponent::getThaiDate($this->paperless_official_date, 'L') . ' เรื่อง' . $this->paperless_topic;
    }

}
