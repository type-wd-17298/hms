<?php

use app\components\Ccomponent;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Html;
//use kartik\form\ActiveForm;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
//use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\DateTimePicker;

$url = Url::to(['calendar']);
if ($model->isNewRecord) {
    $urlBook = Url::to(['create']);
} else {
    $urlBook = Url::to(['update']);
}

//$url = Url::to(['update']);
$js = <<<JS
 $('#frm99').on('beforeSubmit', function(e) {
           e.preventDefault();
            var form = $(this);
            var formData = form.serialize();
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                success: function (data) {
                    if(data.status == 'success'){
                        $.get('$urlBook',{id:'{$_GET['id']}'}, function(data) {
                            $("#modalContent").html(data);
                            $("#modalRoom").modal('hide');
                            $.get("{$url}",{}, function(data) {
                                $("#calendar-body").html(data);
                            });
                            //$.pjax.reload({container: '#mainLeave', async: false});
                            $("#gBookView").yiiGridView('applyFilter');
                        });
                    }else{
                           jQuery.each(data, function(i, val) {
                               //$("#" + i).append(document.createTextNode(" - " + val));
                               alert(val);
                           });
                    }
                },
                error: function () {
                    alert("Something went wrong");
                }
            });
        }).on('submit', function(e){
            e.preventDefault();
            return false; // Cancel form submitting.
        });
JS;

Pjax::begin(['id' => 'frm01', 'timeout' => false, 'enablePushState' => false]);
$this->registerJs($js, $this::POS_READY);
?>

<?php
$form = ActiveForm::begin([
            'id' => 'frm99',
            // 'type' => ActiveForm::TYPE_HORIZONTAL,
            'options' => [
                'data-pjax' => true,
                'enctype' => 'multipart/form-data',
                'class' => 'small',
            ],
            'fieldConfig' => [
                'template' => "<div class='font-weight-bold'>{label}</div>\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            ],
                //'enableAjaxValidation' => true, //เปิดการใช้งาน AjaxValidation
                //'enableClientValidation' => false,
                //'enableClientValidation' => false,
        ]);
print_r($form->errorSummary($model));
?>

<div class="row">
    <div class="col-md-12">
        <?PHP
        /*
          $form->field($model, 'bk_meetingroom_id')->radioList(ArrayHelper::map(\app\modules\office\models\BookMeetingRoom::find()
          ->orderBy(['bk_meetingroom_id' => SORT_ASC])
          ->all(), 'bk_meetingroom_id', 'bk_meetingroom_name')
          , ['custom' => true, 'inline' => true])
         *
         */
        ?>
        <?PHP
        echo $form->field($model, 'bk_meetingroom_id')->dropDownList(
                ArrayHelper::map(\app\modules\office\models\BookMeetingRoom::find()
                                ->orderBy(['bk_meetingroom_id' => SORT_ASC])
                                ->all(), 'bk_meetingroom_id', 'bk_meetingroom_name'),
                [
                    'disabled' => $model->isNewRecord ? false : true,
                    'prompt' => '--เลือกหน่วยงานภายใน--',
                    'class' => 'form-control form-control-lg'
                ]
        );
        ?>
        <?= $form->field($model, 'subject')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
        <?= $form->field($model, 'detail')->textarea(['rows' => 3, 'class' => 'form-control form-control-lg']) ?>
        <?= $form->field($model, 'bk_number_attendee')->textInput(['type' => 'number', 'class' => 'form-control form-control-lg']) ?>
        <?PHP
        echo $form->field($model, 'employee_dep_id')->dropDownList(
                ArrayHelper::map(\app\modules\hr\models\EmployeeDep::find()
                                ->orderBy(['employee_dep_label' => SORT_ASC])
                                ->all(), 'employee_dep_id', 'employee_dep_label'),
                [
                    //'disabled' => $model->isNewRecord ? false : true,
                    'prompt' => '--เลือกหน่วยงานภายใน--',
                    'class' => 'form-control form-control-lg'
                ]
        );
        ?>
        <?php
        echo $form->field($model, 'date_event_timein')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'เวลาเริ่มการประชุม ...'],
            //'disabled' => $model->isNewRecord ? false : true,
            'pluginOptions' => [
                'autoclose' => true
            ]
        ]);
        ?>
        <?php
        echo $form->field($model, 'date_event_timeout')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'เวลาสิ้นสุดการประชุม ...'],
            //'disabled' => $model->isNewRecord ? false : true,
            'pluginOptions' => [
                'autoclose' => true
            ]
        ]);
        ?>
    </div>

    <div class="col-md-12 ">
        <div class="row justify-content-between mt-3 mb-5">
            <div class="col-6">
                <?=
                Html::submitButton('<i class="la la-save la-lg"></i> บันทึกรายการ', [
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

<?php ActiveForm::end(); ?>
<?php Pjax::end() ?>