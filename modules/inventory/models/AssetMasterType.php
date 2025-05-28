<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "asset_master_type".
 *
 * @property int $asset_master_type_id
 * @property string|null $asset_master_type_name
 * @property string|null $asset_master_type_code
 * @property int|null $asset_master_type_active
 */
class AssetMasterType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asset_master_type';
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
            [['asset_master_type_active'], 'integer'],
            [['asset_master_type_name'], 'string', 'max' => 200],
            [['asset_master_type_code'], 'string', 'max' => 10],
            [['asset_master_type_name', 'asset_master_type_code'], 'unique', 'targetAttribute' => ['asset_master_type_name', 'asset_master_type_code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'asset_master_type_id' => 'Asset Master Type ID',
            'asset_master_type_name' => 'Asset Master Type Name',
            'asset_master_type_code' => 'Asset Master Type Code',
            'asset_master_type_active' => 'Asset Master Type Active',
        ];
    }
}
