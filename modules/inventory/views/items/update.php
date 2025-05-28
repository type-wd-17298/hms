<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\Items */

$this->title = 'แก้ไขรายการสินค้า : ' . $model->asset_item_id;
$this->params['breadcrumbs'][] = ['label' => 'สินค้า', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->items_name, 'url' => ['view', 'id' => $model->items_id]];
$this->params['breadcrumbs'][] = $model->asset_item_id;
?>
<div class="items-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
            // 'modelPropertys' => $modelPropertys,
    ])
    ?>

</div>
