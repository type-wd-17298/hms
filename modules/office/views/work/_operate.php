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
use app\modules\hr\models\EmployeePositionHead;
use yii\helpers\ArrayHelper;

//$headerUpLevel = 0;
$headOwnerUp = @EmployeePositionHead::find()->where(['employee_id' => $modelProcess->emp_staff_a])->all();
if (in_array(2, ArrayHelper::getColumn($headOwnerUp, 'executive.employee_executive_level')) && $modelProcess->work_status_id <> 'L99') {
    $headerUpLevel = 1;
}

$defaultPage = '';
$urlAcl = Url::to(['acknowledge']);
$urlOperate = Url::to(['operate']);
$src = Url::to(['view', 'id' => @$modelProcess->work_grid_change_id]);
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
                          //timer: 2000,
                          timerProgressBar: true,
                          didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                            timerInterval = setInterval(() => {
                             // b.textContent = Swal.getTimerLeft()
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
                                $.pjax.reload({container: '#mainLeave', async: false});
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
                                $.pjax.reload({container: '#mainLeave', async: false});
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
<?PHP if ($headerUpLevel) { ?>
    <div class="alert alert-primary alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"><span><i class="mdi mdi-btn-close"></i></span>
        </button>
        <div class="media">
            <div class="media-body">
                <h3 class="mt-1 mb-2 font-weight-bold">แจ้งให้ทราบ</h3>
                <p class="mb-0">ผู้ลายืนใบลาให้คุณในฐานะหัวหน้างาน ในขณะที่คุณเป็นหัวหน้ากลุ่มงาน ระบบจะข้ามขั้นตอนไปที่ระดับหัวหน้าฝ่ายเห็นชอบ</p>
            </div>
        </div>
    </div>
<?PHP } ?>

<div id="smartwizard"  class="form-wizard  sw sw-theme-default sw-justified d-none d-sm-block ">
    <ul class="nav nav-wizard">
        <li>
            <a href="#xxx1" class="nav-link  <?= (in_array($modelProcess->work_status_id, ['L00', 'L01', 'L02', 'L03', 'L04', 'L99', 'L10']) ? 'done' : '') ?>" >&nbsp;<span>1</span>
            </a>
            ยื่นเอกสาร
        </li>
        <li>
            <a href="#xxx1" class="nav-link  <?= (in_array($modelProcess->work_status_id, ['L00', 'L01', 'L02', 'L03', 'L04', 'L99', 'L10']) ? 'done' : '') ?>" >&nbsp;<span>2</span>
            </a>
            ผู้รับแลก
        </li>
        <li>
            <a href="#xxx2" class="nav-link inactive <?= (in_array($modelProcess->work_status_id, ['L03', 'L04', 'L99', 'L10']) ? 'done' : '') ?>" >&nbsp;<span>3</span></a>
            หัวหน้างานเห็นชอบ
        </li>
        <li>
            <a href="#xxx3" class="nav-link inactive <?= (in_array($modelProcess->work_status_id, ['L99', 'L04', 'L10']) ? 'done' : '') ?>" >&nbsp;<span>4</span></a>
            หัวหน้ากลุ่มงานอนุมัติ
        </li>

    </ul>
</div>
<hr>
<div>
    <form id="frm999">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="pt-2 m-1">
                        <div class="mb-3 row">
                            <div class="col-sm-12">
                                <div class="alert alert-light">
                                    <strong>ผู้ยื่นใบขอ<?= $modelProcess->workChange->work_change_name ?> : <?= $modelProcess->emps->employee_fullname ?></strong>
                                    <div>ประเภทเวร : <?= $modelProcess->workType->work_grid_type_name ?></div>
                                    <?PHP if ($modelProcess->work_grid_type_id == '001') { ?>
                                        <p>ผู้รับแลกแทน : <?= $modelProcess->workAssign->employee_fullname ?></p>
                                    <?PHP } ?>
                                    <?PHP if (in_array($modelProcess->work_status_id, ['L00'])) { ?>
                                        <div class="h3">
                                            สถานะหนังสือ : ยื่นเอกสาร
                                        </div>
                                    <?PHP } else { ?>
                                        <div class="h4">
                                            สถานะหนังสือ : <br> <?= @$modelProcess->workStatus->work_status_name ?>
                                            <?PHP if (in_array($modelProcess->work_status_id, ['L08'])) { ?>

                                                (<?= @$modelProcess->lastProcess->process_comment ?>)
                                                <br><small>(<?= @$modelProcess->lastProcess->emp->employee_fullname ?>)</small>
                                            <?PHP } else { ?>
                                                (<?= @$modelProcess->lastProcess->receiver->employee_fullname ?>)
                                            <?PHP } ?>
                                        </div>
                                    <?PHP } ?>
                                    <hr>
                                    <?PHP if (@!empty($modelProcess->leave_detail)) { ?>
                                        <small>หมายเหตุ : <?= $modelProcess->leave_detail ?></small>
                                    <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <?PHP
                        if ($canVisible) {
                            //echo $modelProcess->leaveStatus->leave_status_id;
                            ?>
                            <?PHP if (in_array($modelProcess->workStatus->work_status_id, ['L00', 'L02', 'L99', 'L08']) && $headerUpLevel == 0) { ?>
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
                                                'dropdownParent' => '#modalContent',
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

                            <?PHP } elseif ($modelProcess->workStatus->work_status_id <> 'L00' && 0) { ?>
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
                                                <input class="form-check-input" type="radio" name="frmStatus" value="<?= $row->work_status_id ?>" id="<?= $row->work_status_id ?>">
                                                <label class="form-check-label  <?= ($row->work_status_id == 'L100' ? 'font-weight-bold text-primary' : '') ?>" for="<?= $row->work_status_id ?>">
                                                    <?= $row->work_status_id ?> <?= $row->work_status_operation ?>
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
                                    <input type="hidden" name="id" value="<?= @$modelProcess->work_grid_change_id ?>">
                                    <input type="hidden" name="cancelLeave" id="cancelLeave" >
                                    <button type="button" class="btn btn-primary btn-block font-weight-bold btn-lg" id="btnConf"><i class="fa-solid fa-check"></i> <?= @$modelProcess->workStatus->work_status_operation ?></button>
                                    <?PHP if ($modelProcess->work_status_id <> 'L00') { ?>
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
                            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#home"><i class="la la-keyboard-o me-2"></i> เอกสาร</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#process"><i class="la la-chevron-right me-2"></i> สถานะการดำเนินการ</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home" role="tabpanel">
                            <div class="pt-4">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['view', 'id' => @$modelProcess->work_grid_change_id]) ?>#view=FitW" type="application/pdf" /></iframe>
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
                                                return @$model->status->work_status_name . '<br>' . $emp;
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
                                            return'<span class="badge badge-rounded badge-primary">' . @$model['status']['work_status_name'] . '</span>';
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
