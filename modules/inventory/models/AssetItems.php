<?php

namespace app\modules\inventory\models;

use Yii;
use app\components\Ccomponent;
use app\modules\hr\models\Employee;
use \yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
use yii\helpers\Url;

class AssetItems extends \yii\db\ActiveRecord {

    const UPLOAD_FOLDER = 'data' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'items';

    public $upload_foler = 'data/items';
    public $photo_upload;
    public $items_photo;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'asset_items';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_inventory');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['asset_item_id', 'asset_item_name', 'asset_unit_id'], 'required'],
            [['categories_id', 'asset_type_id', 'asset_unit_id', 'asset_item_active'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['asset_item_id', 'asset_item_name', 'asset_item_detail', 'sku'], 'string'],
            [['asset_item_id'], 'unique'],
            [['asset_item_name'], 'unique'],
            [['sku'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'asset_item_id' => 'รหัส',
            'asset_item_name' => 'ชื่อรายการ',
            'asset_type_id' => 'ประเภท',
            'asset_unit_id' => 'หน่วยนับ',
            'asset_item_active' => 'สถานะ',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'categories_id' => 'ประเภท',
            'asset_item_detail' => 'รายละเอียด',
            'sku' => 'sku'
        ];
    }

    public function getCategoriesId() {
        return str_pad($this->categories_id, 3, '0', STR_PAD_LEFT);
    }

    public function getItemsUnit() {
        return $this->hasOne(AssetItemUnit::className(), ['asset_unit_id' => 'asset_unit_id']);
    }

    public static function getUploadPath() {
        //\Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR
        return Yii::getAlias('@app') . DIRECTORY_SEPARATOR . self::UPLOAD_FOLDER . DIRECTORY_SEPARATOR;
    }

    public static function getPath() {
        return Yii::getAlias('@app') . DIRECTORY_SEPARATOR . self::UPLOAD_FOLDER;
    }

    public static function getUrlPath() {
        return Url::base(true) . '/../' . self::UPLOAD_FOLDER;
    }

    public static function getUploadUrl() {
        return Url::base(true) . '/' . self::UPLOAD_FOLDER . '/';
    }

    public function getThumbnails($ref, $event_name = '') {
        $uploadFiles = AssetUploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
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
        $uploadFiles = AssetUploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $preview = '';
        foreach ($uploadFiles as $file) {
            $preview = self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename;
        }
        return $preview;
    }

    public function getThumbnailsView($ref) {
        $uploadFiles = AssetUploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
        $preview = '';
        foreach ($uploadFiles as $file) {
            $preview .= '<div class="pull-left">' . Html::a(Html::img(self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename, ['width' => 50, 'class' => 'img-thumbnail']), self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename, ['data-fancybox' => true]) . '</div>';
        }
        return $preview;
    }

    public function getThumbnailsViewOne() {
        $ref = $this->product_code;
        $file = AssetUploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->one();
        $preview = '';
        if ($file) {
#foreach ($uploadFiles as $file) {
            $preview .= '<div class="pull-right">' . Html::img(self::getUploadUrl() . $ref . '/thumbnail/' . $file->real_filename, ['width' => 50, 'class' => 'img-thumbnail', 'data-fancybox' => true]) . '</div>';
#}
        }
        return $preview;
    }

    public function getPhoto($ref) {
        $file = AssetUploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->one();
        return $file;
    }

    public function getUrlPdf($ref, $mod = 'p') {
        $uploadFiles = AssetUploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
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

    public function upload($model, $attribute) {
        $photo = UploadedFile::getInstance($model, $attribute);
        $path = $this->getUploadPath();
        $oldFile = $model->getOldAttribute($attribute);
        if ($this->validate() && $photo !== null) {
            $fileName = Yii::$app->security->generateRandomString() . ".{$photo->extension}";
            if ($photo->saveAs($path . $fileName)) {

                Image::thumbnail($path . $fileName, 800, 800)
                        ->resize(new Box(800, 800))
                        ->save($path . $fileName, ['quality' => 100]);

                if (!empty($path . $oldFile) && file_exists($path . $oldFile)) {
                    @unlink($path . $oldFile);
                }
                return $fileName;
            }
        }
        return $model->isNewRecord ? false : $model->getOldAttribute($attribute);
    }

    /*
      public function getUploadPath() {
      return Yii::getAlias('@webroot') . '/' . $this->upload_foler . '/';
      }

      public function getUploadUrl() {
      return Yii::getAlias('@web') . '/' . $this->upload_foler . '/';
      }
     */

    public function getPhotoViewer() {
        $path = $this->getUploadPath() . DIRECTORY_SEPARATOR . $this->asset_item_id;
        if (!is_dir($path)) {
            @mkdir($path);
        }
        return $path; //empty($this->items_photo) ? Yii::getAlias('@web') . '/img/none.png' : $this->getUploadUrl() . $this->items_photo;
    }

}
