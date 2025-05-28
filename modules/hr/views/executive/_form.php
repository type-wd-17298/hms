<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
?>

<div class="project-company-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'frmIndex',
                'action' => ['index', 'id' => @$_GET['id']],
                'method' => 'post',
                'layout' => 'horizontal',
                'options' => [
                    'data-pjax' => 1,
                //'class' => 'form-inline'
                ],
                'enableClientValidation' => false,
    ]);
    ?>
    <div class="card">
        <div class="card-body">
            <?= $form->field($model, 'employee_executive_name')->textInput(['maxlength' => true]) ?>
            <?php
            echo $form->field($model, 'dep_code')->widget(Select2::classname(), [
                'options' => [
                    'placeholder' => 'เลือกหน่วยงาน...',
                    'multiple' => true,
                //'class' => 'small',
                ],
                'theme' => Select2::THEME_MATERIAL,
                'pluginOptions' => [
				
                    'tags' => true,
                    'allowClear' => false,
                    'minimumInputLength' => 0,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => yii\helpers\Url::to(['/office/paperless/deplist']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                    'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
                ],
            ]);
            ?>
            <?= $form->field($model, 'employee_executive_sort')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'employee_executive_level')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'employee_executive_comment')->textarea() ?>
            <div class="form-group mt-2">
                <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
