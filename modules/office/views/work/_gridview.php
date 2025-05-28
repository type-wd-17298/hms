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
            title: 'ยืนยันการลบเอกสารนี้หรือไม่?',
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
            'attribute' => 'work_status_id',
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
            'attribute' => 'work_status_id',
            'vAlign' => 'middle',
            'width' => '2%',
            'vAlign' => 'top',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {

                $status = @$model->workStatus;
                if (!empty($model->lastProcess->receiver->employee_fullname))
                    $who = '<br><small>(' . @$model->lastProcess->receiver->employee_fullname . ')</small>';

                if (!empty($model->lastProcess->staff->employee_fullname) && $model->work_status_id == 'L08')
                    $who = '<br><small>(' . @$model->lastProcess->staff->employee_fullname . ')</small>';

                if (in_array($model->work_status_id, ['L10'])) {
                    return '<span class="btn-block badge badge-' . @$status->work_status_color . '"><i class="fa-solid fa-sheet-plastic"></i> ' . @$status->work_status_name . @$who . '</span>';
                } else {
                    return '<span class="btn-block badge badge-' . @$status->work_status_color . '"><i class="fa-solid fa-sheet-plastic"></i> ' . @$status->work_status_name . @$who . '</span>';
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
                $status = $model->workStatus;
                if (@$model->lastProcess->process_receiver == $user->employee_id || @$model->emp_staff_a == $user->employee_id || \Yii::$app->user->can('SuperAdmin') || (\Yii::$app->user->can('HRsAdmin') && $model->work_status_id == 'L99')) {
                    if (!in_array($model->work_status_id, ['L10'])) {
                        $operation = $status->work_status_operation;
                        //if (@$model->employee_id == $user->employee_id && in_array($model->leave_status_id, ['L01']))
                        //$operation = 'ยกเลิกการรับมอบ';
                        return $html = @Html::a($operation, 'javascript:;',
                                        [
                                            'data' => ['pjax' => 1, 'pid' => @$model->work_grid_change_id],
                                            'class' => "btnLink btnOper btn  btn-xs  btn-outline-primary   btn-block",
                        ]);
                    }
                }

                if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin')) {
                    return $html = @Html::a($status->work_status_operation, 'javascript:;',
                                    [
                                        'data' => ['pjax' => 1, 'pid' => @$model->work_grid_change_id],
                                        'class' => "btnLink btnOper btn  btn-xs btn-outline-dark  btn-block",
                    ]);
                }
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'รูป',
            'attribute' => 'emp_staff_a',
            'format' => 'raw',
            'width' => '50px',
            'visible' => 1,
            'noWrap' => TRUE,
            'value' => function ($model) {
                $userProfile = @Cdata::getDataUserAccount($model->emps->employee_cid);
                if ($model->emp_staff_a)
                    return @Html::img($userProfile['pictureUrl'], ['class' => 'img-thumbnail1 img-responsive1', 'width' => 40]);
            },
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ชื่อ-สกุล',
            'attribute' => 'emp_staff_a',
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
            'label' => 'แบบการแลกเวร',
            'attribute' => 'workChange.work_change_name',
            'format' => 'raw',
            //'width' => '5%',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'visible' => 1,
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'วันที่แลกเวร',
            'attribute' => 'work_grid_change_date_a',
            'format' => 'raw',
            'noWrap' => TRUE,
            //'width' => '5%',
            //'hAlign' => 'center',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return $model->workType->work_grid_type_name . '<br><b>' . Ccomponent::getThaiDate($model->work_grid_change_date_a, 'S', 0) . '</b>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'รูป',
            'attribute' => 'emp_staff_b',
            'format' => 'raw',
            'width' => '50px',
            'visible' => 1,
            'noWrap' => TRUE,
            'value' => function ($model) {
                $userProfile = Cdata::getDataUserAccount($model->emps2->employee_cid);
                return @Html::img($userProfile['pictureUrl'], ['class' => 'img-thumbnail1 img-responsive1', 'width' => 40]);
            },
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ผู้รับแลกเวร',
            'attribute' => 'emp_staff_b',
            'format' => 'raw',
            'noWrap' => TRUE,
            #'width' => '5%',
            #'hAlign' => 'right',
            'vAlign' => 'top',
            'value' => function ($model) {
                return '<b class="text-dark">' . $model->emps2->employee_fullname . '</b><br><small>' .
                $model->emps2->dep->employee_dep_label . '</small>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เวรใช้คืน',
            'attribute' => 'work_grid_change_date_b',
            'format' => 'raw',
            'noWrap' => TRUE,
            //'width' => '5%',
            //'hAlign' => 'center',
            'vAlign' => 'middle',
            'value' => function ($model) {
                if ($model->workType2->work_grid_type_id)
                    return $model->workType2->work_grid_type_name . '<br><b>' . Ccomponent::getThaiDate($model->work_grid_change_date_b, 'S', 0) . '</b>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'small'],
            'label' => 'วันที่เขียน',
            'attribute' => 'work_grid_change_date',
            'format' => 'raw',
            'noWrap' => TRUE,
            //'width' => '5%',
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->work_grid_change_date, 'S', 0);
            }
        ],
        [
            'label' => '',
            'format' => 'raw',
            #'vAlign' => 'middle',
            'width' => '1%',
            'noWrap' => TRUE,
            'hAlign' => 'center',
            'value' => function ($model) {
                $html = '<div class="btn-group btn-group-sm">';
                if (in_array($model->work_status_id, ['L10']) && $model->work_grid_type_id <> 9)
                    $html .= '<button class="btn btn-primary btn-sm ">' . Html::a('<i class="fa-solid fa-hand"></i>', ['cancel', 'id' => $model->work_grid_change_id], ['class' => 'text-white text-decoration-none btnUpdate1']) . '</button>';

                $html .= '<button class="btn btn-xs btn-secondary active">' . Html::a('<i class="fa-regular fa-file-pdf"></i>', ['viewdoc', 'id' => $model->work_grid_change_id], ['target' => '_blank', 'data' => ['pjax' => 0], 'class' => 'text-white text-decoration-none']) . '</button>';
                if (in_array($model->work_status_id, ['L00', 'L08']) && !(\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin'))) {
                    if ($model->work_grid_type_id <> 9)
                        $html .= '<button class="btn btn-secondary btn-sm active">' . Html::a('<i class="fa-solid fa-marker"></i>', ['update', 'id' => $model->work_grid_change_id], ['class' => 'text-white text-decoration-none btnUpdate1']) . '</button>';
                    $html .= '<button class="btn btn-danger btn-sm ">' . Html::a('<i class="fa-solid fa-trash-can"></i>', false, ['data-id' => $model->work_grid_change_id, 'class' => 'text-white text-decoration-none btnDelete']) . '</button>';
                } else {
                    if ((\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin'))) {
                        if ($model->work_grid_type_id <> 9)
                            $html .= '<button class="btn btn-secondary btn-sm active">' . Html::a('<i class="fa-solid fa-marker"></i>', ['update', 'id' => $model->work_grid_change_id], ['class' => 'text-white text-decoration-none btnUpdate1']) . '</button>';
                        $html .= '<button class="btn btn-danger btn-sm ">' . Html::a('<i class="fa-solid fa-trash-can"></i>', false, ['data-id' => $model->work_grid_change_id, 'class' => 'text-white text-decoration-none btnDelete']) . '</button>';
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
                <h3 class="modal-title" id="myModalLabel16">แบบฟอร์มขอแลกเวร/โอนเวร/แลกวันหยุด</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1">
                <div id="modalContent" class="m-2"></div>
            </div>
        </div>
    </div>
</div>

