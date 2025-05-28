<?php

use yii\bootstrap4\Html;
//use kartik\form\ActiveForm;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;
//use kartik\daterange\DateRangePicker;
use kartik\widgets\FileInput;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
//use kartik\date\DatePicker;
use kartik\widgets\DatePicker;
use app\components\Ccomponent;

$js = <<<JS
    setReceiver('{$model->leave_type_id}');
    $('#leavemain-leave_type_id').change(function(){
        var chk = $('#leavemain-leave_type_id').val();
        setReceiver(chk);
    });

    function setReceiver(chk){
        if(chk>0){
            if(chk == 1 ){
                $('#leave_assign').removeClass('d-none');
                $('#leave_detail').addClass('d-none');
            }else{
                $('#leave_assign').addClass('d-none');
                $('#leave_detail').removeClass('d-none');
            }
        }
    }
/*
$('#frmLeave').on('beforeSubmit', function(e) {
    e.preventDefault();
    var form = $(this);
    var formData = form.serialize();
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: formData,
        success: function (data) {
            $('#modalPapaer').modal('toggle');
            $('#frmSearch').submit();
            Swal.fire({
                //position: 'top-end',
                icon: 'success',
                title: 'บันทึกรายการสำเร็จ',
                showConfirmButton: false,
                timer: 1500
              });
        },
        error: function (e) {
            //alert("Something went wrong ");
            return false;
        }
    });
}).on('submit', function(e){
    e.preventDefault();
    return false;
});
    */
JS;
$this->registerJs($js, $this::POS_READY);
?>
<div class="alert alert-warning solid alert-dismissible fade show">
    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
    <strong>Warning!</strong>
    อ้างอิงการได้รับอนุญาตให้<?= $modelLeave->leaveType->leave_type_name ?> เลขที่ <?= $modelLeave->leave_id ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
    </button>
</div>
<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">อ้างอิงการได้รับอนุญาตให้<?= $modelLeave->leaveType->leave_type_name ?> ตั้งแต่วันที่ <?= Ccomponent::getThaiDate($modelLeave->leave_start, 'S') . '- ' . Ccomponent::getThaiDate($modelLeave->leave_end, 'S') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-2 font-weight-bold">ชื่อเจ้าหน้าที่</div> <div class="col-9"><?= $modelLeave->emps->employee_fullname ?></div>
                <div class="col-2 font-weight-bold">ประเภทการลา</div> <div class="col-9"><?= $modelLeave->leaveType->leave_type_name ?></div>
                <div class="col-2 font-weight-bold">ช่วงวันที่</div> <div class="col-9"><?= Ccomponent::getThaiDate($modelLeave->leave_start, 'S') . '- ' . Ccomponent::getThaiDate($modelLeave->leave_end, 'S') ?></div>
                <div class="col-2 font-weight-bold">จำนวนวัน</div> <div class="col-9"><?= $modelLeave->leave_day ?></div>
            </div>
        </div>
        <!--
          <div class="card-footer">
           <p class="card-text text-dark">Last updateed 3 min ago</p>
          </div>
        -->
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h3 class="mt-1 mb-2">แบบฟอร์มยกเลิกใบลา</h3>
        <hr>


        <?php
        $form = ActiveForm::begin([
                    'enableClientValidation' => false,
                    'id' => 'frmLeave',
                    'options' => [
                        'data-pjax' => TRUE,
                        'enctype' => 'multipart/form-data',
                    ],
                    'fieldConfig' => [
                        'template' => "<div class='font-weight-bold'>{label}</div>\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    ],
        ]);
        print_r($form->errorSummary($model));
        ?>

        <?= $form->field($model, 'leave_type_time')->radioList(['F' => 'ลาเต็มวัน', 'H1' => 'ลาครึ่งวันเช้า (เวลา 8:30 - 12:00 น.)', 'H2' => 'ลาครึ่งวันบ่าย (เวลา 13:00 - 16:30 น.)'], ['inline' => true, 'custom' => true,]) ?>
        <?= $form->field($model, 'leave_day')->textInput([]) ?>
        <?php
        $form->field($model, 'leave_status_id')->widget(Select2::classname(), [
            'data' => yii\helpers\ArrayHelper::map(app\modules\office\models\LeaveStatus::find()->where(['leave_status_active' => 1])->orderBy(['leave_status_id' => SORT_ASC])->all(), 'leave_status_id', 'leave_status_name'),
            'options' => ['placeholder' => '---เลือกรายการ---', 'multiple' => false, 'class' => 'form-control form-control-lg'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>

        <?=
        $form->field($model, 'leave_start')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => '---วันที่เริ่มต้นยกเลิกการลา---', 'class' => 'form-control form-control-lg'],
            'language' => 'th-TH',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true,
                'todayHighlight' => true,
            ]
        ]);
        ?>
        <?=
        $form->field($model, 'leave_end')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => '---วันที่สิ้นสุดยกเลิกการลา---', 'class' => 'form-control form-control-lg'],
            'language' => 'th-TH',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true,
                'todayHighlight' => true,
            ]
        ]);
        ?>

        <div class="d-none" id="leave_assign">
            <?php
            $formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
    }
