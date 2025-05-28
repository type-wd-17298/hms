<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "stock_card".
 *
 * @property int $stock_id
 * @property string|null $ref_id
 * @property string $asset_item_id รหัสสินค้า
 * @property string $asset_master_type_id
 * @property string|null $lot_no
 * @property int $stock_type_id IN/OUT ,T-IN/T-OUT
 * @property int $quantity_up ปริมาณเพิ่ม
 * @property int $quantity_down ปริมาณลด
 * @property int $balance
 * @property string $stock_date
 * @property int $employee_id
 * @property string|null $remark
 */
class StockCard extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'stock_card';
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
            [['asset_item_id', 'asset_master_type_id', 'stock_type_id', 'quantity_up', 'quantity_down', 'balance', 'stock_date', 'employee_id'], 'required'],
            [['stock_type_id', 'quantity_up', 'quantity_down', 'balance', 'employee_id'], 'integer'],
            [['stock_date'], 'safe'],
            [['ref_id'], 'string', 'max' => 45],
            [['asset_item_id'], 'string', 'max' => 30],
            [['asset_master_type_id'], 'string', 'max' => 5],
            [['lot_no'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 200],
            [['asset_item_id', 'asset_master_type_id', 'lot_no', 'ref_id', 'stock_type_id'], 'unique', 'targetAttribute' => ['asset_item_id', 'asset_master_type_id', 'lot_no', 'ref_id', 'stock_type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'stock_id' => 'Stock ID',
            'ref_id' => 'Ref ID',
            'asset_item_id' => 'รหัสสินค้า',
            'asset_master_type_id' => 'Branch No',
            'lot_no' => 'Lot No',
            'stock_type_id' => 'IN/OUT ,T-IN/T-OUT',
            'quantity_up' => 'ปริมาณเพิ่ม',
            'quantity_down' => 'ปริมาณลด',
            'balance' => 'Balance',
            'stock_date' => 'Stock Date',
            'employee_id' => 'Employee ID',
            'remark' => 'Remark',
        ];
    }

    public function getMaster() {
        return $this->hasOne(AssetMasterType::className(), ['asset_master_type_id' => 'asset_master_type_id']);
    }

    public function getItems() {
        return $this->hasOne(AssetItems::className(), ['asset_item_id' => 'asset_item_id']);
    }

    public function getStockType() {
        return $this->hasOne(StockType::className(), ['stock_type_id' => 'stock_type_id']);
    }

    public function getRef() {
        if ($this->stock_type_id == 1) {
            return $this->hasOne(AssetStockin::className(), ['asset_stockin_no' => 'ref_id']);
        }
        if ($this->stock_type_id == 2) {
            return $this->hasOne(AssetStockout::className(), ['asset_stockout_no' => 'ref_id']);
        }
    }

}
