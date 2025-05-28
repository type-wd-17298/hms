<?php

use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Html;
//use kartik\form\ActiveForm;
//use yii\helpers\ArrayHelper;
use yii\helpers\Url;
//use kartik\date\DatePicker;
//use kartik\widgets\FileInput;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use kartik\grid\GridView;

if (isset($model->processlist_id))
    $modelProcess->processlist_id = 0;

//ข้อมูลผู้รับ
$headHtml = '';
if (!empty($modelProcess->process_receiver)) {
    $head = @$modelProcess->receiver->getHead();

    foreach ($head as $value) {
        $headHtml .= @Html::tag('div', $value['executive'], ['class' => 'small']);
    }
    $headHtml = @Html::tag('b', '(' . $modelProcess->receiver->employee_fullname . ')', ['class' => '']) . ' ' . @$headHtml;
} else {

}
$processlist_id = @$modelProcess->processlist_id;

$defaultPage = '';
$operate = @$model->paperless_status_id;
$urlAcl = Url::to(['acknowledge']);
$urlOperate = Url::to(['operate']);
$src = Url::to(['view', 'id' => @$model->paperless_id]);
$url = Url::to(['createprocess']);
$urlList = Url::to(['paperless/emplist2']);
$urlUpdate = Url::to(['update']);
$js = <<<JS
/*
function offerText(d){
        console.log(d.text);
        $('#comm').append(d.text);
   }
*/
function listExecutive(mode){
        $.get("{$urlList}",{mode:mode}, function(data) {
                //console.log(data['results']);
                var ArrText = '<div class="">';
                for (var item of data['results']) {
                     ArrText += '<div class="form-check">';
                     ArrText += '<input class="form-check-input" type="radio" name="receiver" value="'+item.id+'" id="'+item.id+'">';
                     ArrText += '<label class="form-check-label"  for="'+item.id+'">';
                     ArrText +=  item.text;
                     ArrText += '</label>';
                     ArrText += '</div>';
                }
                ArrText += '</div>';
                ArrText += '<hr>';
                $('#receiverDiv').html(ArrText);
        });
}

$(".ckItems").click(function(e){
        var ck = $('input[name=frmStatus]:checked', '#frm999').val();
        if(ck == 'F03' || ck == 'F18' || ck == 'F19'){
           $('#receiverID').prop('disabled', false);
           $('#receiverDiv2').prop('class', '');

         if(ck == 'F18' || ck == 'F19'){
            $('#receiverDiv2').prop('class', 'd-none');
            if(ck == 'F18')
                listExecutive(2);
            if(ck == 'F19')
                listExecutive(1);
            $('#receiverID').prop('disabled', true);
        }else{
             $('#receiverDiv').html('');
        }

        }else{
           $('#receiverDiv2').prop('class', '');
           $('#receiverID').prop('disabled', true);
           $('#receiverID').val(null).trigger('change');
           //$('#rcp').prop('class','d-none');
           $('#receiverDiv').html('');
        }
});
   $("#btnAcknowledge").click(function(e){
        //Reload page
              $.get("{$urlOperate}",{id:'{$model->paperless_id}',ac:'{$processlist_id}'}, function(data) {
                  $("#modalContents").html(data);
              });
              //$.pjax.reload({container: '#pjPaperGrid', async: false});
              Swal.fire({
                    icon: 'success',
                    title: 'คุณได้รับทราบตามเอกสารที่เสนอมาแล้ว !',
                    showConfirmButton: false,
                    timer: 1500
                  });
                if('{$modelProcess->paperless_status_id}' == 'F00'){
                    $("#modalContents").html('');
                    $('#modalForm').modal('show');
                    event.preventDefault();
                    $.get("{$urlUpdate}",{id:'{$model->paperless_id}'}, function(data) {
                        $("#modalContents").html(data);
                    });
                }
     });

    $("#btnAccept").click(function(){
        $.get("{$urlAcl}",{id:$(this).data("id")}, function(data) {
             $("#btnAccept").html(data);
             $("#frmIndex").submit()
        });
    });

     $(".btnReload").click(function(){
           $("#embedContent").html('');
           $("#embedContent").append('<div class="embed-responsive embed-responsive-16by9"><embed class="embed-responsive-items" src="{$src}#view=FitH" type="application/pdf" /></div>');
     });
     $("#btnConf").click(function(e){
           e.preventDefault();
           var form = $(this).parents('form');
           Swal.fire({
            icon: 'warning',
            title: 'ยืนยันการเสนอแฟ้มหนังสือ?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'ดำเนินการ',
            denyButtonText: 'ยกเลิก',
          }).then((result) => {
            if (result.isConfirmed) {

           let timerInterval
                        Swal.fire({
                          title: 'กรุณารอสักครู่',
                          html: 'ระบบกำลังดำเนินการ',
                          allowOutsideClick: false,
                          timer: 2000,
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
                    if(data.status == 'success'){
                            Swal.fire({
                                icon: 'success',
                                html:data.message,
                                title: '<strong>ผลการดำเนินการ</strong>',
                                showConfirmButton: false,
                                timer: 3000
                          });
                        //$.pjax.reload({container: '#pjPaperMonitor', async: false});
                        //$.pjax.reload({container: '#pjPaperGrid', async: false});
                        $("#gview01").yiiGridView('applyFilter');
                        $("#modalForm").modal('hide');
                        }else{
                        Swal.fire({
                                icon: 'error',
                                html:data.message,
                                title: '<strong>ผลการดำเนินการ</strong>',
                                showConfirmButton: false,
                                timer: 3000
                          });
                        }
                });

            } else if (result.isDenied) {
              //Swal.fire('Changes are not saved', '', 'info',  timer: 2000)
            }
          })
       });
