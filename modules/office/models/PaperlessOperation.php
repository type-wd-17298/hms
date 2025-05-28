<?php

namespace app\modules\office\models;

use Yii;

class PaperlessOperation extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'paperless_operation';
    }

}
