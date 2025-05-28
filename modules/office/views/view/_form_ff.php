<?php

use app\components\Ccomponent;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
//use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\widgets\Pjax;
//use kartik\grid\GridView;
use yii\helpers\Url;

$defaultPage = '';

//$operate = $model->paperless_status_id;
$urlAcl = Url::to(['acknowledge']);
$src = Url::to(['view', 'id' => @$model2->paperless_view_id]);
//$url = Url::to(['createprocess']);
$this->params['mqttFuncMessage'] = <<<JS

JS;

Pjax::begin(['id' => 'frm01', 'timeout' => false, 'enablePushState' => false]);

$js = <<<JS
     $('#btnFrm99').on('click', function (e) {
        var form = $(this).parents('form');
      Swal.fire({
            icon: 'warning',
            title: 'ยืนยันการบันทึกข้อมูล?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'บันทึกรายการ',
            denyButtonText: 'ยกเลิก',
          }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกรายการแล้ว !',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    //$("#modalForm").modal('hide');
            } else if (result.isDenied) {
              Swal.fire({
                    icon: 'info',
                    title: 'ข้อมูลของคุณไม่ได้ถูกบันทึก !',
                    showConfirmButton: false,
                    timer: 1500
                  });
                  $("#modalForm").modal('hide');
            }
          })
     });

JS;
$this->registerJs($js, $this::POS_READY);
?>

<?php
$form = ActiveForm::begin([
            'id' => 'frm99',
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'options' => [
                'data-pjax' => true,
                'enctype' => 'multipart/form-data',
                'class' => 'small',
            ],
                //'enableAjaxValidation' => true, //เปิดการใช้งาน AjaxValidation
                //'enableClientValidation' => false,
                //'enableClientValidation' => false,
        ]);
print_r($form->errorSummary($model));
?>

<div class="row m-2">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2">
                ID
            </div>
            <div class="col-md-10">
                <?= $model2->paperless_view_id ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                เรื่อง
            </div>
            <div class="col-md-10">
                <?PHP
                if (substr($model2->paperless_paper_ref, 0, 1) == 'A') {
                    $topic = $model2->paperless_topic;
                } else {
                    $topic = @$model2->paper->paperless_topic;
                }
                echo $topic;
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                รายละเอียด
            </div>
            <div class="col-md-10">
                <?= $model2->paperless_detail ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                วันที่
            </div>
            <div class="col-md-10">
                <?= Ccomponent::getThaiDate(($model2->paperless_view_startdate), 'S', 0) ?>
            </div>
        </div>
        <hr>
        <?= $form->field($model2, 'paperless_detail')->textarea(['rows' => 2, 'class' => 'form-control form-control-lg']) ?>
        <hr>
        <div class="row">
            <div class="col-md-2 font-weight-bold">
                ส่งต่อ/เวียนต่อ
            </div>
        </div>

        <?php
        echo $form->field($model, 'paperless_view_deps')->widget(Select2::classname(), [
            //'name' => 'paperless_view_deps',
            'data' => ArrayHelper::map(\app\modules\hr\models\EmployeeDep::find()->orderBy(['employee_dep_label' => SORT_ASC])->all(), 'employee_dep_id', 'employee_dep_label'),
            'id' => 'deps',
            'options' => [
                'placeholder' => 'เลือกมอบให้(หน่วยงาน)...',
                'multiple' => true,
                'class' => 'form-control form-control-lg',
            ],
            'theme' => Select2::THEME_MATERIAL,
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

        <?php
        echo $form->field($model, 'paperless_view_emps')->widget(Select2::classname(), [
            //'name' => 'paperless_view_emps',
            'data' => ArrayHelper::map(\app\modules\hr\models\Employee::find()->where(['employee_status' => 1])->orderBy(['employee_id' => SORT_ASC])->all(), 'employee_id', 'employee_fullname'),
            'id' => 'emps',
            'options' => [
                'placeholder' => 'เลือกมอบให้(เจ้าหน้าที่)...',
                'multiple' => true,
                'class' => 'form-control form-control-lg',
            ],
            'theme' => Select2::THEME_MATERIAL,
//                'pluginEvents' => [
//                    "select2:select" => "function(d) { offerText(d.params.data); }",
//                ],
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
        <div class="row justify-content-between mt-3 mb-5">
            <div class="col-6">
                <?=
                Html::button('<i class="la la-save la-lg"></i> บันทึกรายการ', [
                    'class' => 'btn btn-primary btn-lg font-weight-bold',
                    'id' => 'btnFrm99'
                ])
                ?>
            </div>
            <div class="col-6 text-right">
                <?= Html::a('<i class="la la-angle-left la-lg"></i> กลับหน้าจัดการ', 'javascript:;', ['class' => 'btn btn-dark  btn-lg font-weight-bold', 'data-bs-dismiss' => 'modal']) ?>
            </div>
        </div>
    </div>
</div>
<div class="pt">
    <div id="embedContent">
        <div class="embed-responsive embed-responsive-4by3">
            <iframe class="embed-responsive-item" src="<?= yii\helpers\Url::to(['view', 'id' => $model2->paperless_paper_ref]) ?>#view=FitW" allowfullscreen></iframe>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end() ?>