JS;
$this->registerJs($js, $this::POS_READY);
Pjax::begin(['id' => 'frm01', 'timeout' => false, 'enablePushState' => false]);
?>
<!-- Nav tabs -->
<?PHP if (!empty($model->paperless_direct) && $model->paperless_direct > 0) { ?>
    <div class="alert alert-danger left-icon-big alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"><span><i class="mdi mdi-btn-close"></i></span>
        </button>
        <div class="media">
            <div class="alert-left-icon-big">
                <span><i class="fa-solid fa-qrcode fa-2x"></i></span>
            </div>
            <div class="media-body">
                <h4 class="mt-1 mb-2">เอกสารฉบับนี้ใช้งานเพื่อติดตามผ่านระบบเท่านั้น</h4>
                <p class="mb-0"></p>
            </div>
        </div>
    </div>
<?PHP } ?>
<div class="default-tab">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#home"><i class="fa fa-file-pdf  me-2"></i> เอกสาร</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#status"><i class="fa fa-tag me-2"></i> สถานะ <span class="badge badge-primary badge-sm"><?= $dataProvider->getTotalCount() > 0 ? $dataProvider->getTotalCount() : '-' ?></span></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel">
            <form id="frm999">
                <div class="row">

                    <?PHP if ($canVisible) { ?>
                        <div class="col-md-5">
                            <div class="card1">
                                <div class="pt-2">
                                    <div class="h4 font-weight-bold d-none">การดำเนินการ</div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <!--                                        <div class="alert alert-primary alert-alt">
                                                                                        <strong>ความมุ่งหมาย!</strong>  <?= @$model->command->paperless_command_label; ?>
                                                                                    </div>
                                            -->

                                            <div class="alert alert-social facebook alert alert-<?= @$modelProcess->status->paperless_status_color ?>">
                                                <strong>เจ้าของเรื่อง : </strong>
                                                <?= @(!empty($model->owner->employee_fullname) ? $model->owner->employee_fullname : $model->emp->employee_fullname); ?>
                                                <br><b>สถานะหนังสือ</b><br><?= @$modelProcess->status->paperless_status ?>
                                                <?= @$headHtml ?>
                                                <hr>
                                                หนังสือเสนอมาเมื่อ (<?= Ccomponent::getThaiDate(@$modelProcess->process_create, 'S', 1) ?>)
                                                <p class="small">
                                                    <b>ความเห็น</b> (<?= @$modelProcess->emp->employee_fullname ?>)
                                                    <br>
                                                    "<?= @$modelProcess->process_comment; ?>"
                                                </p>
                                            </div>
                                            <?PHP if (!empty($modelProcess->process_acknowledge_datetime)) { ?>
                                                                                                                                                                                                                <!--                                                <button type="button" class="btn btn-primary btn-block font-weight-bold btn-lg" id="btnAcknowledge"><i class="fa-solid fa-paper-plane"></i> รับทราบ <?= (!empty($modelProcess->process_acknowledge_datetime) ? 'เมื่อ ' . Ccomponent::getThaiDate($modelProcess->process_acknowledge_datetime, 'S', 1) : '' ) ?></button>-->
                                            <?PHP } else { ?>
                                                <button type="button" class="btn btn-primary btn-block font-weight-bold btn-lg" id="btnAcknowledge"><i class="fa-solid fa-paper-plane"></i> รับทราบ</button>
                                            <?PHP } ?>
                                        </div>
                                    </div>
                                    <?PHP if (@!in_array($modelProcess->status->paperless_status_code, ['FF'])) { ?>
                                        <?PHP if ($operate <> 1 && @$modelProcess->process_acknowledge == 1) {
                                            ?>
                                            <div class="alert alert-light alert-alt solid">
                                                <div class="mb-3 row">
                                                    <div class="col-sm-6 font-weight-bold">ดำเนินการ/ส่งคืน</div>
                                                    <div class="col-sm-6">
                                                        <?PHP
                                                        foreach ($status as $row) {
                                                            if ($header == 1) {
                                                                if ($row->paperless_status_id == 'F15')//สำหรับหัวหน้ากลุ่มงาน
                                                                    $row->paperless_status = 'เสนอเพื่อดำเนินการต่อ';
                                                            } else {
                                                                if ($model->paperless_direct > 0 && $row->paperless_status_id == 'F03')
                                                                    continue; //ข้ามรายการที่เป็นส่งตรง
                                                                if ($row->paperless_status_id == 'F03')
                                                                    $row->paperless_status = 'เสนอเพื่อดำเนินการต่อ';
                                                            }
                                                            ?>
                                                            <div class="form-check ckItems">
                                                                <input class="form-check-input" type="radio" name="frmStatus" value="<?= $row->paperless_status_id ?>" id="<?= $row->paperless_status_id ?>">
                                                                <label class="form-check-label  <?= ($row->paperless_status_id == 'FF' ? 'font-weight-bold text-primary' : '') ?>" for="<?= $row->paperless_status_id ?>">
                                                                    <?= $row->paperless_status ?>
                                                                </label>
                                                            </div>
                                                        <?PHP } ?>

                                                    </div>
                                                </div>

                                                <div class="mb-3 row" >
                                                    <div class="col-sm-12 col-form-label  font-weight-bold">เสนอหนังสือถึง</div>
                                                    <div class="col-sm-12 ml-4" id="receiverDiv"></div>
                                                    <div class="col-sm-12" id="receiverDiv2">
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
                                                        $formatJs = <<< 'JS'
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
        '<b class="text-primary h4">' + repo.text + '</b>' +
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
                                                            'id' => 'receiverID',
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
                                                                    'url' => new JsExpression('function(){
                                                                            var ck = $("input[name=frmStatus]:checked", "#frm999").val();
                                                                            if(ck == "F18" || ck == "F19"){
                                                                                return "' . Url::to(['paperless/emplist2']) . '";
                                                                            }else{

                                                                                return "' . Url::to(['paperless/emplist']) . '";
                                                                            }
                                                                            }'),
                                                                    'cache' => true,
                                                                    'dataType' => 'json',
                                                                    //'data' => new JsExpression('function(params) {return {q:params.term,mode:"D"}; }')
                                                                    'data' => new JsExpression('function(params) {return {q:params.term,mode:"D",dep:"' . $modelProcess->paperless->paperless_from . '"}; }')
                                                                ],
                                                                'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                                                                'templateResult' => new JsExpression('formatRepo'),
                                                                'templateSelection' => new JsExpression('formatRepoSelection'),
                                                            ],
                                                        ]);
                                                        ?>
                                                    </div>
                                                </div>

                                                <fieldset class="mb-3">
                                                    <div class="row">
                                                        <label class="col-form-label col-sm-12 pt-0 font-weight-bold">หมายเหตุ/ความเห็น</label>
                                                        <div class="col-sm-12">
                                                            <textarea name="comment" id="comm"  rows="5" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <?PHP if (@in_array($modelProcess->status->paperless_status_code, ['F15', 'F16', 'F18', 'F19'])) { ?>
                                                    <div class="mb-3 row">
                                                        <div class="col-sm-12 font-weight-bold">มอบให้(เจ้าหน้าที่)</div>
                                                        <div class="col-sm-12">
                                                            <div class="form-check">
                                                                <?php
                                                                echo Select2::widget([
                                                                    'name' => 'emps',
                                                                    'options' => [
                                                                        'placeholder' => 'เลือกมอบให้(เจ้าหน้าที่)...',
                                                                        'multiple' => true,
                                                                    //'class' => 'form-control form-control-lg',
                                                                    ],
                                                                    'theme' => Select2::THEME_MATERIAL,
                                                                    'pluginEvents' => [
                                                                        "select2:select" => "function(d) { offerText(d.params.data); }",
                                                                    ],
                                                                    'pluginOptions' => [
                                                                        //'dropdownParent' => '#modalForm',
                                                                        'allowClear' => true,
                                                                        'minimumInputLength' => 0,
                                                                        'language' => [
                                                                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                                                        ],
                                                                        'ajax' => [
                                                                            'url' => Url::to(['paperless/emplist']),
                                                                            'dataType' => 'json',
                                                                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                                                        ],
                                                                        //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                                                        'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                                                                        'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
                                                                    ],
                                                                ]);

                                                                /*
                                                                  echo $form->field($model, 'paperless_from', [
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
                                                                 *
                                                                 */
                                                                ?>
                                                                <label class="form-check-label">
                                                                    ดําเนินการ
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 row">
                                                        <div class="col-sm-12 font-weight-bold">มอบให้(หน่วยงาน)</div>
                                                        <div class="col-sm-12">
                                                            <div class="form-check">
                                                                <?php
                                                                echo Select2::widget([
                                                                    'name' => 'deps',
                                                                    'options' => [
                                                                        'placeholder' => 'เลือกมอบให้(หน่วยงาน)...',
                                                                        'multiple' => true,
                                                                    //'class' => 'form-control form-control-lg',
                                                                    ],
                                                                    'theme' => Select2::THEME_MATERIAL,
                                                                    'pluginOptions' => [
                                                                        //'dropdownParent' => '#modalForm',
                                                                        'allowClear' => true,
                                                                        'minimumInputLength' => 0,
                                                                        'language' => [
                                                                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                                                        ],
                                                                        'ajax' => [
                                                                            'url' => Url::to(['/office/paperless/deplist']),
                                                                            'dataType' => 'json',
                                                                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                                                        ],
                                                                        //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                                                        'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                                                                        'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
                                                                    ],
                                                                ]);
                                                                ?>
                                                                <label class="form-check-label">
                                                                    ดําเนินการ
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?PHP } ?>

                                                <div class="mb-3 row">
                                                    <div class="col-sm-12">
                                                        <input type="hidden" name="id" value="<?= @$model->paperless_id ?>">
                                                        <input type="hidden" name="receiver_edit" value="<?= @$model->employee_id ?>">
                                                        <button type="button" class="btn btn-dark  font-weight-bold btn-lg " id="btnConf"><i class="fa-solid fa-file-contract"></i> ดำเนินการ</button>
                                                    </div>
                                                </div>

                                            </div>
                                        <?PHP } ?>
                                    <?PHP } ?>
                                </div>
                            </div>
                        </div>
                    <?PHP } else { ?>
                        <div class="col-md-5">
                            <!--                            <div class="accordion accordion-header-shadow accordion-rounded  mt-2" id="accordion-eight">
                                                            <div class="accordion-item">
                                                                <div class="accordion-header rounded-lg" id="accord-8One" data-bs-toggle="collapse" data-bs-target="#collapse8One" aria-controls="collapse8One" aria-expanded="true" role="button">
                                                                    <span class="accordion-header-icon"></span>
                                                                    <span class="accordion-header-text">Accordion Header One</span>
                                                                    <span class="accordion-header-indicator"></span>
                                                                </div>
                                                                <div id="collapse8One" class="accordion__body collapse show" aria-labelledby="accord-8One" data-bs-parent="#accordion-eight" style="">
                                                                    <div class="accordion-body-text">
                                                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <div class="accordion-header rounded-lg collapsed" id="accord-8Two" data-bs-toggle="collapse" data-bs-target="#collapse8Two" aria-controls="collapse8Two" aria-expanded="false" role="button">
                                                                    <span class="accordion-header-icon"></span>
                                                                    <span class="accordion-header-text">Accordion Header Two</span>
                                                                    <span class="accordion-header-indicator"></span>
                                                                </div>
                                                                <div id="collapse8Two" class="accordion__body collapse" aria-labelledby="accord-8Two" data-bs-parent="#accordion-eight" style="">
                                                                    <div class="accordion-body-text">
                                                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>-->
                            <div class="h4 font-weight-bold">การดำเนินการ</div>
                            <hr>
                            <div  class="widget-timeline dlab-scroll  ps ps--active-y mt-2">
                                <ul class="timeline">
                                    <?PHP
                                    foreach ($data as $row) {
                                        if (empty($row->process_comment))
                                            continue;
                                        if (empty($row->process_comment))
                                            continue;

                                        $emp = @$row->emp->employee_fullname
                                        ?>
                                        <li>
                                            <div class="timeline-badge dark">
                                            </div>
                                            <a class="timeline-panel text-muted" href="javascript:;">
                                                <span><?= Ccomponent::getThaiDate(($row->process_create), 'S', 1) ?></span>
                                                <h6 class="mb-0"><strong class="text-warning"><?= $emp ?></strong>
                                                    <br><?= $row->process_comment ?></h6>
                                            </a>
                                        </li>
                                    <?PHP } ?>
                                </ul>
                            </div>
                        </div>
                    <?PHP } ?>
                    <div class="col-md-7">
                        <div class="pt-2">
                            <div>
                                <div class="embed-responsive embed-responsive-1by1">
                                    <iframe  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['view', 'id' => @$model->paperless_id]) ?>#view=FitH" type="application/pdf" /></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade small" id="status">
            <?PHP
