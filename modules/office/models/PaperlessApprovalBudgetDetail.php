<?php

namespace app\modules\office\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

/**
 * This is the model class for table "paperless_approval_budget_detail".
 *
 * @property string $budget_detail_id
 * @property int $approval_id
 * @property int $employee_id
 * @property float|null $budget_detail_costs1 ค่าลงทะเบียน
 * @property float|null $budget_detail_costs2 ค่าเบี้ยเลี้ยง
 * @property float|null $budget_detail_costs3 ค่าที่พัก
 * @property float|null $budget_detail_costs4 ค่าพาหนะ
 */
class PaperlessApprovalBudgetDetail extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'paperless_approval_budget_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['approval_id', 'employee_id'], 'required'],
            [['employee_id'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['budget_detail_costs1', 'budget_detail_costs2', 'budget_detail_costs3', 'budget_detail_costs4'], 'number'],
            [['approval_id'], 'string', 'max' => 20],
            [['budget_detail_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'budget_detail_id' => 'Budget Detail ID',
            'approval_id' => 'Approval ID',
            'employee_id' => 'Employee ID',
            'budget_detail_costs1' => 'ค่าลงทะเบียน',
            'budget_detail_costs2' => 'ค่าเบี้ยเลี้ยง',
            'budget_detail_costs3' => 'ค่าที่พัก',
            'budget_detail_costs4' => 'ค่าพาหนะ',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getApproval() {
        return $this->hasOne(PaperlessApproval::className(), ['approval_id' => 'approval_id']);
    }

}
