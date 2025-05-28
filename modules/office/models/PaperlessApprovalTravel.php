<?php

namespace app\modules\office\models;

use Yii;

/**
 * This is the model class for table "paperless_approval_type".
 *
 * @property int $approval_type_id
 * @property string $approval_type_name
 */
class PaperlessApprovalTravel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paperless_approval_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['approval_type_id', 'approval_type_name'], 'required'],
            [['approval_type_id'], 'integer'],
            [['approval_type_name'], 'string', 'max' => 200],
            [['approval_type_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'approval_type_id' => 'Approval Type ID',
            'approval_type_name' => 'Approval Type Name',
        ];
    }
}
