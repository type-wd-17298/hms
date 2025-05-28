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
use app\modules\office\components\Ccomponent as CC;

/*
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
 */


//นับเอกสารแนบ
$cf = @count($modelProcess->paper->getUrlPdf($modelProcess->approval_id));

$vehicle = $modelProcess->type->vehicle_type;
if ($modelProcess->travelby == 1 && $modelProcess->driver == 'Y')
    $vehicle = $vehicle . " (พร้อมพนักงานขับรถ)";
if ($modelProcess->travelby == 2)
    $vehicle = $vehicle . " (ทะเบียน {$modelProcess->vehicle_personal})";

$cc = 0;
if ($modelProcess->employee_id == '') {
    $emp = "-";
} else {
    $data = CC::getListStaff($modelProcess->employee_id);
    $emp = @implode(", ", $data);
    $cc = @count($data);
}
$html = ""//Html::a($modelProcess->topic, 'javascript:;', ['class' => 'font-weight-bold btnOper', 'data' => ['pid' => $model->approval_id]])
        . Html::tag('span', 'ณ ' . $modelProcess->place, ['class' => ''])
        . Html::tag('span', ' โดย ' . $vehicle, ['class' => '']);
if ($cc > 0)
    $html = $html . Html::tag('span', " พร้อมเจ้าหน้าที่ {$cc} ท่าน", ['class' => '']);


$defaultPage = '';
$urlAcl = Url::to(['acknowledge']);
$urlOperate = Url::to(['operate']);
$src = Url::to(['view', 'id' => @$modelProcess->approval_id]);
$url = Url::to(['createprocess']);
$urlProcessUpdate = Url::to(['process-update']);
$js = <<<JS
/*
$(".ckItems").click(function(e){
        var ck = $('input[name=frmStatus]:checked', '#frm999').val();
        if(ck == 'F3' || ck == 'F18' || ck == 'F19'){
           $('#receiverID').prop('disabled', false);
        }else{
           $('#receiverID').prop('disabled', true);
           $('#receiverID').val(null).trigger('change');
        }
});
*/

