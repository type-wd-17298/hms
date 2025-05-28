<?php

namespace app\modules\project\models;

use Yii;

/**
 * This is the model class for table "{{%project_type}}".
 *
 * @property int $project_type_id
 * @property string|null $project_type_name
 */
class ProjectType extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_project;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%project_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_type_name'], 'required'],
            [['project_type_name'], 'unique'],
            [['project_type_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'project_type_id' => 'รหัส',
            'project_type_name' => 'ชื่อประเภท',
        ];
    }

}
