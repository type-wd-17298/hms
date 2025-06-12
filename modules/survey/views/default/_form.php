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

    console.log('Form URL:', url);
    const obj = {};
    data.forEach((value, key) => {
    obj[key] = value;
    });
    console.log("FormData Object:", obj);
    

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


function togglePartnumberField() {
    let selected = $('input[name$="[survey_type]"]:checked').val();
    if (selected === 'ทดแทน') {
        $('#partnumber-select').closest('.form-group').show();
    } else {
        $('#partnumber-select').closest('.form-group').hide();
        $('#partnumber-field').hide();
    }
}

togglePartnumberField();

$(document).on('change', 'input[name$="[survey_type]"]', function () {
    togglePartnumberField();
});

document.getElementById('partnumber-select').addEventListener('change', function() {
    const selected = this.value;
    const details = partData[selected];

    if (details) {

        document.getElementById('item-name').textContent = details.name;
        document.getElementById('purchase-date').textContent = details.purchase_date;
        document.getElementById('usage-years').textContent = details.usage_years;

        document.getElementById('partnumber-field').style.display = 'block'; 
    } else {
        document.getElementById('partnumber-field').style.display = 'none';
    }
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
        'deviceSize' => ActiveForm::SIZE_X_LARGE
    ],
    'options' => [
        'data-pjax' => true,
        'enctype' => 'multipart/form-data'
    ],
    // 'enableClientValidation' => true,
    //'enableAjaxValidation' => false,
]);
//print_r($form->errorSummary($model));
?>

<?php
$partOptions = [
    'PN001' => [
        'name' => 'ชุดโปรแกรมระบบปฏิบัติการสำหรับเครื่องคอมพิวเตอร์แม่ข่าย (Server) สำหรับรองรับหน่วยประมวลผลกลาง (CPU) ไม่น้อยกว่า 16 แกนหลัก (16 core) ที่มีลิขสิทธิ์ถูกต้องตามกฎหมาย',
        'purchase_date' => '24 กรกฎาคม 2558',
        'usage_years' => '10 ปี'
    ],
    'PN002' => [
        'name' => 'เครื่องคอมพิวเตอร์พกพา (Notebook)',
        'purchase_date' => '15 มีนาคม 2562',
        'usage_years' => '5 ปี'
    ],
    'PN003' => [
        'name' => 'เครื่องพิมพ์เลเซอร์สี',
        'purchase_date' => '10 มกราคม 2560',
        'usage_years' => '8 ปี'
    ],
];
?>

