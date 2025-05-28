<?php

namespace app\modules\servicedesk\models;

use Yii;

class ServiceUrgency extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_servicedesk;
    }

    public static function tableName() {
        return 'service_urgency';
    }

}
