<?php

namespace app\modules\inventory\models;

use Yii;
use mdm\autonumber\AutoNumber;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\db\Expression;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

/**
 * This is the model class for table "asset_stockin".
 *
 * @property string $asset_stockin_id
 * @property int $asset_master_type_id
 * @property int $asset_supplier_id
 * @property int $employee_id
 * @property string|null $asset_stockin_no เลขที่ลงรับ
 * @property string|null $asset_stockin_refno เลขที่อ้างอิง
 * @property string|null $asset_stockin_date วันที่รับของ
 * @property string|null $asset_stockin_comment
 * @property string|null $create_at
 * @property string|null $update_at
 */
class AssetStockin extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'asset_stockin';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_inventory');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['asset_master_type_id', 'employee_id'], 'required'],
            [['asset_master_type_id', 'asset_supplier_id', 'employee_id'], 'integer'],
            [['asset_stockin_date', 'create_at', 'update_at', 'asset_stockin_summary'], 'safe'],
            [['asset_stockin_id', 'asset_stockin_no', 'asset_stockin_refno'], 'string', 'max' => 50],
            [['asset_stockin_comment'], 'string', 'max' => 255],
            [['asset_stockin_no'], 'unique'],
            [['asset_stockin_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'asset_stockin_id' => 'เลขที่ใบรับวัสดุ/ครุภัณฑ์',
            'asset_master_type_id' => 'คลัง',
            'asset_supplier_id' => 'ผู้ขาย/ผู้จำหน่าย',
            'employee_id' => 'เจ้าหน้าที่',
            'asset_stockin_no' => 'เลขที่ใบรับวัสดุ/ครุภัณฑ์',
            'asset_stockin_refno' => 'เลขที่อ้างอิง',
            'asset_stockin_date' => 'วันที่รับของ',
            'asset_stockin_comment' => 'หมายเหตุ',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'asset_order_status_id' => 'สถานะ',
            'asset_stockin_summary' => 'summary'
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
            $this->asset_stockin_no = AutoNumber::generate('STI' . (date('Y') + 543) . '-?????');  // STIN -> stock-in
            $year = substr(date('Y') + 543, 2);
            $month = date('m');
            $this->asset_stockin_id = $this->asset_stockin_no = AutoNumber::generate('STIN' . $year . '-????');
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    public function getSupplier() {
        return $this->hasOne(AssetSupplier::className(), ['asset_supplier_id' => 'asset_supplier_id']);
    }

    public function getStatus() {
        return $this->hasOne(AssetOrderStatus::className(), ['asset_order_status_id' => 'asset_order_status_id']);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'employee_dep_id']);
    }

    public function getStockinList() {
        return $this->hasMany(AssetStockinList::className(), ['asset_stockin_id' => 'asset_stockin_id']);
    }

    public function getMaster() {
        return $this->hasOne(AssetMasterType::className(), ['asset_master_type_id' => 'asset_master_type_id']);
    }

    public function getItemsCount() {
        return $this->getStockinList()->count('amount');
    }

    public function getItemsCountSum() {
        return $this->getStockinList()->sum('amount');
    }

    public function getDate() {
        return $this->asset_stockin_date;
    }

}
