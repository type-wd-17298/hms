<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;
use app\modules\office\models\PaperlessProcessList;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
//ตรวจสอบ topic
$this->params['mqttFuncCheck'] = <<<JS
if(topic == 'hms/service/paper/update/BNN'){
      //$.pjax.reload({container: '#pjPaperGrid', async: false});
      $.pjax.reload({container: '#pjPaperMonitor', async: false});
      //$("#modalForm").modal('hide');
      //$("#frmIndex").submit();
}
JS;
//ตรวจสอบ topic
$this->params['mqttSubTopics'] = <<<JS
        sub_topics('hms/service/paper/update/BNN');
JS;

$urlOperate = Url::to(['operate']);
$url = Url::to(['create']);
$url2 = Url::to(['update']);
$urlDelete = Url::to(['delete']);
$js = <<<JS

  $("#modalForm").on('hide.bs.modal', function(){
     //$("#frmIndex").submit();
  });

var barcode = '';
            var interval;
            document.addEventListener('keydown', function(evt) {
                if (interval)
                    clearInterval(interval);
                if (evt.code == 'Enter') {
                    if (barcode)
                        handleBarcode(barcode);
                    barcode = '';
                    return;
                }
                if (evt.key != 'Shift')
                    barcode += evt.key;
                interval = setInterval(() => barcode = '', 20);
            });
            function handleBarcode(scanned_barcode) {
                //alert(scanned_barcode);
                //document.querySelector('#last-barcode').innerHTML = scanned_barcode;
                if(scanned_barcode.length >= 17){
                    $("#modalContents").html('');
                    event.preventDefault();
                    $.get("{$urlOperate}",{id:scanned_barcode}, function(data) {
                        if(data.status=='false'){
                            alert(data.message);
                        }else{
                            $('#modalForm').modal('show');
                            $("#modalContents").html(data);
                        }
                    });
                }
            }

//$("[data-toggle=tooltip").tooltip();
$(".btnOperate").click(function(event){
       $("#refID").html($(this).data("id"));
       $("#modalContents").html('');
       event.preventDefault();
       $.get("{$urlOperate}",{id:$(this).data("id")}, function(data) {
            if(data.status=='false'){
                            alert(data.message);
                        }else{
                            $('#modalForm').modal('show');
                            $("#modalContents").html(data);
             }
       });
});

$(".btnPopup").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       event.preventDefault();
       $.get("{$url}", function(data) {
           $("#modalContents").html(data);
       });
});

