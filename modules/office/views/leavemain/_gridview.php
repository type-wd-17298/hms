<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
//use app\modules\edocument\components\Ccomponent as CC;
use app\components\Ccomponent;
use app\components\Cdata;
use yii\helpers\ArrayHelper;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$this->title = 'ระบบการลาออนไลน์';
$this->params['breadcrumbs'][] = $this->title;
$userID = $user->employee_id;
/*
  //ตรวจสอบ topic
  $this->params['mqttFuncCheck'] = <<<JS
  if(topic == 'hms/service/paper/update/L-{$userID}'){
  $.pjax.reload({container: '#mainLeave', async: false});
  //$("#modalForm").modal('hide');
  //$("#frmSearch").submit();
  }
  JS;
  //ตรวจสอบ topic
  $this->params['mqttSubTopics'] = <<<JS
  sub_topics('hms/service/paper/update/L-{$userID}');
  JS;
 */
Pjax::begin(['id' => 'mainLeave', 'timeout' => false, 'enablePushState' => false]);
$url = Url::to(['operate']);
$urlCreate = Url::to(['create']);
$urlDelete = Url::to(['delete']);
$js = <<<JS
$(".btnCreateLink").click(function(event){
      window.location.href="{$urlCreate}";
});
$(".btnOper").click(function(){
       var id = $(this).data('id');
       $("#modalContent").html('');
       $('#modalPapaer').modal('show');
        $.get("{$url}",{id:$(this).data("pid")}, function(data) {
           $("#modalContent").html(data);
        });
    });
