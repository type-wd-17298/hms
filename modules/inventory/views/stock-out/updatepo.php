<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\PurchaseOrder */

$this->title = 'แก้ไขใบสั่งซื้อ: ' . $model->asset_stockin_id;
$this->params['breadcrumbs'][] = ['label' => 'ใบสั่งซื้อ', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->purchase_order_id, 'url' => ['view', 'id' => $model->purchase_order_id]];
$this->params['breadcrumbs'][] = 'แก้ไขใบสั่งซื้อ';
?>
<div class="purchase-order-update">

    <?=
    $this->render('_formpo', [
        'model' => $model,
        #'model2' => $model2,
        'modelList' => $modelList,
    ])
    ?>

</div>
