<?php

use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Html;
use kartik\form\ActiveForm;
//use yii\helpers\ArrayHelper;
//use kartik\date\DatePicker;
//use kartik\widgets\FileInput;
//use yii\widgets\Pjax;
//use app\components\Ccomponent;
use yii\helpers\Url;

//use kartik\widgets\SwitchInput;

$stepUp = 0;
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
                //'showErrors' => false,
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


            <div class="col-md-12">
                <?= $form->field($model, 'traffic_number')->textInput() ?>
            </div>
            <div class="col-md-12">
                <?PHP
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

                echo $form->field($model, 'employee_id')->label('ชื่อเจ้าของรถ')->widget(Select2::classname(), [
                    'options' => ['placeholder' => '--เลือกชื่อเจ้าของรถ--'],
                    'initValueText' => ($model->employee_id <> '' ? $model->getWorkAssign()->employee_fullname : ''), // set the initial display text
                    'theme' => Select2::THEME_KRAJEE_BS5,
                    'pluginOptions' => [
                        'dropdownParent' => '#modalForm',
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
                            'data' => new JsExpression('function(params) {return {q:params.term,mode:"A",ac:"1"}; }')
                        ],
                        'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                        'templateResult' => new JsExpression('formatRepo'),
                        'templateSelection' => new JsExpression('formatRepoSelection'),
                    ],
                ]);
                ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'comments')->textInput() ?>
            </div>
                <div class="col-md-12">
                    <?=
                    $form->field($model, 'traffic_status')->dropDownList([
                        '1' => 'ใช้งาน',
                        '0' => 'ไม่ใช้งาน',
                            ], ['prompt' => 'เลือกสถานะ...']);
                    ?>

                </div>
           
        </div>
    </div>
    <div class="col-md-12">
        <div class="row justify-content-between mt-3 mb-5">
            <div class="col-6">
                <?= Html::a('<i class="fa fa-angle-left fa-lg"></i> กลับหน้าจัดการ', 'javascript:;', ['class' => 'btn  btn-sm btn-dark font-weight-bold', 'data-bs-dismiss' => 'modal']) ?>
            </div>
            <div class="col-6 text-right">
                <?=
                Html::button('<i class="fa fa-save fa-lg"></i> บันทึกข้อมูล', ['class' => 'btn btn-sm btn-primary font-weight-bold', 'id' => 'btnFrmOffice',])
                ?>
            </div>
        </div>
        <hr>
    </div>
</div>
<?php ActiveForm::end(); ?>