var ArrText = '<div class="row small  ml-0">';
for (var textExcutive of repo.excutive) {
  ArrText += '<div class="col-12 small">- ' + textExcutive.executive +' ('+textExcutive.dep+')</div>' ;
}

ArrText += '</div>';
var textPosition = '';
if(repo.position != null){
    textPosition =  '<small style="margin-left:3px">' + repo.position + '</small>';
}

var markup =
'<div class="row small">' +
    '<div class="col-12">' +
        '<b class="text-primary h4">' + repo.text + '</b>' +
    '</div>' +
     '<div class="col-12 ml-2">' +
        textPosition + '<small class="margin-left:3px">(' + repo.dep + ')</small>' +
    '</div>' +
    ArrText +
'</div>';
    if (repo.description) {
      markup += '<p>' + repo.description + '</p>';
    }
    return '<div style="overflow:hidden;">' + markup + '</div>';
};
var formatRepoSelection = function (repo) {
    return repo.full_name || repo.text;
}
JS;
// Register the formatting script
            $this->registerJs($formatJs, $this::POS_HEAD);

            echo $form->field($model, 'leave_assign')->label('ผู้รับมอบ')->widget(Select2::classname(), [
                'options' => ['placeholder' => '--เลือกผู้รับมอบ--'],
                'initValueText' => (isset($model->leave_assign) && $model->leave_assign <> '' ? $model->getLeaveAssign()->employee_fullname : ''), // set the initial display text
                'theme' => Select2::THEME_KRAJEE_BS5,
                'pluginOptions' => [
                    'dropdownParent' => '#modalContents',
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['paperless/emplist']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {return {q:params.term,mode:"D"}; }')
                    ],
                    'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                    'templateResult' => new JsExpression('formatRepo'),
                    'templateSelection' => new JsExpression('formatRepoSelection'),
                ],
            ]);
            ?>

        </div>
        <div class="d-none" id="leave_detail">
            <?= $form->field($model, 'leave_detail')->label('รายละเอียด/เหตุผลยกเลิกการลา')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
        </div>

        <div class="form-group">
            <div class="row justify-content-between mt-3 mb-5">
                <div class="col-6">
                    <?= Html::a('<i class="la la-angle-left la-lg"></i> กลับหน้าหลัก', ['index'], ['class' => 'btn btn-light btn-lg btn-dark', 'data-bs-dismiss' => 'modal1', 'data-pjax' => 0]) ?>
                </div>
                <div class="col-6 text-right">
                    <?= Html::submitButton('<i class="fas fa-plus fa-lg"></i> บันทึกรายการ', ['class' => 'btn btn-primary btn-lg']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
