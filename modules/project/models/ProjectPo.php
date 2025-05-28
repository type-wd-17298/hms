<?php

namespace app\modules\project\models;

use Yii;
use mdm\autonumber\AutoNumber;

/**
 * This is the model class for table "app_project_po".
 *
 * @property string $project_po_id
 * @property string|null $project_po_book
 * @property string $project_id
 * @property string|null $project_po_date
 * @property string|null $project_po_create
 * @property string|null $project_po_update
 * @property float|null $project_po_cost
 * @property string|null $project_po_comment
 * @property int|null $project_po_active
 */
class ProjectPo extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_project;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'app_project_po';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_po_cost'], 'required'],
            [['project_po_date', 'project_po_create', 'project_po_update'], 'safe'],
            [['project_po_cost'], 'safe'],
            [['project_po_comment'], 'string'],
            [['project_po_active'], 'integer'],
            [['project_po_id', 'project_po_book', 'project_id'], 'string', 'max' => 20],
            [['project_po_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'project_po_id' => 'Project Po ID',
            'project_po_book' => 'เลขใบสั่งซื้อ/สั่งจ้าง',
            'project_id' => 'Project ID',
            'project_po_date' => 'วันที่ออกเลข',
            'project_po_create' => 'Project Po Create',
            'project_po_update' => 'Project Po Update',
            'project_po_cost' => 'จำนวนเงิน',
            'project_po_comment' => 'หมายเหตุ',
            'project_po_active' => 'Project Po Active',
        ];
    }

    public function getProject() {
        return $this->hasOne(Project::class, ['project_id' => 'project_id']);
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $year = (Yii::$app->params['budgetYearAsset'] + 543);
            $date = $year . date('mdHis');
            $this->project_po_id = $date . substr($now->format('u'), 0, 2);
            $this->project_po_book = AutoNumber::generate("PO-????/$year");
            $this->project_po_create = new \yii\db\Expression('NOW()');
        } else {
            $this->project_po_update = new \yii\db\Expression('NOW()');
        }
        return parent::beforeSave($insert);
    }

}
