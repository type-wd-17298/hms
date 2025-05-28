<?php

namespace app\modules\office\models;

use Yii;
use yii\helpers\Url;
use app\components\Ccomponent;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;
use app\modules\office\models\Uploads;

/**
 * This is the model class for table "paperless_view".
 *
 * @property string $paperless_view_id
 * @property string|null $paperless_view_startdate วันที่เริ่มต้นการเวียน
 * @property string|null $paperless_view_enddate วันที่สิ้นสุดการเวียน
 * @property string|null $paperless_view_deps หน่วยงาน
 * @property string|null $paperless_view_emps เจ้าหน้าที่
 * @property string|null $paperless_view_auth กลุ่มเฉพาะ
 * @property string $employee_id เจ้าของเรื่อง
 * @property string|null $paperless_paper_ref เอกสารที่ต้องการเวียน
 * @property string|null $update_at
 * @property string|null $create_at
 * @property int|null $paperless_view_active การใช้งาน
 */
class PaperlessView extends \yii\db\ActiveRecord {

    const UPLOAD_FOLDER = '../data/files'; //path files data

    public $photo_upload;

    public static function tableName() {
        return 'paperless_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            // [['paperless_view_id'], 'required'],
            [['employee_id', 'paperless_view_startdate', 'paperless_view_enddate', 'update_at', 'create_at', 'paperless_level_id'], 'safe'],
            [['paperless_view_active'], 'integer'],
            [['paperless_view_id', 'paperless_paper_ref'], 'string', 'max' => 50],
            [['paperless_view_deps', 'paperless_view_emps', 'paperless_view_auth', 'paperless_topic', 'paperless_detail', 'paperless_from'], 'safe'],
            [['paperless_view_id'], 'unique'],
            [['paperless_topic', 'paperless_detail', 'paperless_from'], 'required', 'on' => 'manual'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'paperless_view_id' => 'Paperless View ID',
            'paperless_view_startdate' => 'วันที่เริ่มต้นการเวียน',
            'paperless_view_enddate' => 'วันที่สิ้นสุดการเวียน',
            'paperless_view_deps' => 'หน่วยงาน',
            'paperless_view_emps' => 'เจ้าหน้าที่',
            'paperless_view_auth' => 'กลุ่มเฉพาะ',
            'employee_id' => 'เจ้าของเรื่อง',
            'paperless_paper_ref' => 'เอกสารที่ต้องการเวียน',
            'update_at' => 'Update At',
            'create_at' => 'Create At',
            'paperless_view_active' => 'การใช้งาน',
            'paperless_from' => 'หน่วยงาน',
            'paperless_detail' => 'รายละเอียด',
            'paperless_topic' => 'เรื่อง',
            'paperless_level_id' => 'ความเร่งด่วน',
        ];
    }

    public function scenarios() {
        $sn = parent::scenarios();
        $sn['manual'] = ['paperless_level_id', 'employee_id', 'paperless_view_startdate', 'paperless_view_enddate', 'update_at', 'create_at', 'paperless_view_id', 'employee_id', 'paperless_topic', 'paperless_detail', 'paperless_from', 'paperless_view_deps', 'paperless_view_emps', 'paperless_view_auth'];

        return $sn;
    }

    public function getPaper() {
        if (substr($this->paperless_paper_ref, 0, 3) == 'BNN') {
            return $this->hasOne(Paperless::className(), ['paperless_id' => 'paperless_paper_ref']);
        } else if (substr($this->paperless_paper_ref, 0, 3) == 'BRN') {
            return $this->hasOne(PaperlessOfficial::className(), ['paperless_id' => 'paperless_paper_ref']);
        }
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getLevel() {
        return $this->hasOne(PaperlessLevel::className(), ['paperless_level_id' => 'paperless_level_id']);
    }

    public static function getUploadPath() {
        return Yii::getAlias('@webroot') . '/' . self::UPLOAD_FOLDER . '/';
    }

    public static function getUploadUrl() {
        return Url::base(true) . '/' . self::UPLOAD_FOLDER . '/';
    }

    public function getThumbnails($ref, $event_name = '') {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $preview = [];
        foreach ($uploadFiles as $file) {
            $preview[] = [
                'url' => self::getUploadUrl(true) . $ref . '/' . $file->real_filename,
                'src' => self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename,
                'options' => ['title' => $event_name, 'class' => 'img-responsive']
            ];
        }
        return $preview;
    }

    public function getUrlView($ref) {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $preview = '';
        foreach ($uploadFiles as $file) {
            $preview = self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename;
        }
        return $preview;
    }

    public function getThumbnailsView($ref) {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $preview = '';
        foreach ($uploadFiles as $file) {
            $preview .= '<div class="pull-left">' . Html::a(Html::img(self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename, ['width' => 50, 'class' => 'img-thumbnail']), self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename, ['data-fancybox' => true]) . '</div>';
        }
        return $preview;
    }

    public function getThumbnailsViewOne() {
        $ref = $this->product_code;
        $file = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->one();
        $preview = '';
        if ($file) {
#foreach ($uploadFiles as $file) {
            $preview .= '<div class="pull-right">' . Html::img(self::getUploadUrl() . $ref . '/thumbnail/' . $file->real_filename, ['width' => 50, 'class' => 'img-thumbnail', 'data-fancybox' => true]) . '</div>';
#}
        }
        return $preview;
    }

    public function getPhoto($ref) {
        $file = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->one();
        return $file;
    }

    public function getUrlPdf($ref, $mod = 'p') {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $fileSrc = [];
        $path = self::getUploadUrl();
        if ($mod = 'u')
            $path = self::getUploadPath();

        foreach ($uploadFiles as $file) {
            if ($file->type == 'pdf')
                $fileSrc[] = $path . $ref . '/' . $file->real_filename;
        }
        return $fileSrc;
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->create_at = new \yii\db\Expression('NOW()');
        } else {
            $this->update_at = new \yii\db\Expression('NOW()');
        }
        return parent::beforeSave($insert);
    }

}
