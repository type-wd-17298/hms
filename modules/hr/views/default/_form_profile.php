<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;

$js = <<<JS
$.pjax.reload({container: '#gridViewDep', async: false});
JS;

//Pjax::begin(['id' => 'frmEmp', 'timeout' => false, 'enablePushState' => false]); //
//$this->registerJs($js, $this::POS_READY);
?>
<?php if (Yii::$app->session->hasFlash('alert')): ?>
    <?php
    $op = ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options');
    ?>
    <div class="alert <?= $op['class'] ?> alert-dismissible fade show  mt-2" role="alert">
        <p class="mb-0">
            <?= ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body') ?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>

<?php endif; ?>

<div class="project-company-form mt-3">
    <?php
    $form = ActiveForm::begin([
                'id' => 'frmProfile',
                'action' => ['executive', 'id' => @$_GET['id']],
                // 'method' => 'post',
                'layout' => 'horizontal',
                'options' => [
                    'data-pjax' => 1,
                    'data-pjax-container' => 'profileForm',
                ],
                    //'enableClientValidation' => false,
    ]);
    //print_r($form->errorSummary($model));
    ?>
    <div class="card">
        <div class="card-body1">
            <?=
            $form->field($model, 'employee_cid')->widget(MaskedInput::className(), [
                'mask' => '9-9999-99999-99-9',
                'options' => [
                    'class' => 'form-control form-control-lg',
                    'disabled' => !$model->isNewRecord,
                ],
                'clientOptions' => [
                    'removeMaskOnSubmit' => true,
                ]
            ])
            ?>
            <?= $form->field($model, 'employee_fullname')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
            <?=
            $form->field($model, 'employee_dep_id')->widget(Select2::classname(),
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
            $form->field($model, 'employee_position_id')->widget(Select2::classname(),
                    [
                        'data' => $position,
                        'options' => ['placeholder' => 'เลือกตำแหน่ง...'],
                        'pluginOptions' => [
						
                            'dropdownParent' => '#modalContents',
                            'allowClear' => true,
                        //'minimumInputLength' => 2,
                        ],
            ]);
            ?>
            <?=
            $form->field($model, 'employee_type_id')->widget(Select2::classname(),
                    [
                        'data' => $type,
                        'options' => ['placeholder' => 'เลือกปรเภทเจ้าหน้าที่...'],
                        'pluginOptions' => [
						
                            'dropdownParent' => '#modalContents',
                            'allowClear' => true,
                        //'minimumInputLength' => 2,
                        ],
            ]);
            ?>
            <?PHP
            /*
              $form->field($model, 'category_id')->widget(Select2::classname(),
              [
              'data' => $lists,
              'options' => ['placeholder' => 'เลือกประเภทหน่วยงาน...'],
              'pluginOptions' => [
              'allowClear' => true,
              //'minimumInputLength' => 2,
              ],
              ]);
             *
             */
            ?>

            <?= $form->field($model, 'employee_phone')->textInput(['class' => 'form-control form-control-lg']) ?>
            <?= $form->field($model, 'employee_address')->textarea(['class' => 'form-control form-control-lg']) ?>
            <?php
            echo $form->field($model, 'employee_status')->dropDownList(
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
<?php
//Pjax::end(); ?>