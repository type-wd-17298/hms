<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model app\modules\survey\models\SurveyComputer */
?>

<?php $form = ActiveForm::begin([
    'id' => 'create-form',
    'enableAjaxValidation' => false,
    // 'action' => Url::to(['/survey/default/create-master']), 
]); ?>


<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'item')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'short_name')->textInput() ?>
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
        <?= $form->field($model, 'specification')->textarea(['rows' => 5]) ?>
        <?= $form->field($model, 'active')->dropDownList([1 => 'Active', 0 => 'Inactive']) ?>
    </div>
</div>


<div class="form-group mt-2">
    <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
</div>

<?php ActiveForm::end(); ?>