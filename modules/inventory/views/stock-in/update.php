<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\PurchaseOrder */

$this->title = 'แก้ไขใบรับสินค้า: ' . $model->asset_stockin_id;
$this->params['breadcrumbs'][] = ['label' => 'ใบรับสินค้า', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->purchase_order_id, 'url' => ['view', 'id' => $model->purchase_order_id]];
$this->params['breadcrumbs'][] = 'แก้ไขใบรับสินค้า';
?>
<div class="purchase-order-update">

    <?=
    $this->render('_form', [
        'model' => $model,
        #'model2' => $model2,
        'modelList' => $modelList,
    ])
    ?>

</div>
