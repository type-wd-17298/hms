<?php

namespace app\modules\office\models;

use Yii;

class PaperlessCommand extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'paperless_command';
    }

}
