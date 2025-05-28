<?php

namespace app\modules\office\models;

use Yii;
use app\modules\hr\models\Employee;
use app\components\Ccomponent;
use app\modules\office\models\Uploads;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\db\Expression;

class LeaveMain extends \yii\db\ActiveRecord {

    public $rankdate;
    public $pcheck;
    public $cc;

    const UPLOAD_FOLDER = '../data/files'; //path files data

    public $photo_upload;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'leave_main';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    /*
      public static function getDb() {
      return Yii::$app->get('db_hr');
      }
     */

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['leave_start', 'leave_end', 'leave_type_id', 'employee_id', 'leave_day'], 'required'],
            //[['leave_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
            [['leave_assign', 'leave_type_id', 'employee_id', 'employee_dep_id', 'leave_cancel_id'], 'integer'],
            [['leave_day'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/', 'min' => 0.5, 'max' => 180],
            [['update_at', 'create_at', 'leave_type_time', 'rankdate', 'leave_create', 'leave_start', 'leave_end', 'leave_date_approved', 'leave_date_disapproved', 'leave_status_id', 'leave_lastprocess_id', 'leave_day'], 'safe'],
            [['leave_detail', 'leave_address', 'leave_file', 'leave_comment'], 'string'],
            [['leave_type_id', 'employee_id', 'leave_start', 'leave_end', 'leave_type_time'], 'unique', 'targetAttribute' => ['leave_type_id', 'employee_id', 'leave_start', 'leave_end', 'leave_type_time']],
            ['leave_assign', 'required', 'when' => function ($model) {
                    return $model->leave_type_id == 1;
                }],
            ['leave_detail', 'required', 'when' => function ($model) {
                    return in_array($model->leave_type_id, [2, 4, 9]);
                }],
            ['leave_detail', 'required', 'message' => 'ไม่สามารถบันทึกข้อมูลได้เนื่องจาก คุณมีวันลาพักผ่อนไม่พอ ', 'when' => function ($model) {
                    if ($model->leave_type_id == 1 && in_array($model->leave_status_id, ['L00'])) {
                        $emp = Employee::find()
                                ->where(['employee.employee_id' => $model->employee_id, 'employee_status' => 1, 'budgetYear' => (Yii::$app->params['budgetYear'] + 543)])
                                ->joinWith(['empLeave'])
                                ->one();
                        $datetime2 = new \Datetime($model->leave_start);
                        $datetime1 = new \Datetime($model->leave_end);
                        $interval = Ccomponent::getWeekdayDifference($datetime2, $datetime1);
                        if ($model->leave_day == '') {
                            $day = $interval;
                        } else {
                            $day = $model->leave_day;
                        }
                        return ($emp->empLeave->accrued - $day) < 0;
                    }
                    return false;
                }],
            ['photo_upload', 'required', 'message' => 'คุณต้องแนบเอกสารไฟล์แนบที่เป็น PDF เนื่องจากลาเกิน 3 วัน', 'when' => function ($model) {
                    $datetime2 = new \Datetime($model->leave_start);
                    $datetime1 = new \Datetime($model->leave_end);
                    $interval = Ccomponent::getWeekdayDifference($datetime2, $datetime1);
                    $datas = 0;
                    if ($model->isNewRecord) {
                        //$pdf = UploadedFile::getInstancesByName('LeaveMain[leave_file]');
                        $pdf = UploadedFile::getInstancesByName('upload_ajax');
                        if (count($pdf) > 0) {
                            $datas = 1;
                        } else {
                            $datas = 0;
                        }
                    } else {
                        $datas = Uploads::find()->where(['ref' => 'L' . $model->leave_id])->count();
                        if ($datas > 0) {
                            $datas = 1;
                        } else {
                            $pdf = UploadedFile::getInstancesByName('upload_ajax');
                            if (count($pdf) > 0) {
                                print_r($pdf);
                                $datas = 1;
                            } else {
                                $datas = 0;
                            }
                        }
                    }
                    return ($interval >= 3) && in_array($model->leave_type_id, [3, 4]) && $datas < 1;
                }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'leave_id' => 'รหัส',
            'leave_type_id' => 'ประเภทการลา',
            'employee_id' => 'ชื่อ-สกุล',
            'employee_dep_id' => 'หน่วยงาน',
            'leave_status_id' => 'สถานะ',
            'leave_create' => 'วันที่ทำรายการ',
            'leave_start' => 'วันที่เริ่มต้น',
            'leave_end' => 'วันที่สิ้นสุด',
            'leave_date_approved' => 'วันที่อนุมัติ',
            'leave_date_disapproved' => 'วันที่ยกเลิก',
            'leave_detail' => 'รายละเอียด/เหตุผลการลา',
            'leave_address' => 'ติดต่อระหว่างการลา',
            'leave_file' => 'เอกสารแนบ',
            'leave_comment' => 'หมายเหตุ',
            'rankdate' => 'ตั้งแต่วันที่',
            'leave_assign' => 'ผู้รับมอบงาน',
            'leave_type_time' => 'แบบการลา',
            'create_at' => 'วันที่ทำรายการ',
            'leave_lastprocess_id' => 'leave_lastprocess_id',
            'leave_day' => 'จำนวนวันลา',
            'photo_upload' => 'เอกสารไฟล์แนบที่เป็น PDF',
            'leave_cancel_id' => 'leave_cancel_id'
        ];
    }

    public function getLeaveStatus() {
        return $this->hasOne(LeaveStatus::className(), ['leave_status_id' => 'leave_status_id']);
    }

    public function getEmps() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getLeave() {
        return $this->hasOne(LeaveMain::className(), ['leave_id' => 'leave_cancel_id']);
    }

    public function getLeaveType() {
        return $this->hasOne(LeaveType::className(), ['leave_type_id' => 'leave_type_id']);
    }

    public function beforeSave($insert) {
        //$this->leave_file = '';
        if ($this->isNewRecord) {
            #$cid = \yii::$app->user->identity->profile->cid;
            #$staff = Employee::find()->where(['employee_cid' => $cid])->one();
            //$this->employee_dep_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
            $this->create_at = new \yii\db\Expression('NOW()');
            if (!empty($this->leave_type_time) && $this->leave_type_time <> 'F') {
                $this->leave_end = $this->leave_start;
            }
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }
        /*
          $datetime2 = new \Datetime($this->leave_start);
          $datetime1 = new \Datetime($this->leave_end);
          $interval = Ccomponent::getWeekdayDifference($datetime2, $datetime1);
         */
        if ($this->leave_type_time <> 'F') {
            $this->leave_day = 0.5;
        } else {
            //$this->leave_day = $interval;
        }
        return parent::beforeSave($insert);
    }

    public function getLeaveAssign() {
        return Employee::findOne($this->leave_assign);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getLeaveLevel($level) {
        return LeaveSignature::find()->where(['leave_id' => $this->leave_id, 'leave_level' => $level])->one();
    }

    public function getLastProcess() {
        return @LeaveProcessList::find()->where(['processlist_id' => $this->leave_lastprocess_id])->one();
    }

    public function getProcessStaff() {
        return @LeaveProcessList::find()->where(['leave_id' => $this->leave_id, 'leave_status_id' => 'L04'])->one();
    }

    public function getProcessL1() {
        return @LeaveProcessList::find()->where(['leave_id' => $this->leave_id, 'leave_status_id' => 'L02'])->one();
    }

    public function getProcessL3() {
        return @LeaveProcessList::find()->where(['leave_id' => $this->leave_id, 'leave_status_id' => 'L03'])->one();
    }

    public function getProcessExcutive() {
        return @LeaveProcessList::find()->where(['leave_id' => $this->leave_id, 'leave_status_id' => 'L10'])->one();
    }

    public function getProcessStaffExcutive() {
        return @LeaveProcessList::find()->where(['leave_id' => $this->leave_id])->one();
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

    /*
      public function getAccruedFF($type = 1) { //วันลาอยู่ระหว่างดำเนินการ
      $budgetYear = (in_array(date('m'), ['10', '11', '12']) ? Yii::$app->params['budgetYear'] : Yii::$app->params['budgetYear'] - 1);
      $model = @LeaveMain::find()
      ->select(['SUM(leave_day) AS cc'])
      ->where(['AND', ['employee_id' => $this->employee_id],
      ['leave_type_id' => $type],
      new Expression(" leave_start < '{$this->leave_start}' "),
      new Expression(" leave_start >= '{$budgetYear}-10-01' "),
      ['NOT IN', 'leave_status_id', ['L10', 'L00', 'L08', 'L09']
      ]])->cache(600)->one();
      return @$model['cc'];
      }
     */
    /*
      public function getAccruedWait($type = 1) { //วันลาอยู่ระหว่างดำเนินการ
      $budgetYear = (in_array(date('m'), ['10', '11', '12']) ? Yii::$app->params['budgetYear'] : Yii::$app->params['budgetYear'] - 1);
      $model = @LeaveMain::find()
      ->select(['SUM(leave_day) AS cc'])
      ->where(['AND', ['employee_id' => $this->employee_id],
      ['leave_type_id' => $type],
      //new Expression(" leave_start < '{$this->leave_start}' "),
      new Expression(" leave_start >= '{$budgetYear}-10-01' "),
      ['NOT IN', 'leave_status_id', ['L10', 'L00', 'L08', 'L09']
      ]])->cache(600)->one();
      return @$model['cc'];
      }
     */

    public function getAccruedCC($type = 1) { //ตัดวันลา
        $budgetYear = \Yii::$app->params['budgetYear'];
        $budgetYear_start = ($budgetYear - 1) . "-10-01";
        $budgetYear_end = ($budgetYear) . "-09-30";

        //$budgetYear = (in_array(date('m'), ['10', '11', '12']) ? Yii::$app->params['budgetYear'] : Yii::$app->params['budgetYear'] - 1);
        $model = @LeaveMain::find()
                        ->select(['SUM(leave_day) AS cc'])
                        ->where(['AND', ['employee_id' => $this->employee_id],
                            ['leave_type_id' => $type],
                            //new Expression(" leave_start < '{$this->leave_start}' "),
                            new Expression(" leave_start BETWEEN '{$budgetYear_start}' AND '{$budgetYear_end}' "),
                            ['IN', 'leave_status_id', ['L10']
                    ]])->one();
        return @$model['cc'];
    }

    public function getAccruedSS($type = 1) { //วันลาสะสม
        $budgetYear = \Yii::$app->params['budgetYear'];
        $budgetYear_start = ($budgetYear - 1) . "-10-01";
        $budgetYear_end = ($budgetYear) . "-09-30";
        //$budgetYear = (in_array(date('m'), ['10', '11', '12']) ? Yii::$app->params['budgetYear'] : Yii::$app->params['budgetYear'] - 1);
        $model = @LeaveMain::find()
                        ->select(['SUM(leave_day) AS cc'])
                        ->where(['AND', ['employee_id' => $this->employee_id],
                            ['leave_type_id' => $type],
                            new Expression(" leave_start < '{$this->leave_start}' "),
                            new Expression(" leave_start BETWEEN '{$budgetYear_start}' AND '{$budgetYear_end}'  "),
                            ['NOT IN', 'leave_status_id', ['L00', 'L08', 'L09']
                    ]])->one();

        $modelCancel = @LeaveMain::find()
                        ->select(['SUM(leave_day) AS cc'])
                        ->where(['AND', ['employee_id' => $this->employee_id],
                            ['leave_type_id' => 9],
                            new Expression(" leave_start BETWEEN '{$budgetYear_start}' AND '{$budgetYear_end}'  "),
                            ['NOT IN', 'leave_status_id', ['L00', 'L08', 'L09']],
                            new Expression(" (SELECT 1 FROM leave_main m WHERE m.leave_id = leave_cancel_id AND m.leave_type_id = 1) "),
                        ])->one(); //นับรวมยกเลิกวันลา

        $cc = LeaveAccumulate::findOne(['employee_id' => $this->employee_id, 'budgetyear' => ($budgetYear + 543)]);
        return (@$model['cc'] + @$cc->vacation_leave) - @$modelCancel['cc']; //vacation_leave => เป็นวันลาพักผ่อนของเก่าที่ยกยอดมา
    }

    public function getVacationSS() { //วันลาสะสม
        $budgetYear = \Yii::$app->params['budgetYear'];
        $budgetYear_start = ($budgetYear - 1) . "-10-01";
        $budgetYear_end = ($budgetYear) . "-09-30";
        //$budgetYear = (in_array(date('m'), ['10', '11', '12']) ? Yii::$app->params['budgetYear'] : Yii::$app->params['budgetYear'] - 1);
        $model = @LeaveMain::find()
                        ->select(['SUM(leave_day) AS cc'])
                        ->where(['AND', ['employee_id' => $this->employee_id],
                            ['leave_type_id' => 1],
                            new Expression(" leave_start BETWEEN '{$budgetYear_start}' AND '{$budgetYear_end}'  "),
                            ['NOT IN', 'leave_status_id', ['L00', 'L08', 'L09']
                    ]])->one();

        $modelCancel = @LeaveMain::find()
                        ->select(['SUM(leave_day) AS cc'])
                        ->from('leave_main l')
                        ->where(['AND', ['employee_id' => $this->employee_id],
                            ['leave_type_id' => 9],
                            ['NOT IN', 'leave_status_id', ['L00', 'L08', 'L09']],
                            new Expression(" leave_start BETWEEN '{$budgetYear_start}' AND '{$budgetYear_end}'  "),
                            new Expression(" (SELECT 1 FROM leave_main m WHERE m.leave_id = l.leave_cancel_id AND m.leave_type_id = 1) "),
                        ])->one(); //นับรวมยกเลิกวันลา

        $cc = LeaveAccumulate::findOne(['employee_id' => $this->employee_id, 'budgetyear' => ($budgetYear + 543)]);
        return number_format((@$model['cc'] + $cc->vacation_leave) - @$modelCancel['cc'], 1); //vacation_leave => เป็นวันลาพักผ่อนของเก่าที่ยกยอดมา
        //return (@$cc->vacation_leave); //vacation_leave => เป็นวันลาพักผ่อนของเก่าที่ยกยอดมา
    }

    public function getAccruedCancel($type = 1) { //ยกเลิกวันลาสะสม
        $budgetYear = \Yii::$app->params['budgetYear'];
        $budgetYear_start = ($budgetYear - 1) . "-10-01";
        $budgetYear_end = ($budgetYear) . "-09-30";
        //$budgetYear = (in_array(date('m'), ['10', '11', '12']) ? Yii::$app->params['budgetYear'] : Yii::$app->params['budgetYear'] - 1);
        $model = @LeaveMain::find()
                        ->select(['SUM(leave_day) AS cc'])
                        ->from('leave_main l')
                        ->where(['AND', ['employee_id' => $this->employee_id],
                            ['leave_type_id' => 9],
                            new Expression(" leave_start BETWEEN '{$budgetYear_start}' AND '{$budgetYear_end}'  "),
                            ['NOT IN', 'leave_status_id', ['L00', 'L08', 'L09']],
                            new Expression(" (SELECT 1 FROM leave_main m WHERE m.leave_id = l.leave_cancel_id AND m.leave_type_id = '$type') "),
                        ])->one(); //นับรวมยกเลิกวันลา

        $cc = LeaveAccumulate::findOne(['employee_id' => $this->employee_id, 'budgetyear' => ($budgetYear + 543)]);
        return @$model['cc'] + @$cc->cancel_leave; //vacation_leave => เป็นวันลาพักผ่อนของเก่าที่ยกยอดมา
    }

    public function getLeaveSS($type) { //วันลาสะสม
        $budgetYear = \Yii::$app->params['budgetYear'];
        $budgetYear_start = ($budgetYear - 1) . "-10-01";
        $budgetYear_end = ($budgetYear) . "-09-30";
        switch ($type) {
            case 'personal_leave':
                $typeID = 2;
                break;
            case 'sick_leave':
                $typeID = 4;
                break;
            case 'maternity_leave':
                $typeID = 3;
                break;
            default:
                break;
        }

        //$budgetYear = (in_array(date('m'), ['10', '11', '12']) ? Yii::$app->params['budgetYear'] : Yii::$app->params['budgetYear'] - 1);
        $model = @LeaveMain::find()
                        ->select(['SUM(leave_day) AS cc'])
                        ->where(['AND', ['employee_id' => $this->employee_id],
                            ['leave_type_id' => $typeID],
                            new Expression(" leave_start BETWEEN '{$budgetYear_start}' AND '{$budgetYear_end}'  "),
                            ['NOT IN', 'leave_status_id', ['L00', 'L08', 'L09']
                    ]])->one();

        $modelCancel = @LeaveMain::find()
                        ->select(['SUM(leave_day) AS cc'])
                        ->from('leave_main l')
                        ->where(['AND', ['employee_id' => $this->employee_id],
                            ['leave_type_id' => 9],
                            ['NOT IN', 'leave_status_id', ['L00', 'L08', 'L09']],
                            new Expression(" leave_start BETWEEN '{$budgetYear_start}' AND '{$budgetYear_end}'  "),
                            new Expression(" (SELECT 1 FROM leave_main m WHERE m.leave_id = l.leave_cancel_id AND m.leave_type_id = $typeID) "),
                        ])->one(); //นับรวมยกเลิกวันลา

        $cc = LeaveAccumulate::findOne(['employee_id' => $this->employee_id, 'budgetyear' => ($budgetYear + 543)]);
        return (@$model['cc'] + @$cc->$type) - @$modelCancel['cc'];
    }

}
