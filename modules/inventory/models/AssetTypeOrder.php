<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "asset_type_order".
 *
 * @property int $asset_type_order_id
 * @property string|null $asset_type_order_name
 */
class AssetTypeOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asset_type_order';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_inventory');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asset_type_order_id'], 'required'],
            [['asset_type_order_id'], 'integer'],
            [['asset_type_order_name'], 'string', 'max' => 50],
            [['asset_type_order_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'asset_type_order_id' => 'Asset Type Order ID',
            'asset_type_order_name' => 'Asset Type Order Name',
        ];
    }
}
