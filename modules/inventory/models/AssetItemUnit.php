<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "asset_item_unit".
 *
 * @property int $asset_unit_id
 * @property string|null $asset_unit_name
 */
class AssetItemUnit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asset_item_unit';
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
            [['asset_unit_id'], 'required'],
            [['asset_unit_id'], 'integer'],
            [['asset_unit_name'], 'string', 'max' => 50],
            [['asset_unit_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'asset_unit_id' => 'Asset Unit ID',
            'asset_unit_name' => 'Asset Unit Name',
        ];
    }
}
