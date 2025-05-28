<?php

namespace app\modules\survey\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;
use app\components\Ccomponent;

class SurveyComputer extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'survey_computer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'item', 'price', 'specification'], 'required'],
            [['id', 'price'], 'number'],
            [['item', 'specification'], 'string'],
        ];
    }

    public function getFullname() {
        return $this->id . ' ' . $this->item;
    }

}
