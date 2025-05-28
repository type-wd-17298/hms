<?php

namespace app\modules\inventory\controllers;

use Yii;
use app\modules\inventory\models\AssetItems;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\inventory\models\AssetStockinList;
//use app\modules\inventories\models\ItemsProperty;
//use app\modules\inventories\models\ItemsTranferDetail;
//use app\modules\inventories\models\PurchaseOrderDetail;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Items models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => AssetItems::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGenId() {
        return 'G' . date('ymdHis'); //GEN รหัสสินค้า
    }

    /**
     * Displays a single Items model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function addItems($items_id, $property_id, $items_property_name, $pk) {
        $query2 = (new Query())
                ->select(['cc' => 'count(*)'])
                ->from('items')
                ->where(['items_id' => $property_id]);
        $findID = $query2->createCommand()->queryScalar(); //Add PK
        $m = Items::findOne($items_id); //Clone data->select(['cc' => 'RIGHT(items_id,1)'])

        if ($findID == 0) {
            $m2 = new Items($m->getAttributes()); //Clone data
            $m2->items_id = $property_id;
        } else {
            $m2 = Items::findOne($property_id);
        }

        $m2->items_name = $m->items_name . ' ' . $items_property_name;
        $m2->items_property_id = $pk;
        $m2->items_status = 1;
        $m2->items_group_id = 1;
        if (!$m2->save()) {
            #print_r($m2->getErrors());
            #exit;
        }
    }

    /**
     * Creates a new Items model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new AssetItems();
        $model->create_at = new \yii\db\Expression('NOW()');
        $model->asset_item_active = 1; //สถานะ

        if ($this->request->isPost && $model->load($this->request->post())) {
            //$model->items_photo = $model->upload($model, 'items_photo');
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);

                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลไม่สำเร็จ..',
                    'options' => ['class' => 'alert-warning']
                ]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Items model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $post = Yii::$app->request->post();
        $model = AssetItems::findOne($id);
        //$Propertys = ItemsProperty::find()->where("items_ref_id = '{$id}'")->orderBy(['items_id' => 'ASC']);
        /*
          $cc = $Propertys->count();
          $modelPropertys = $Propertys->all();

          if (!$modelPropertys) {
          $modelPropertys = [new ItemsProperty()];
          if (isset($post['ItemsProperty'])) {
          for ($i = 0; $i < count($post['ItemsProperty']); $i++) {
          $modelPropertys[] = new ItemsProperty();
          }
          }
          } elseif (isset($post['ItemsProperty'])) {

          if ($cc <> count($post['ItemsProperty']))
          $modelPropertys = array_merge_recursive($modelPropertys, [new ItemsProperty()]);
          }
         */
        $model->update_at = new \yii\db\Expression('NOW()');

        if ($model->load($post)) {
            //$model->items_photo = $model->upload($model, 'items_photo');
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);

                //return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลไม่สำเร็จ..',
                    'options' => ['class' => 'alert-warning']
                ]);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                        //'modelPropertys' => $Propertys,
        ]);
    }

    /**
     * Deletes an existing Items model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        //ตรวจสอบข้อมูลก่อนลบข้อมูล
        $check01 = AssetStockinList::find()->where(['asset_item_id' => $id])->count();
        //$check02 = PurchaseOrderDetail::find()->where(['items_id' => $id])->count();
        //$check03 = ItemsProperty::find()->where(['items_ref_id' => $id])->count();

        if (($check01) == 0) {
            try {
                //ลบข้อมูล Property
//                if (($pp = ItemsProperty::find()->where(['asset_item_id' => $id])->one()) !== null) {
//                    $pp->delete();
//                }
            } catch (\Exception $exc) {
                //echo $exc->getTraceAsString();
            }


            //------------------------------------------------------------------
            $model = AssetItems::findOne($id);
            if ($model->delete()) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'ลบข้อมูลสำเร็จ...',
                    'options' => ['class' => 'alert-success']
                ]);
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'ลบข้อมูลไม่สำเร็จ..',
                    'options' => ['class' => 'alert-danger']
                ]);
            }
            return $this->redirect(['index']);
        } else {
            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากมีการใช้งานอยู่ค่ะ..',
                'options' => ['class' => 'alert-danger']
            ]);
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionModel($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = new \yii\db\Query();
        $query->select('model_id AS id, model_name AS name, model_code AS code,model_detail as caption')
                ->from('model')
                ->where("model_id = '" . $id . "'")
                ->limit(30);
        $command = $query->createCommand();
        $data = $command->queryOne();
        return $data;
    }

    public function actionModellist() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $query = new \yii\db\Query();
                $query->select('model_id AS id, model_name AS name,model_name AS groups,model_code,model_detail')
                        ->from('model')
                        #->where(['province_id' => $cat_id])
                        ->where("brand_id = '" . $cat_id . "'")
                        ->limit(30);
                $command = $query->createCommand();
                $data = $command->queryAll();
                $dataArray = [];
                foreach ($data as $key => $value) {
                    $dataArray[$value['groups']][] = [
                        'id' => $value['id'],
                        'name' => '<b class="text-danger">' . $value['model_code'] . '</b> ' . $value['groups'] . ' ' . $value['model_detail'],
                            #'code' => $value['model_code'],
                            #'caption' => $value['groups'],
                            #'detail' => $value['model_detail'],
                    ];
                }


                $out = array_values($dataArray);

                return ['output' => $dataArray, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionSetdisplay($id, $status) {
        $model = AssetItems::findOne($id);
        $model->asset_item_active = $status;
        $model->save();
    }

    public function actionUploadphoto($id) {
        $model = AssetItems::findOne($id);
        if ($model) {
            $uploadDir = $model->getPath() . DIRECTORY_SEPARATOR . $model->asset_item_id;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir);
            }
            if (Yii::$app->request->isPost) {
                $file = UploadedFile::getInstancesByName('photo_upload');
                foreach ($file as $image) {
                    $ext = $image->extension;
                    $string_to_encrypt = "Items";
                    $encrypted_string = openssl_encrypt($string_to_encrypt, "AES-128-ECB", $image->name);
                    $encrypted_string = str_replace('/', '', $encrypted_string);
                    $encrypted_string = str_replace('==', '', $encrypted_string);
                    $imageName = $encrypted_string . ".{$ext}";
                    $path = $uploadDir . DIRECTORY_SEPARATOR . $imageName;
                    $image->saveAs($path);
                    Image::thumbnail($path, 1000, 1000)
                            #->resize(new Box(500, 500))
                            ->save($path, ['quality' => 100]);
                }
                if (Yii::$app->request->isAjax) {
                    return json_encode(['success' => 'true']);
                }
            }
        }

        return $this->renderAjax('_uploadphoto', [
                    'model' => $model,
        ]);
    }

}
