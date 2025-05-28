<?php

use yii\bootstrap4\Html;
#use yii\bootstrap4\ActiveForm;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use kartik\date\DatePicker;

$this->title = 'แบบฟอร์มการบึนทันทึกโครงการ';
$url = Url::to(['/project/company']);
$js = <<<JS
        $("#btnPopup").click(function(){
            $('#modalCompany').modal('show');
            $("#modalContentType").html('กำลังเรียกข้อมูล...');
            $.get("{$url}",{}, function(data) {
               $("#modalContentType").html(data);
            });
    });
JS;
$this->registerJs($js, $this::POS_LOAD);
?>

<div class="person-form">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">ระบบบริหารงานพัสดุ</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $this->title ?></a></li>
        </ol>
    </div>
    <div class="alert alert-primary">
        <div class="mb-0">
            <strong>คำแนะนำใช้งาน</strong><br><?= $this->title ?>
        </div>
    </div>


    <?php
    $form = ActiveForm::begin([
                'id' => 'frm',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'options' => ['enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        #'columnSize' => 'lg',
                        'label' => 'col-sm-4',
                        'offset' => 'offset-sm-2',
                        'wrapper' => 'col-sm-8',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
    ]);

    /*
      $form = ActiveForm::begin([
      'id' => 'frm',
      'options' => ['enctype' => 'multipart/form-data'],
      'layout' => 'horizontal',
      'fieldConfig' => [
      'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
      'horizontalCssClasses' => [
      'label' => 'col-sm-4',
      'offset' => 'offset-sm-2',
      'wrapper' => 'col-sm-8',
      'error' => '',
      'hint' => '',
      ],
      ],
      ]);
     *
     */
    //print_r($form->errorSummary($model))
    ?>

    <div class="card border-secondarys">
        <div class="card-header">
            <b><?= $this->title ?></b>
        </div>
        <div class="card-block m-2">
            <?php
            echo $form->field($model, 'project_type_id')->dropDownList(
                    ArrayHelper::map(app\modules\project\models\ProjectType::find()
                                    ->orderBy(['project_type_id' => SORT_ASC])
                                    ->all(), 'project_type_id', 'project_type_name'), [
                #'disabled' => $model->isNewRecord ? false : true,
                'prompt' => '--เลือกประเภท--',
            ]);
            ?>
            <?=
            $form->field($model, 'project_buy_type')->radioList([
                1 => 'ทั่วไป',
                2 => 'ซื้อเข้าคลัง',
                    ], ['disabled' => $model->isNewRecord ? false : true])
            ?>
            <?=
            $form->field($model, 'project_type_order_id')->radioList(
                    ArrayHelper::map(app\modules\project\models\ProjectTypeOrder::find()
                                    ->orderBy(['project_type_order_id' => SORT_ASC])
                                    ->all(), 'project_type_order_id', 'project_type_order_name')
            )
            ?>
            <?=
            $form->field($model, 'project_type_prefer_id')->radioList(
                    ArrayHelper::map(app\modules\project\models\ProjectTypePrefer::find()
                                    ->orderBy(['project_type_prefer_id' => SORT_ASC])
                                    ->all(), 'project_type_prefer_id', 'project_type_prefer_name')
            )
            ?>

            <?php
            $prefix = empty($model->project_company_id) ? '' : app\modules\project\models\ProjectCompany::findOne($model->project_company_id)->project_company_name; //กำหนดค่าเริ่มต้น
            echo $form->field($model, 'project_company_id')->widget(Select2::classname(), [
                //'data' => $data,
                'initValueText' => $prefix, //กำหนดค่าเริ่มต้น
                'size' => Select2::MEDIUM,
                'options' => ['placeholder' => '--เลือกบริษัท--', 'multiple' => false],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['company/search']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {return {q:params.term}; }')
                    ],
                ],
            ]);
            ?>



            <div class="text-right mb-2">
                <button class="btn  btn-primary btn-danger" id="btnPopup" type="button">เพิ่มรายการบริษัท</button>
            </div>
            <?=
            $form->field($model, 'project_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'วันที่'],
                'language' => 'th-TH',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayBtn' => true,
                    'todayHighlight' => true,
                #'yearRange' => '+543',
                ]
            ]);
            ?>
            <?= $form->field($model, 'project_code')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'project_name')->textInput(['maxlength' => true]) ?>
            <?=
            $form->field($model, 'project_cost')->widget(\yii\widgets\MaskedInput::className(), [
                'clientOptions' => [
                    'alias' => 'decimal',
                    'groupSeparator' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ])
            ?>

            <?= $form->field($model, 'project_comment')->textarea() ?>
        </div>
    </div>

    <div class="row justify-content-between mt-3 mb-5">
        <div class="col-6">
            <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
        <div class="col-6 text-right">
            <?= Html::a('กลับหน้าจัดการ', ['index'], ['class' => 'btn btn-light btn-lg']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<!-- Modal -->
<div class="modal fade" id="modalCompany" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เพิ่มรายการบริษัท</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div id="modalContentType" class="m-2"></div>
        </div>
    </div>
</div>
</div>


