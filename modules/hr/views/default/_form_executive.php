<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
?>

<div class="project-company-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'frmExecutive',
                'action' => ['executive', 'id' => @$_GET['id']],
                'method' => 'post',
                'options' => [
                    'data-pjax' => 1,
                //'class' => 'form-inline'
                ],
                    //'enableClientValidation' => false,
    ]);
    ?>
    <div class="card">
        <div class="card-body">
            <?=
            $form->field($model, 'employee_executive_id')->widget(Select2::classname(),
                    [
                        'data' => $list,
                        'options' => ['placeholder' => 'เลือกตำแหน่งบริหาร...'],
                        'pluginOptions' => [
						'dropdownParent' => '#modalContents',
                            'allowClear' => true,
                        //'minimumInputLength' => 2,
                        ],
            ]);
            ?>

            <?=
            $form->field($model, 'employee_dep_id')->widget(Select2::classname(),
                    [
                        'data' => $dep,
                        'options' => ['placeholder' => 'เลือกหน่วยงาน...'],
                        'pluginOptions' => [
						'dropdownParent' => '#modalContents',
                            'allowClear' => true,
                        //'minimumInputLength' => 2,
                        ],
            ]);
            ?>

            <div class="form-group mt-2">
                <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
