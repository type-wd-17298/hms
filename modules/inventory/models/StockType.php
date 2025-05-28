<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "stock_type".
 *
 * @property int $stock_type_id
 * @property string $stock_type_name
 */
class StockType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_type';
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
            [['stock_type_name'], 'required'],
            [['stock_type_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'stock_type_id' => 'Stock Type ID',
            'stock_type_name' => 'Stock Type Name',
        ];
    }
}
