<?php

namespace app\modules\project\models;

use Yii;

/**
 * This is the model class for table "app_project_bank".
 *
 * @property int $project_bank_id
 *
 * @property ProjectContact[] $projectContacts
 */
class ProjectBank extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_project;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'app_project_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['project_bank_id'], 'required'],
            [['project_bank_id'], 'integer'],
            [['project_bank_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'project_bank_id' => 'Project Bank ID',
        ];
    }

    /**
     * Gets query for [[ProjectContacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectContacts() {
        return $this->hasMany(ProjectContact::class, ['project_bank_id' => 'project_bank_id']);
    }

}
