<?php

use yii\bootstrap4\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
//use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\widgets\Pjax;

//use Itstructure\CKEditor\CKEditor;

Pjax::begin(['id' => 'frm01', 'timeout' => false, 'enablePushState' => false]);
$src = Url::to(['view', 'id' => $model->memorandum_id]);
$js = <<<JS
     $(".btnReload").click(function(){
           $("#embedContent").html('');
           $("#embedContent").append('<div class="embed-responsive embed-responsive-4by3"><embed class="embed-responsive-items" src="{$src}" type="application/pdf" /></div>');
     });
JS;
$this->registerJs($js, $this::POS_READY);
$this->registerJsFile('@web/../themes/custom/assets/xhtml/vendor/ckeditor/ckeditor.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<!-- Nav tabs -->
<div class="default-tab">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#home"><i class="la la-keyboard-o me-2"></i> เขียนบันทึกข้อความ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#profile"><i class="la la-television me-2"></i> แสดงตัวอย่าง</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#paper"><i class="la la-comments me-2"></i> เอกสาร</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#contact"><i class="la la-bookmark me-2"></i> สถานะหนังสือ <span class="badge badge-primary badge-sm">99</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#message"><i class="la la-envelope me-2"></i> Message <span class="badge badge-danger badge-sm">99</span></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show1" id="home" role="tabpanel">
            <div class="pt-4">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'frm99',
                            'type' => ActiveForm::TYPE_HORIZONTAL,
                            'options' => [
                                'data-pjax' => true,
                                'enctype' => 'multipart/form-data'
                            ],
                                //'enableClientValidation' => false,
                ]);