$(".btnFilters").click(function(event){
       $("#refID").html($(this).data("id"));
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       event.preventDefault();
       $.get("{$url2}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
});
$(".widget-stat").hover(
   function () {
    $(this).addClass('bg-primary-light');
  },
  function () {
    $(this).removeClass('bg-primary-light');
  }
);

$(".btnDelete").click(function(event){
       var id = $(this).data('id');
       Swal.fire({
            icon: 'error',
            title: 'ยืนยันการลบนี้หรือไม่ '+id+' ?',
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
                                $.pjax.reload({container: '#pjPaperMonitor', async: false});
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
?>
<?PHP
Pjax::begin(['id' => 'pjPaperGrid', 'timeout' => false, 'enablePushState' => false]); //
$this->registerJs($js, $this::POS_READY);

echo GridView::widget([
    'id' => 'gview01',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table verticle-middle table-responsive-md'],
    'rowOptions' => function ($model, $key, $index, $widget) {
        //$query = PaperlessProcessList::find()->where(['paperless_id' => $model->paperless_id, 'processlist_id' => $model->paperless_lastprocess_id])->one();
        //$emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        if (\Yii::$app->user->can('SecretaryAdmin'))
            return ['class' => ''];
        //แสดงรายการที่เกี่ยวข้อง
        if ($model->pcheck == 1)
            return ['class' => 'bg-primary-light'];
        /*
          if (@$query->process_acknowledge == 0 && @$query->paperless_status_id <> 'FF') {
          $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
          $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
          if (@in_array($query->status->paperless_status_auth, array_keys($role)))
          return ['class' => 'bg-primary-light'];
          }
         *
         */
    },
    'panel' => [
        'heading' => '',
        'type' => '',
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
            'label' => 'ความเร่งด่วน',
            'attribute' => 'paperless_level',
            'format' => 'raw',
            'vAlign' => 'top',
            //'contentOptions' => ['class' => 'small'],
            'hAlign' => 'center',
            'visible' => 0,
            'value' => function ($model) {
                return Html::tag('div', '<i class="fa-solid fa-quote-left"></i> ' . @$model->level->paperless_level, ['class' => 'badge badge-outline-' . @$model->level->paperless_level_color]);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'วันที่ดำเนินการ',
            'attribute' => 'paperless_date',
            'format' => 'raw',
            'vAlign' => 'top',
            //'vAlign' => 'middle',
            //'width' => '1%',
            //'noWrap' => TRUE,
            'hAlign' => 'center',
            'visible' => 1,
            'value' => function ($model) {
                $date = Ccomponent::getThaiDate(($model['paperless_date']), 'S', 1);
                return '<span class="badge badge-primary"><i class="fa-solid fa-calendar-days"></i> ' . $date . '</span>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'สถานะ',
            'attribute' => 'paperless_status_id',
            'format' => 'raw',
            'vAlign' => 'top',
            'hAlign' => 'center',
            'visible' => 1,
            'value' => function ($model) {
                if (@$model['status']['paperless_status_code'] == 'F00') {
                    return'<span class="badge  badge-outline-danger"><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . '</span>';
                } elseif (in_array(@$model['status']['paperless_status_code'], ['F18', 'F19'])) {
                    $addon = '<br><small>' . @$model->lastProcess->receiver->employee_fullname . '</small>';
                    return '<span class="badge badge-outline-primary"><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . $addon . '</span>';
                } elseif (in_array(@$model['status']['paperless_status_code'], ['F03'])) {
                    $addon = '<br><small>' . @$model->lastProcess->receiver->employee_fullname . '</small>';
                    return '<span class="badge badge-outline-secondary"><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . $addon . '</span>';
                } elseif (in_array(@$model['status']['paperless_status_code'], ['F14', 'F13', 'F12', 'F11'])) {
                    if (empty($model->LastProcess->process_acknowledge_staff)) {
                        $addon = '<br><small>[รอเอกสาร]</small>';
                        return '<span class="badge  badge-warning light"><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . $addon . '</span>';
                    } else {
                        $addon = '<br><small>' . @$model->lastProcess->staff->employee_fullname . '</small>';
                        return '<span class="badge badge-outline-primary "><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . $addon . '</span>';
                    }
                    return '<span class="badge badge-outline-primary"><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . $addon . '</span>';
                } else if (@$model['status']['paperless_status_code'] == 'F17') {
                    if (empty($model->LastProcess->process_acknowledge_staff) && $model->paperless_direct > 0) {
                        $addon = '<br><small>[รอเอกสาร]</small>';
                        return '<span class="badge  badge-warning light"><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . $addon . '</span>';
                    } else {
                        $addon = '<br><small>' . @$model->lastProcess->staff->employee_fullname . '</small>';
                        return '<span class="badge  badge-warning light"><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . $addon . '</span>';
                    }
                } else if (@$model['status']['paperless_status_code'] == 'FF') {
                    return'<span class="badge badge-success"><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . '</span>';
                } else {
                    return'<span class="badge  badge-outline-secondary"><i class="fa-regular fa-clipboard"></i> ' . @$model['status']['paperless_status'] . '</span>';
                }
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'หน่วยงาน',
            'vAlign' => 'top',
            'attribute' => 'paperless_from',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'hAlign' => 'center',
            'visible' => 0,
            'value' => function ($model) {
                return $model->dep->employee_dep_label;
            }
        ],
        [
            'visible' => 1,
            'label' => '',
            'vAlign' => 'top',
            //'attribute' => 'docs_filename',
            'width' => '1%',
            'format' => 'raw',
            'noWrap' => TRUE,
            'hAlign' => 'right',
            'value' => function ($model) {
                $html = '<div class="btn-group btn-group-xs text-center">';
                $oper = 0;
                /*
                  $query = PaperlessProcessList::find()->where(['paperless_id' => $model->paperless_id, 'processlist_id' => $model->paperless_lastprocess_id])->one();
                  $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
                  //แสดงรายการที่เกี่ยวข้อง
                  if ((@$query->paperless_status_id <> 'FF' && @$model->LastProcess->process_receiver == $emp->employee_id))
                  $oper = 1;
                  if (@$query->process_acknowledge == 0 && @$query->paperless_status_id <> 'FF') {
                  $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
                  $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
                  if (@in_array($query->status->paperless_status_auth, array_keys($role)))
                  $oper = 1;
                  } */
                if ($model->pcheck == 1)
                    $oper = 1;
                if ($oper) { //ดำเนินการตามเอกสาร
                    $html .= Html::a('<i class="fas fa-file-signature"></i><br>' . @$model['status']['paperless_status_command'], 'javascript:;',
                                    [
                                        'class' => 'btn btn-primary btn-xs text-white text-decoration-none btnOperate',
                                        'data' => [
                                            'id' => @$model['paperless_id'],
                                        ]
                                    ]
                    );
                }
                if (\Yii::$app->user->can('ExecutiveUser')) {

                } else {
                    if (in_array(@$model['status']['paperless_status_code'], ['F00', 'F01'])) {
                        $html .= Html::a('<i class="fa-solid fa-file-contract"></i><br>เสนอ/แก้ไข', 'javascript:;',
                                        [
                                            'class' => 'btn btn-dark btn-xs text-white text-decoration-none btnFilters',
                                            'data' => [
                                                'id' => @$model['paperless_id'],
                                            ]
                                        ]
                        );

                        if (in_array(@$model['status']['paperless_status_code'], ['F01'])) {
                            $html .= Html::a('<i class="fa-solid fa-trash-arrow-up"></i><br>ลบ', 'javascript:;',
                                            [
                                                'class' => 'btn btn-danger btn-xs text-white text-decoration-none btnDelete',
                                                'data' => [
                                                    'id' => @$model['paperless_id'],
                                                ]
                                            ]
                            );
                        }
                    }
                }
                /*
                  if (in_array(@$model['status']['paperless_status_code'], ['FF'])) {
                  $html .= Html::a('<i class="fa-brands fa-buffer"></i><br>จัดเก็บ', 'javascript:;',
                  [
                  'class' => 'btn btn-dark btn-xs text-white text-decoration-none btnKeep',
                  'data' => [
                  'id' => @$model['paperless_id'],
                  ]
                  ]
                  );
                  }
                 */
                $html .= '</div>';

                return $html;
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เรื่อง',
            'vAlign' => 'top',
            'attribute' => 'paperless_topic',
            'noWrap' => TRUE,
            'format' => 'raw',
            //'vAlign' => 'middle',
            'visible' => 1,
            'value' => function ($model) {
                $direct = '';
                if (!empty($model->paperless_direct) && $model->paperless_direct > 0) {
                    if ($model->paperless_direct == 1)
                        $html = Html::tag('div', '<i class="fa-solid fa-file-signature animation-blink1"></i> ผ่านงานเลขา', ['class' => 'badge-xs badge light badge-danger']);
                    if ($model->paperless_direct == 2)
                        $html = Html::tag('div', '<i class="fa-solid fa-file-signature animation-blink1"></i> ผ่านงานการเงิน', ['class' => 'badge-xs badge light badge-danger']);
                    if ($model->paperless_direct == 3)
                        $html = Html::tag('div', '<i class="fa-solid fa-file-signature animation-blink1"></i> ผ่านงานบุคลากร', ['class' => 'badge-xs badge light badge-danger']);
                    if ($model->paperless_direct == 4)
                        $html = Html::tag('div', '<i class="fa-solid fa-file-signature animation-blink1"></i> ผ่านงานบัญชี', ['class' => 'badge-xs badge light badge-danger']);
                    if ($model->paperless_direct == 5)
                        $html = Html::tag('div', '<i class="fa-solid fa-file-signature animation-blink1"></i> ผ่านงานพัสดุ', ['class' => 'badge-xs badge light badge-danger']);
                    //$model->paperless_direct;
                } else {
                    $html = @$model->level->paperless_level == '' ? '' : Html::tag('span', '' . @$model->level->paperless_level, ['class' => 'badge light badge-xs  badge-' . @$model->level->paperless_level_color]);
                }
                $own = !empty($model->owner->employee_fullname) ? $model->owner->employee_fullname : $model->emp->employee_fullname;
                $paperless_number = (!empty($model->paperless_number) ? Yii::$app->params['dep_bookNumberPrefix'] . @substr($model->paperless_number, 8) : '');
                $topic = $html . ' ' . Html::tag('b', '' . $paperless_number, ['class' => 'text-primary'])
                        . ' ' . Html::tag('b', $model['paperless_topic'], ['class' => ''])
                        . '<br>' . @Html::tag('small', 'หน่วยงาน : ' . $model->dep->employee_dep_label, ['style' => ['font-size' => '12px']])
                        . @Html::tag('small', " ({$own})", ['class' => '']); // . date('H:m:s');

                return Html::a($topic, 'javascript:;', [
                    'class' => 'btnOperate',
                    'data' => [
                        'id' => @$model['paperless_id'],
                        'toggle' => 'tooltip',
                        'placement' => 'right',
                    ],]);
            }
        ],
        /*
          [
          'headerOptions' => ['class' => 'font-weight-bold'],
          'label' => 'ดำเนินการ',
          'attribute' => 'budgetyear',
          'format' => 'raw',
          //'contentOptions' => ['class' => 'small'],
          //'vAlign' => 'middle',
          'visible' => 0,
          'value' => function ($model) use ($var) {
          $html = '';
          foreach ($var['pStatus'] as $key => $value) {
          $html .= '<a class="dropdown-item btnOperate font-weight-bold" data-id="' . $model->paperless_id . '" href="javascript:void();" data-id="' . $value['paperless_operation_id'] . '">' . $value ['paperless_operation'] . '</a>';
          }
          return '<div class="dropdown custom-dropdown mb-0">
          <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown" aria-expanded="false">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g></svg>
          </div>
          <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">
          ' . $html . '
          <a class="dropdown-item text-danger" href="javascript:void();;">ยกเลิกรายการ</a>
          </div>
          </div>';
          }
          ],
         *
         */
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'noWrap' => TRUE,
            'label' => 'ใช้เวลา',
            'hAlign' => 'right',
            'value' => function ($model) {
                return $model->getUseTime();
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เจ้าของเรื่อง',
            // 'attribute' => 'whoRecord.fullname',
            //'noWrap' => TRUE,
            'format' => 'raw',
            //'vAlign' => 'middle',
            'visible' => 0,
            'value' => function ($model) {
                $own = !empty($model->owner->employee_fullname) ? $model->owner->employee_fullname : $model->emp->employee_fullname;
                return @Html::tag('div', $own, ['class' => 'badge badge-rounded badge-primary']); // . date('H:m:s');
            }
        ],
        [
            'visible' => 0,
            'label' => '#',
            'width' => '2%',
            'noWrap' => TRUE,
            'format' => 'raw',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'value' => function ($model) {
                $return = '<div class="input-group input-group-sm">'
                        . '<div class="input-group-prepend">' .
                        Html::a('เอกสาร', 'javascript:;', [
                            'class' => 'btn btn-dark btnFilters',
                            'data' => [
                                'id' => @$model['paperless_id'],
                                'toggle' => 'tooltip',
                                'placement' => 'right',
                            ],
                                //'title' => 'แสดงข้อมูล',
                        ])//
                        . Html::a('แก้ไข', 'javascript:;', ['class' => 'btn  btn-primary'])
                        #. Html::a('', ['update', 'id' => $model['person_id']], ['class' => 'btn  btn-primary active'])
                        . '</div></div>';
                return $return;
            },
        ],
    ],
]);
?>
<?PHP Pjax::end(); ?>
<!-- Modal -->
<div class="modal fade" id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">ดำเนินการทางเอกสาร <span id="refID"></span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1 m-3">
                <div id="modalContents"></div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
