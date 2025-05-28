<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\widgets\Pjax;

$js = <<<JS
$.pjax.reload({container: '#gridViewDep', async: false});
JS;

Pjax::begin(['id' => 'frmDep', 'timeout' => false, 'enablePushState' => false]); //
$this->registerJs($js, $this::POS_READY);
?>

<div class="project-company-form">
    <?php
    $form = ActiveForm::begin([
                'id' => 'frmManage',
                'action' => ['manage', 'id' => @$_GET['id']],
                'method' => 'post',
                'layout' => 'horizontal',
                'options' => [
                    'data-pjax' => 1,
                ],
                    //'enableClientValidation' => false,
    ]);
    ?>
    <div class="card">
        <div class="card-body">
            <?= $form->field($model, 'employee_dep_code')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
            <?= $form->field($model, 'employee_dep_label')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
            <?=
            $form->field($model, 'employee_dep_parent')->widget(Select2::classname(),
                    [
                        'data' => $dep,
                        'options' => ['placeholder' => 'เลือกหน่วยงาน...'],
                        'pluginOptions' => [
                            'dropdownParent' => '#modalContents',
                            'allowClear' => true,
                        //'minimumInputLength' => 2,
                        ],
            ]);
            ?>
            <?=
            $form->field($model, 'category_id')->widget(Select2::classname(),
                    [
                        'data' => $lists,
                        'options' => ['placeholder' => 'เลือกประเภทหน่วยงาน...'],
                        'pluginOptions' => [
                            'dropdownParent' => '#modalContents',
                            'allowClear' => true,
                        //'minimumInputLength' => 2,
                        ],
            ]);
            ?>

            <?= $form->field($model, 'employee_dep_sort')->textInput(['type' => 'number', 'class' => 'form-control form-control-lg']) ?>
            <?= $form->field($model, 'employee_dep_level')->textInput(['type' => 'number', 'class' => 'form-control form-control-lg']) ?>
            <?php
            echo $form->field($model, 'employee_dep_status')->dropDownList(
                    ['1' => 'ใช้งาน', '0' => 'เลิกใช้งาน']
                    , ['class' => 'form-control form-control-lg']
            );
            ?>

            <div class="form-group mt-2">
                <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php Pjax::end(); ?>