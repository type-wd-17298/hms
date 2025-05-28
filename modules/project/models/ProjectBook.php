<?php

namespace app\modules\project\models;

use Yii;

/**
 * This is the model class for table "app_project_book".
 *
 * @property string $project_book_id
 * @property string $project_book_ordernumber
 * @property string $project_book_datetime
 * @property string $project_book_title
 * @property string $staff
 * @property string|null $dep
 */
class ProjectBook extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_project;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'app_project_book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            //[['project_book_id', 'project_book_ordernumber', 'project_book_datetime', 'project_book_title', 'staff'], 'required'],
            [['project_book_datetime'], 'safe'],
            [['project_book_id', 'staff', 'dep', 'project_id'], 'string', 'max' => 50],
            [['project_book_ordernumber'], 'string', 'max' => 100],
            [['project_book_title'], 'string', 'max' => 200],
            [['project_book_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'project_book_id' => 'Project Book ID',
            'project_book_ordernumber' => 'Project Book Ordernumber',
            'project_book_datetime' => 'Project Book Datetime',
            'project_book_title' => 'Project Book Title',
            'staff' => 'Staff',
            'dep' => 'Dep',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $year = (Yii::$app->params['budgetYear'] + 543);
            $date = $year . date('mdHis');
            $this->project_book_id = $date . substr($now->format('u'), 0, 2);
            $this->project_book_datetime = new \yii\db\Expression('NOW()');
        }

        return parent::beforeSave($insert);
    }

}
