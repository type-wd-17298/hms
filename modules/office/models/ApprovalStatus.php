<?php

namespace app\modules\office\models;

use Yii;

class ApprovalStatus extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'paperless_approval_status';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['approval_status_active'], 'integer'],
            [['approval_status_name', 'approval_status_color'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'approval_status_id' => 'Leave Status ID',
            'approval_status_name' => 'สถานะดำเนินการ',
            'approval_status_active' => 'Status Active',
        ];
    }

}
