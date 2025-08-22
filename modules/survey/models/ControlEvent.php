<?php

namespace app\modules\survey\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "control_event".
 *
 * @property int $id
 * @property string|null $event_name
 * @property string|null $description
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class ControlEvent extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'control_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_name'], 'string', 'max' => 255],
            [['active'], 'integer'],
        ];
    }

    /**
     * Attribute labels
     */
    public function attributeLabels()
    {
        return [
            'event_id'   => 'Event ID',
            'event_name' => 'Event Name',
            'active'     => 'Active',
        ];
    }
}
