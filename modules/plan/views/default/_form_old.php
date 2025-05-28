<?php

use yii\bootstrap4\Html;
use kartik\form\ActiveForm;
use app\modules\survay\models\Thaiaddress;
use kartik\widgets\DatePicker;
use app\modules\survay\models\Cdisatype;
use yii\helpers\ArrayHelper;
use app\modules\survay\models\Cdep;
?>

<div class="person-screen-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'frm',
                //'type' => ActiveForm::TYPE_HORIZONTAL,
                'options' => ['enctype' => 'multipart/form-data'],
                'formConfig' => [
                //'deviceSize' => ActiveForm::SIZE_MEDIUM,
                ],
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
//                    'horizontalCssClasses' => [
//                        'label' => 'col-sm-4',
//                        'offset' => 'offset-sm-2',
//                        'wrapper' => 'col-sm-6',
//                        'error' => '',
//                        'hint' => '',
//                    ],
                ],
    ]);
    print_r($form->errorSummary($model))
    ?>

    <div class="card mb-3 ">
        <div class="card-header"><i class="fa-solid fa-chalkboard-user"></i> แผนปฏิบัติการโรงพยาบาล ปีงบประมาณ 2567</div>
        <div class="card-body">
            <h4 class="card-title"></h4>
            <?= $form->field($model, 'plan_list_title')->textInput() ?>
            <?= $form->field($model, 'plan_list_objective')->textarea() ?>
            <?= $form->field($model, 'plan_list_target')->textarea() ?>
            <?= $form->field($model, 'plan_list_activity')->textarea() ?>
            <?= $form->field($model, 'plan_list_costdetail')->textarea() ?>
            <?= $form->field($model, 'plan_list_kpi')->textarea() ?>
            <?= $form->field($model, 'plan_list_period')->textInput() ?>
            <?= $form->field($model, 'plan_list_budget')->textInput() ?>
            <div class="form-group">
                <?= Html::submitButton('<i class="fa-solid fa-paper-plane"></i> บันทึกข้อมูล', ['class' => 'btn btn-success btn-lg btn-block']) ?>
                <br>
            </div>


        </div>
    </div>

    <div id="myTabContent" class="tab-content bg-white">
        <div class="tab-pane fade show active mr-2 ml-2" id="home">
            <br>

        </div>
        <div class="tab-pane fade show" id="com">
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
