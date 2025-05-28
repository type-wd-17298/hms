<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\PurchaseOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-order-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1
                ],
    ]);
    ?>

    <?= $form->field($model, 'purchase_order_id') ?>
    <?= $form->field($model, 'purchase_order_no') ?>
    <?= $form->field($model, 'employee_id') ?>
    <?= $form->field($model, 'purchase_order_status_id') ?>
        <?= $form->field($model, 'purchase_order_date') ?>
        <?php // echo $form->field($model, 'purchase_order_refno') ?>
    <div class="form-group">
<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
