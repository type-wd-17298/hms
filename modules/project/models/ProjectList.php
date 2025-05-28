<?php

namespace app\modules\project\models;

use Yii;
use mdm\autonumber\AutoNumber;
use app\models\ExtProfile;

/**
 * This is the model class for table "{{%project_list}}".
 *
 * @property int $project_id ลำดับที่
 * @property string|null $project_orderdate วันที่ออกเลข
 * @property string|null $project_number เลขทะเบียน
 * @property string|null $project_contactnumber เลขที่สัญญา
 * @property string|null $project_name ชื่อโครงการ
 * @property string|null $project_ordernumber เลขที่ใบสั่งซื้อ/จ้าง
 * @property int|null $project_type_id ประเภทโครงการ
 * @property float|null $project_amount จำนวนเงิน
 * @property string|null $project_contactname ชื่อผู้ค้า
 * @property string|null $project_contactdetail ข้อมูลติดต่อผู้ค้า
 * @property string|null $project_contactmain ข้อมูลหลักผู้ขาย
 * @property string|null $project_staff ข้อมูลผู้ปฏิบัติ
 * @property string|null $project_create
 * @property string|null $project_update
 * @property string|null $project_comment หมายเหตุ
 */
class ProjectList extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_project;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%project_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_type_id', 'project_type_order_id', 'project_name', 'project_amount'], 'required'],
            [['project_id', 'project_type_id', 'project_type_order_id'], 'integer'],
            [['project_orderdate', 'project_create', 'project_update'], 'safe'],
            [['project_amount'], 'number'],
            [['project_comment'], 'string'],
            [['project_number', 'project_contactnumber', 'project_ordernumber', 'project_staff'], 'string', 'max' => 50],
            [['project_name', 'project_contactdetail'], 'string', 'max' => 255],
            [['project_contactname', 'project_contactmain'], 'string', 'max' => 200],
            [['project_id'], 'unique'],
            [['project_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'project_id' => 'ลำดับที่',
            'project_orderdate' => 'วันที่ออกเลข',
            'project_number' => 'เลขทะเบียน',
            'project_contactnumber' => 'เลขที่สัญญา',
            'project_name' => 'ชื่อโครงการ',
            'project_ordernumber' => 'เลขที่ PO',
            'project_type_id' => 'ประเภทโครงการ',
            'project_amount' => 'จำนวนเงิน',
            'project_contactname' => 'ชื่อผู้ค้า',
            'project_contactdetail' => 'ข้อมูลติดต่อผู้ค้า',
            'project_contactmain' => 'ข้อมูลหลักผู้ขาย',
            'project_staff' => 'ข้อมูลผู้ปฏิบัติ',
            'project_create' => 'Project Create',
            'project_update' => 'Project Update',
            'project_comment' => 'หมายเหตุ',
            'project_type_order_id' => 'ประเภทเลขที่'
        ];
    }

    public function getType() {
        return $this->hasOne(ProjectType::className(), ['project_type_id' => 'project_type_id']);
    }

    public function getWhoRecord() {
        return $this->hasOne(ExtProfile::className(), ['cid' => 'project_staff']);
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $date = date('YmdHis');
            $year = (Yii::$app->params['budgetYearAsset'] + 543); //substr(date('Y') + 543, 2);
            $this->project_id = $date . substr($now->format('u'), 0, 2);
            $this->project_number = AutoNumber::generate("????/$year");
            $this->project_ordernumber = $this->project_number;
            $this->project_create = new \yii\db\Expression('NOW()');
            $this->project_staff = Yii::$app->user->identity->profile->cid;
        } else {
            $this->project_update = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

}
