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
            $('#modalForm').modal('toggle');
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
                'showErrors' => false,
                'showHints' => false,
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
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model, 'plan_list_title')->textInput() ?>
            </div>
            <div class="col-md-3">
                <?PHP
                $budgetYear = [];
                for ($y = (date('Y') + 544); $y >= (date('Y') + 541); $y--) {
                    $budgetYear[$y] = $y;
                }
                echo $form->field($model, 'plan_budget_year')->dropDownList(
                        $budgetYear, ['class' => 'form-control'])
                ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'plan_list_objective')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'plan_list_target')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'plan_list_activity')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'plan_list_costdetail')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'plan_list_kpi')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'plan_list_period')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'plan_list_budget')->textInput() ?>
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