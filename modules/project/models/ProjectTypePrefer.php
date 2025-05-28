<?php

namespace app\modules\project\models;

use Yii;

/**
 * This is the model class for table "{{%project_type}}".
 *
 * @property int $project_type_id
 * @property string|null $project_type_name
 */
class ProjectTypePrefer extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_project;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%project_type_prefer}}';
    }

}
