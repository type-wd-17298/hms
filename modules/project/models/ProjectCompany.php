<?php

namespace app\modules\project\models;

use Yii;

/**
 * This is the model class for table "{{%project_type}}".
 *
 * @property int $project_type_id
 * @property string|null $project_type_name
 */
class ProjectCompany extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_project;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%project_company}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_company_name'], 'string', 'max' => 100],
            [['project_company_name'], 'unique'],
                //[['project_company_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'project_company_id' => 'รหัสบริษัท',
            'project_company_name' => 'ชื่อบริษัท',
            'project_company_code' => 'รหัสบริษัท'
        ];
    }

}
