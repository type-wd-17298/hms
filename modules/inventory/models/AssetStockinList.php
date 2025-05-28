<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "asset_stockin_list".
 *
 * @property string $asset_stockin_list_id
 * @property string $asset_stockin_id
 * @property string $asset_item_id
 * @property int $amount
 * @property int $price
 * @property string|null $lot_no
 * @property string|null $comment
 * @property string|null $create_at
 * @property string|null $update_at
 */
class AssetStockinList extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'asset_stockin_list';
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
            [['asset_stockin_id', 'asset_item_id', 'price', 'amount',], 'required'],
            [['amount', 'price'], 'safe'],
            [['create_at', 'update_at'], 'safe'],
            [['asset_stockin_list_id', 'asset_stockin_id', 'asset_item_id', 'lot_no'], 'string', 'max' => 50],
            [['comment'], 'string', 'max' => 255],
            [['asset_stockin_list_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'asset_stockin_list_id' => 'Asset Stockin List ID',
            'asset_stockin_id' => 'Asset Stockin ID',
            'asset_item_id' => 'รายการวัสดุ/ครุภัณฑ์',
            'amount' => 'จำนวน',
            'price' => 'ราคาต่อหน่วย',
            'lot_no' => 'Lot',
            'comment' => 'หมายเหตุ',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'exp_date' => 'exp',
            'barcode' => 'barcode'
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $this->asset_stockin_list_id = (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }

        if ($this->lot_no == '')
            $this->lot_no = $this->assetStockin->asset_stockin_date;

        return parent::beforeSave($insert);
    }

    public function getItems() {
        return $this->hasOne(AssetItems::className(), ['asset_item_id' => 'asset_item_id']);
    }

    public function getAssetStockin() {
        return $this->hasOne(AssetStockin::className(), ['asset_stockin_id' => 'asset_stockin_id']);
    }

}
