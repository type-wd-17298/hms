<?php

//use kartik\widgets\Select2;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
//use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\widgets\Pjax;
use app\components\Ccomponent;
//use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\editors\Summernote;

/*
  ///-----------------------------------------------------------------------------------------------------
  ขั้นตอนการดำเนินการ
 * 1.เอกสารร่าง -> เสนอ หัวหน้างาน
 * 2.เอกสาร -> เสนอ หัวหน้ากลุ่มงาน
 * ---------------------------------------------------
 * 3.เอกสาร -> เสนอ รองผู้อำนวยการ
 * 4.เอกสาร -> เสนอ ผู้อำนวยการ/นายแพทย์
 */
$defaultPage = '';
Pjax::begin(['id' => 'frm01', 'timeout' => false, 'enablePushState' => false]);
$operate = $model->paperless_status_id;
$urlAcl = Url::to(['acknowledge']);
$src = Url::to(['view', 'id' => $model->paperless_id]);
$url = Url::to(['createprocess']);
$this->params['mqttFuncMessage'] = <<<JS

JS;
$js = <<<JS
$(".ckItems").click(function(e){
        var ck = $('input[name=frmStatus]:checked', '#frm999').val();
        if(ck == 'F03'){
           $('#booking').prop('class', '');
        }else{
           $('#booking').prop('class', 'd-none');
           $('#booking').val(null).trigger('change');
        }
});

    $("#btnAccept").click(function(){
        $.get("{$urlAcl}",{id:$(this).data("id")}, function(data) {
             $("#btnAccept").html(data);
             $.pjax.reload({container:"#mainDocument"});  //Reload GridView
             $("#frmIndex").submit();
        });
    });

     $('#btnFrm99').on('click', function (e) {
        var form = $(this).parents('form');
      Swal.fire({
            icon: 'warning',
            title: 'ยืนยันการบันทึกข้อมูล?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'บันทึกรายการ',
            denyButtonText: 'ยกเลิก',
          }).then((result) => {
            if (result.isConfirmed) {

                form.submit();
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกรายการแล้ว !',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    //$("#modalForm").modal('hide');
            } else if (result.isDenied) {
              Swal.fire({
                    icon: 'info',
                    title: 'ข้อมูลของคุณไม่ได้ถูกบันทึก !',
                    showConfirmButton: false,
                    timer: 1500
                  });
                  $("#modalForm").modal('hide');
            }

          })
     });
     $(".btnReload").click(function(){
           $("#embedContent").html('');
           $("#embedContent").append('<div class="embed-responsive embed-responsive-16by9"><embed class="embed-responsive-items" src="{$src}#view=FitH" type="application/pdf" /></div>');
     });

$("#btnConf2").click(function(e){//ทดสอบ
    e.preventDefault();
    $("#modalForm").modal('hide');
    $("#frmIndex").submit();
});

$("#btnConf").click(function(e){
           e.preventDefault();
           var form = $(this).parents('form');
           Swal.fire({
           icon: 'warning',
            title: 'ยืนยันการเสนอแฟ้มหนังสือ?',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'ดำเนินการ',
            cancelButtonText: 'ยกเลิก',
          }).then((result) => {
            if (result.isConfirmed) {

                let timerInterval
                        Swal.fire({
                          title: 'กรุณารอสักครู่',
                          html: 'ระบบกำลังดำเนินการ',
                          allowOutsideClick: false,
                          //timer: 2000,
                          timerProgressBar: true,
                          didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                            timerInterval = setInterval(() => {
                              //b.textContent = Swal.getTimerLeft()
                            }, 100)
                          },
                          willClose: () => {
                            clearInterval(timerInterval)
                          }
                        }).then((result) => {
                          /* Read more about handling dismissals below */
                          if (result.dismiss === Swal.DismissReason.timer) {
                            console.log('I was closed by the timer')
                          }
                        });

                $.post("{$url}",$.param(form.serializeArray()), function(data) {
                    Swal.fire({
                    icon: 'success',
                    title: '<strong>ผลการดำเนินการ</strong>',
                    showConfirmButton: false,
                    html:data.message,
                    timer: 3000
                  });
                });
                $("#modalForm").modal('hide');
                $("#frmIndex").submit();
            } else if (result.isDenied) {
                //Swal.fire('Changes are not saved', '', 'info')
                $("#modalForm").modal('hide');
            }
          })

       });

