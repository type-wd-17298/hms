<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\ItemsCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="items-categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'categories_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
