<?php

use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use yii\helpers\Url;

$defaultPage = '';
$js = <<<JS
$("#btnFrmOffice").click(function(event){
    event.preventDefault();
    $('#frm').submit();
});
$('#frm').on('beforeSubmit', function(e) {
    e.preventDefault();
    var form = this;
    var data = new FormData(form);
    var url = form.action;
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        success: function (data) {
            $("#modalContents").html(data);
            //$('#modalForm').modal('toggle');
            $('#frmSearch').submit();
            Swal.fire({
                //position: 'top-end',
                icon: 'success',
                title: 'บันทึกรายการสำเร็จ',
                showConfirmButton: false,
                timer: 1500
              });
        },
        error: function () {
            alert("Something went wrong");
        }
    });
}).on('submit', function(e){
    e.preventDefault();
    return false;
});
JS;
$this->registerJs($js, $this::POS_READY);
?>

<?php
$form = ActiveForm::begin([
            'id' => 'frm',
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => [
                'labelSpan' => 12,
                //'showErrors' => false,
                //'showHints' => false,
                'deviceSize' => ActiveForm::SIZE_X_LARGE],
            'options' => [
                'data-pjax' => true,
                'enctype' => 'multipart/form-data'
            ],
                // 'enableClientValidation' => true,
                //'enableAjaxValidation' => false,
        ]);
//print_r($form->errorSummary($model));
?>
<div class="rows">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">

                <?=
                $form->field($model, 'staff_worklist8h_date')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'วันที่เริ่มดำเนินงาน', 'class' => 'form-control'],
                    'language' => 'th-TH',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayBtn' => true,
                        'todayHighlight' => true,
                    ]
                ]);
                ?>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header h3">การปฏิบัติงานช่วงเช้า</div>
                    <div class="card-content m-2">
                        <?= $form->field($model, 'staff_worklist8h_hour8')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
                        <?= $form->field($model, 'staff_worklist8h_hour9')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
                        <?= $form->field($model, 'staff_worklist8h_hour10')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
                        <?= $form->field($model, 'staff_worklist8h_hour11')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header h3">การปฏิบัติงานช่วงบ่าย</div>
                    <div class="card-content m-2">
                        <?= $form->field($model, 'staff_worklist8h_hour13')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
                        <?= $form->field($model, 'staff_worklist8h_hour14')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
                        <?= $form->field($model, 'staff_worklist8h_hour15')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
                        <?= $form->field($model, 'staff_worklist8h_hour16')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row justify-content-between mt-3 mb-5">
            <div class="col-6">
                <?= Html::a('<i class="fa fa-angle-left fa-lg"></i> กลับหน้าจัดการ', 'javascript:;', ['class' => 'btn btn-dark btn-lg font-weight-bold', 'data-bs-dismiss' => 'modal']) ?>
            </div>
            <div class="col-6 text-right">
                <?=
                Html::button('<i class="fa fa-save fa-lg"></i> บันทึกข้อมูล', ['class' => 'btn btn-primary btn-lg font-weight-bold', 'id' => 'btnFrmOffice',])
                ?>
            </div>
        </div>
        <hr>
    </div>
</div>
<?php ActiveForm::end(); ?>