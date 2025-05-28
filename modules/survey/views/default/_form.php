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
            <div class="col-md-12">
                <?PHP
                $budgetYear = [];
                for ($y = (date('Y') + 544); $y >= (date('Y') + 544); $y--) {
                    $budgetYear[$y] = $y;
                }
                echo $form->field($model, 'survey_budget_year')->dropDownList(
                        $budgetYear, ['class' => 'form-control'])
                ?>
            </div>


            <div class="col-md-8">
                <?php
                echo $form->field($model, 'item_id')->dropDownList(
                        ArrayHelper::map(app\modules\survey\models\SurveyComputer::find()
                                        ->orderBy(['id' => SORT_ASC])
                                        ->all(), 'id', 'fullname'), [
                    #'disabled' => $model->isNewRecord ? false : true,
                    'prompt' => '--เลือกรายการ--',
                ]);
                ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'survey_list_reuest')->textInput() ?>
            </div>


            <div class="col-md-4">
                <?= $form->field($model, 'survey_list_problem')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'survey_list_desc')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'survey_list_compare')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>
            <div class="col-md-4">
                <?PHP
                $form->field($model, 'survey_type')->dropDownList(
                        ['ทดแทน' => 'ทดแทน', 'เพิ่มเติม' => 'เพิ่มเติม'],
                        ['prompt' => 'เลือกรายการ']
                );
                ?>

                <?=
                $form->field($model, 'survey_type')->radioList([
                    'ทดแทน' => 'ทดแทน',
                    'เพิ่มเติม' => 'เพิ่มเติม',
                        ], ['custom' => true, 'inline' => true])
                ?>

            </div>
            <div class="col-md-8">
                <?= $form->field($model, 'survey_list_partnumber')->textInput() ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'survey_list_comment')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row justify-content-between mt-3 mb-5">
            <div class="col-6">
                <?= Html::a('<i class="fa fa-angle-left fa-lg"></i> กลับหน้าจัดการ', 'javascript:;', ['class' => 'btn btn-dark btn-lg font-weight-bold', 'data-bs-dismiss' => 'modal']) ?>
            </div>
            <div class="col-6 text-right">
                <?= Html::button('<i class="fa fa-save fa-lg"></i> บันทึกข้อมูล', ['class' => 'btn btn-primary btn-lg font-weight-bold', 'id' => 'btnFrmOffice',]) ?>
                <?= Html::button('<i class="fa fa-delete fa-lg"></i> ลบข้อมูล', ['class' => 'btn btn-danger btn-lg font-weight-bold', 'id' => 'btnFrmDelete',]) ?>
            </div>
        </div>
        <hr>
    </div>
</div>
<?php ActiveForm::end(); ?>