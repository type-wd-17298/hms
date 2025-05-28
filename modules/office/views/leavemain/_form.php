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
use app\modules\hr\models\EmployeePositionHead;
use yii\helpers\ArrayHelper;

$stepUp = 0;
$headOwnerUp = @EmployeePositionHead::find()->where(['employee_id' => $model->employee_id])->all();
if (in_array(2, ArrayHelper::getColumn($headOwnerUp, 'executive.employee_executive_level'))) {
    $stepUp = 1;
}

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
<div class="card">
    <div class="card-body">
        <h3 class="mt-1 mb-2">แบบฟอร์มใบลา</h3>
        <hr>
        <div class="alert alert-danger solid alert-dismissible fade show d-none">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"><span><i class="mdi mdi-btn-close"></i></span>
            </button>
            <div class="media">
                <div class="media-body">
                    <h5 class="mt-1 mb-2 text-white">แจ้งให้ทราบ</h5>
                    <p class="mb-0">
                        หลังจากบันทึกรายการเสร็จแล้วต้อง <b class='h3 text-white'>เสนอใบลา</b> ถึงหัวหน้างานทุกครั้ง
                    </p>
                </div>
            </div>
        </div>
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
        <?php
        echo $form->field($model, 'leave_type_id')->widget(Select2::classname(), [
            'data' => yii\helpers\ArrayHelper::map(app\modules\office\models\LeaveType::find()->where(['leave_type_active' => 1])->orderBy(['leave_type_id' => SORT_ASC])->all(), 'leave_type_id', 'leave_type_name'),
            'options' => ['placeholder' => '---เลือกประเภทการลา---', 'multiple' => false, 'class' => 'form-control form-control-lg'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
        <?php
        echo $form->field($model, 'employee_id')->widget(Select2::classname(), [
            'data' => yii\helpers\ArrayHelper::map(app\modules\hr\models\Employee::find()->where(['employee_status' => 1])->orderBy(['employee_id' => SORT_ASC])->all(), 'employee_id', 'employee_fullname', 'dep.employee_dep_label'),
            'options' => ['placeholder' => '---เลือกรายการ---', 'multiple' => false, 'class' => 'form-control form-control-lg'],
            'pluginOptions' => [
                'dropdownParent' => '#modalPapaer',
                'allowClear' => true
            ],
        ]);
        ?>
        <?= $form->field($model, 'leave_type_time')->radioList(['F' => 'ลาเต็มวัน', 'H1' => 'ลาครึ่งวันเช้า (เวลา 8:30 - 12:00 น.)', 'H2' => 'ลาครึ่งวันบ่าย (เวลา 13:00 - 16:30 น.)'], ['inline' => true, 'custom' => true,]) ?>

        <?php
        $form->field($model, 'leave_status_id')->widget(Select2::classname(), [
            'data' => yii\helpers\ArrayHelper::map(app\modules\office\models\LeaveStatus::find()->where(['leave_status_active' => 1])->orderBy(['leave_status_id' => SORT_ASC])->all(), 'leave_status_id', 'leave_status_name'),
            'options' => ['placeholder' => '---เลือกรายการ---', 'multiple' => false, 'class' => 'form-control form-control-lg'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>

        <?php
        /*
          $form->field($model, 'rankdate', [
          'addon' => ['prepend' => ['content' => '<i class="fas fa-calendar-alt"></i>']],
          //'options' => ['class' => 'form-control form-control-lg'],
          ])->widget(DateRangePicker::classname(), [
          // 'value' => $model->start_date . ' - ' . $model->end_date,
          'convertFormat' => TRUE,
          'readonly' => TRUE,
          'pluginOptions' => [
          'locale' => ['format' => 'Y-m-d'],
          # 'opens' => 'left',
          'ignoreReadonly' => TRUE,
          'autoApply' => TRUE,
          #'ignoreReadonly' => TRUE,
          #'disableTouchKeyboard' => TRUE,
          #'Readonly' => true,
          ],
          'pluginEvents' => [
          "show.daterangepicker" => "function(ev, picker) { picker.autoUpdateInput = true; }",
          ],
          'language' => 'th',
          'startAttribute' => 'leave_start',
          'endAttribute' => 'leave_end',
          'useWithAddon' => TRUE
          ]);
         *
         */
        ?>
        <?= $form->field($model, 'leave_day')->textInput(['type' => 'number', 'step' => 0.5]) ?>
        <?=
        $form->field($model, 'leave_start')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => '---วันที่เริ่มต้นการลา---', 'class' => 'form-control form-control-lg'],
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
            'options' => ['placeholder' => '---วันที่เริ่มต้นการลา---', 'class' => 'form-control form-control-lg'],
            'language' => 'th-TH',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true,
                'todayHighlight' => true,
            ]
        ]);
        ?>
        <?PHP // $form->field($model, 'leave_start')->textInput(['placeholder' => '---วันที่เริ่มต้นการลา---',]) ?>
        <?PHP // $form->field($model, 'leave_end')->textInput(['placeholder' => '---วันที่เริ่มต้นการลา---',])   ?>
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
                    //'dropdownParent' => '#modalPapaer',
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        //'url' => Url::to(['paperless/emplist']),
                        'url' => new JsExpression('function(){

                                                                            if("' . $stepUp . '" == "1"){
                                                                                return "' . Url::to(['paperless/emplist2']) . '";
                                                                            }else{
                                                                                return "' . Url::to(['paperless/emplist']) . '";
                                                                            }
                                                                            }'),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {return {q:params.term,mode:"D",ac:"1"}; }')
                    ],
                    'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                    'templateResult' => new JsExpression('formatRepo'),
                    'templateSelection' => new JsExpression('formatRepoSelection'),
                ],
            ]);
            /*
              echo Select2::widget([
              'name' => 'leave_assign',
              'options' => [
              'placeholder' => 'เลือกผู้รับมอบ..',
              'multiple' => false,
              'class' => 'form-control form-control-lg',
              ],
              'theme' => Select2::THEME_KRAJEE_BS4,
              'pluginOptions' => [
              'allowClear' => true,
              'minimumInputLength' => 0,
              'language' => [
              'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
              ],
              'ajax' => [
              'url' => Url::to(['paperless/emplist']),
              'cache' => true,
              'dataType' => 'json',
              'data' => new JsExpression('function(params) {return {q:params.term,mode:"D"}; }')
              ],
              'escapeMarkup' => new JsExpression("function(m) { return m; }"),
              'templateResult' => new JsExpression('formatRepo'),
              'templateSelection' => new JsExpression('formatRepoSelection'),
              ],
              ]);
             *
             */
            ?>

        </div>
        <div class="d-none" id="leave_detail">
            <?= $form->field($model, 'leave_detail')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
        </div>
        <?= $form->field($model, 'leave_address')->textarea(['rows' => 1, 'class' => 'form-control form-control-lg']) ?>
        <?php
        /*
          echo $form->field($model, 'leave_file')->widget(FileInput::classname(), [
          'options' => ['accept' => 'pdf'],
          'pluginOptions' => [
          'initialPreview' => $initialPreview,
          'initialPreviewConfig' => $initialPreviewConfig,
          'overwriteInitial' => true,
          'allowedFileExtensions' => ['pdf'],
          'showRemove' => false,
          'showUpload' => false,
          'uploadExtraData' => [
          'ref' => @('L' . $model->leave_id),
          ],
          //'showPreview' => false,
          ]
          ]);
         *
         */
        ?>
        <div class="font-weight-bold">เอกสารแนบเพิ่มเติม</div>
        <?PHP
        echo FileInput::widget([
            'name' => 'upload_ajax[]',
            'options' => ['accept' => 'pdf'],
            'pluginOptions' => [
                'allowedFileExtensions' => ['pdf'],
                'overwriteInitial' => false,
                'initialPreviewShowDelete' => true,
                'initialPreviewAsData' => true,
                'initialPreview' => $initialPreview,
                'initialPreviewConfig' => $initialPreviewConfig,
                //'uploadUrl' => Url::to(['upload-ajax']),
                'uploadExtraData' => [
                    'ref' => @('L' . $model->leave_id),
                ],
                'maxFileCount' => 1
            ]
        ]);
        ?>

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
