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

$js = <<<JS
JS;
$this->registerJs($js, $this::POS_LOAD);
?>

<div class="person-form">
    <div class="alert alert-secondary">
        <div class="mb-0">
            <strong>คำแนะนำใช้งาน</strong><br>ทะเบียนคุมเลขโครงการ e-GP
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
            <b>ฟอร์มทะเบียนคุมเลขโครงการ e-GP</b>
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
            $form->field($model, 'project_type_order_id')->radioList(
                    ArrayHelper::map(app\modules\project\models\ProjectTypeOrder::find()
                                    ->orderBy(['project_type_order_id' => SORT_ASC])
                                    ->all(), 'project_type_order_id', 'project_type_order_name')
            )
            ?>

            <?=
            $form->field($model, 'project_orderdate')->widget(DatePicker::classname(), [
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
            <?= $form->field($model, 'project_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'project_ordernumber')->textInput(['disabled' => true]) ?>
            <?=
            $form->field($model, 'project_amount')->widget(\yii\widgets\MaskedInput::className(), [
                'clientOptions' => [
                    'alias' => 'decimal',
                    'groupSeparator' => ',',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                ],
            ])
            ?>
            <?= $form->field($model, 'project_contactnumber')->textInput(['disabled' => $model->isNewRecord ? false : true,]) ?>
            <?= $form->field($model, 'project_contactname')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'project_contactdetail')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'project_contactmain')->textInput(['maxlength' => true]) ?>
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
