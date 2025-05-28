<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "asset_supplier".
 *
 * @property int $asset_supplier_id
 * @property string|null $asset_supplier_name
 */
class AssetSupplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asset_supplier';
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
            [['asset_supplier_id'], 'required'],
            [['asset_supplier_id'], 'integer'],
            [['asset_supplier_name'], 'string', 'max' => 50],
            [['asset_supplier_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'asset_supplier_id' => 'Asset Supplier ID',
            'asset_supplier_name' => 'Asset Supplier Name',
        ];
    }
}