JS;
$this->registerJs($js, $this::POS_READY);
?>

<!-- Nav tabs -->
<div class="default-tab">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#home"><i class="la la-keyboard-o me-2"></i> เขียนบันทึกข้อความ <?= (!in_array($model->paperless_status_id, ['F00', 'F01']) ? '<i class="la la-lock text-danger"></i>' : '') ?></a>
        </li>
        <?PHP IF (!$model->isNewRecord) { ?>
            <!--            <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#profile"><i class="la la-television me-2"></i> แสดงตัวอย่าง</a>
                        </li>-->
            <li class="nav-item">
                <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#paper"><i class="la la-chevron-right me-2"></i> ดำเนินการเอกสาร</a>
            </li>
        <?PHP } ?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel">
            <div class="pt-4">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'frm99',
                            'type' => ActiveForm::TYPE_HORIZONTAL,
                            'disabled' => in_array($model->paperless_status_id, ['F00', 'F01']) || $model->isNewRecord ? false : true, //ให้แก้ไขได้เฉพาะเอกสารใหม่ และมีสถานะแก้ไขเท่านั้น //สถานะแก้ไขเอกสาร F00
                            'options' => [
                                'data-pjax' => true,
                                'enctype' => 'multipart/form-data',
                                'class' => 'font-weight-bold',
                            ],
                            //'enableAjaxValidation' => true, //เปิดการใช้งาน AjaxValidation
                            'enableClientValidation' => false,
                                //'enableClientValidation' => false,
                ]);
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <?= $form->field($model, 'paperless_uuid')->hiddenInput()->label(false) ?>
                        <?=
                        $form->field($model, 'paperless_level_id')->radioList(ArrayHelper::map(\app\modules\office\models\PaperlessLevel::find()
                                                ->orderBy(['paperless_level' => SORT_ASC])
                                                ->all(), 'paperless_level_id', 'paperless_level')
                                , ['custom' => true, 'inline' => true])
                        ?>

                        <?PHP
                        echo $form->field($model, 'paperless_from')->dropDownList(
                                ArrayHelper::map(\app\modules\hr\models\EmployeeDep::find()
                                                ->orderBy(['employee_dep_id' => SORT_ASC])
                                                ->all(), 'employee_dep_id', 'employee_dep_label'),
                                [
                                    //'disabled' => $model->isNewRecord ? false : true,
                                    'prompt' => '--เลือกหน่วยงานภายใน--',
                                    'class' => 'form-control form-control-lg'
                                ]
                        );
                        ?>
                        <?= $form->field($model, 'paperless_to')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
                        <?= $form->field($model, 'paperless_topic')->textInput(['maxlength' => true, 'class' => 'form-control form-control-lg']) ?>
                        <?PHP echo $form->field($model, 'paperless_detail')->textarea(['rows' => 10, 'class' => 'form-control form-control-lg']) ?>
                        <?PHP
                        /*
                          $form->field($model, 'paperless_detail')->widget(Summernote::class, [
                          'useKrajeePresets' => true,
                          // other widget settings
                          ]);
                         *
                         */
                        ?>

                        <?PHP