<div class="row m-2">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <?PHP
                $budgetYear = [];
                for ($y = (date('Y') + 544); $y >= (date('Y') + 544); $y--) {
                    $budgetYear[$y] = $y;
                }
                echo $form->field($model, 'survey_budget_year')->dropDownList(
                    $budgetYear,
                    ['class' => 'form-control']
                )
                ?>
            </div>


            <div class="col-md-8">
                <?php
                echo $form->field($model, 'item_id')->dropDownList(
                    ArrayHelper::map(app\modules\survey\models\SurveyComputer::find()
                        ->orderBy(['id' => SORT_ASC])
                        ->all(), 'id', 'fullname'),
                    [
                        #'disabled' => $model->isNewRecord ? false : true,
                        'prompt' => '--เลือกรายการ--',
                    ]
                );
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
                // $form->field($model, 'survey_type')->dropDownList(
                //     ['ทดแทน' => 'ทดแทน', 'เพิ่มเติม' => 'เพิ่มเติม'],
                //     ['prompt' => 'เลือกรายการ']
                // );
                // 
                ?>

                <div class="col-md-4">
                    <?= $form->field($model, 'survey_type')->radioList([
                        'ทดแทน' => 'ทดแทน',
                        'เพิ่มเติม' => 'เพิ่มเติม',
                    ], ['inline' => true]) ?>
                </div>

            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'survey_list_partnumber')->dropDownList(
                    array_combine(array_keys($partOptions), array_keys($partOptions)),
                    ['prompt' => 'เลือกเลขครุภัณฑ์', 'id' => 'partnumber-select']
                ) ?>
            </div>

            <div class="col-md-4" id="partnumber-field" style="display: none; flex-direction: column; gap: 8px; padding-top: 10px;">
                <input type="hidden" id="selected-partnumber" name="selected_partnumber">
                <div><strong>รายการ:</strong> <span id="item-name"></span></div>
                <div><strong>วันที่จัดซื้อ:</strong> <span id="purchase-date"></span></div>
                <div><strong>อายุการใช้งาน:</strong> <span id="usage-years"></span></div>
            </div>

            <!-- <div class="col-md-4">
                <label>
                    <strong>ชื่อครุภัณฑ์ :</strong>
                    ชุดโปรแกรมระบบปฏิบัติการสำหรับเครื่องคอมพิวเตอร์แม่ข่าย (Server) สำหรับรองรับหน่วยประมวลผลกลาง (CPU) ไม่น้อยกว่า 16 แกนหลัก (16 core) ที่มีลิขสิทธิ์ถูกต้องตามกฎหมาย
                </label><br />
                <label><strong>วันที่จัดซื้อ : </strong>วันที่ 24 กรกฎาคม 2558</label><br />
                <label><strong>อายุการใช้งานจนถึงปัจจุบัน :</strong> 10 ปี</label>
            </div> -->


            <div class="col-md-6">
                <?= $form->field($model, 'survey_list_comment')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>
            <?php if (Yii::$app->user->can('SuperAdmin') || Yii::$app->user->can('ITAdmin')): ?>
                <div class="col-md-6">
                    <?= $form->field($model, 'it_comment')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
                </div>
            <?php endif; ?>

        </div>
        <div class="col-md-12">
            <div class="row justify-content-between mt-3 mb-5">
                <div class="col-6">
                    <?= Html::a('<i class="fa fa-angle-left fa-lg"></i> กลับหน้าจัดการ', 'javascript:;', ['class' => 'btn btn-dark btn-lg font-weight-bold', 'data-bs-dismiss' => 'modal']) ?>
                </div>
                <div class="col-6 text-right">
                    <?php if ($mode === 'approve'): ?>
                        <?= Html::button('<i class="fas fa-check"></i> อนุมัติ', ['class' => 'btn btn-success btn-lg font-weight-bold', 'id' => 'btnApprove']) ?>
                        <?= Html::button('<i class="fas fa-times"></i> ไม่อนุมัติ', ['class' => 'btn btn-danger btn-lg font-weight-bold', 'id' => 'btnReject']) ?>
                    <?php else: ?>
                        <?= Html::button('<i class="fa fa-save fa-lg"></i> บันทึกข้อมูล', ['class' => 'btn btn-primary btn-lg font-weight-bold', 'id' => 'btnFrmOffice']) ?>
                        <?= Html::button('<i class="fa fa-delete fa-lg"></i> ลบข้อมูล', ['class' => 'btn btn-danger btn-lg font-weight-bold', 'id' => 'btnFrmDelete']) ?>
                    <?php endif; ?>


                </div>
            </div>
            <hr>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <script>
        const partData = <?= json_encode($partOptions, JSON_UNESCAPED_UNICODE); ?>;

        document.getElementById('partnumber-select').addEventListener('change', function() {
            const selected = this.value;
            const details = partData[selected];

            if (details) {
                document.getElementById('selected-partnumber').value = selected;
                document.getElementById('item-name').textContent = details.name;
                document.getElementById('purchase-date').textContent = details.purchase_date;
                document.getElementById('usage-years').textContent = details.usage_years;
                document.getElementById('partnumber-field').style.display = 'flex';
            } else {
                document.getElementById('partnumber-field').style.display = 'none';
            }
        });
    </script>