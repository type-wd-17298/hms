<?php

namespace app\modules\project\models;

use Yii;
use mdm\autonumber\AutoNumber;
use app\models\ExtProfile;
use app\modules\hr\models\Employee;
use app\components\Ccomponent;

/**
 * This is the model class for table "app_project".
 *
 * @property int $project_id ลำดับที่โครงการ
 * @property string|null $project_book_number01
 * @property string|null $project_book_number02
 * @property string|null $project_book_number03
 * @property int $department_id หน่วยงาน
 * @property string $project_code เลขที่โครงการ
 * @property string|null $project_name ชื่อโครงการ
 * @property float $project_cost
 * @property int $project_type_id
 *
 * @property Cdepartment $department
 * @property ProjectContact $projectContact
 * @property ProjectPo[] $projectPos
 * @property ProjectType $projectType
 */
class Project extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_project;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%project}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_type_prefer_id', 'project_type_order_id', 'project_name', 'project_cost', 'project_type_id', 'project_company_id'], 'required'],
            [['project_type_prefer_id', 'project_buy_type', 'project_type_order_id', 'project_company_id', 'project_id', 'department_id', 'project_type_id', 'project_status'], 'integer'],
            [['project_code'], 'string', 'min' => 11, 'max' => 11],
            [['project_date', 'project_create', 'project_update'], 'safe'],
            [['project_cost'], 'number'],
            [['project_book_number00', 'project_book_number01', 'project_book_number02', 'project_book_number03'], 'string', 'max' => 20],
            [['project_name'], 'string', 'max' => 255],
            [['project_comment'], 'string'],
            [['project_id'], 'unique'],
            [['project_code'], 'unique'],
            [['project_book_number01'], 'unique'],
            [['project_book_number02'], 'unique'],
            [['project_book_number03'], 'unique'],
            [['project_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectType::class, 'targetAttribute' => ['project_type_id' => 'project_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'project_id' => 'ลำดับที่โครงการ',
            'project_book_number00' => 'อนุมัติหลักการ',
            'project_book_number01' => 'แต่งตั้ง คกร.',
            'project_book_number02' => 'รายงานขอซื้อ',
            'project_book_number03' => 'รายงานผล',
            'department_id' => 'หน่วยงาน',
            'project_code' => 'เลขที่โครงการ',
            'project_name' => 'ชื่อโครงการ',
            'project_cost' => 'จำนวนเงิน',
            'project_type_id' => 'ประเภทโครงการ',
            'project_staff' => 'ข้อมูลผู้ปฏิบัติ',
            'project_create' => 'Project Create',
            'project_update' => 'Project Update',
            'project_comment' => 'หมายเหตุ',
            'project_date' => 'วันที่ออกเลข',
            'project_company_id' => 'บริษัท',
            'project_type_order_id' => 'ประเภทการจัดซื้อ',
            'project_buy_type' => 'ประเภทการดำเนินการ',
            'project_type_prefer_id' => 'วิธีซื้อหรือจ้าง'
        ];
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getWhoRecord() {
        //return $this->hasOne(ExtProfile::className(), ['cid' => 'project_staff']);
        return $this->hasOne(Employee::className(), ['employee_cid' => 'project_staff']);
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment() {
        return $this->hasOne(Cdepartment::class, ['department_id' => 'department_id']);
    }

    /**
     * Gets query for [[ProjectContact]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectContract() {
        return $this->hasOne(ProjectContract::class, ['project_id' => 'project_id']);
    }

    /**
     * Gets query for [[ProjectPos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectPos() {
        return $this->hasMany(ProjectPo::class, ['project_id' => 'project_id']);
    }

    /**
     * Gets query for [[ProjectType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType() {
        return $this->hasOne(ProjectType::class, ['project_type_id' => 'project_type_id']);
    }

    public function getTypeOrder() {
        return $this->hasOne(ProjectTypeOrder::class, ['project_type_order_id' => 'project_type_order_id']);
    }

    public function getTypePrefer() {
        return $this->hasOne(ProjectTypePrefer::class, ['project_type_prefer_id' => 'project_type_prefer_id']);
    }

    public function getSumpo() {
        $model = ProjectPo::find()->where(['project_id' => $this->project_id])->all();
        $sum = 0;
        foreach ($model as $value) {
            $sum += $value->project_po_cost;
        }
        return $sum;
    }

    public function getSumContract() {
        $model = ProjectContract::find()->where(['project_id' => $this->project_id])->all();
        $sum = 0;
        foreach ($model as $value) {
            $sum += $value->project_contract_cost;
        }
        return $sum;
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $year = (Yii::$app->params['budgetYear'] + 543);
            $date = $year . date('mdHis');
            $this->project_id = $date . substr($now->format('u'), 0, 2);
            /*
              $this->project_book_number01 = AutoNumber::generate("P-????/$year"); //กำหนดเลข
              $this->project_book_number02 = AutoNumber::generate("P-????/$year");
              $this->project_book_number03 = AutoNumber::generate("P-????/$year");
             */
            $this->project_create = new \yii\db\Expression('NOW()');
            $this->project_staff = Yii::$app->user->identity->profile->cid;
            $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
            $this->department_id = $emp->employee_dep_id;
        } else {
            $this->project_update = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

}