//$this->render('_search', ['model' => $dataProvider]);
            echo GridView::widget([
                'id' => 'gviewStatus01',
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table verticle-middle table-responsive-md'],
                'panel' => [
                    'heading' => '',
                    'type' => '',
                    'before' => '',
                    //'before' => $this->render('_search', ['model' => $dataProvider]),
                    'footer' => false,
                ],
                'panelTemplate' => '<div class="">
          {panelBefore}
          {items}
          {panelAfter}
          {panelFooter}
          <div class="text-center m-2">{summary}</div>
          <div class="text-center m-2">{pager}</div>
          </div>',
                'responsiveWrap' => FALSE,
                'striped' => FALSE,
                'hover' => TRUE,
                'bordered' => FALSE,
                'condensed' => TRUE,
                'export' => FALSE,
                'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
                'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
//                    [
//                        'headerOptions' => ['class' => 'font-weight-bold timeline'],
//                        'label' => '#',
//                        'attribute' => 'process_command',
//                        'format' => 'raw',
//                        'visible' => 1,
//                        'value' => function ($model) {
//                            //return Html::tag('div', 'ssss', ['class' => 'timeline-badge primary timeline-panel text-muted']);
//                            return '<a class="timeline-panel text-muted" href="#">
//                                                <span>10 minutes ago</span>
//                                                <h6 class="mb-0">Youtube, a video-sharing website, goes live <strong class="text-primary">$500</strong>.</h6>
//                                            </a>';
//                        }
//                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'เสนอหนังสือเมื่อวันที่',
                        'attribute' => 'process_create',
                        'format' => 'raw',
                        //'vAlign' => 'middle',
                        //'width' => '1%',
                        'noWrap' => TRUE,
                        //'hAlign' => 'center',
                        'visible' => 1,
                        'value' => function ($model) {
                            $date = Ccomponent::getThaiDate(($model['process_create']), 'S', 1);
                            return $date;
                        }
                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'ผู้เสนอ',
                        'format' => 'raw',
                        //'vAlign' => 'middle',
                        'visible' => 1,
                        'value' => function ($model) {
                            return $model->emp->employee_fullname;
                        }
                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'ความเห็นผู้เสนอหนังสือ',
                        'attribute' => 'process_comment',
                        'format' => 'raw',
                        'visible' => 1,
//                        'value' => function ($model) {
//                            return'<span class="badge badge-rounded badge-primary sweet-message">' . @$model['status']['paperless_status'] . '</span>';
//                        }
                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'เสนอ/ผู้ดำเนินการ',
                        'attribute' => 'process_receiver',
                        'format' => 'raw',
                        'visible' => 1,
                        'value' => function ($model) {

                            if (!empty($model->process_receiver)) {
                                $head = $model->receiver->getHead();
                                $headHtml = '';
                                foreach ($head as $value) {
                                    $headHtml .= @Html::tag('div', $value['executive'] . ' ' . $value['dep'], ['class' => 'small']);
                                }
                                return @Html::tag('b', $model->receiver->employee_fullname, ['class' => '']) . ' ' . @$headHtml;
                            } else {

                                $emp = @Html::tag('div', $model->staff->employee_fullname, ['class' => 'small font-weight-bold']);
                                return @$model->status->paperless_status . '<br>' . $emp;
                            }
                        }
                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'สั่งการ',
                        'attribute' => 'process_command',
                        'format' => 'raw',
                        'visible' => 1,
//                        'value' => function ($model) {
//                            return'<span class="badge badge-rounded badge-primary sweet-message">' . @$model['status']['paperless_status'] . '</span>';
//                        }
                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'ใช้เวลาดำเนินการ',
                        'attribute' => 'paperless_tt',
                        'format' => 'raw',
                        'visible' => 1,
                    //'value' => function ($model) {
                    //return'<span class="badge badge-rounded badge-primary">' . @$model['status']['paperless_status'] . '</span>';
                    //}
                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'สถานะเอกสาร',
                        'attribute' => 'process_receiver',
                        'format' => 'raw',
                        'visible' => 1,
                        'value' => function ($model) {
                            return'<span class="badge badge-rounded badge-primary">' . @$model['status']['paperless_status'] . '</span>';
                        }
                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'สถานะดำเนินการ',
                        'attribute' => 'process_receiver',
                        'format' => 'raw',
                        'visible' => 1,
                        'value' => function ($model) {
                            return'<span class="badge badge-rounded  badge-outline-primary">' .
                            (!empty($model->process_acknowledge_datetime) ? Ccomponent::getThaiDate($model->process_acknowledge_datetime, 'S', 1) : '' )
                            . '</span>';
                        }
                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => ':',
                        'attribute' => 'process_receiver',
                        'format' => 'raw',
                        'visible' => 1,
                        'value' => function ($model) {
                            if (@$model['status']['paperless_status_code'] == 'F00') {
                                return'<i class="fa-solid fa-reply-all fa-2x text-danger"></i>';
                            } else if (@$model['status']['paperless_status_code'] == 'FF') {
                                return'<i class="fa-solid fa-flag-checkered text-success fa-2x "></i>';
                            } else {
                                return'<i class="fa-solid fa-turn-up fa text-secondary fa-2x "></i>';
                            }
                        }
                    ],
                ]
                    ]
            );
            ?>
        </div>
    </div>
</div>

<?php Pjax::end() ?>
