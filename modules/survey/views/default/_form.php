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
use app\modules\survey\models\EmployeeSubDept;
use PHPUnit\Util\Log\JSON;
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

    const selectedPartnumbers = $('#partnumber-select').val(); 
    const selectedSurveyType = $('input[name$="[survey_type]"]:checked').val();
    const assetNumbers = [];
    if (selectedSurveyType === 'ทดแทน') {
    const expectedCount = parseInt($('#request-count').val()) || 0;

    for (let [key, value] of data.entries()) {
        if (key.match(/^SurveyComputerList\[items\]\[\d+\]\[asset_number\]$/)) {
            assetNumbers.push(value);
        }
    }

    const filledAssets = assetNumbers.filter(val => val && val.trim() !== '');
    const actualCount = filledAssets.length;

    if (actualCount < expectedCount) {
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกเลขครุภัณฑ์ให้ครบ',
            text: 'คุณต้องกรอกเลขครุภัณฑ์ ' + expectedCount + ' รายการ',
            confirmButtonText: 'ตกลง'
        });
        return false;
    }

    // รวมเฉพาะค่าที่กรอกจริง
    const combined = filledAssets.join(',');
    data.set('SurveyComputerList[survey_list_partnumber]', combined);
}
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
                icon: 'success',
                title: 'บันทึกรายการสำเร็จ',
                showConfirmButton: false,
                timer: 1500
            });
        },
        error: function () {
            alert("เกิดข้อผิดพลาดในการบันทึกข้อมูล");
        }
    });
}).on('submit', function (e) {
    e.preventDefault();
    return false;
});


function togglePartnumberField() {
    let selected = $('input[name$="[survey_type]"]:checked').val();
    if (selected === 'ทดแทน') {
        $('#partnumber-table-wrapper').show();
    } else {
        $('#partnumber-table-wrapper').hide();
        $('#partnumber-select').val('').trigger('change'); 
        $('#receiv-date').text('-');
        $('#asset-description').text('-');
        $('#asset-age').text('-');
    }
}

togglePartnumberField();

$(document).on('change', 'input[name$="[survey_type]"]', function () {
    togglePartnumberField();
});

JS;
$this->registerJs($js, $this::POS_READY);
$this->registerCss(".table-compact th, .table-compact td { padding: 4px 8px !important; vertical-align: middle; font-size: 13px; }");

?>

<?php
$this->registerCssFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['depends' => \yii\web\JqueryAsset::class]);
$jsAssetData = [];
foreach ($assetList as $item) {
    $jsAssetData[$item['asset_number']] = [
        'receiv_date' => $item['receiv_date'] ?? '',
        'description' => $item['description'] ?? '',
    ];
}
$jsAssetDataJson = json_encode($jsAssetData);
$assetListDropdown = \yii\helpers\ArrayHelper::map($assetList, 'asset_number', 'asset_number');
$assetData = $jsAssetData;

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

