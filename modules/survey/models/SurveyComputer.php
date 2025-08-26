<?php

namespace app\modules\survey\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;
use app\components\Ccomponent;

class SurveyComputer extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_computer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item', 'price', 'specification', 'DE_id'], 'required'],
            [['_id', 'id', 'price', 'active', 'DE_id'], 'number'],
            [['item', 'specification', 'short_name'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item' => 'ชื่ออุปกรณ์/ระบบ',
            'short_name' => 'ชื่อย่อ',
            'price' => 'ราคา',
            'specification' => 'รายละเอียด/คุณลักษณะ',
            'DE_id' => 'ลำดับในเกณฑ์ราคากลาง',
            'active' => 'สถานะ',
        ];
    }

    public function getFullname()
    {
        return $this->item;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->id = $this->_id;
            $this->updateAttributes(['id']);
        }
    }
}
