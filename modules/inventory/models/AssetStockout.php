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
 * This is the model class for table "asset_stockout".
 *
 * @property string $asset_stockout_id
 * @property int $asset_master_type_id
 * @property int $asset_supplier_id
 * @property int $employee_id
 * @property string|null $asset_stockout_no เลขที่ลงรับ
 * @property string|null $asset_stockout_refno เลขที่อ้างอิง
 * @property string|null $asset_stockout_date วันที่รับของ
 * @property string|null $asset_stockout_comment
 * @property string|null $create_at
 * @property string|null $update_at
 */
class AssetStockout extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'asset_stockout';
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
            [['asset_stockout_date', 'create_at', 'update_at', 'asset_stockout_summary'], 'safe'],
            [['asset_stockout_id', 'asset_stockout_no', 'asset_stockout_refno'], 'string', 'max' => 50],
            [['asset_stockout_comment'], 'string', 'max' => 255],
            [['asset_stockout_no'], 'unique'],
            [['asset_stockout_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'asset_stockout_id' => 'เลขที่ใบเบิกวัสดุ/ครุภัณฑ์',
            'asset_master_type_id' => 'คลัง',
            'asset_supplier_id' => 'ผู้ขาย/ผู้จำหน่าย',
            'employee_id' => 'เจ้าหน้าที่',
            'asset_stockout_no' => 'เลขที่ใบเบิกวัสดุ/ครุภัณฑ์',
            'asset_stockout_refno' => 'เลขที่อ้างอิง',
            'asset_stockout_date' => 'วันที่เบิกของ',
            'asset_stockout_comment' => 'หมายเหตุ',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'asset_order_status_id' => 'สถานะ',
            'asset_stockout_summary' => 'summary'
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
            $this->asset_stockout_no = AutoNumber::generate('STO' . (date('Y') + 543) . '-?????');  // STIN -> stock-in
            $year = substr(date('Y') + 543, 2);
            $month = date('m');
            $this->asset_stockout_id = $this->asset_stockout_no = AutoNumber::generate('STOUT' . $year . '-????');
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

    public function getStockoutList() {
        return $this->hasMany(AssetStockoutList::className(), ['asset_stockout_id' => 'asset_stockout_id']);
    }

    public function getMaster() {
        return $this->hasOne(AssetMasterType::className(), ['asset_master_type_id' => 'asset_master_type_id']);
    }

    public function getItemsCount() {
        return $this->getStockoutList()->count('amount');
    }

    public function getItemsCountSum() {
        return $this->getStockoutList()->sum('amount');
    }

    public function getDate() {
        return $this->asset_stockout_date;
    }

}
