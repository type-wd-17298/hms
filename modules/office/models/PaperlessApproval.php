<?php

namespace app\modules\office\models;

use Yii;
use mdm\autonumber\AutoNumber;
use app\components\Ccomponent;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\db\Expression;

class PaperlessApproval extends \yii\db\ActiveRecord {

    const UPLOAD_FOLDER = '../data/files'; //path files data

    public $pcheck;
    public $cc;
    public $photo_upload;

    public static function tableName() {
        return 'paperless_approval';
    }

    public function rules() {
        return [
            [['organized', 'paperless_ref_id', 'topic', 'place', 'approval_day', 'startdate', 'enddate', 'travelby', 'withdraw', 'approval_type_id', 'develop_id'], 'required'],
            [['approval_day', 'employee_own_id', 'employee_dep_id', 'active', 'approval_type_id'], 'integer'],
            [['employee_id', 'startdate', 'enddate', 'create_at', 'update_at', 'approval_status_id', 'approval_costs', 'develop_id', 'withdraw',], 'safe'],
            [['approval_id', 'driver', 'paperless_ref_id'], 'string', 'max' => 20],
            [['topic', 'place', 'organized'], 'string', 'max' => 255],
            [['approval_lastprocess_id', 'travelby', 'vehicle_personal', 'withdraw_from'], 'string'],
            [['vehicle_public'], 'string', 'max' => 1],
            ['withdraw_from', 'required', 'when' => function ($model) { //ถ้าเลือกขอเบิก จะต้องระบุว่าเบิกจากไหน
                    return $model->withdraw == 4;
                }],
            ['driver', 'required', 'when' => function ($model) { //ถ้าเลือกรถ ส่วนกลางต้องกรอกคนขับรถด้วย
                    return in_array($model->travelby, [1]);
                }],
            ['vehicle_personal', 'required', 'when' => function ($model) { //ถ้าเลือกรถ ส่วนบุคคลต้องกรอกคนขับรถด้วย
                    return in_array($model->travelby, [2]);
                }],
            [['approval_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'approval_id' => 'Approval ID',
            'topic' => 'ขออนุญาตไปราชการเพื่อ',
            'place' => 'ณ',
            'approval_day' => 'มีกำหนด(วัน)',
            'employee_id' => 'พร้อมเจ้าหน้าที่',
            'employee_own_id' => 'ผู้เขียนไปราชการ',
            'employee_dep_id' => 'หน่วยงาน',
            'driver' => 'พนักงานขับรถ',
            'travelby' => 'เดินทางโดย',
            //'vehicle_public' => 'รถยนต์ส่วนกลาง',
            'vehicle_personal' => 'รถยนต์ส่วนบุคคล หมายเลขทะเบียน',
            'startdate' => 'ตั้งแต่วันที่',
            'enddate' => 'ถึงวันที่',
            'approval_status_id' => 'Status ID',
            'laststatus_id' => 'Laststatus ID',
            'withdraw' => 'ค่าใช้จ่ายเบิกจาก',
            'withdraw_from' => 'ขอเบิกจาก',
            'active' => 'Active',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'paperless_ref_id' => 'เอกสารอ้างอิง',
            'approval_costs' => 'ประมาณค่าใช้จ่าย',
            'organized' => 'จัดโดย',
            'approval_type_id' => 'ประเภทการไป',
            'develop_id' => 'สมรรถนะที่ได้รับ',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
            $this->approval_id = AutoNumber::generate('APV' . (date('Y') + 543) . '-?????');
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    public function getPaper() {
        return $this->hasOne(PaperlessOfficial::className(), ['paperless_id' => 'paperless_ref_id']);
    }

    public function getType() {
        return $this->hasOne(PaperlessApprovalType::className(), ['vehicle_id' => 'travelby']);
    }

    public function getBudget() {
        return $this->hasOne(PaperlessApprovalBudget::className(), ['budget_id' => 'withdraw']);
    }

    public function getDepFrom() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'employee_dep_id']);
    }

    public function getApprovalStatus() {
        return $this->hasOne(ApprovalStatus::className(), ['approval_status_id' => 'approval_status_id']);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_own_id']);
    }

    public function getEmps() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_own_id']);
    }

    public function getLastProcess() {
        return @ApprovalProcessList::find()->where(['processlist_id' => $this->approval_lastprocess_id])->one();
    }

    public function getProcessA1() {
        return @ApprovalProcessList::find()->where(['AND', ['approval_id' => $this->approval_id], ['IN', 'approval_status_id', ['A01', 'A02', 'A04']]])->one();
    }

    public function getProcessA2() {
        return @ApprovalProcessList::find()->where(['approval_id' => $this->approval_id, 'approval_status_id' => 'A04'])->one();
    }

    public function getProcessA5() {
        return @ApprovalProcessList::find()->where(['approval_id' => $this->approval_id, 'approval_status_id' => 'A06'])->one();
    }

    public function getProcessA6() {
        return @ApprovalProcessList::find()->where(['approval_id' => $this->approval_id, 'approval_status_id' => 'A10'])->one();
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

}
