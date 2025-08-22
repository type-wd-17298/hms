<?php

namespace app\modules\survey\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "survey_request_item".
 *
 * @property int $id
 * @property int $survey_request_id
 * @property string $asset_number
 * @property int|null $status 0=รออนุมัติ, 1=อนุมัติ, 2=ปฏิเสธ
 * @property int|null $approved_by
 * @property string|null $approved_at
 * @property string|null $remark
 */
class SurveyRequestItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_request_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['survey_request_id', 'asset_number'], 'required'],
            [['survey_request_id', 'status', 'approved_by'], 'integer'],
            [['approved_at'], 'safe'],
            [['remark'], 'string'],
            [['asset_number'], 'string', 'max' => 255],
            ['status', 'default', 'value' => 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'survey_request_id' => 'Survey Request ID',
            'asset_number' => 'Asset Number',
            'status' => 'สถานะ',
            'approved_by' => 'ผู้อนุมัติ',
            'approved_at' => 'วันที่อนุมัติ',
            'remark' => 'หมายเหตุ',
        ];
    }
}
