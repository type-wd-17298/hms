<?php

namespace app\modules\office\models;

use Yii;

/**
 * This is the model class for table "paperless_level".
 *
 * @property int $paperless_id
 * @property string $paperless_level
 * @property string|null $paperless_level_color
 */
class PaperlessLevel extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'paperless_level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['paperless_level_id', 'paperless_level'], 'required'],
            [['paperless_level_id'], 'integer'],
            [['paperless_level', 'paperless_level_color'], 'string', 'max' => 20],
            [['paperless_level'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'paperless_level_id' => 'Paperless ID',
            'paperless_level' => 'Paperless Level',
            'paperless_level_color' => 'Paperless Level Color',
        ];
    }

}
