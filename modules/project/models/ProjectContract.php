<?php

namespace app\modules\project\models;

use Yii;
use mdm\autonumber\AutoNumber;

/**
 * This is the model class for table "app_project_contract".
 *
 * @property int $project_contract_id
 * @property string|null $project_contract_booknumber เลขที่สัญญา
 * @property string|null $project_contac_name
 * @property int $project_id
 * @property string $project_startdate
 * @property string $project_finishdate
 * @property float|null $project_contract_cost วงเงินในสัญญา
 * @property float|null $project_contract_pay จ่ายมัดจำ 5% ของวงเงิน
 * @property int $project_bank_id
 *
 * @property Project $project
 * @property ProjectBank $projectBank
 */
class ProjectContract extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_project;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'app_project_contract';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_contract_cost', 'project_contract_type_id', 'project_startdate', 'project_finishdate'], 'required'],
            [['project_bank_id', 'project_contract_type_id'], 'integer'],
            [['project_startdate', 'project_id', 'project_finishdate', 'project_contract_date'], 'safe'],
            [['project_contract_cost', 'project_contract_pay'], 'number'],
            [['project_contract_id', 'project_contract_book'], 'string', 'max' => 20],
            //[['project_id'], 'unique'],
            [['project_contract_book'], 'unique'],
            [['project_contract_id'], 'unique'],
            [['project_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectBank::class, 'targetAttribute' => ['project_bank_id' => 'project_bank_id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'project_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'project_contract_id' => 'Project Contact ID',
            'project_contract_book' => 'เลขที่สัญญา',
            'project_contract_date' => 'วันที่',
            'project_id' => 'โครงการ',
            'project_startdate' => 'วันที่เริ่มสัญญา',
            'project_finishdate' => 'วันที่สิ้นสุดสัญญา',
            'project_contract_cost' => 'วงเงินในสัญญา',
            'project_contract_pay' => 'จ่ายมัดจำ 5% ของวงเงิน',
            'project_bank_id' => 'ธนาคาร',
            'project_contract_comment' => 'หมายเหตุ',
            'project_contract_type_id' => 'ประเภทค้ำประกัน',
        ];
    }

    /**
     * Gets query for [[Project]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProject() {
        return $this->hasOne(Project::class, ['project_id' => 'project_id']);
    }

    /**
     * Gets query for [[ProjectBank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBank() {
        return $this->hasOne(ProjectBank::class, ['project_bank_id' => 'project_bank_id']);
    }

    public function getType() {
        return $this->hasOne(ProjectContractType::class, ['project_contract_type_id' => 'project_contract_type_id']);
    }

    public function beforeSave($insert) {

        if ($this->isNewRecord) {
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $year = (Yii::$app->params['budgetYear'] + 543);
            $date = $year . date('mdHis');
            $this->project_contract_id = $date . substr($now->format('u'), 0, 2);
            $this->project_contract_book = AutoNumber::generate("PC-????/$year"); //กำหนดเลข
            $this->project_contract_create = new \yii\db\Expression('NOW()');
        } else {

            $this->project_contract_update = new \yii\db\Expression('NOW()');
        }
        $this->project_contract_pay = ($this->project_contract_cost * 5) / 100;
        return parent::beforeSave($insert);
    }

}
