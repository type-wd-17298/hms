<?php

namespace app\modules\office\models;

use Yii;

/**
 * This is the model class for table "uploads".
 *
 * @property integer $upload_id
 * @property integer $ref
 * @property string $file_name
 * @property string $real_filename
 * @property string $create_date
 */
class Uploads extends \yii\db\ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('db_inventory');
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'asset_uploads';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['file_name'], 'required'],
            [['upload_id'], 'integer'],
            [['create_date', 'ref'], 'safe'],
            //[['ref'], 'string', 'max' => 100],
            [['file_name', 'real_filename'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'upload_id' => 'Upload ID',
            'ref' => 'Ref',
            'file_name' => 'ชื่อไฟล์',
            'real_filename' => 'ชื่อไฟล์จริง',
            'create_date' => 'Create Date',
        ];
    }

}