$(".btnUpdate").click(function(event){
       event.preventDefault();
       $("#modalContent").html('');
       $('#modalPapaer').modal('show');
       var url = $(this).attr("href");
       $.get(url, function(data) {
           $("#modalContent").html(data);
       });
});
$(".btnCreate").click(function(event){
       $("#modalContent").html('');
       $('#modalPapaer').modal('show');
       $.get('{$urlCreate}', function(data) {
           $("#modalContent").html(data);
       });
});
$(".btnDelete").click(function(event){
       var id = $(this).data('id');
       Swal.fire({
            icon: 'error',
            title: 'ยืนยันการลบใบลานี้หรือไม่?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'ลบข้อมูล',
            denyButtonText: 'ยกเลิก',
          }).then((result) => {
            if (result.isConfirmed) {
                $.post('{$urlDelete}',{id:id}, function(data) {
                        if(data.status == 'success'){
                            Swal.fire({
                                icon: 'success',
                                html:data.message,
                                title: '<strong>ผลการดำเนินการ</strong>',
                                showConfirmButton: false,
                                timer: 3000
                          });
                                $.pjax.reload({container: '#mainLeave', async: false});
                                //$("#frmSearch").submit();
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

        }
       });
});
JS;
$this->registerJs($js, $this::POS_READY);
?>

<?=
GridView::widget([
    'id' => 'gLeaveView',
    'dataProvider' => $dataProvider,
    #'filterModel' => $searchModel,
    'rowOptions' => function ($model, $key, $index, $widget) {
        if (\Yii::$app->user->can('HRsAdmin'))
            return ['class' => ''];
        //แสดงรายการที่เกี่ยวข้อง
        if ($model->pcheck == 1)
            return ['class' => 'bg-primary-light'];
    },
    'panel' => [
        // 'type' => 'default',
        'heading' => 'รายการลา',
        'before' => $this->render('_search', ['model' => $dataProvider]),
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
    'hover' => FALSE,
    'bordered' => FALSE,
    'condensed' => FALSE,
    'export' => FALSE,
    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ระยะเวลา',
            'attribute' => 'leave_status_id',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 0,
            'value' => function ($model) {
                return '';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'สถานะ',
            'attribute' => 'leave_status_id',
            'vAlign' => 'middle',
            'width' => '2%',
            'vAlign' => 'top',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {

                $status = @$model->leaveStatus;
                if (!empty($model->lastProcess->receiver->employee_fullname))
                    $who = '<br><small>(' . @$model->lastProcess->receiver->employee_fullname . ')</small>';

                if (!empty($model->lastProcess->staff->employee_fullname) && $model->leave_status_id == 'L08')
                    $who = '<br><small>(' . @$model->lastProcess->staff->employee_fullname . ')</small>';

                if (in_array($model->leave_status_id, ['L10'])) {
                    return '<span class="btn-block badge badge-' . @$status->leave_status_color . '"><i class="fa-solid fa-sheet-plastic"></i> ' . @$status->leave_status_name . @$who . '</span>';
                } else {
                    return '<span class="btn-block badge badge-' . @$status->leave_status_color . '"><i class="fa-solid fa-sheet-plastic"></i> ' . @$status->leave_status_name . @$who . '</span>';
                }
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ดำเนินการ',
            'format' => 'raw',
            'noWrap' => TRUE,
            'vAlign' => 'top',
            'width' => '1%',
            'value' => function ($model) use ($user) {
                $status = $model->leaveStatus;
                if (@$model->lastProcess->process_receiver == $user->employee_id || @$model->employee_id == $user->employee_id || \Yii::$app->user->can('SuperAdmin') || (\Yii::$app->user->can('HRsAdmin') && $model->leave_status_id == 'L99')) {
                    if (!in_array($model->leave_status_id, ['L10'])) {
                        $operation = $status->leave_status_operation;
                        //if (@$model->employee_id == $user->employee_id && in_array($model->leave_status_id, ['L01']))
                        //$operation = 'ยกเลิกการรับมอบ';

                        return $html = @Html::a($operation, 'javascript:;',
                                        [
                                            'data' => ['pjax' => 1, 'pid' => @$model->leave_id],
                                            'class' => "btnLink btnOper btn  btn-xs  btn-outline-primary   btn-block",
                        ]);
                    }
                }

                if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin')) {
                    return $html = @Html::a($status->leave_status_operation, 'javascript:;',
                                    [
                                        'data' => ['pjax' => 1, 'pid' => @$model->leave_id],
                                        'class' => "btnLink btnOper btn  btn-xs btn-outline-dark  btn-block",
                    ]);
                }
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'Profile',
            'attribute' => 'leave_type_time',
            'format' => 'raw',
            'width' => '5%',
            'visible' => 0,
            'noWrap' => TRUE,
            'value' => function ($model) {
                $userProfile = Cdata::getDataUserAccount($model->emps->employee_cid);
                return @Html::img($userProfile['pictureUrl'], ['class' => 'img-thumbnail img-responsive', 'width' => 50]);
            },
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ชื่อ-สกุล',
            'attribute' => 'employee_id',
            'format' => 'raw',
            'noWrap' => TRUE,
            #'width' => '5%',
            #'hAlign' => 'right',
            'vAlign' => 'top',
            'value' => function ($model) {
                return '<b class="text-dark">' . $model->emps->employee_fullname . '</b><br><small>' .
                $model->emps->dep->employee_dep_label . '</small>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'หน่วยงาน',
            'attribute' => 'emps.dep.employee_dep_label',
            'format' => 'raw',
            'noWrap' => TRUE,
            'visible' => 0,
            #'width' => '5%',
            #'hAlign' => 'right',
            'vAlign' => 'middle',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ประเภทการลา/วันที่ลา',
            'attribute' => 'leave_start',
            'format' => 'raw',
            'noWrap' => TRUE,
            #'width' => '5%',
            #'hAlign' => 'center',
            'vAlign' => 'middle',
            'value' => function ($model) {
                $leave_type_time = ['F' => 'เต็มวัน', 'H1' => 'ครึ่งวันเช้า', 'H2' => 'ครึ่งวันบ่าย'];
                $color = $model->leaveType->leave_type_color;
                $cf = @count($model->getUrlPdf('L' . $model->leave_id));
                $html = Html::a(($cf > 0 ? "<div class='ml-2 badge badge-outline-primary' >เอกสารแนบ <i class='fa-regular fa-file-pdf fa-lg text-danger'></i></div>" : ''), 'javascript:;', [
                            'class' => 'btnView',
                            'data' => [
                                'id' => @$model['leave_id'],
                            ],]);
                @$paperCancel = @$model->leave->leaveType->leave_type_name;
                return ' <div class="rows justify-content-between1">'
                . '<div class="col-md-6">'
                . '<div class="badge badge-outline-light" style="color:' . $color . '"> <i class="' . $model->leaveType->leave_type_icon . '"></i> ' . $model->leaveType->leave_type_name . ' ' . @$paperCancel . ' (' . @$leave_type_time[$model->leave_type_time] . ')</div>'
                . $html
                . '</div>'
                . '<div class="col-md-6 small font-weight-bold">'
                . Ccomponent::getThaiDate($model->leave_start, 'S') . '- ' . Ccomponent::getThaiDate($model->leave_end, 'S')
                . '</div></div>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'แบบการลา',
            'attribute' => 'leave_type_time',
            'format' => 'raw',
            //'width' => '5%',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'visible' => 0,
            'value' => function ($model) {
                $leave_type_time = ['F' => 'ลาเต็มวัน', 'H1' => 'ลาครึ่งวันเช้า', 'H2' => 'ลาครึ่งวันบ่าย'];
                return @$leave_type_time[$model->leave_type_time];
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'วันลาสะสมคงเหลือ',
            'attribute' => 'leave_day',
            'format' => 'raw',
            //'width' => '1%',
            'visible' => 0,
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'value' => function ($model) {
                //return @$model->accruedSS;
                //return '<div class="badge badge-lg light badge-primary"><b>' . $model->accruedSS . '</b></div>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'จำนวนวัน',
            'attribute' => 'leave_day',
            'format' => 'raw',
            //'width' => '1%',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return '<div class="badge badge-lg light badge-danger"><b>' . $model->leave_day . '</b></div>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'small'],
            'label' => 'วันที่',
            'attribute' => 'create_at',
            'format' => 'raw',
            'noWrap' => TRUE,
            //'width' => '5%',
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->create_at, 'S', 0);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'หัวหน้า',
            #'attribute' => 'emps.department.department_name',
            'format' => 'raw',
            'visible' => 0,
            #'width' => '5%',
            #'hAlign' => 'right',
            #'vAlign' => 'middle',
            'value' => function ($model) {
                $emp = $model->emps->department->getChief();
                return $emp->employee_fullname;
//                            . ' ' . Html::a('เซ็นต์อนุมัติ', ['signature', 'id' => $model->leave_id], [
//                                        'class' => 'btn btn-icon  btn-sm btn-block btn-primary ',
//                            ]);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ผู้บริหาร',
            #'attribute' => 'emps.department.department_name',
            'format' => 'raw',
            'visible' => 0,
            #'width' => '5%',
            #'hAlign' => 'right',
            #'vAlign' => 'middle',
            'value' => function ($model) {
                $emp = $model->emps->department->getDirector();
                return $emp->employee_fullname;
//                                    . ' ' . Html::a('เซ็นต์อนุมัติ', ['signature', 'id' => $model->leave_id], [
//                                        'class' => 'btn btn-icon  btn-sm btn-primary ',
//                            ]);
            }
        ],
        [
            'label' => '#',
            'attribute' => 'docs_filename',
            'width' => '5%',
            'format' => 'raw',
            #'vAlign' => 'middle',
            'visible' => 0,
            'noWrap' => TRUE,
            'hAlign' => 'center',
            'value' => function ($model) {
                $completePath = Yii::getAlias('@web' . '/documents/' . $model->leave_file);
                $html = '<div class="btn-group btn-group-toggle btn-group-sm" data-toggle="buttons">
  <label class="btn btn-danger btn-sm active">
   ' . Html::a('<i class="fas fa-file-pdf fa-xl"></i> เอกสาร', ['viewdoc', 'id' => $model->leave_id], ['target' => '_blank', 'data' => ['pjax' => 0], 'class' => 'text-white text-decoration-none']) . '
  </label>
</div>';
                return $html; //Html::a('<i class="fas fa-file-alt fa-lg"></i> เอกสาร', '#', ['class' => 'btn btn-warning btn-sm btn-block']);
            }
        ],
        //['class' => 'kartik\grid\ActionColumn'],
        [
            'label' => '',
            'format' => 'raw',
            #'vAlign' => 'middle',
            'width' => '1%',
            'noWrap' => TRUE,
            'hAlign' => 'center',
            'value' => function ($model) {
                $completePath = Yii::getAlias('@web' . '/documents/' . $model->leave_file);
                $html = '<div class="btn-group btn-group-sm">';
                if (in_array($model->leave_status_id, ['L10']) && $model->leave_type_id <> 9)
                    $html .= '<button class="btn btn-primary btn-sm ">' . Html::a('<i class="fa-solid fa-hand"></i>', ['cancel', 'id' => $model->leave_id], ['class' => 'text-white text-decoration-none btnUpdate1']) . '</button>';

                $html .= '<button class="btn btn-xs btn-secondary active">' . Html::a('<i class="fa-regular fa-file-pdf"></i>', ['viewdoc', 'id' => $model->leave_id], ['target' => '_blank', 'data' => ['pjax' => 0], 'class' => 'text-white text-decoration-none']) . '</button>';
                if (in_array($model->leave_status_id, ['L00', 'L08']) && !(\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin'))) {
                    if ($model->leave_type_id <> 9)
                        $html .= '<button class="btn btn-secondary btn-sm active">' . Html::a('<i class="fa-solid fa-marker"></i>', ['update', 'id' => $model->leave_id], ['class' => 'text-white text-decoration-none btnUpdate1']) . '</button>';
                    $html .= '<button class="btn btn-danger btn-sm ">' . Html::a('<i class="fa-solid fa-trash-can"></i>', false, ['data-id' => $model->leave_id, 'class' => 'text-white text-decoration-none btnDelete']) . '</button>';
                } else {
                    if ((\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin'))) {
                        if ($model->leave_type_id <> 9)
                            $html .= '<button class="btn btn-secondary btn-sm active">' . Html::a('<i class="fa-solid fa-marker"></i>', ['update', 'id' => $model->leave_id], ['class' => 'text-white text-decoration-none btnUpdate1']) . '</button>';
                        $html .= '<button class="btn btn-danger btn-sm ">' . Html::a('<i class="fa-solid fa-trash-can"></i>', false, ['data-id' => $model->leave_id, 'class' => 'text-white text-decoration-none btnDelete']) . '</button>';
                    }
                }
                return $html . '</div>';
            }
        ],
    ],
]);
?>

<?php Pjax::end(); ?>
<div class="modal fade text-left " id="modalPapaer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" id="modalPapaerContent">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel16">แบบฟอร์ม ขออนุมัติการลา</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1">
                <div id="modalContent" class="m-2"></div>
            </div>
        </div>
    </div>
</div>

