<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model app\modules\survey\models\SurveyComputer */
?>

<?php $form = ActiveForm::begin([
    'id' => 'update-form',
    'enableAjaxValidation' => true,
]); ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'item')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'short_name')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'price')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'DE_id')->textInput() ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'specification')->textarea(['rows' => 10]) ?>
        <?= $form->field($model, 'active')->dropDownList([1 => 'Active', 0 => 'Inactive']) ?>
    </div>
</div>

<div class="form-group mt-2">
    <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
</div>

<?php ActiveForm::end(); ?>
<?php
$js = <<<JS
$('#update-form').on('beforeSubmit', function(e) {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(response) {
            if(response.success){
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#updateModal').modal('hide');
                $.pjax.reload({container:'#grid-pjax'});
            } else {
                Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาด', 'error');
            }
        },
        error: function(){
            Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาด', 'error');
        }
    });
    return false; 
});
JS;

$this->registerJs($js);
?>