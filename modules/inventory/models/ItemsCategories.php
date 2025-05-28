<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "items_categories".
 *
 * @property int $categories_id
 * @property string $categories_name
 *
 * @property ItemsSubcategories[] $itemsSubcategories
 */
class ItemsCategories extends \yii\db\ActiveRecord {

    public static function getDb() {
        // use the "db_project" application component
        return \Yii::$app->db_inventory;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'items_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['categories_name'], 'required'],
            [['categories_group'], 'integer',],
            [['categories_name'], 'string', 'max' => 45],
            [['categories_group'], 'unique', 'targetAttribute' => ['categories_name', 'categories_group'], 'message' => 'ชื่อหมวดสินค้าที่จะเพิ่ม มีอยู่หมวดหลักที่เลือกอยู่แล้วค่ะ'],
            [['categories_group'], 'exist', 'skipOnError' => true, 'targetClass' => ItemsCategories::className(), 'targetAttribute' => ['categories_group' => 'categories_id']],
            [['categories_name'], 'filter', 'filter' => 'trim'],
            [['categories_name'], 'filter', 'filter' => 'strtolower'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'categories_id' => 'Categories ID',
            'categories_name' => 'หมวดหมู่สินค้า',
            'categories_group' => 'หมวดหมู่หลัก'
        ];
    }

    /**
     * Gets query for [[ItemsSubcategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemsSubcategories() {
        return $this->hasMany(ItemsSubcategories::className(), ['categories_id' => 'categories_id']);
    }

    public function beforeSave($insert) {

        $this->categories_name = trim($this->categories_name);

        if ($this->categories_group < 1)
            $this->categories_group = 0;

        /*
          if ($insert) {

          $query = new Query;

          $query->select('max(TypeProduct_ID) as TypeProduct_ID')->from('ms_typeproduct')->limit(1)->Scalar();

          $UrutTP = 'TP' + substr('00', intval($query) + 1);
          $this->TypeProduct_ID = $UrutTP;
          }
         */
        return parent::beforeSave($insert);
    }

    public function buildTreeLabel($elements, $parentId = 0, $level = 0, $catname = '') {
        $branch = [];
        $level_ini = $level;
        $addString = '';
        foreach ($elements as $element) {
            $level = $level_ini;
            if ($element['categories_group'] == $parentId) {
                switch ($level) {
                    case 0 :
                        $addString = $element['categories_name'];
                        break;
                    default :
                        $addString = ' > ' . $element['categories_name'];
                        break;
                }

                $addString = $catname . $addString;
                $level++;
                $children = self::buildTreeLabel($elements, $element['categories_id'], $level, $addString);
                $branch[$element['categories_id']] = [
                    'categories_id' => $element['categories_id'],
                    'categories_name' => $element['categories_name'],
                    'categories_code' => $element['categories_code'],
                    'categories_title' => $addString,
                ];

                if ($children)
                    $branch = yii\helpers\ArrayHelper::merge($branch, $children);
            }
        }

        return $branch;
    }

    public function buildTree($elements, $parentId = 0, $level = 0) {
        $branch = [];
        $level_ini = $level;
        //$levelValue_ini = $level;
        foreach ($elements as $element) {
            $level = $level_ini;
            if ($element['categories_group'] == $parentId) {
                $addString = $element['categories_name'];
                switch ($level) {

                    case 0 :
                        $addString = '<i class="fa-solid fa-ellipsis-vertical"></i> <b>' . $element['categories_name'] . '</b>';
                        break;
                    case 1 :
                        $addString = '&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-angle-right fa-xs"></i> ' . $element['categories_name'];
                        break;
                    case 2 :
                        $addString = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-solid fa-angle-right fa-xs"></i> ' . $element['categories_name'];
                        break;
                }

                $element['categories_title'] = $addString;
                //$level .= '_';
                $element['categories_name'] = $element ['categories_name'];
                $level++;
                $children = self::buildTree($elements, $element['categories_id'], $level);
                $branch[(int) $element['categories_id']] = [
                    'categories_id' => (int) $element['categories_id'],
                    'categories_name' => $element['categories_name'],
                    'categories_code' => $element['categories_code'],
                    'categories_title' => $element['categories_title'],
                    'categories_level' => $level,
                ];

                if ($children)
                    $branch = yii\helpers\ArrayHelper::merge($branch, $children);
            }
        }
        /*
          $arr = [];
          foreach ($branch as $row) {
          $arr[] = $row;
          }
         */
        return $branch;
    }

}
