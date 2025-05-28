<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\inventory\models\AssetStockinList;

/**
 * This is the model class for table "stock".
 *
 * @property int $stock_items_id
 * @property int $asset_master_type_id
 * @property string $asset_item_id รหัสสินค้า
 * @property string|null $lot_no
 * @property int|null $quantity
 * @property string|null $stock_update
 * @property string|null $remark
 */
class Stock extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'stock';
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
            [['asset_master_type_id', 'asset_item_id'], 'required'],
            [['asset_master_type_id', 'quantity'], 'integer'],
            [['stock_update'], 'safe'],
            [['asset_item_id'], 'string', 'max' => 50],
            [['lot_no'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 200],
            [['asset_master_type_id', 'asset_item_id', 'lot_no'], 'unique', 'targetAttribute' => ['asset_master_type_id', 'asset_item_id', 'lot_no']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'stock_items_id' => 'Stock Items ID',
            'asset_master_type_id' => 'Asset Master Type ID',
            'asset_item_id' => 'รหัสสินค้า',
            'lot_no' => 'Lot No',
            'quantity' => 'Quantity',
            'stock_update' => 'Stock Update',
            'remark' => 'Remark',
        ];
    }

    public function getItems() {
        return $this->hasOne(AssetItems::className(), ['asset_item_id' => 'asset_item_id']);
    }

    public function getMaster() {
        return $this->hasOne(AssetMasterType::className(), ['asset_master_type_id' => 'asset_master_type_id']);
    }

    public function getStockType() {
        return $this->hasOne(StockType::className(), ['stock_type_id' => 'stock_type_id']);
    }

}
