<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\PurchaseOrder */

$this->title = 'สร้างใบสั่งซื้อ';
$this->params['breadcrumbs'][] = ['label' => 'ข้อมูลใบสั่งซื้อ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'model2' => $model2,
        'modelList' => $modelList,
    ])
    ?>

</div>
