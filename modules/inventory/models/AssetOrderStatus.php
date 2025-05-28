<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "asset_order_status".
 *
 * @property int $asset_order_status_id
 * @property string|null $asset_order_status_name
 *
 * @property PurchaseOrder[] $assetOrders
 */
class AssetOrderStatus extends \yii\db\ActiveRecord {

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_inventory');
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'asset_order_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['asset_order_status_id'], 'required'],
            [['asset_order_status_id'], 'integer'],
            [['asset_order_status_name'], 'string', 'max' => 45],
            [['asset_order_status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'asset_order_status_id' => 'Purchase Order Status ID',
            'asset_order_status_name' => 'Purchase Order Status Name',
            'asset_order_status_class' => 'class'
        ];
    }

    public function getStatus_name() {
        return $this->asset_order_status_id . ' ' . $this->asset_order_status_name;
    }

}
