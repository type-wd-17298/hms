<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\project\models\ProjectType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="project-company-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'frmIndex',
                'action' => ['index', 'id' => @$_GET['id']],
                'method' => 'post',
                'options' => [
                    'data-pjax' => 1,
                //'class' => 'form-inline'
                ],
                'enableClientValidation' => false,
    ]);
    ?>
    <div class="card">
        <div class="card-body">
            <?= $form->field($model, 'project_company_name')->textInput(['maxlength' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
