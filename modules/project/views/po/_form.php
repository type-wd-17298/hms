<?php

use yii\bootstrap4\Html;
#use yii\bootstrap4\ActiveForm;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use kartik\date\DatePicker;
use yii\widgets\Pjax;

Pjax::begin(['id' => 'pjax-formPO', 'timeout' => false, 'enablePushState' => false]);
$url = Url::to(['default/manage']);
$js = <<<JS
        $('#frmPo').on('beforeSubmit', function(e) {
           e.preventDefault();
            var form = $(this);
            var formData = form.serialize();
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                success: function (data) {
                    if(data.status == 'success'){
                        $.get('$url',{id:'{$_GET['pid']}'}, function(data) {
                            $("#modalContent").html(data);
                            $("#modalForm2").modal('hide');
                        });
                    }
                },
                error: function () {
                    alert("Something went wrong");
                }
            });
        }).on('submit', function(e){
            e.preventDefault();
            return false; // Cancel form submitting.
        });
JS;
$this->registerJs($js, $this::POS_LOAD);
?>

<div class="person-form">
    <?php
    $form = ActiveForm::begin([
                'id' => 'frmPo',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'options' => [
                    //'enctype' => 'multipart/form-data',
                    'data-pjax' => 1,
                ],
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        #'columnSize' => 'lg',
                        'label' => 'col-sm-4',
                        'offset' => 'offset-sm-2',
                        'wrapper' => 'col-sm-8',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
    ]);
    ?>
    <div class="alert alert-secondary">
        <div class="mb-0">
            <strong>คำแนะนำใช้งาน</strong><br>การบันทึกข้อมูลใบสั่งซื้อ/สั่งจ้าง หลายครั้งอาจทำให้เกิดการออกเลขซ้ำซ้อนกันได้ในระบบ
            <br><?PHP print_r($form->errorSummary($model)); ?>
        </div>
    </div>

    <div class="card border-secondarys">
        <div class="card-header">
            <b>ฟอร์มออกเลขใบสั่งซื้อ/สั่งจ้าง</b>
        </div>
        <div class="card-block m-2">
            <?=
            $form->field($model, 'project_po_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'วันที่'],
                'language' => 'th-TH',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayBtn' => true,
                    'todayHighlight' => true,
                #'yearRange' => '+543',
                ]
            ]);
            ?>
            <?=
            $form->field($model, 'project_po_cost')->widget(\yii\widgets\MaskedInput::className(), [
                'clientOptions' => [
                    'alias' => 'decimal',
                    'groupSeparator' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ])
            ?>

            <?= $form->field($model, 'project_po_comment')->textarea() ?>
        </div>
    </div>

    <div class="row justify-content-between mt-3 mb-5">
        <div class="col-6">
            <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
        <div class="col-6 text-right">
            <?PHP Html::a('กลับหน้าจัดการ', ['index'], ['class' => 'btn btn-light btn-lg']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php Pjax::end(); ?>