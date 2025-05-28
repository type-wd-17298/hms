<?php

namespace app\modules\office\models;

use Yii;

/**
 * This is the model class for table "summary_operation".
 *
 * @property int $employee_id
 * @property string|null $update_at
 * @property string|null $create_at
 * @property int|null $cc_approval ไปราชการ
 * @property int|null $cc_official หนังสือราชการ
 * @property int|null $cc_view หนังสือเวียน
 * @property int|null $cc_paperless บันทึกข้อความ
 * @property int|null $cc_leave ระบบลา
 */
class SummaryOperation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'summary_operation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id', 'cc_approval', 'cc_official', 'cc_view', 'cc_paperless', 'cc_leave'], 'integer'],
            [['update_at', 'create_at'], 'safe'],
            [['employee_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'update_at' => 'Update At',
            'create_at' => 'Create At',
            'cc_approval' => 'ไปราชการ',
            'cc_official' => 'หนังสือราชการ',
            'cc_view' => 'หนังสือเวียน',
            'cc_paperless' => 'บันทึกข้อความ',
            'cc_leave' => 'ระบบลา',
        ];
    }
}
