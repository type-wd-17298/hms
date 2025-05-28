<?php

use yii\bootstrap4\Html;
#use yii\bootstrap4\ActiveForm;
use kartik\form\ActiveForm;
use app\modules\survay\models\Thaiaddress;
use kartik\widgets\DatePicker;
#use kartik\widgets\Select2;
#use yii\web\JsExpression;
#use yii\helpers\Url;
#use yii\widgets\MaskedInput;
#use kartik\date\DatePicker;
use app\modules\survay\models\Cdisatype;
use yii\helpers\ArrayHelper;
#use app\modules\survay\models\Crightgroup;
use app\modules\survay\models\Cdep;
#use app\modules\survay\models\Csmoke;
#use app\modules\survay\models\Calcohol;
use app\modules\survay\components\Cmophic;

$vacc = Cmophic::searchCurrentByPID($data->person_cid);
$ccv = @count($vacc['vaccine_history']);
if (empty($model->person_screen_vcc19) || $ccv > 0) {
    $model->person_screen_vcc19 = $ccv . ' เข็ม';
}
?>

<div class="person-screen-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'frm',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'options' => ['enctype' => 'multipart/form-data'],
                'formConfig' => [
                //'deviceSize' => ActiveForm::SIZE_MEDIUM,
                ],
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-sm-4',
                        'offset' => 'offset-sm-2',
                        'wrapper' => 'col-sm-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
    ]);
#print_r($form->errorSummary($model))
    ?>

    <div class="card border-secondary mb-3 " >
        <div class="card-header bg-primary text-white"><i class="fa-solid fa-chalkboard-user"></i> ข้อมูลผู้รับการสำรวจ</div>
        <div class="card-body">
            <h4 class="card-title text-primary"><?= $data->person_fullname ?></h4>
            <hr>
            <p class="card-text">
                <b>เพศ</b> <?= $data->person_sex == 1 ? 'ชาย' : 'หญิง' ?>
                <b>อายุ</b> <?= (date('Y') - substr($data->person_birthdate, 0, 4)) . ' ปี'; ?>
                <br><b>ที่อยู่</b> <?=
                $data->person_address_no .
                ($data->person_address_moo <> '' ? ' ม.' . $data->person_address_moo : '')
                . @(isset($data->person_address_code) ? ' ' . Thaiaddress::findOne($data->person_address_code)->full_name : '');
                ?>
            </p>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active text-primary" data-toggle="tab" href="#home"><i class="fa-brands fa-wpforms fa-lg"></i> แบบฟอร์มสำรวจ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-primary" data-toggle="tab" href="#com">ภาวะแทรกซ้อน</a>
        </li>
    </ul>
    <div id="myTabContent" class="tab-content bg-white">
        <div class="tab-pane fade show active mr-2 ml-2" id="home">
            <br>
            <?=
            $form->field($model, 'person_screen_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'วันที่ประเมิน'],
                'language' => 'th-TH',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayBtn' => true,
                    'todayHighlight' => true,
                ]
            ]);
            ?>
            <?= $form->field($model, 'person_screen_weight')->textInput() ?>

            <?= $form->field($model, 'person_screen_height')->textInput() ?>

            <?= $form->field($model, 'person_screen_waist_cm')->textInput() ?>

            <?= $form->field($model, 'person_screen_sbp')->textInput() ?>

            <?= $form->field($model, 'person_screen_dbp')->textInput() ?>

            <?= $form->field($model, 'person_screen_pulse')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'person_screen_fbs')->textInput() ?>

            <?php
            echo $form->field($model, 'person_screen_intype')->dropDownList(
                    ArrayHelper::map(app\modules\survay\models\Crightgroup::find()
                                    ->orderBy(['rightgroupcode' => SORT_ASC])
                                    ->all(), 'rightgroupcode', 'rightgroupname'), [
                #'disabled' => $model->isNewRecord ? false : true,
                'prompt' => '--เลือกสิทธิในการรักษา--',
            ]);
            ?>

            <?php
            echo $form->field($model, 'person_screen_smoke')->dropDownList(
                    ArrayHelper::map(app\modules\survay\models\Csmoke::find()
                                    ->orderBy(['id_smoke' => SORT_ASC])
                                    ->all(), 'id_smoke', 'smoke'), [
                #'disabled' => $model->isNewRecord ? false : true,
                'prompt' => '--เลือกการสูบบุหรี่--',
            ]);
            ?>

            <?php
            echo $form->field($model, 'person_screen_alcohol')->dropDownList(
                    ArrayHelper::map(app\modules\survay\models\Calcohol::find()
                                    ->orderBy(['id_alcohol' => SORT_ASC])
                                    ->all(), 'id_alcohol', 'alcohol'), [
                #'disabled' => $model->isNewRecord ? false : true,
                'prompt' => '--เลือกการเครื่องดื่มแอลกอฮอล์--',
            ]);
            ?>
            <hr>
            <?php
            echo $form->field($model, 'person_screen_disability')->checkboxList(
                    ArrayHelper::map(Cdisatype::find()
                                    ->orderBy(['id_disatype' => SORT_ASC])
                                    ->all(), 'id_disatype', 'disatype'), [
                    #'separator' => '<br>',
                    #'prompt' => 'เลือกสถานบริการพยาบาล',
            ]);
            ?>
            <hr>
            <?php
            echo $form->field($model, 'person_screen_hospcode')->checkboxList(
                    ArrayHelper::map(Cdep::find()
                                    ->orderBy(['dep_code' => SORT_ASC])
                                    ->all(), 'dep_code', 'dep_name'), [
                    #'separator' => '<br>',
                    //'custom' => true, 'inline' => true
            ]);
            ?>
            <hr>
            <?= $form->field($model, 'person_screen_eating2')->checkboxList([1 => 'การต้ม', 2 => 'การตุ๋น', 3 => 'การนึ่ง', 4 => 'การย่างการปิ้ง', 5 => 'การทอด', 6 => 'การผัด']) ?>
            <hr>
            <?= $form->field($model, 'person_screen_eating')->checkboxList([1 => 'หวาน', 2 => 'เปรี้ยว', 3 => 'เค็ม'], ['custom' => true, 'inline' => true,]) ?>
            <hr>
            <?= $form->field($model, 'person_screen_activity_body')->radioList([1 => 'เพียงพอ', 0 => 'ไม่เพียงพอ'], ['custom' => true, 'inline' => true]) ?>
            <hr>
            <?= $form->field($model, 'person_screen_activity_mind')->radioList([1 => 'เพียงพอ', 0 => 'ไม่เพียงพอ'], ['custom' => true, 'inline' => true]) ?>
            <hr>
            <?= $form->field($model, 'person_screen_q1')->radioList([1 => 'มี', 0 => 'ไม่มี'], ['custom' => true, 'inline' => true]) ?>
            <hr>
            <?= $form->field($model, 'person_screen_q2')->radioList([1 => 'มี', 0 => 'ไม่มี'], ['custom' => true, 'inline' => true]) ?>
            <hr>
            <?= $form->field($model, 'person_screen_vcc19')->textInput() ?>
            <?= $form->field($model, 'person_screen_income')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('<i class="fa-solid fa-paper-plane"></i> บันทึกแบบสำรวจ', ['class' => 'btn btn-success btn-lg btn-block']) ?>
                <br>
            </div>
        </div>
        <div class="tab-pane fade show" id="com">
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
