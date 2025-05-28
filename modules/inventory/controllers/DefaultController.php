<?php

namespace app\modules\inventory\controllers;

use yii\web\Controller;
use app\modules\hr\models\Employee;
use yii\data\ArrayDataProvider;
use app\modules\inventory\models\Stock;
use app\modules\inventory\models\StockCard;
use yii\data\ActiveDataProvider;

/**
 * Default controller for the `inventory` module
 */
class DefaultController extends Controller {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $param = \Yii::$app->request->get();

        $group = '';
        if (isset($param['stockStatus'])) {
            switch ($param['stockStatus']) {
                case 1:
                    $group = " HAVING SUM(quantity) > 0 ";
                    break;
                case 2:
                    $group = " HAVING SUM(quantity) < 1 ";
                    break;
                case 3:
                    $group = " HAVING (SUM(quantity) < 5 AND SUM(quantity) > 0)";
                    break;
                case 4:
                    $group = " HAVING SUM(quantity) = 0 ";
                    break;
                default:
                    break;
            }
        }

        $where = "";
        if (isset($param['keySearch']))
            $where = " AND (a1.lot_no LIKE '%{$param['keySearch']}%'
                OR asset_item_name LIKE '%{$param['keySearch']}%'
                )";

        $emp = Employee::findOne(['employee_cid' => \Yii::$app->user->identity->profile->cid]);
        try {
            $query = "SELECT
                        a1.asset_item_id
                        ,a1.lot_no
                        #,a1.stock_asset_item_id
                        ,asset_item_name
                        ,asset_unit_name
                        ,ifnull(CONCAT(a5.categories_name,' -> ',a4.categories_name),a4.categories_name) AS categories_name
                        ,SUM(quantity) AS cc
                        ,'' as cname
                        FROM stock a1
                        LEFT JOIN asset_items a2 ON a1.asset_item_id = a2.asset_item_id
                        LEFT JOIN asset_item_unit a3 ON a3.asset_unit_id = a2.asset_unit_id
                        LEFT JOIN items_categories a4 ON a4.categories_id = a2.categories_id
                        LEFT JOIN items_categories a5 ON a5.categories_id = a4.categories_group
                        #LEFT JOIN asset_stockout_list a6 ON a6.lot_no = a1.lot_no
                        WHERE a1.asset_master_type_id
                            {$where}
                        GROUP BY a1.asset_item_id
                            {$group}
                        ";
            $result = \Yii::$app->db_inventory->createCommand($query)->queryAll();
            $attributes = @count($result[0]) > 0 ? array_keys($result[0]) : array(); //หาชื่อ field ในตาราง
            $dataProvider = new ArrayDataProvider([
                'allModels' => $result,
                'sort' => [
                    'attributes' => $attributes,
                ],
                'pagination' => [
                    'pageSize' => 200,
                ],
            ]);
        } catch (\Exception $e) {
            throw new \yii\web\HttpException(405, 'Error MySQL Query' . $e->getMessage());
            $dataProvider = new ArrayDataProvider();
        }

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'data' => $result
        ]);
    }

    public function actionAllstock() {
        $param = \Yii::$app->request->get();
        $group = '';
        if (isset($param['stockStatus'])) {
            switch ($param['stockStatus']) {
                case 1:
                    $group = " HAVING SUM(quantity) > 0 ";
                    break;
                case 2:
                    $group = " HAVING SUM(quantity) < 1 ";
                    break;
                case 3:
                    $group = " HAVING (SUM(quantity) < 5 AND SUM(quantity) > 0)";
                    break;
                case 4:
                    $group = " HAVING SUM(quantity) = 0 ";
                    break;
                default:
                    break;
            }
        }

        $where = "";
        if (isset($param['keySearch']))
            $where = " AND (a1.lot_no LIKE '%{$param['keySearch']}%'
                OR items_name LIKE '%{$param['keySearch']}%'
                )";

        $emp = Employee::findOne(['employee_cid' => \Yii::$app->user->identity->profile->cid]);
        try {
            $query = "SELECT
                        a1.asset_item_id
                        ,a1.lot_no
                        ,a1.stock_asset_item_id
                        ,items_name
                        ,items_unit_name
                        ,ifnull(CONCAT(a5.categories_name,' -> ',a4.categories_name),a4.categories_name) as categories_name
                        ,SUM(quantity) AS cc
                        FROM stock a1
                        LEFT JOIN items a2 ON a1.asset_item_id = a2.asset_item_id
                        LEFT JOIN items_unit a3 ON a3.items_unit_id = a2.items_unit_id
                        LEFT JOIN items_categories a4 ON a4.categories_id = a2.categories_id
                        LEFT JOIN items_categories a5 ON a5.categories_id = a4.categories_group
                        WHERE 1 #a1.branch_no = '{$emp->branch_no}'
                            {$where}
                        GROUP BY a1.asset_item_id
                            {$group}
                        ";
            $result = \Yii::$app->db->createCommand($query)->queryAll();
            $attributes = @count($result[0]) > 0 ? array_keys($result[0]) : array(); //หาชื่อ field ในตาราง
            $dataProvider = new ArrayDataProvider([
                'allModels' => $result,
                'sort' => [
                    'attributes' => $attributes,
                ],
                'pagination' => [
                    'pageSize' => 200,
                ],
            ]);
        } catch (\Exception $e) {
            throw new \yii\web\HttpException(405, 'Error MySQL Query' . $e->getMessage());
            $dataProvider = new ArrayDataProvider();
        }

        return $this->render('allstock', [
                    'dataProvider' => $dataProvider,
                    'data' => $result
        ]);
    }

    public function actionStockcard($id, $lot) {
        $model = Stock::find()->where(['asset_item_id' => $id, 'lot_no' => $lot])->one();
        $query = StockCard::find()->where(['asset_item_id' => $id, 'lot_no' => $lot]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['stock_date' => SORT_ASC]]
        ]);
        return $this->render('stockcard', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
        ]);
    }

    public function actionStock($id) {
        $model = Stock::find()->where(['asset_item_id' => $id]);
        //$query = StockCard::find()->where(['asset_item_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
                // 'sort' => ['defaultOrder' => ['stock_date' => SORT_ASC]]
        ]);
        return $this->render('stock', [
                    'dataProvider' => $dataProvider,
                    'model' => $model->one(),
        ]);
    }

    public function actionSearch() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = \Yii::$app->request->get();
        $where = '';
        if (isset($post['search']) && !empty($post['search'])) {
            $where = " and (a2.items_id LIKE '%{$post['search']}%' || a2.items_name LIKE '%{$post['search']}%' )";
        }
        try {
            $query = "SELECT
a1.items_id
,a1.stock_items_id
,items_name
,items_unit_name
,a1.lot_no
,ifnull(CONCAT(a5.categories_name,' -> ',a4.categories_name),a4.categories_name) as categories_name
#,SUM(quantity) AS cc
,quantity
,b.branch_name
,b.branch_no
,brand_name
,model_detail
,m.*
,po.engine_no
,po.vin_no
,c.items_color_name
FROM stock a1
LEFT JOIN items a2 ON a1.items_id = a2.items_id
LEFT JOIN branch b ON b.branch_no = a1.branch_no
LEFT JOIN model m ON m.model_id = a2.model_id
LEFT JOIN brand bb ON bb.brand_id = a2.brand_id
LEFT JOIN purchase_order_detail po ON po.vin_no = a1.lot_no AND po.items_id = a1.items_id
LEFT JOIN items_color c ON c.items_color_id = po.items_color_id
LEFT JOIN items_unit a3 ON a3.items_unit_id = a2.items_unit_id
LEFT JOIN items_categories a4 ON a4.categories_id = a2.categories_id
LEFT JOIN items_categories a5 ON a5.categories_id = a4.categories_group
WHERE quantity > 0
#{$where}
#GROUP BY a1.items_id
";
            $result = \Yii::$app->db->createCommand($query)->queryAll();
            $json = [];
            foreach ($result as $key => $value) {
                $json['listItems'][] = ['name' => $value['model_code'] . ' ' . $value['brand_name'] . ' ' . $value['items_name'] . ' ' . $value['items_color_name'] . '  | <b class="text-success">' . $value['branch_name'] . ' พร้อมจำหน่าย</b>',
                    'url' => Url::to(['/store/default/index']),
                    'icon' => 'feather icon-shopping-cart',
                        ]
                ;
            }
        } catch (\Exception $e) {
            $json = [];
        }
        //echo json_encode($json);
        return $json;
    }

    public function actionUpstatus($id, $status) {
        $ref = '';
        $model = ItemsSale::findOne($id);
        $model->items_sale_status_id = $status;
        if ($status == 99) {//ยกเลิกรายการ
            $model->sale_cancel_date = new \yii\db\Expression('NOW()');
        } else {
            $model->sale_cancel_date = '';
        }

        if ($model->save()) {
            if ($model->items_sale_status_id == 3) {
                $ref = CStock::StockSaleUpdate($model->sale_no); //ปรับ Stock
                CStock::StockGiftUpdate($model->sale_id); //ปรับ Stock ของแถม
                if ($ref) {
                    Yii::$app->getSession()->setFlash('alert', [
                        'body' => 'ระบบปรับ Stock สำเร็จ..',
                        'options' => ['class' => 'alert-primary']
                    ]);
                }
            } elseif ($model->items_sale_status_id == 99) {//ยกเลิกรายการ
                /**
                  คืนการใช้งานทะเบียนป้ายแดง
                 */
                if (!empty($model->regis_no)) {
                    if (($plate = LicensePlateUsed::findOne(['ref_id' => $model->sale_no])) !== null) {
                        $plate->black_date = new \yii\db\Expression('NOW()');
                        $plate->license_plate_statusid = 1;
                        $plate->used_remark = 'รายการถูกยกเลิก จากการขาย';
                        $plate->save();
                    }
                }
                $ref = CStock::StockSaleUpdate($model->sale_no, 'IN'); //ปรับ Stock
                CStock::StockGiftUpdate($model->sale_id, 'IN'); //ปรับ Stock ของแถม
//if ($ref) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'รายการถูกยกเลิกรายการสำเร็จ..',
                    'options' => ['class' => 'alert-danger']
                ]);
//}
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'ปรับสถานะข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
            }
        } else {
            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'ปรับสถานะข้อมูลไม่สำเร็จ..',
                'options' => ['class' => 'alert-warning']
            ]);
        }
    }

    public function actionStockitems() {
        $get = \Yii::$app->request->get();
        //$post = \Yii::$app->request->post();
        $where = '';
        if (isset($get['search']) && !empty($get['search'])) {
            $where = " AND (a2.asset_item_id LIKE '%{$get['search']}%' || a2.asset_item_name LIKE '%{$get['search']}%' || a1.lot_no LIKE '%{$get['search']}%' ) ";
        }

        //if (isset($post['master']) && !empty($post['master'])) {
        $where .= " AND a1.asset_master_type_id = '{$get['master']}'";
        //}

        $emp = Employee::findOne(['employee_cid' => \Yii::$app->user->identity->profile->cid]);
        try {
            $query = "SELECT
a1.asset_item_id
,a1.stock_items_id
,asset_item_name
,asset_unit_name
,a1.lot_no
,po.exp_date
,ifnull(CONCAT(a5.categories_name,' -> ',a4.categories_name),a4.categories_name) as categories_name
#,SUM(quantity) AS cc
,quantity
,b.asset_master_type_name
,b.asset_master_type_id
FROM stock a1
LEFT JOIN asset_items a2 ON a1.asset_item_id = a2.asset_item_id
LEFT JOIN asset_master_type b ON b.asset_master_type_id = a1.asset_master_type_id
LEFT JOIN asset_stockin_list po ON po.lot_no = a1.lot_no AND po.asset_item_id = a1.asset_item_id
#LEFT JOIN asset_stockin s ON po.asset_stockin_id = s.asset_stockin_id
LEFT JOIN asset_item_unit a3 ON a3.asset_unit_id = a2.asset_unit_id
LEFT JOIN items_categories a4 ON a4.categories_id = a2.categories_id
LEFT JOIN items_categories a5 ON a5.categories_id = a4.categories_group
WHERE quantity > 0
    {$where}
#GROUP BY a1.items_id
ORDER BY a1.asset_item_id asc ,a1.lot_no asc,po.exp_date asc
LIMIT 10
";
            $result = \Yii::$app->db_inventory->createCommand($query)->queryAll();
            $attributes = @count($result[0]) > 0 ? array_keys($result[0]) : array(); //หาชื่อ field ในตาราง
            $dataProvider = new ArrayDataProvider([
                'allModels' => $result,
                'sort' => [
                    'attributes' => $attributes,
                ],
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } catch (\Exception $e) {
            throw new \yii\web\HttpException(405, 'Error MySQL Query' . $e->getMessage());
            $dataProvider = new ArrayDataProvider();
        }


        return $this->renderAjax('_stockitems', [
                    'dataProvider' => $dataProvider,
                        // 'branch_no' => $emp->branch_no
        ]);
    }

    public function actionSummary() {

        $query = "SELECT
asset_master_type_id
,asset_item_name
,a.asset_item_id
,asset_unit_name
,a.lot_no

,SUM(quantity) AS amount
,SUM(price*quantity) AS pp
,SUM(c.ss_in) AS quantity_up
,SUM(c.ss_out) AS quantity_down
,SUM(c.bb)  AS balance

FROM stock a
INNER JOIN (SELECT * FROM asset_stockin_list b2 GROUP BY b2.asset_item_id) b ON a.asset_item_id = b.asset_item_id AND a.lot_no = b.lot_no
INNER JOIN asset_items i ON i.asset_item_id = a.asset_item_id
LEFT JOIN asset_item_unit u ON i.asset_unit_id = u.asset_unit_id
LEFT JOIN (SELECT
asset_item_id
,lot_no
,SUM(IF(tt = 'IN',amount,0)) AS ss_in
,SUM(IF(tt = 'OUT',amount,0)) AS ss_out
,SUM(IF(tt = 'IN',amount,0)) - SUM(IF(tt = 'OUT',amount,0)) AS bb
FROM
(SELECT
asset_master_type_id
,a1.asset_item_id
,a1.lot_no
,amount
,'IN' AS tt
,0 AS pp
FROM asset_stockin_list a1
INNER JOIN asset_stockin a2 ON a1.asset_stockin_id = a2.asset_stockin_id
WHERE a2.asset_master_type_id
AND asset_order_status_id = 3
UNION ALL
SELECT
asset_master_type_id
,a1.asset_item_id
,a1.lot_no
,amount
,'OUT' AS tt
,0 AS pp
FROM asset_stockout_list a1
INNER JOIN asset_stockout a2 ON a1.asset_stockout_id = a2.asset_stockout_id
WHERE a2.asset_master_type_id
AND asset_order_status_id = 3) q
GROUP BY asset_item_id,lot_no) c ON a.asset_item_id = c.asset_item_id AND a.lot_no = c.lot_no
WHERE 1
GROUP BY asset_item_id";

        $result = \Yii::$app->db_inventory->createCommand($query)->queryAll();
        $attributes = @count($result[0]) > 0 ? array_keys($result[0]) : array(); //หาชื่อ field ในตาราง
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'sort' => [
                'attributes' => $attributes,
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $this->render('summary', [
                    'dataProvider' => $dataProvider,
        ]);
    }

}
