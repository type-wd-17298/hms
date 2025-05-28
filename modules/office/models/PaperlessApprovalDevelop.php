<?php

namespace app\modules\office\models;

use Yii;

/**
 * This is the model class for table "paperless_approval_develop".
 *
 * @property int $develop_id
 * @property string $develop_name
 */
class PaperlessApprovalDevelop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paperless_approval_develop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['develop_id', 'develop_name'], 'required'],
            [['develop_id'], 'integer'],
            [['develop_name'], 'string', 'max' => 200],
            [['develop_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'develop_id' => 'Develop ID',
            'develop_name' => 'Develop Name',
        ];
    }
}
