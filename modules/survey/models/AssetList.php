<?php

namespace app\modules\survey\models;

use Yii;
use yii\db\ActiveRecord;

class AssetList extends ActiveRecord
{
    public static function tableName()
    {
        return 'asset_list';
    }

    public function rules()
    {
        return [
            [
                [
                    'receiv_date',
                    'asset_number',
                    'model',
                    'serial_no',
                    'qty',
                    'status_id',
                    'asset_id',
                    'price',
                    'fund_id',
                    'depart_id',
                    'depart_place_id',
                    'source_id',
                    'emp_id',
                    'equip_id',
                    'vendor_id',
                    'donor_id',
                    'description'
                ],
                'required'
            ],
            [
                [
                    'asset_id',
                    'qty',
                    'status_id',
                    'fund_id',
                    'depart_id',
                    'depart_place_id',
                    'source_id',
                    'vendor_id',
                    'donor_id',
                    'emp_id'
                ],
                'integer'
            ],
            [['price'], 'number'],
            [['receiv_date', 'create_at', 'update_at'], 'safe'],
            [['asset_number', 'gfmis', 'model', 'description', 'serial_no', 'comment', 'equip_id'], 'string', 'max' => 255],
        ];
    }

    public static function getDb()
    {
        return Yii::$app->db_erp; 
    }
}