<div class="row m-2">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
                <?= $form->field($model, 'sub_department_id')->dropDownList(
                    ArrayHelper::map(
                        EmployeeSubDept::find()->orderBy(['employee_subDept_label' => SORT_ASC])->all(),
                        'employee_subDept_id',
                        'employee_subDept_label'
                    ),
                    [
                        'prompt' => '-- เลือกกลุ่มงาน/ฝ่าย/หน่วยงานย่อย --'
                    ]
                ) ?>
            </div>


            <div class="col-md-8">
                <?php

                // use yii\helpers\ArrayHelper;
                use app\modules\survey\models\SurveyComputer;

                $items = ArrayHelper::map(
                    SurveyComputer::find()->orderBy(['id' => SORT_ASC])->all(),
                    'id',
                    'fullname'
                );

                $items += [0 => 'อื่น ๆ'];

                echo $form->field($model, 'item_id')->dropDownList(
                    $items,
                    [
                        'prompt' => '--เลือกรายการ--',
                        'id' => 'item-dropdown'
                    ]
                );
                ?>
            </div>


            <div class="col-md-4">
                <?= $form->field($model, 'survey_list_reuest')->textInput(['id' => 'request-count']) ?>
            </div>

            <div class="col-md-12" id="detail-field" style="display: none;">
                <?= $form->field($model, 'detail')->textarea([
                    'rows' => 4,
                ]) ?>
            </div>
            <?php
            $this->registerJs(<<<JS
    function toggleDetailField() {
        var value = $('#item-dropdown').val();
        if (value === '0') {
            $('#detail-field').slideDown();
        } else {
            $('#detail-field').slideUp();
        }
    }

    $('#item-dropdown').on('change', toggleDetailField);
    toggleDetailField(); // เรียกตอนโหลดหน้า เพื่อเคลียร์สถานะเดิม
JS);
            ?>
            <div class="col-md-4">
                <?= $form->field($model, 'survey_list_problem')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'survey_list_desc')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'survey_list_compare')->textarea(['rows' => 4, 'class' => 'form-control form-control-sm']) ?>
            </div>
            <div class="col-md-2">
                <?PHP
                // $form->field($model, 'survey_type')->dropDownList(
                //     ['ทดแทน' => 'ทดแทน', 'เพิ่มเติม' => 'เพิ่มเติม'],
                //     ['prompt' => 'เลือกรายการ']
                // );
                // 
                ?>

                <!-- <div class="col-md-4"> -->
                <?= $form->field($model, 'survey_type')->radioList([
                    'ทดแทน' => 'ทดแทน',
                    'เพิ่มเติม' => 'เพิ่มเติม',
                ], ['inline' => true]) ?>
                <!-- </div> -->
            </div>
            <input type="hidden" id="selected-partnumbers" value="<?= $model->survey_list_partnumber ?>">
            <div class="row col-md-10">
                <div id="partnumber-table-wrapper" style="display: none;">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th style="width: 20%;">เลขครุภัณฑ์</th>
                                <th style="width: 15%;">วันที่รับครุภัณฑ์</th>
                                <th style="width: 50%;">รายละเอียด</th>
                                <th style="width: 15%;">อายุการใช้งาน</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-rows">
                            <!-- JS will populate here -->
                        </tbody>
                    </table>
                </div>

            </div>


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
        const assetData = <?= $jsAssetDataJson ?>;

        function calculateAge(receivDateStr) {
            const startDate = new Date(receivDateStr);
            const now = new Date();

            let years = now.getFullYear() - startDate.getFullYear();
            let months = now.getMonth() - startDate.getMonth();
            let days = now.getDate() - startDate.getDate();

            if (days < 0) {
                months--;
                const prevMonth = new Date(now.getFullYear(), now.getMonth(), 0);
                days += prevMonth.getDate();
            }

            if (months < 0) {
                years--;
                months += 12;
            }

            const ageText = `${years} ปี ${months} เดือน ${days} วัน`;

            return ageText;
        }

        function bindPartnumberChange() {
            $('#partnumber-select').off('change').on('change', function() {
                const selected = $(this).val();

                if (selected && assetData[selected]) {
                    const receivDate = assetData[selected].receiv_date || '';
                    $('#receiv-date').text(receivDate);
                    $('#asset-description').text(assetData[selected].description || '-');

                    if (receivDate) {
                        const ageText = calculateAge(receivDate);
                        $('#asset-age').text(ageText);
                    } else {
                        $('#asset-age').text('-');
                    }
                    $('#asset-details').show();
                } else {
                    $('#receiv-date').text('-');
                    $('#asset-description').text('-');
                    $('#asset-age').text('-');
                    $('#asset-details').hide();
                }
            });
        }

        function bindSelect2() {
            $('.select2').select2({
                width: '100%',
                dropdownParent: $('#modalForm'),
                placeholder: 'เลือกเลขครุภัณฑ์',
                allowClear: true
            });
        }


        function createRow(index) {
            const options = Object.keys(assetData).map(assetNumber =>
                `<option value="${assetNumber}">${assetNumber}</option>`
            ).join('');

            return `
            <tr>
                <td>
                    <select 
                        name="SurveyComputerList[items][${index}][asset_number]"
                        class="form-control asset-select select2 form-select"
                        data-index="${index}"
                        style="width: 100%;"
                        data-placeholder="-- เลือกเลขครุภัณฑ์ --">
                        <option></option>
                        ${options}
                    </select>
                </td>
                <td><span id="receiv-date-${index}">-</span></td>
                <td><span id="description-${index}">-</span></td>
                <td><span id="age-${index}">-</span></td>
            </tr>`;
        }




        function bindRequestCountInput() {
            $('#request-count').off('input').on('input', function() {
                const count = parseInt($(this).val()) || 0;
                const $tbody = $('#dynamic-rows');
                $tbody.empty();

                for (let i = 0; i < count; i++) {
                    $tbody.append(createRow(i));
                }
                bindSelect2();
            });
        }

        $(document).on('change', '.asset-select', function() {
            const index = $(this).data('index');
            const selected = $(this).val();
            const asset = assetData[selected];

            if (asset) {
                $(`#receiv-date-${index}`).text(asset.receiv_date || '-');
                $(`#description-${index}`).text(asset.description || '-');
                $(`#age-${index}`).text(asset.receiv_date ? calculateAge(asset.receiv_date) : '-');
            } else {
                $(`#receiv-date-${index}, #description-${index}, #age-${index}`).text('-');
            }
        });

        $(document).ready(function() {
            $('#modalForm').on('shown.bs.modal', function() {
                bindPartnumberChange();
                bindRequestCountInput();

                const surveyType = $('input[name$="[survey_type]"]:checked').val();
                const isReplacement = surveyType === 'ทดแทน';

                const selectedStr = $('#selected-partnumbers').val();
                const requestCountVal = parseInt($('#request-count').val());
                const $tbody = $('#dynamic-rows');

                $tbody.empty();

                if (isReplacement) {
                    if (selectedStr) {
                        const selectedArray = selectedStr.split(',');
                        selectedArray.forEach((assetNumber, index) => {
                            const rowHtml = createRow(index);
                            $tbody.append(rowHtml);
                            const $select = $(`select[name="SurveyComputerList[items][${index}][asset_number]"]`);
                            $select.val(assetNumber);
                            $select.trigger('change');
                        });
                        bindSelect2();
                        $('#partnumber-table-wrapper').show();
                    } else if (!isNaN(requestCountVal) && requestCountVal > 0) {
                        for (let i = 0; i < requestCountVal; i++) {
                            $tbody.append(createRow(i));
                        }
                        $('#partnumber-table-wrapper').show();
                    }
                } else {
                    $('#partnumber-table-wrapper').hide();
                }
            });
        });
    </script>