//print_r($form->errorSummary($model));
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <?=
                        $form->field($model, 'memorandum_level')->radioList(ArrayHelper::map(\app\modules\office\models\PaperlessLevel::find()
                                                ->orderBy(['paperless_id' => SORT_ASC])
                                                ->all(), 'paperless_id', 'paperless_level')
                                , ['custom' => true, 'inline' => true])
                        ?>
                        <?php
                        echo $form->field($model, 'memorandum_from')->dropDownList(
                                ArrayHelper::map(\app\modules\office\models\Cdepartment::find()
                                                ->orderBy(['department_id' => SORT_ASC])
                                                ->all(), 'department_id', 'department_name'),
                                [
                                    //'disabled' => $model->isNewRecord ? false : true,
                                    'prompt' => '--เลือกหน่วยงานภายใน--',
                                    'class' => 'form-control form-control-lg'
                                ]
                        );
                        ?>
                        <?= $form->field($model, 'memorandum_topic')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
                        <?= $form->field($model, 'memorandum_detail')->textarea(['rows' => 20, 'class' => 'form-control form-control-lg']) ?>

                        <?PHP
                        /*
                          $form->field($model, 'memorandum_detail')
                          ->widget(
                          CKEditor::className(),
                          [
                          'preset' => 'basic',
                          'clientOptions' => [
                          'toolbarGroups' => [
                          [
                          'name' => 'undo'
                          ],
                          [
                          'name' => 'basicstyles',
                          'groups' => ['basicstyles', 'cleanup']
                          ],
                          [
                          'name' => 'colors'
                          ],
                          [
                          'name' => 'others',
                          'groups' => ['others', 'about']
                          ],
                          ],
                          //                                                'filebrowserBrowseUrl' => '/ckfinder/ckfinder.html',
                          //                                                'filebrowserImageBrowseUrl' => '/ckfinder/ckfinder.html?type=Images',
                          //                                                'filebrowserUploadUrl' => '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                          //                                                'filebrowserImageUploadUrl' => '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                          //                                                'filebrowserWindowWidth' => '1000',
                          //                                                'filebrowserWindowHeight' => '700',
                          //                                                'allowedContent' => true,
                          'language' => 'th',
                          ]
                          ]
                          );
                         *
                         */
                        ?>

                        <?PHP
                        /*
                          $form->field($model, 'memorandum_date')->widget(DatePicker::classname(), [
                          //'options' => ['placeholder' => 'Enter birth date ...'],
                          'language' => 'th',
                          'type' => DatePicker::TYPE_COMPONENT_APPEND,
                          'pluginOptions' => [
                          'autoclose' => true,
                          'format' => 'yyyy-mm-dd'
                          ]
                          ]);
                         *
                         */
                        ?>

                    </div>
                    <div class="col-md-4">
                        <div class="">เอกสารแนบเพิ่มเติม</div>
                        <?PHP
                        echo FileInput::widget([
                            'name' => 'upload_ajax[]',
                            'options' => ['multiple' => true, 'accept' => '*'], //'accept' => 'image/*' หากต้องเฉพาะ image
                            'pluginOptions' => [
                                'overwriteInitial' => false,
                                'initialPreviewShowDelete' => true,
                                'initialPreviewAsData' => true,
                                'initialPreview' => $initialPreview,
                                'initialPreviewConfig' => $initialPreviewConfig,
                                'uploadUrl' => Url::to(['upload-ajax']),
                                'uploadExtraData' => [
                                    'ref' => @$model->memorandum_id,
                                ],
                                'maxFileCount' => 10
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-md-12 ">
                        <div class="row justify-content-between mt-3 mb-5">
                            <div class="col-6">
                                <?= Html::submitButton('<i class="la la-save la-lg"></i> บันทึกร่างเอกสาร', ['class' => 'btn btn-primary font-weight-bold']) ?>
                            </div>
                            <div class="col-6 text-right">
                                <?= Html::a('<i class="la la-angle-left la-lg"></i> กลับหน้าจัดการ', ['index'], ['class' => 'btn btn-dark font-weight-bold']) ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="profile">
            <div class="pt-2">
                <div class="btn btn-block btn-dark btnReload mb-2" >แสดงตัวอย่าง</div>
                <div id="embedContent">
                    <div class="embed-responsive embed-responsive-4by3">
                        <embed class="embed-responsive-items" src="<?= yii\helpers\Url::to(['view', 'id' => $model->memorandum_id]) ?>#view=FitH" type="application/pdf" />
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show active" id="paper">
            <div class="row">
                <div class="col-md-8">
                    <div class="pt-2">
                        <div>
                            <div class="embed-responsive embed-responsive-16by9">
                                <embed  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['view', 'id' => $model->memorandum_id]) ?>#view=FitH" type="application/pdf" />
                            </div>
                        </div>
                        <div>
                            <?PHP
                            $pdfs = $model->getUrlPdf($model->memorandum_id);
                            if (is_array($pdfs)) {
                                foreach ($pdfs as $no => $file) {
                                    ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <embed id="pdf<?= $no ?>"  class="embed-responsive-items" src="<?= $file ?>#view=FitH" type="application/pdf" />
                                    </div>
                                    <?PHP
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="card1">
                        <div class="pt-2">
                            <div class="h4 font-weight-bold">การดำเนินการ</div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label font-weight-bold">ความมุ่งหมาย</label>
                                <div class="col-sm-8">
                                    <?php
                                    echo $form->field($model, 'memorandum_from', [
                                        'template' => '<div class=\"\">{input}</div><div class=\"\">{error}</div>'
                                    ])->dropDownList(
                                            ArrayHelper::map(\app\modules\office\models\PaperlessCommand::find()
                                                            ->orderBy(['paperless_command_id' => SORT_ASC])
                                                            ->all(), 'paperless_command_id', 'paperless_command_label'),
                                            [
                                                //'disabled' => $model->isNewRecord ? false : true,
                                                'prompt' => '--เลือก--',
                                                'class' => 'form-control form-control-lg'
                                            ]
                                    )->label(false);
                                    ?>
                                </div>
                            </div>

                            <fieldset class="mb-3 d-none">
                                <div class="row">
                                    <label class="col-form-label col-sm-4 pt-0 font-weight-bold">คำอธิบาย</label>
                                    <div class="col-sm-8">
                                        <textarea  rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="mb-3">
                                <div class="row">
                                    <label class="col-form-label col-sm-4 pt-0 font-weight-bold">ปฏิบัติ</label>
                                    <div class="col-sm-8">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="option1" checked="">
                                            <label class="form-check-label">
                                                ทราบ
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="option2">
                                            <label class="form-check-label">
                                                อนุมัติ
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="option2">
                                            <label class="form-check-label">
                                                อนุมัติหลักการ
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="option2">
                                            <label class="form-check-label">
                                                อนุมัติดําเนินการตามเสนอ
                                            </label>
                                        </div>
                                        <div class="form-check disabled">
                                            <input class="form-check-input" type="radio" name="gridRadios" value="option3" disabled="">
                                            <label class="form-check-label">
                                                เห็นชอบ
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="mb-3 row">
                                <div class="col-sm-4 font-weight-bold">ส่งคืน</div>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gridRadios">
                                        <label class="form-check-label">
                                            แก้ไข
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gridRadios">
                                        <label class="form-check-label">
                                            อื่นๆ(ตามหมายเหตุ)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <fieldset class="mb-3">
                                <div class="row">
                                    <label class="col-form-label col-sm-4 pt-0 font-weight-bold">ความเห็น</label>
                                    <div class="col-sm-8">
                                        <?=
                                        $form->field($model, 'memorandum_detail', [
                                            'template' => '<div class=\"\">{input}</div><div class=\"\">{error}</div>'
                                        ])->textarea(['rows' => 5, 'class' => 'form-control form-control-lg'])->label(false);
                                        ?>

                                    </div>
                                </div>
                            </fieldset>
                            <div class="mb-3 row">
                                <div class="col-sm-4 font-weight-bold">มอบให้(เจ้าหน้าที่)</div>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gridRadios">
                                        <?php
                                        echo $form->field($model, 'memorandum_from', [
                                            'template' => '<div class=\"\">{input}</div><div class=\"\">{error}</div>'
                                        ])->dropDownList(
                                                ArrayHelper::map(\app\modules\office\models\PaperlessCommand::find()
                                                                ->orderBy(['paperless_command_id' => SORT_ASC])
                                                                ->all(), 'paperless_command_id', 'paperless_command_label'),
                                                [
                                                    //'disabled' => $model->isNewRecord ? false : true,
                                                    'prompt' => '--เลือก--',
                                                    'class' => 'form-control form-control-lg'
                                                ]
                                        )->label(false);
                                        ?>
                                        <label class="form-check-label">
                                            ดําเนินการ
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-4 font-weight-bold">มอบให้(หน่วยงาน)</div>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gridRadios">
                                        <?php
                                        echo $form->field($model, 'memorandum_from', [
                                            'template' => '<div class=\"\">{input}</div><div class=\"\">{error}</div>'
                                        ])->dropDownList(
                                                ArrayHelper::map(\app\modules\office\models\Cdepartment::find()
                                                                ->orderBy(['department_id' => SORT_ASC])
                                                                ->all(), 'department_id', 'department_name'),
                                                [
                                                    //'disabled' => $model->isNewRecord ? false : true,
                                                    'prompt' => '--เลือก--',
                                                    'class' => 'form-control form-control-lg'
                                                ]
                                        )->label(false);
                                        ?>
                                        <label class="form-check-label">
                                            ดําเนินการ
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <fieldset class="mb-3">
                                <div class="row">
                                    <label class="col-form-label col-sm-4 pt-0 font-weight-bold">หมายเหตุ</label>
                                    <div class="col-sm-8">
                                        <textarea  rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="mb-3 row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">ดําเนินการ</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Pjax::end() ?>
