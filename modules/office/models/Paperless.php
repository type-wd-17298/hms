<?php

namespace app\modules\office\models;

use Yii;
use yii\helpers\Url;
use mdm\autonumber\AutoNumber;
use app\components\Ccomponent;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

class Paperless extends \yii\db\ActiveRecord {

    const UPLOAD_FOLDER = '../data/files';

    public $photo_upload;
    public $pcheck; //จัดเรียงความสำคัญใน gridview

    public static function tableName() {
        return 'paperless';
    }

    public function rules() {
        return [
            [['employee_owner_id', 'paperless_command_id', 'paperless_to', 'paperless_from', 'paperless_detail', 'paperless_level_id', 'paperless_topic'], 'required'],
            [['employee_owner_id', 'paperless_level_id', 'paperless_command_id'], 'safe'],
            [['paperless_direct', 'paperless_uuid', 'paperless_date', 'create_at', 'paperless_id', 'update_at', 'pcheck'], 'safe'],
            [['budgetyear'], 'string', 'max' => 4],
            [['paperless_status_id'], 'safe'],
            [['paperless_to', 'paperless_number', 'paperless_detail', 'employee_dep_id', 'employee_id', 'paperless_topic', 'paperless_from'], 'safe'],
            [['paperless_uuid'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'paperless_id' => 'ID',
            'employee_dep_id' => 'หน่วยงาน',
            'paperless_date' => 'วันที่่',
            'budgetyear' => 'ปีงบประมาณ',
            'paperless_topic' => 'เรื่อง',
            'paperless_from' => 'ส่วนราชการ',
            'paperless_number' => 'เลขที่หนังสือ',
            'paperless_detail' => 'รายละเอียด',
            'paperless_level_id' => 'ความเร่งด่วน',
            'paperless_command_id' => 'ความมุ่งหมาย',
            'employee_owner_id' => 'เจ้าของเรื่อง',
            'paperless_uuid' => 'UUID',
            'pcheck' => 'pcheck',
            'paperless_to' => 'เรียน',
            'paperless_direct' => 'ส่งานเลขา',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
            $this->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
            $this->employee_dep_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $this->paperless_id = 'BNN' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }
        //จะได้เลขหนังสือก็ต่อเมื่อเสนอหนังสือแล้วเท่านั้น
        if (empty($this->paperless_number) && $this->paperless_status_id <> 'F01') {
            $this->paperless_number = AutoNumber::generate('BNN' . (date('Y') + 543) . '-?????');
        }

        return parent::beforeSave($insert);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getLastProcess() {
        return @PaperlessProcessList::find()->where(['paperless_id' => $this->paperless_id])->orderBy(['processlist_id' => SORT_DESC])->limit(1)->one();
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

    public function getUseTime2() {
        $f = 'MIN(process_create)';
        $s = 'IFNULL(MAX(process_create),MAX(process_acknowledge))';
        return PaperlessProcessList::find()
                        ->select(["TIMEDIFF($f,$s) AS tt"])
                        ->where(['paperless_id' => $this->paperless_id])->scalar();
    }

    public function getUseTime() {
        $f = 'MIN(process_create)';
        $s = 'IF(paperless_status_id = "FF",IFNULL(MAX(process_create),MAX(process_acknowledge)),NOW())';
        return PaperlessProcessList::find()
                        ->select(["CONCAT(IF(TIMESTAMPDIFF(DAY,$s,$f) <> 0,CONCAT(TIMESTAMPDIFF(DAY,$f,$s),' วัน '),'')
                ,IF(MOD(TIMESTAMPDIFF(HOUR,$s,$f), 24) <> 0,CONCAT(MOD(TIMESTAMPDIFF(HOUR,$f,$s), 24),' ชม. '),'')
                ,IF(MOD(TIMESTAMPDIFF(MINUTE,$s,$f), 60) <> 0,CONCAT(MOD(TIMESTAMPDIFF(MINUTE,$f,$s), 60),' น. '),'')
                ) AS usetime"])
                        ->where(['paperless_id' => $this->paperless_id])->scalar();
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

    public function getBn() {//เลขที่หนังสือ
        return $this->paperless_number;
    }

    public function getFm() {//เลขที่หนังสือ
        return $this->depFrom->employee_dep_label;
    }

}