$(".btnProcessUpdate").click(function(e){
         Swal.fire({
            icon: 'warning',
            title: 'คุณต้องการลบข้อมูลหรือไม่ ?',
            showCancelButton: true,
            confirmButtonText: 'ลบรายการ',
          }).then((result) => {
            if (result.isConfirmed) {
                        var pid = $(this).data("id");
                        $("#gviewStatus").yiiGridView('applyFilter');
                        $.post('{$urlProcessUpdate}',{id:pid}, function(data) {
                            Swal.fire({
                            icon: 'success',
                            title: 'คุณได้ลบรายการดำเนินการสำเร็จ !',
                            showConfirmButton: false,
                            timer: 1500
                            });
                        });
            }
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
            title: 'ยืนยันการเสนอแฟ้มไปราชการ?',
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
                          //timer: 2000,
                          timerProgressBar: true,
                          didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                            timerInterval = setInterval(() => {
                              b.textContent = Swal.getTimerLeft()
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
                        })


                $.post("{$url}",$.param(form.serializeArray()), function(data) {
                    if(data.status == 'success'){
                            Swal.fire({
                                icon: 'success',
                                html:data.message,
                                title: '<strong>ผลการดำเนินการ</strong>',
                                showConfirmButton: false,
                                timer: 3000
                          });
                                $.pjax.reload({container: '#pjGview', async: false});
                                $('#modalPapaer').modal('toggle');
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
$("#btnConfCancel").click(function(e){
           $('#cancelLeave').val("cancel");
           e.preventDefault();
           var form = $(this).parents('form');
           Swal.fire({
            icon: 'error',
            title: 'ยืนยันการปฏิเสธคำขอ?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'ดำเนินการ',
            denyButtonText: 'ยกเลิก',
          }).then((result) => {
            if (result.isConfirmed) {
                $.post("{$url}",$.param(form.serializeArray()), function(data) {
                    if(data.status == 'success'){
                            Swal.fire({
                                icon: 'success',
                                html:data.message,
                                title: '<strong>ผลการดำเนินการ</strong>',
                                showConfirmButton: false,
                                timer: 3000
                          });
                                $.pjax.reload({container: '#pjGview', async: false});
                                $('#modalPapaer').modal('toggle');
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

Pjax::begin(['id' => 'frm01', 'timeout' => false, 'enablePushState' => false]);
$this->registerJs($js, $this::POS_READY);
?>
<div class="alert alert-primary alert-dismissible fade show">
<!--    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"><span><i class="mdi mdi-btn-close"></i></span></button>-->
    <div class="media">
        <div class="media-body">
            <h4 class="mt-1 mb-2 font-weight-bold"><?= $modelProcess->topic ?></h4>
            <p class="mb-0"><?= $html ?></p>
        </div>
    </div>
</div>

<!--
<div id="smartwizard"  class="form-wizard  sw sw-theme-default sw-justified d-none d-sm-block">
    <ul class="nav nav-wizard">
        <li>
            <a href="#xxx1" class="nav-link  <?= (in_array($modelProcess->approval_status_id, ['A00', 'A01', 'A02', 'A03', 'A04', 'A99', 'A10']) ? 'done' : '') ?>" >&nbsp;<span>1</span>
            </a>
            ยื่นแบบฟอร์มขออนุญาตไปราชการ
        </li>
        <li>
            <a href="#xxx2" class="nav-link inactive <?= (in_array($modelProcess->approval_status_id, ['A03', 'A04', 'A99', 'A10']) ? 'done' : '') ?>" >&nbsp;<span>2</span></a>
            หัวหน้างานเห็นชอบ
        </li>
        <li>
            <a href="#xxx3" class="nav-link inactive <?= (in_array($modelProcess->approval_status_id, ['A99', 'A04', 'A10']) ? 'done' : '') ?>" >&nbsp;<span>3</span></a>
            หัวหน้าฝ่ายเห็นชอบ
        </li>
        <li>
            <a href="#xxx4" class="nav-link inactive <?= (in_array($modelProcess->approval_status_id, ['A10', 'A04']) ? 'done' : '') ?>" >&nbsp;<span>4</span></a>
            เจ้าหน้าที่ตรวจสอบเอกสาร
        </li>
        <li>
            <a href="#xxx5" class="nav-link inactive <?= (in_array($modelProcess->approval_status_id, ['A10']) ? 'done' : '') ?>">&nbsp;<span>5</span></a>
            ผู้บริหารอนุมัติ
        </li>
    </ul>
</div>

<hr>-->
<div>
    <form id="frm999">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="pt-2 m-1">
                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <div class="alert alert-light">
                                    <strong>ผู้ขออนุญาตไปราชการ : <?= $modelProcess->emps->employee_fullname ?></strong>
                                    <?PHP if (in_array($modelProcess->approval_status_id, ['A00'])) { ?>
                                        <div class="h4">
                                            สถานะหนังสือ : ยื่นขออนุญาตไปราชการ
                                        </div>
                                    <?PHP } else { ?>
                                        <div class="h4">
                                            สถานะหนังสือ : <br> <?= @$modelProcess->approvalStatus->approval_status_name ?>
                                            (<?= @$modelProcess->lastProcess->receiver->employee_fullname ?>)
                                        </div>
                                    <?PHP } ?>
                                    <hr>
                                    <?PHP if (@!empty($modelProcess->lastProcess->process_comment)) { ?>
                                        <small>หมายเหตุ : <?= $modelProcess->lastProcess->process_comment ?></small>
                                    <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <?PHP
                        if ($canVisible) {
                            //echo $modelProcess->approvalStatus->approval_status_id;
                            ?>
                            <?PHP if (in_array($modelProcess->approvalStatus->approval_status_id, ['A00', 'A01', 'A08'])) { ?>
                                <div class="mb-3 row" >
                                    <div class="col-sm-12 col-form-label  font-weight-bold">เสนอหนังสือถึง</div>
                                    <div class="col-sm-12 ml-4" id="receiverDiv"></div>
                                    <div class="col-sm-12" id="receiverDiv2">
                                        <?php
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
        '<b class="text h4">' + repo.text + '</b>' +
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
                                                //'dropdownParent' => '#modalPapaer',
                                                'allowClear' => true,
                                                'minimumInputLength' => 0,
                                                'language' => [
                                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                                ],
                                                'ajax' => [
                                                    'url' => new JsExpression('function(){
                                                                            var ck = $("input[name=frmStatus]:checked", "#frm999").val();
                                                                            if("' . $header . '" == "1"){
                                                                                return "' . Url::to(['paperless/emplist2']) . '";
                                                                            }else{
                                                                                return "' . Url::to(['paperless/emplist']) . '";
                                                                            }
                                                                            }'),
                                                    'cache' => true,
                                                    'dataType' => 'json',
                                                    'data' => new JsExpression('function(params) {return {q:params.term,mode:"D",dep:"' . $modelProcess->emp->employee_dep_id . '"}; }')
                                                ],
                                                'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                                                'templateResult' => new JsExpression('formatRepo'),
                                                'templateSelection' => new JsExpression('formatRepoSelection'),
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </div>

                            <?PHP } elseif ($modelProcess->approvalStatus->approval_status_id <> 'A00' && 0) { ?>
                                <div class="mb-3 row">
                                    <div class="col-sm-6 font-weight-bold">ส่งดำเนินการต่อ</div>
                                    <div class="col-sm-6">
                                        <?PHP
                                        foreach ($status as $row) {
                                            /*
                                              if ($header == 1) {
                                              if ($row->paperless_status_id == 'F15')//สำหรับหัวหน้ากลุ่มงาน
                                              $row->paperless_status = 'เสนอเพื่อดำเนินการต่อ';
                                              } else {
                                              if ($model->paperless_direct > 0 && $row->paperless_status_id == 'F03')
                                              continue; //ข้ามรายการที่เป็นส่งตรง
                                              if ($row->paperless_status_id == 'F03')
                                              $row->paperless_status = 'เสนอเพื่อดำเนินการต่อ';
                                              }
                                             *
                                             */
                                            ?>
                                            <div class="form-check ckItems">
                                                <input class="form-check-input" type="radio" name="frmStatus" value="<?= $row->approval_status_id ?>" id="<?= $row->approval_status_id ?>">
                                                <label class="form-check-label  <?= ($row->approval_status_id == 'A10' ? 'font-weight-bold text-primary' : '') ?>" for="<?= $row->approval_status_id ?>">
                                                    <?= $row->approval_status_id ?> <?= $row->approval_status_operation ?>
                                                </label>
                                            </div>
                                        <?PHP } ?>
                                    </div>
                                </div>

                            <?PHP } ?>
                            <fieldset class="mb-3">
                                <div class="row">
                                    <label class="col-form-label col-sm-12 pt-0 font-weight-bold">หมายเหตุ/ความเห็น</label>
                                    <div class="col-sm-12">
                                        <textarea name="comment"  rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <input type="hidden" name="id" value="<?= @$modelProcess->approval_id ?>">
                                    <input type="hidden" name="cancelLeave" id="cancelLeave" >
                                    <button type="button" class="btn btn-primary btn-block font-weight-bold btn-lg" id="btnConf"><i class="fa-solid fa-check"></i> <?= @$modelProcess->approvalStatus->approval_status_operation ?></button>
                                    <?PHP if ($modelProcess->approval_status_id <> 'A00') { ?>
                                        <button type="button" class="btn btn-danger btn-block font-weight-bold btn-lg" id="btnConfCancel"><i class="fa-solid fa-ban"></i> ปฏิเสธคำขอ</button>
                                    <?PHP } ?>
                                </div>
                            </div>
                        <?PHP } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-8">

                <div class="default-tab1">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#home"><i class="la la-keyboard-o me-2"></i> แบบฟอร์มเอกสาร</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#process"><i class="la la-chevron-right me-2"></i> สถานะการดำเนินการ</a>
                        </li>
                        <?PHP if ($cf > 0) { ?>
                            <li class="nav-item">
                                <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#paper"><i class="la la-chevron-right me-2"></i> เอกสารแนบ</a>
                            </li>
                        <?PHP } ?>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#paper2"><i class="la la-chevron-right me-2"></i> เอกสารต้นเรื่อง</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home" role="tabpanel">
                            <div class="pt-4">
                                <div class="embed-responsive embed-responsive-1by1">
                                    <iframe  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['viewfile', 'id' => @$modelProcess->approval_id]) ?>#view=FitW" type="application/pdf" /></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paper" role="tabpanel">
                            <div class="pt-4">
                                <?PHP
                                $pdf = $modelProcess->getUrlPdf('L' . $modelProcess->approval_id);
                                ?>
                                <div class="embed-responsive embed-responsive-1by1">
                                    <iframe  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['viewpaper', 'id' => $modelProcess->paperless_ref_id]) ?>#view=FitW" type="application/pdf" /></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paper2" role="tabpanel">
                            <div class="pt-4">
                                <?PHP
                                $pdf = $modelProcess->getUrlPdf('L' . $modelProcess->approval_id);
                                ?>
                                <div class="embed-responsive embed-responsive-1by1">
                                    <iframe  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['viewpaper', 'id' => $modelProcess->paperless_ref_id]) ?>#view=FitW" type="application/pdf" /></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade small" id="process" role="tabpanel">
                            <?PHP
                            Pjax::begin(['id' => 'gviewStatus999', 'timeout' => false, 'enablePushState' => false]);
//$this->registerJs($jsGrid, $this::POS_READY);
                            echo GridView::widget([
                                'id' => 'gviewStatus',
                                //'pjax' => false,
                                'dataProvider' => $dataProvider,
                                'tableOptions' => ['class' => 'table verticle-middle table-responsive-md small'],
                                'panel' => [
                                    'heading' => '',
                                    'type' => '',
                                    'before' => '',
                                    //'before' => $this->render('_search', ['model' => $dataProvider]),
                                    'footer' => false,
                                ],
                                'panelTemplate' => '<div class="small">
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
                                        'visible' => 0,
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
                                                return @$model->status->approval_status_name . '<br>' . $emp;
                                            }
                                        }
                                    ],
                                    [
                                        'headerOptions' => ['class' => 'font-weight-bold'],
                                        'label' => 'สั่งการ',
                                        'attribute' => 'process_command',
                                        'format' => 'raw',
                                        'visible' => 0,
//                        'value' => function ($model) {
//                            return'<span class="badge badge-rounded badge-primary sweet-message">' . @$model['status']['paperless_status'] . '</span>';
//                        }
                                    ],
                                    [
                                        'headerOptions' => ['class' => 'font-weight-bold'],
                                        'label' => 'สถานะ',
                                        'attribute' => 'process_receiver',
                                        'format' => 'raw',
                                        'visible' => 1,
                                        'value' => function ($model) {
                                            return'<span class="badge badge-rounded badge-primary">' . @$model['status']['approval_status_name'] . '</span>';
                                        }
                                    ],
                                    [
                                        //'class' => 'kartik\grid\ActionColumn',
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => ' {delete}',
                                        // 'noWrap' => TRUE,
                                        'visible' => \Yii::$app->user->can('SuperAdmin'),
                                        'buttons' => [
                                            'delete' => function ($url, $model) {
                                                return Html::a('ลบรายการ ' . $model->processlist_id, false, [
                                                    'class' => 'btn btn-danger btn-xs btnProcessUpdate',
                                                    'data-id' => $model->processlist_id,
                                                        //'pjax-container' => 'gviewStatus999',
                                                ]);
                                            }
                                        ],
                                    ],
                                ]
                                    ]
                            );
                            Pjax::end();
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php Pjax::end() ?>