//                        echo $form->field($model, 'paperless_detail')->widget(Summernote::class, [
//                                //'useKrajeePresets' => true,
//                                // other widget settings
//                        ]);
                        ?>
                        <?php
                        echo $form->field($model, 'paperless_command_id')->dropDownList(
                                ArrayHelper::map(\app\modules\office\models\PaperlessCommand::find()
                                                ->orderBy(['paperless_command_id' => SORT_ASC])
                                                ->all(), 'paperless_command_id', 'paperless_command_label'),
                                [
                                    //'disabled' => $model->isNewRecord ? false : true,
                                    'prompt' => '--เลือก--',
                                    'class' => 'form-control form-control-lg'
                                ]
                        );
                        ?>
                        <?php
                        /*
                          echo $form->field($model, 'employee_owner_id')->dropDownList(
                          ArrayHelper::map(\app\modules\hr\models\Employee::find()
                          ->where(['employee_dep_id' => Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id])
                          ->orderBy(['employee_id' => SORT_ASC])
                          ->all(), 'employee_id', 'employee_fullname'),
                          [
                          //'disabled' => $model->isNewRecord ? false : true,
                          'prompt' => '--เลือก--',
                          'class' => 'form-control form-control-lg'
                          ]
                          );
                         *
                         */
                        ?>
                        <?php
                        echo $form->field($model, 'employee_owner_id')->widget(Select2::classname(), [
                            //'name' => 'paperless_view_deps',
                            'data' => ArrayHelper::map(\app\modules\hr\models\Employee::find()->where(['employee_status' => 1])->orderBy(['employee_id' => SORT_ASC])->all(), 'employee_id', 'employee_fullname'),
                            'id' => 'deps',
                            'options' => [
                                'placeholder' => '--เลือก--',
                                'multiple' => false,
                                'class' => 'form-control form-control-lg',
                            ],
                            'theme' => Select2::THEME_KRAJEE_BS5,
                            'pluginOptions' => [
                                //'dropdownParent' => '#modalForm',
                                // 'allowClear' => true,
                                'minimumInputLength' => 0,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => Url::to(['/office/paperless/emplist', 'mode' => 'D']),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                ],
                                //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                                'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-md-4">
                        <div class="">เอกสารแนบเพิ่มเติม</div>
                        <?PHP
                        echo FileInput::widget([
                            'name' => 'upload_ajax[]',
                            'options' => ['multiple' => true, 'accept' => 'pdf'], //'accept' => 'image/*' หากต้องเฉพาะ image
                            'pluginOptions' => [
                                'overwriteInitial' => false,
                                'initialPreviewShowDelete' => true,
                                'initialPreviewAsData' => true,
                                'initialPreview' => $initialPreview,
                                'initialPreviewConfig' => $initialPreviewConfig,
                                'uploadUrl' => Url::to(['upload-ajax']),
                                'uploadExtraData' => [
                                    'ref' => @$model->paperless_id,
                                ],
                                'maxFileCount' => 10
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-md-12 ">
                        <div class="row justify-content-between mt-3 mb-5">
                            <div class="col-6">
                                <?=
                                Html::button('<i class="la la-save la-lg"></i> บันทึกร่างเอกสาร', [
                                    'class' => 'btn btn-primary btn-lg font-weight-bold',
                                    'id' => 'btnFrm99'
                                ])
                                ?>
                            </div>
                            <div class="col-6 text-right">
                                <?= Html::a('<i class="la la-angle-left la-lg"></i> กลับหน้าจัดการ', 'javascript:;', ['class' => 'btn btn-dark  btn-lg font-weight-bold', 'data-bs-dismiss' => 'modal']) ?>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="profile">
            <!--            <div class="pt-2">
                            <div class="btn btn-block btn-dark btnReload mb-2" >แสดงตัวอย่าง</div>
                            <div id="embedContent">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <embed class="embed-responsive-items" src="<?= yii\helpers\Url::to(['view', 'id' => $model->paperless_id]) ?>#view=FitH" type="application/pdf" />
                                </div>
                            </div>
                        </div>-->
        </div>
        <div class="tab-pane fade" id="paper">
            <?PHP IF (!$model->isNewRecord) { ?>
                <form id="frm999">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="pt-2">
                                <div class="btn btn-block btn-dark btnReload mb-2" >แสดงตัวอย่าง</div>
                                <div id="embedContent">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['view', 'id' => @$model->paperless_id]) ?>#view=FitH" type="application/pdf" /></iframe>
                                    </div>
                                </div>
                                <!--                                <div>
                                                                    <div class="embed-responsive embed-responsive-1by1">
                                                                        <iframe  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['view', 'id' => $model->paperless_id]) ?>#view=FitH" type="application/pdf" /></iframe>
                                                                    </div>
                                                                </div>-->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card1">
                                <div class="pt-2">
                                    <div class="h4 font-weight-bold">การดำเนินการ</div>
                                    <hr>
                                    <div class="mb-3 row">
                                        <div class="col-sm-12">
                                            <div class="alert alert-primary alert-alt d-none">
                                                <strong>ความมุ่งหมาย!</strong>  <?= @$model->command->paperless_command_label; ?>
                                            </div>
                                            <div class="alert alert-danger alert-dismissible fade show">
                                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                                <strong>คำแนะนำ!</strong> ในขึ้นตอนนี้ผู้ร่างเอกสารจะต้องเสนอผ่านไปยังหัวหน้าของแต่ละหน่วยงานเพื่อตรวจสอบความถูกต้อง และปฏิบัติตามระเบียนของงานสารบรรณ
                                                <hr>
                                                <strong>การออกเลขหนังสือ!</strong> ระบบจะออกเลขหนังสือหลังจากที่มีการเสนอหนังสือแล้วเท่านั้น

                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="col-sm-12 col-form-label  font-weight-bold">เสนอหนังสือถึงงานเลขา (กรณีส่งตัวจริงเพื่อลงนาม)</div>
                                        <div class="col-sm-12">
                                            <div class="form-check ckItems">
                                                <input class="form-check-input" type="radio" name="frmStatus" value="F02" id="F02">
                                                <label class="form-check-label  " for="F02">
                                                    เสนอหนังสือผ่านงานเลขา
                                                </label>
                                                <hr>
                                            </div>
                                            <div class="form-check ckItems">
                                                <input class="form-check-input" type="radio" name="frmStatus" value="F14" id="F14">
                                                <label class="form-check-label  " for="F14">
                                                    เสนอหนังสือผ่านงานการเงิน(Finance)
                                                </label>
                                                <hr>
                                            </div>
                                            <div class="form-check ckItems">
                                                <input class="form-check-input" type="radio" name="frmStatus" value="F13" id="F13">
                                                <label class="form-check-label  " for="F13">
                                                    เสนอหนังสือผ่านงานบุคลากร(HR)
                                                </label>
                                                <hr>
                                            </div>
                                            <div class="form-check ckItems">
                                                <input class="form-check-input" type="radio" name="frmStatus" value="F12" id="F12">
                                                <label class="form-check-label  " for="F12">
                                                    เสนอหนังสือผ่านงานพัสดุ
                                                </label>
                                                <hr>
                                            </div>
                                            <div class="form-check ckItems">
                                                <input class="form-check-input" type="radio" name="frmStatus" value="F11" id="F11">
                                                <label class="form-check-label  " for="F11">
                                                    เสนอหนังสือผ่านงานบัญชี
                                                </label>
                                                <hr>
                                            </div>
                                            <div class="form-check ckItems">
                                                <input class="form-check-input" type="radio" name="frmStatus" value="F03" id="F03">
                                                <label class="form-check-label  " for="F03">
                                                    เสนอหนังสือทั่วไป
                                                </label>
                                                <hr>
                                            </div>
                                        </div>
                                        <div id="booking" class="d-none">
                                            <div class="col-sm-12 col-form-label font-weight-bold">เสนอหนังสือถึง</div>
                                            <div class="col-sm-12">
                                                <?php
                                                $formatJs1 = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
    }
var ArrText = '<div class="row small">';
for (var textExcutive of repo.excutive) {
  ArrText += '<div class="col-12 font-weight-bold"><i class="fa-regular fa-circle-user"></i> ' + textExcutive.executive +' ('+textExcutive.dep+')</div>' ;
}

ArrText += '</div>';
var textPosition = '';
if(repo.position != null){
    textPosition =  '<small style="margin-left:3px">' + repo.position + '</small>';
}

var markup =
'<div class="row small">' +
    '<div class="col-12">' +
        '<b style="margin-left:3px">' + repo.text + '</b>' +
    '</div>' +
     '<div class="col-12">' +
        textPosition +
         '<small style="margin-left:3px">(' + repo.dep + ')</small>' +
    '</div>' +
    ArrText +
'</div>';
    if (repo.description) {
      markup += '<p>' + repo.description + '</p>';
    }
    return '<div style="overflow:hidden;">' + markup + '</div>';
};
var formatRepoSelection = function (repo) {
    return repo.full_name || repo.text;
}
JS;
                                                $formatJs = <<< JS
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
    }
var ArrText = '<div class="row small  ml-0">';
for (var textExcutive of repo.excutive) {
  ArrText += '<div class="col-12 small">- ' + textExcutive.executive +' ('+textExcutive.dep+')</div>' ;
}

ArrText += '</div>';
var textPosition = '';
if(repo.position != null){
    textPosition =  '<small style="margin-left:3px">' + repo.position + '</small>';
}

var markup =
'<div class="row small">' +
    '<div class="col-12">' +
        '<b class="h4">' + repo.text + '</b>' +
    '</div>' +
     '<div class="col-12 ml-2">' +
        textPosition + '<small class="margin-left:3px">(' + repo.dep + ')</small>' +
    '</div>' +
    ArrText +
'</div>';
    if (repo.description) {
      markup += '<p>' + repo.description + '</p>';
    }
    return '<div style="overflow:hidden;">' + markup + '</div>';
};
var formatRepoSelection = function (repo) {
    return repo.full_name || repo.text;
}
JS;
// Register the formatting script
                                                $this->registerJs($formatJs, $this::POS_HEAD);
                                                echo Select2::widget([
                                                    'name' => 'receiver',
                                                    'options' => [
                                                        'placeholder' => 'เลือกหัวหน้า..',
                                                        'multiple' => false,
                                                    //'class' => 'form-control form-control-lg',
                                                    ],
                                                    'theme' => Select2::THEME_KRAJEE_BS4,
                                                    'pluginOptions' => [
                                                        'dropdownParent' => '#modalContents',
                                                        'allowClear' => true,
                                                        'minimumInputLength' => 0,
                                                        'language' => [
                                                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                                        ],
                                                        'ajax' => [
                                                            'url' => Url::to(['paperless/emplist']),
                                                            'cache' => true,
                                                            'dataType' => 'json',
                                                            'data' => new JsExpression('function(params) {return {q:params.term,mode:"D",dep:"' . $model->paperless_from . '"}; }')
                                                        ],
                                                        'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                                                        'templateResult' => new JsExpression('formatRepo'),
                                                        'templateSelection' => new JsExpression('formatRepoSelection'),
                                                    ],
                                                ]);
                                                ?>
                                            </div>
                                            <div class="row">
                                                <label class="col-form-label col-sm-12 pt-0 font-weight-bold">หมายเหตุ/ความเห็น</label>
                                                <div class="col-sm-12">
                                                    <textarea name="comment"  rows="5" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 mb-3 row">
                                        <div class="col-sm-12">
                                            <input type="hidden" name="id" value="<?= $model->paperless_id ?>">
                                            <button type="button" class="btn btn-primary btn-block font-weight-bold btn-lg" id="btnConf"><i class="fa-regular fa-clone fa-1x"></i> เสนอหนังสือ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?PHP } ?>
        </div>
    </div>
</div>

<?php Pjax::end() ?>
