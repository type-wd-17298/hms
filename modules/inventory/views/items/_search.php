<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\ItemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="items-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'items_id') ?>

    <?= $form->field($model, 'supplying_id') ?>

    <?= $form->field($model, 'subcategories_id') ?>

    <?= $form->field($model, 'items_unit_id') ?>

    <?= $form->field($model, 'model_id') ?>

    <?php // echo $form->field($model, 'brand_id') ?>

    <?php // echo $form->field($model, 'items_group_id') ?>

    <?php // echo $form->field($model, 'items_name') ?>

    <?php // echo $form->field($model, 'itemst_status')->checkbox() ?>

    <?php // echo $form->field($model, 'items_color_id') ?>

    <?php // echo $form->field($model, 'items_tag') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
