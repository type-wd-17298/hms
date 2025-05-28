<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "asset_stockout_list".
 *
 * @property string $asset_stockout_list_id
 * @property string $asset_stockout_id
 * @property string $asset_item_id
 * @property int $amount
 * @property int $price
 * @property string|null $lot_no
 * @property string|null $comment
 * @property string|null $create_at
 * @property string|null $update_at
 */
class AssetStockoutList extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'asset_stockout_list';
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
            [['asset_stockout_id', 'asset_item_id', 'amount',], 'required'],
            [['amount'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['asset_stockout_list_id', 'asset_stockout_id', 'asset_item_id', 'lot_no'], 'string', 'max' => 50],
            [['comment'], 'string', 'max' => 255],
            [['asset_stockout_list_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'asset_stockout_list_id' => 'Asset Stockin List ID',
            'asset_stockout_id' => 'Asset Stockin ID',
            'asset_item_id' => 'รายการวัสดุ/ครุภัณฑ์',
            'amount' => 'จำนวน',
            'lot_no' => 'Lot',
            'comment' => 'หมายเหตุ',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $this->asset_stockout_list_id = (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }


        return parent::beforeSave($insert);
    }

    public function getItems() {
        return $this->hasOne(AssetItems::className(), ['asset_item_id' => 'asset_item_id']);
    }

    public function getAssetStockout() {
        return $this->hasOne(AssetStockout::className(), ['asset_stockout_id' => 'asset_stockout_id']);
    }

}
