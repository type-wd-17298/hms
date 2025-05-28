<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\PurchaseOrder */

$this->title = 'แก้ไขใบเบิกพัสดุ: ' . $model->asset_stockout_id;
$this->params['breadcrumbs'][] = ['label' => 'ใบเบิกพัสดุ', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->purchase_order_id, 'url' => ['view', 'id' => $model->purchase_order_id]];
$this->params['breadcrumbs'][] = 'แก้ไขใบเบิกพัสดุ';
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
