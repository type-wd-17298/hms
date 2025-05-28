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

Pjax::begin(['id' => 'frmOUT', 'timeout' => false, 'enablePushState' => false]);
$defaultPage = '';
$js = <<<JS
$("#btnFrmOffice").click(function(event){
    event.preventDefault();
    $('#frm').submit();
});
$('#frm').on('beforeSubmit', function(e) {
    e.preventDefault();

}).on('submit', function(e){
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
            if(data.status == 'success'){
           
            $('#modalForm').modal('toggle');
            $('#frmSearch').submit();
            Swal.fire({
                //position: 'top-end',
                icon: 'success',
                title: 'บันทึกรายการสำเร็จ',
                showConfirmButton: false,
                timer: 1500
              });
        }
        },
        error: function () {
            alert("Something went wrong");
        }
    });
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
                #'showErrors' => false,
                'showHints' => false,
                'deviceSize' => ActiveForm::SIZE_X_LARGE],
            'options' => [
                'data-pjax' => true,
            //'enctype' => 'multipart/form-data'
            ],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
        ]);
//print_r($form->errorSummary($model));
?>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <?=
                $form->field($model, 'dead_date')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'วันที่เสียชีวิต', 'class' => 'form-control'],
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
            <div class="col-md-12">
                <?PHP
                echo $form->field($model, 'dead_infomation')->label('ผู้ป่วยที่เสียชีวิต (ค้นหาจาก HN,CID,ชื่อ-นามสกุล)')->widget(Select2::classname(), [
                    'options' => ['placeholder' => '--เลือกผู้ป่วยที่เสียชีวิต--'],
                    'initValueText' => $model->dead_infomation, // set the initial display text
                    'theme' => Select2::THEME_KRAJEE_BS5,
                    'pluginOptions' => [
                        'dropdownParent' => '#modalForm',
                        'allowClear' => true,
                        'minimumInputLength' => 0,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['/office/dead/patientlist']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                        'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-12">
                <?PHP //$form->field($model, 'dead_cid')->textInput() ?>
            </div>

            <div class="col-md-12">
                <?PHP
                echo $form->field($model, 'employee_id')->label('ผู้แจ้ง')->widget(Select2::classname(), [
                    'options' => ['placeholder' => '--เลือกผู้แจ้ง--'],
                    'initValueText' => (isset($model->employee_id) && $model->employee_id <> '' ? $model->emp->employee_fullname : ''), // set the initial display text
                    'theme' => Select2::THEME_KRAJEE_BS5,
                    'pluginOptions' => [
                        'dropdownParent' => '#modalForm',
                        'allowClear' => true,
                        'minimumInputLength' => 0,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['/office/paperless/emplist']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                        'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-12">
                <?PHP
                echo $form->field($model, 'department_id')->label('หน่วยงานที่แจ้ง')->widget(Select2::classname(), [
                    'options' => ['placeholder' => '--เลือกหน่วยงานภายใน--'],
                    'initValueText' => (isset($model->department_id) && $model->department_id <> '' ? $model->dep->employee_dep_label : ''), // set the initial display text
                    'theme' => Select2::THEME_KRAJEE_BS5,
                    'pluginOptions' => [
                        'dropdownParent' => '#modalForm',
                        'allowClear' => true,
                        'minimumInputLength' => 0,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['/office/paperless/deplist']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                        'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
                    ],
                ]);
                ?>
            </div>

        </div>
    </div>

    <div class="col-md-12">
        <div class="alert alert-danger left-icon-big alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"><span><i class="mdi mdi-btn-close"></i></span>
            </button>
            <div class="media">
                <div class="alert-left-icon-big">
                    <span><i class="mdi mdi-email-alert"></i></span>
                </div>
                <div class="media-body">
                    <h6 class="mt-1 mb-2">คำแนะนำการใช้งาน</h6>
                    <p class="mb-0">- เมื่อบันทึกข้อมูลแล้วจะไม่สามารถลบรายการได้ กรุณาตรวจสอบข้อมูลให้ถูกต้องทุกครั้งก่อนบันทึกข้อมูล</p>
                </div>
            </div>
        </div>
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
<?php Pjax::end() ?>