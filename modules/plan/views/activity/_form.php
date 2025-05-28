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
use kartik\widgets\FileInput;
?>
<div class="card border-secondarys">
    <div class="card-header">
        <b>กรอกข้อมูลกิจกรรมสุขภาพ</b>
    </div>
    <div class="card-block m-2">
        <div class="dep-activity-form">

            <?php
            $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data'],
            ]);
            ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'dep_activity_title')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'dep_activity_summary')->textarea(['rows' => 6]) ?>
                    <?= $form->field($model, 'dep_activity_purpose')->textarea() ?>
                    <?=
                    $form->field($model, 'dep_activity_date')->widget(DatePicker::classname(), [
                        //'options' => ['placeholder' => 'Enter birth date ...'],
                        'language' => 'th',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                    ]);
                    ?>

                </div>
                <div class="col-md-6">
                    <?php
                    /*
                      echo $form->field($model, 'photo_upload')->widget(FileInput::classname(), [
                      'options' => ['multiple' => false, 'accept' => 'pdf/*'],
                      'pluginOptions' => [
                      'initialPreview' => [
                      //!empty($model->docs_filename) || !$model->isNewRecord ? Url::toRoute(['viewdoc', 'id' => $model->docs_id], true) : '',
                      ],
                      'overwriteInitial' => false,
                      'allowedFileExtensions' => ['pdf'],
                      'showRemove' => false,
                      'showUpload' => false,
                      ],
                      ]);
                      //echo ini_get('upload_max_filesize');
                     *
                     */
                    ?>
                    <div class="">แนบรูปภาพกิจกรรม</div>
                    <?=
                    FileInput::widget([
                        'name' => 'upload_ajax[]',
                        'options' => ['multiple' => false, 'accept' => 'image/*'], //'accept' => 'image/*' หากต้องเฉพาะ image
                        'pluginOptions' => [
                            'overwriteInitial' => false,
                            'initialPreviewShowDelete' => true,
                            'initialPreview' => $initialPreview,
                            'initialPreviewConfig' => $initialPreviewConfig,
                            #'uploadUrl' => Url::to(['upload-ajax']),
                            'uploadExtraData' => [
                                'ref' => @$model->dep_activity_id,
                            ],
                            'maxFileCount' => 100
                        ]
                    ]);
                    ?>
                    <hr>

                    <?= $form->field($model, 'dep_activity_videoclip')->textInput(['maxlength' => true]) ?>
                    <div class="text-danger">ตัวอย่างการนำคลิปจาก youtube มีแนบในโปรแกรม</div>
                    <?= yii\helpers\Html::img('@web/img/youtube.png', ['class' => 'img-thumbnail']) ?>
                </div>
            </div>
            <hr>
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
    </div>
</div>