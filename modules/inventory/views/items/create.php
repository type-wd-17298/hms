<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\Items */

$this->title = 'เพิ่มรายการสินค้า';
$this->params['breadcrumbs'][] = ['label' => 'รายการสินค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-create">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
