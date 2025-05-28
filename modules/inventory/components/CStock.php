<?php

namespace app\modules\inventory\components;

use Yii;
use app\modules\inventory\models\Stock;
use app\modules\inventory\models\AssetStockinList;
use app\modules\hr\models\Employee;
use app\modules\inventory\models\StockCard;
use app\modules\inventory\models\AssetStockoutList;
use app\modules\inventory\models\AssetStockout;

class CStock {

    /**
      ตรวจสอบการรับสินค้าเข้า Stock จากการขนส่งสินค้า
     */
    public static function CheckitemsStock($id, $source) {

        if ($source == 'origin') {
            $fieldSource = 'origin';
            $fieldQuantity = 'down';
        }
        if ($source == 'destination') {
            $fieldSource = 'destination';
            $fieldQuantity = 'up';
        }
        try {
            $query = "SELECT
                            COUNT(1) AS cc
                            ,COUNT(if(c.ref_id is not null,1,null)) as cc_complete
                            FROM items_tranfer b
                            LEFT JOIN items_tranfer_detail a ON a.items_tranfer_id = b.items_tranfer_id
                            LEFT JOIN stock_card c ON c.ref_id = b.items_tranfer_no
                            AND c.branch_no = b.branch_{$fieldSource}
                            AND c.asset_item_id = a.asset_item_id
                            AND c.lot_no = a.lot_no
                            AND c.quantity_{$fieldQuantity} = a.amount
                            WHERE b.items_tranfer_id = '{$id}'";

            $row = \Yii::$app->db->createCommand($query)->queryOne();
        } catch (\Exception $e) {
            throw new \yii\web\HttpException(405, 'Error MySQL Query' . $e->getMessage());
            $row = [];
        }

        if ($row['cc'] == $row['cc_complete']) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function getItems($id) {
        $model = Stock::find()->where(['stock_asset_item_id' => $id])->joinWith('items')->one();
        return $model;
    }

    public static function StockUpdate($branch, $item, $lot, $quantity, $operate) {

        $query = "SELECT
                    i.asset_item_id
                    , IFNULL(orders.out1,0) AS out1
                    , IFNULL(recept.in1,0) AS in1
                    ,(IFNULL(recept.in1,0) - IFNULL(orders.out1,0)) AS remain
                    FROM asset_items i

                    LEFT JOIN (SELECT SUM(amount) AS in1 ,asset_item_id
                    FROM asset_stockin_list a
                    INNER JOIN asset_stockin b ON a.asset_stockin_id = b.asset_stockin_id
                    WHERE asset_item_id = '{$item}'
                    AND lot_no = '{$lot}'
                    AND asset_master_type_id = '{$branch}'
                    AND asset_order_status_id = 3) AS recept ON recept.asset_item_id = i.asset_item_id

                    LEFT JOIN (SELECT SUM(amount) AS out1 ,asset_item_id
                    FROM asset_stockout_list a
                    INNER JOIN asset_stockout b ON a.asset_stockout_id = b.asset_stockout_id
                    WHERE asset_item_id = '{$item}'
                    AND lot_no = '{$lot}'
                    AND asset_master_type_id = '{$branch}'
                    AND asset_order_status_id = 3) AS orders ON orders.asset_item_id = i.asset_item_id

                    WHERE i.asset_item_id = '{$item}'

                    ";

        $data = \Yii::$app->db_inventory->createCommand($query)->queryOne();
        $model = Stock::find()->where(['asset_master_type_id' => $branch, 'asset_item_id' => $item, 'lot_no' => $lot])->one();

        if (!$model) {
            $model = new Stock();
            $model->asset_master_type_id = $branch;
            $model->asset_item_id = $item;
            $model->lot_no = $lot;
        }

        $model->quantity = $data['remain']; //Update current quantity
        $model->stock_update = new \yii\db\Expression('NOW()');
        if ($operate)
            $model->save();

        return $data['remain'];
    }

    public static function StockcardPO($po_id) {

        $data = AssetStockinList::find()->joinWith('assetStockin')->select('*')->where("asset_stockin_list.asset_stockin_id = '{$po_id}' AND asset_order_status_id ")->asArray()->all();

        $ref = [];
        $emp = Employee::findOne(['employee_cid' => Yii::$app->user->identity->profile->cid]);
        foreach ($data as $item) {
            $card = StockCard::find()->where(['ref_id' => $item['asset_stockin_no'], 'asset_master_type_id' => $item['asset_master_type_id'], 'asset_item_id' => $item['asset_item_id'], 'lot_no' => $item['lot_no']])->one();

            if (!$card) {
//เป็นการเพิ่มรายการใหม่
                $card = new StockCard();
                $card->asset_item_id = $item['asset_item_id'];
                $card->lot_no = $item['lot_no'];
                $card->asset_master_type_id = $item['asset_master_type_id'];
                $card->ref_id = $item['asset_stockin_no'];
                $card->quantity_up = $item['amount'];
                $bb = $item['amount'];
                $card->stock_type_id = 1; //IN
                $card->quantity_down = 0;
            } else {
//กรณีแก้ไขหรือปรับรายการ ระบบจะหาส่วนต่าง
                if ($card->quantity_up > $item['amount']) {
                    $bb = ($item['amount'] - $card->quantity_up);
                    $card->remark = 'ปรับเนื่องจากมีการปรับแก้ไขรายการ';
                } elseif ($card->quantity_up < $item['amount']) {
                    $bb = ($item['amount'] - $card->quantity_up);
                    $card->remark = 'ปรับเนื่องจากมีการปรับแก้ไขรายการ';
                } else {
                    continue;
                }
            }
            $card->quantity_up = $item['amount'];
            $card->quantity_down = 0;
            $card->balance = self::StockUpdate($item['asset_master_type_id'], $item['asset_item_id'], $item['lot_no'], $bb, FALSE); //ปรับ balance stock
            $card->stock_type_id = 1; //IN
            $card->stock_date = new \yii\db\Expression('NOW()');
            $card->employee_id = $emp->employee_id;
            if ($card->save()) {

            }
            $ref = self::StockUpdate($item['asset_master_type_id'], $item['asset_item_id'], $item['lot_no'], $bb, TRUE); //ปรับ balance stock
        }
        $ref = $data;
        return $ref;
    }

    /**
      ตรวจสอบการรับสินค้าเข้า Stock จากการเบิกสินค้า
     */
    public static function StockOrderUpdate($id, $mod = 'OUT') {

        $data = AssetStockoutList::find()->joinWith('assetStockout')->select('*')->where("asset_stockout_list.asset_stockout_id = '{$id}' AND asset_order_status_id = 3")->asArray()->all();
        $ref = [];
        $emp = Employee::findOne(['employee_cid' => Yii::$app->user->identity->profile->cid]);
        foreach ($data as $item) {
            $card = StockCard::find()->where(['ref_id' => $item['asset_stockout_no'], 'asset_master_type_id' => $item['asset_master_type_id'], 'asset_item_id' => $item['asset_item_id'], 'lot_no' => $item['lot_no']])->one();

            if (!$card) {
//เป็นการเพิ่มรายการใหม่
                $card = new StockCard();
                $card->asset_item_id = $item['asset_item_id'];
                $card->lot_no = $item['lot_no'];
                $card->asset_master_type_id = $item['asset_master_type_id'];
                $card->ref_id = $item['asset_stockout_no'];
                $card->quantity_down = $item['amount'];
                $bb = $item['amount'];
                $card->stock_type_id = 2; //-OUT
                $card->quantity_up = 0;
            } else {
//กรณีแก้ไขหรือปรับรายการ ระบบจะหาส่วนต่าง
                if ($card->quantity_down > $item['amount']) {
                    $bb = ($item['amount'] - $card->quantity_down);
                    $card->remark = 'ปรับเนื่องจากมีการปรับแก้ไขรายการ';
                } elseif ($card->quantity_down < $item['amount']) {
                    $bb = ($item['amount'] - $card->quantity_down);
                    $card->remark = 'ปรับเนื่องจากมีการปรับแก้ไขรายการ';
                } else {
                    continue;
                }
            }
            $card->quantity_down = $item['amount'];
            $card->quantity_up = 0;
            $card->balance = self::StockUpdate($item['asset_master_type_id'], $item['asset_item_id'], $item['lot_no'], $bb, FALSE); //ปรับ balance stock
            $card->stock_type_id = 2; //-OUT
            $card->stock_date = new \yii\db\Expression('NOW()');
            $card->employee_id = $emp->employee_id;
            if ($card->save()) {

            }
            $ref = self::StockUpdate($item['asset_master_type_id'], $item['asset_item_id'], $item['lot_no'], $bb, TRUE); //ปรับ balance stock
        }
        $ref = $data;
        return $ref;

        /*
          $ref = false;

          if ($mod == 'OUT') {
          $stock_type_id = 2;
          }
          if ($mod == 'IN') {
          $stock_type_id = 1;
          }

          $data = AssetStockoutList::find()->joinWith('assetStockout')->select('*')->where("asset_stockout_list.asset_stockout_id = '{$id}' AND asset_order_status_id = 3")->asArray()->all();
          foreach ($data as $item) {
          $card = StockCard::find()->where(['ref_id' => $item['asset_stockout_no'], 'asset_master_type_id' => $item['asset_master_type_id'], 'asset_item_id' => $item['asset_item_id'], 'lot_no' => $item['lot_no']])->one();

          //---เพิ่ม StockCard--------------------------------------------------------------------------
          $card = new StockCard();
          $card->asset_item_id = $out->asset_item_id;
          $card->lot_no = $out->lot_no;
          $card->asset_master_type_id = $out->asset_master_type_id;
          $card->ref_id = $out->asset_stockout_no;
          $card->stock_date = new \yii\db\Expression('NOW()');
          $card->employee_id = $out->employee_id;

          if ($mod == 'OUT') {
          $card->stock_type_id = 2; //-OUT
          $card->quantity_down = 1;
          $card->quantity_up = 0;
          $card->balance = self::StockUpdate($out->asset_master_type_id, $out->asset_item_id, $out->lot_no, -1, FALSE); //ปรับ balance stock
          //if (!$ckCard) {//เป็นการเพิ่มรายการใหม่
          if ($card->save()) {

          }
          self::StockUpdate($out->asset_master_type_id, $out->asset_item_id, $out->lot_no, -1, TRUE); //ปรับ balance stock
          $ref = true;
          //}
          }

          if ($mod == 'IN') {//กรณียกเลิก
          $card->quantity_down = 0;
          $card->stock_type_id = 1; //-IN
          $card->quantity_up = 1;
          $card->remark = 'ปรับเนื่องจากมีการยกเลิกรายการ';
          $card->balance = self::StockUpdate($out->asset_master_type_id, $out->asset_item_id, $out->lot_no, 1, FALSE); //ปรับ balance stock
          //if (!$ckCard) {//เป็นการยกเลิกรายการ
          if ($card->save()) {
          $ref = true;
          }
          self::StockUpdate($out->asset_master_type_id, $out->asset_item_id, $out->lot_no, 1, TRUE); //ปรับ balance stock
          //print_r($card->getErrors());
          //}
          }
          }
          return $ref;
         *
         */
    }

}
