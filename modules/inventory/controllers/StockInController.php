<?php

namespace app\modules\inventory\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\inventory\models\AssetStockin;
use app\modules\inventory\models\AssetStockinList;
use app\components\Ccomponent;
use app\modules\inventory\components\CStock;
use yii\db\Expression;

/**
 * PurchaseOrderController implements the CRUD actions for PurchaseOrder model.
 */
class StockInController extends Controller {

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
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $query = AssetStockin::find();
        $query->joinWith('master');
        $query->where(['AND',
            new Expression(" FIND_IN_SET(asset_master_type_code , (SELECT asset_master_type_code FROM asset_user WHERE asset_master_type_active = 1 AND asset_user.employee_id = '{$emp->employee_id}')) "),
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => ['defaultOrder' => ['asset_stockin_date' => SORT_DESC]],
        ]);
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = new AssetStockin();
        $model2 = new AssetStockinList();
        $query = AssetStockinList::find()->with('items')->where("0");
        $modelList = new ActiveDataProvider([
            'query' => $query,
        ]);
        $model->employee_id = $emp->employee_id;
        //$model->employee_dep_id = $emp->employee_dep_id;
        $model->asset_order_status_id = '00'; //สถานะเริ่มต้น
        $model->asset_stockin_date = date('Y-m-d');
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->asset_stockin_id]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'model2' => $model2,
                    'modelList' => $modelList,
        ]);
    }

    public function actionUpdate($id) {
        $model = AssetStockin::findOne($id);
        $query = AssetStockinList::find()->with('items')->where("asset_stockin_id = '{$id}'");
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

            return $this->redirect(['update', 'id' => $model->asset_stockin_id]);
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
        $model = AssetStockin::findOne($rid);

        if ($pid > 0) {
            $model2 = AssetStockinList::findOne($pid);
        } else {
            $model2 = new AssetStockinList();
        }

        $model2->asset_stockin_id = $model->asset_stockin_id;
        if ($model2->load(Yii::$app->request->post()) && $model2->validate()) {

            if ($model2->save()) {
                $sModel = AssetStockinList::find()->where(['asset_stockin_id' => $model->asset_stockin_id])->sum('(price*amount)');
                $model->asset_stockin_summary = $sModel;
                $model->save();
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

            return $this->redirect(['update', 'id' => $model->asset_stockin_id]);
        }

        return $this->renderAjax('frmdetail', [
                    'model' => $model,
                    'model2' => $model2,
                        //'modelList' => $modelList,
        ]);
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
        $model = AssetStockin::findOne($id);
        if ($model->asset_order_status_id == 0) {
            AssetStockinList::findOne($id2)->delete();
        } else {
            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'ไม่สามารถลบข้อมูลได้เนื่องจาก รายการสินค้านี้อยู่ในสถานะรับสินค้าเข้า Stock แล้ว.. หรืออยู่ในสถานะที่ไม่สามารถลบได้ ค่ะ...',
                'options' => ['class' => 'alert-danger'],
            ]);
        }

        return $this->redirect(['update', 'id' => $id]);
    }

    public function actionUpstatus($id, $status) {
        $model = AssetStockin::findOne($id);
        $model->asset_order_status_id = $status;

        if ($model->save()) {
            if ($model->asset_order_status_id == 3) {
                $result = CStock::StockcardPO($id); //ปรับ Stock
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
