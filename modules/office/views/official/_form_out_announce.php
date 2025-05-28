<?php

use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Html;
use kartik\form\ActiveForm;
//use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\widgets\FileInput;
//use yii\widgets\Pjax;
//use app\components\Ccomponent;
use yii\helpers\Url;

$defaultPage = '';
$js = <<<JS
$("#btnFrmOffice").click(function(event){
    $('#frmBookNumber').submit();
});
$('#frmBookNumber').on('beforeSubmit', function(e) {
    e.preventDefault();
    var form = this;
    var data = new FormData(form);
    var url = form.action;

    if ($(this).find('.has-error').length) {
        	return false;
    } else {
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
    }
}).on('submit', function(e){
    e.preventDefault();
    return false;
});
JS;
$this->registerJs($js, $this::POS_READY);
?>

<?php
//Pjax::begin(['id' => 'frmOUT', 'timeout' => false, 'enablePushState' => false]);
$form = ActiveForm::begin([
            'id' => 'frmBookNumber',
            //'' => '',
            //'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => [
                'labelSpan' => 3,
                'showErrors' => false,
                'showHints' => false,
                'deviceSize' => ActiveForm::SIZE_X_LARGE
            ],
            'options' => [
                'data-pjax' => true,
                'enctype' => 'multipart/form-data'
            ],
                //'enableClientValidation' => false,
                // 'enableAjaxValidation' => true,
        ]);
//print_r($form->errorSummary($model));
?>
<div class="row">
    <div class="col-md-7">
        <div class="row">
            <div class="col-md-12">
                <?=
                $form->field($model, 'paperless_official_date')->label('วันที่ประกาศ')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'วันที่ประกาศ', 'class' => 'form-control form-control-lg'],
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

            <?= $form->field($model, 'paperless_topic')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
            <?= $form->field($model, 'paperless_official_detail')->textarea(['rows' => 4, 'class' => 'form-control form-control-lg']) ?>

            <?PHP
            echo $form->field($model, 'employee_dep_id')->label('หนังสือของกลุ่มงาน')->widget(Select2::classname(), [
                'options' => ['placeholder' => '--เลือกหน่วยงานภายใน--'],
                'initValueText' => (isset($model->employee_dep_id) && $model->employee_dep_id <> '' ? $model->dep->employee_dep_label : ''), // set the initial display text
                'theme' => Select2::THEME_KRAJEE_BS5,
                'pluginOptions' => [
                    'dropdownParent' => '#modalContents',
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
    <div class="col-md-5">
        <div class="">เอกสารแนบเพิ่มเติม</div>
        <?PHP
        echo FileInput::widget([
            'name' => 'upload_ajax[]',
            'options' => ['multiple' => true, 'accept' => ''], //'accept' => 'image/*' หากต้องเฉพาะ image
            'pluginOptions' => [
                'overwriteInitial' => false,
                'initialPreviewShowDelete' => true,
                'initialPreviewAsData' => true,
                //'reversePreviewOrder' => true,
                'initialPreview' => $initialPreview,
                'initialPreviewConfig' => $initialPreviewConfig,
                'uploadUrl' => Url::to(['upload-ajax']),
                'uploadExtraData' => [
                    'ref' => @$model->paperless_id,
                ],
                'maxFileCount' => 5
            ]
        ]);
        ?>
    </div>
    <div class="col-md-12 ">
        <div class="row justify-content-between mt-3 mb-5">
            <div class="col-6">
                <?=
                Html::button('<i class="la la-save la-lg"></i> บันทึกข้อมูล', [
                    'class' => 'btn btn-primary font-weight-bold',
                    'id' => 'btnFrmOffice'
                ])
                ?>
            </div>
            <div class="col-6 text-right">
                <?= Html::a('<i class="la la-angle-left la-lg"></i> กลับหน้าจัดการ', 'javascript:;', ['class' => 'btn btn-dark font-weight-bold', 'data-bs-dismiss' => 'modal']) ?>
            </div>
        </div>
        <hr>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php //Pjax::end() ?>
