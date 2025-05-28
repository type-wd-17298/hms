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
                //'enableClientValidation' => false,
        ]);
//print_r($form->errorSummary($model));
?>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <?=
                $form->field($model, 'software_list_regisdate')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'วันที่เริ่มใช้งาน', 'class' => 'form-control'],
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
            <div class="col-md-4">
                <?=
                $form->field($model, 'software_type_id')->dropDownList(ArrayHelper::map(app\modules\servicedesk\models\SoftwareType::find()
                                        ->orderBy(['software_type_id' => SORT_ASC])
                                        ->all(), 'software_type_id', 'software_type_name')
                        , ['class' => 'form-control'])
                ?>
            </div>
            <div class="col-md-4">
                <?=
                $form->field($model, 'software_status_id')->dropDownList(ArrayHelper::map(app\modules\servicedesk\models\SoftwareStatus::find()
                                        ->orderBy(['software_status_id' => SORT_ASC])
                                        ->all(), 'software_status_id', 'software_status_name')
                        , ['class' => 'form-control'])
                ?>
            </div>
            <div class="col-md-12">
                <?PHP echo $form->field($model, 'software_list_name')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'software_list_detail')->textarea(['rows' => 2, 'class' => 'form-control form-control-lg']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'software_list_vender')->textarea(['rows' => 2, 'class' => 'form-control form-control-lg']) ?>
            </div>

            <div class="col-md-3">
                <?PHP echo $form->field($model, 'software_list_license')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
            </div>
            <div class="col-md-3">
                <?PHP echo $form->field($model, 'software_list_license_amount')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
            </div>
            <div class="col-md-3">
                <?PHP echo $form->field($model, 'software_list_ma')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model, 'software_list_date_expire')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'วันที่หมดอายุ', 'class' => 'form-control'],
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
                echo $form->field($model, 'employee_id')->label('ผู้ประสานงาน')->widget(Select2::classname(), [
                    'options' => ['placeholder' => '--เลือกหน่วยงานภายใน--'],
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
                echo $form->field($model, 'department_id')->label('หน่วยงานที่ติดตั้ง')->widget(Select2::classname(), [
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
            <div class="col-md-12">
                <?PHP
                echo $form->field($model, 'employee_id_staff')->label('IT ผู้ดำเนินการ')->widget(Select2::classname(), [
                    'options' => ['placeholder' => '--เลือกหน่วยงานภายใน--'],
                    'initValueText' => (isset($model->employee_id_staff) && $model->employee_id_staff <> '' ? $model->empStaff->employee_fullname : ''), // set the initial display text
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
                            // 'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            'data' => new JsExpression('function(params) {return {q:params.term,mode:"D",ac:"1"}; }')
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