<?php

namespace app\modules\inventory\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\inventory\models\AssetStockout;
use app\modules\inventory\models\AssetStockoutList;
use app\components\Ccomponent;
use app\modules\inventory\components\CStock;
use yii\db\Expression;

/**
 * PurchaseOrderController implements the CRUD actions for PurchaseOrder model.
 */
class StockOutController extends Controller {

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

    public function actionIndex() {
        $param = \Yii::$app->request->get();
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $query = AssetStockout::find();
        $query->select('`asset_stockout`.*');
        //$query->addSelect('s.*');
        $query->joinWith('master');
        //$query->joinWith(['stockoutList', 'items']);
        $query->join('LEFT JOIN', 'asset_stockout_list s', 'asset_stockout.asset_stockout_id = s.asset_stockout_id');
        $query->join('LEFT JOIN', 'asset_items i', 'i.asset_item_id = s.asset_item_id');
        // if (isset($param['textSearch']) && !empty($param['textSearch'])) {
        $query->where(['AND',
            new Expression(" FIND_IN_SET(asset_master_type_code , (SELECT asset_master_type_code FROM asset_user WHERE asset_master_type_active = 1 AND asset_user.employee_id = '{$emp->employee_id}')) "),
        ]);
        $query->andWhere(['OR',
            ['asset_stockout.asset_stockout_id' => $param['textSearch']],
            ['asset_stockout_no' => $param['textSearch']],
            ['asset_stockout_refno' => $param['textSearch']],
            ['asset_stockout_comment' => $param['textSearch']],
            ['asset_item_name' => $param['textSearch']],
            ['sku' => $param['textSearch']],
            ['asset_item_detail' => $param['textSearch']],
        ]);
        // }
        $query->groupBy(['asset_stockout.asset_stockout_id']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => ['defaultOrder' => ['asset_stockout_date' => SORT_DESC]],
        ]);
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = new AssetStockout();
        $model2 = new AssetStockoutList();
        $query = AssetStockoutList::find()->with('items')->where("0");
        $modelList = new ActiveDataProvider([
            'query' => $query,
        ]);
        $model->employee_id = $emp->employee_id;
        //$model->employee_dep_id = $emp->employee_dep_id;
        $model->asset_order_status_id = '00'; //สถานะเริ่มต้น
        $model->asset_stockout_date = date('Y-m-d');
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->asset_stockout_id]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'model2' => $model2,
                    'modelList' => $modelList,
        ]);
    }

    public function actionUpdate($id) {
        $model = AssetStockout::findOne($id);
        $query = AssetStockoutList::find()->with('items')->where("asset_stockout_id = '{$id}'");
        $modelList = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-primary'],
                ]);
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลไม่สำเร็จ..',
                    'options' => ['class' => 'alert-warning'],
                ]);
            }

            return $this->redirect(['update', 'id' => $model->asset_stockout_id]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'modelList' => $modelList,
        ]);
    }

    public function actionUpdatedetail() {
        $post = Yii::$app->request->post();
        $rid = $post['rid'];
        $pid = isset($post['pid']) ? $post['pid'] : 0; //สำหรับแก้ไขรายการ
        $sn = isset($post['sn']) ? $post['sn'] : 0; //บอกประเภทการบันทึก
        $model = AssetStockout::findOne($rid);
        $quantity = isset($post['quantity']) ? $post['quantity'] : 0; //สำหรับแก้ไขจำนวน

        if ($pid > 0) {
            $model2 = AssetStockoutList::findOne($pid);
        } else {
            $model2 = (AssetStockoutList::find()->where(['asset_stockout_id' => $rid, 'asset_item_id' => $post['items'], 'lot_no' => $post['lot']])->one() ?: new AssetStockoutList());
        }

        $model2->asset_item_id = $post['items'];
        $model2->lot_no = $post['lot'];
        if ($model2->isNewRecord) {
            $model2->amount = $quantity;
            $model2->asset_stockout_id = $rid;
        } else {
            $model2->amount = $model2->amount + $quantity;
        }
        if ($model2->validate()) {

            if ($model2->save()) {
                /*
                  $sModel = AssetStockoutList::find()->where(['asset_stockout_id' => $model->asset_stockout_id])->sum('(price*amount)');
                  $model->asset_stockout_summary = $sModel;
                  $model->save();
                 */
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success'],
                ]);
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลไม่สำเร็จ..' . print_r($model2->getErrors(), true),
                    'options' => ['class' => 'alert-warning'],
                ]);
            }

            //return $this->redirect(['update', 'id' => $model->asset_stockout_id]);
        }
        /*
          return $this->renderAjax('frmdetail', [
          'model' => $model,
          'model2' => $model2,
          //'modelList' => $modelList,
          ]);
         *
         */
    }

    public function actionAutosearch($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $model = \app\modules\inventory\models\AssetItems::find()->where("(asset_item_id like '%$q%' || asset_item_name like '%$q%') ")->all();
            $data = [];
            foreach ($model as $get) {
                $data[] = [
                    'text' => '<b class="text-danger">' . $get->asset_item_id . '</b> ' . $get->asset_item_name . ' ',
                    'value' => $get->asset_item_id . ' ' . $get->asset_item_name . ' -',
                    'units' => '',
                    'id' => $get->asset_item_id,
                ];
            }

            $out['results'] = array_values($data);
        } elseif ($id > 0) {
//$out['results'] = ['id' => $id, 'text' => StockItem::find($id)->name];
        }
        return $out;
    }

    public function actionDeleteDetail($id, $id2) {
        $model = AssetStockout::findOne($id);
        if ($model->asset_order_status_id == 0) {
            AssetStockoutList::findOne($id2)->delete();
        } else {
            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'ไม่สามารถลบข้อมูลได้เนื่องจาก รายการสินค้านี้อยู่ในสถานะรับสินค้าเข้า Stock แล้ว.. หรืออยู่ในสถานะที่ไม่สามารถลบได้ ค่ะ...',
                'options' => ['class' => 'alert-danger'],
            ]);
        }

        return $this->redirect(['update', 'id' => $id]);
    }

    public function actionUpstatus($id, $status) {
        $model = AssetStockout::findOne($id);
        $model->asset_order_status_id = $status;

        if ($model->save()) {
            if ($model->asset_order_status_id == 3) {
                $result = CStock::StockOrderUpdate($id); //ปรับ Stock
                //print_r($result);
            }
            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'ปรับสถานะข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-success'],
            ]);
        } else {
            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'ปรับสถานะข้อมูลไม่สำเร็จ..',
                'options' => ['class' => 'alert-warning'],
            ]);
        }
    }

}
