<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;

$this->title = 'ระบบจองห้องประชุม';
$this->params['breadcrumbs'][] = $this->title;
Pjax::begin(['id' => 'mainLeave', 'timeout' => false, 'enablePushState' => false]);
$url = Url::to(['calendar']);
$urlCreate = Url::to(['create']);
$urlDelete = Url::to(['delete']);
$urlBook = Url::to(['update']);
$js = <<<JS

$(".bookingRoomUpdate").click(function(event){
        $('#modalRoom').modal('show');
         $.get("{$urlBook}",{id:$(this).data('id')}, function(data) {
                 $("#modalRoomContents").html(data);
         });
});

$('.pjax-delete-link').on('click', function(e) {
            e.preventDefault();
            var result = confirm('ยืนยันการยกเลิกจองห้องประชุม หรือไม่?');
            if(result) {
                $.ajax({
                    url: '{$urlDelete}?id='+$(this).data('id'),
                    type: 'post',
                    error: function(xhr, status, error) {
                        alert('There was an error with your request.' + xhr.responseText);
                    }
                }).done(function(data) {
                    $.get("{$url}",{}, function(data) {
                          $("#calendar-body").html(data);
                    });
                     $("#gBookView").yiiGridView('applyFilter');
                });
            }
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
<?= Html::tag('h3', 'วันที่ ' . Ccomponent::getThaiDate($date, 'L'), ['class' => ' text-center']) ?>
<?=

GridView::widget([
    'id' => 'gBookView',
    'dataProvider' => $dataProvider,
    'panel' => [
        // 'type' => 'default',
        'heading' => '',
        //'before' => $this->render('_search', ['model' => $dataProvider]),
        'footer' => FALSE,
    ],
    'panelTemplate' => '<div class="">
          {panelBefore}
          {items}
          {panelAfter}
          {panelFooter}
          </div>',
    'responsiveWrap' => FALSE,
    'striped' => TRUE,
    'hover' => FALSE,
    'bordered' => FALSE,
    'condensed' => FALSE,
    'export' => FALSE,
//    'export' => [
//        'header' => 'ส่งออกรายงานสรุปวันลา'
//    ],
    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none d-none'],
    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
    /*
      'beforeHeader' => [
      [
      'columns' => [
      //['content' => '', 'options' => ['colspan' => 3, 'class' => 'font-weight-bold text-center  bg-primary text-white']],
      ['content' => 'รายงานสรุปวันลา', 'options' => ['colspan' => 12, 'class' => 'font-weight-bold text-center bg-primary text-white']],
      // ['content' => 'ลาอื่นๆ', 'options' => ['colspan' => 6, 'class' => 'font-weight-bold text-center bg-dark text-white']],
      ],
      #'options' => ['class' => 'skip-export'] // remove this row from export
      ]
      ],
     */
    'columns' => [
        // ['class' => 'kartik\grid\SerialColumn'],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'หน่วยงาน',
            'attribute' => 'rooms.bk_meetingroom_name',
            'format' => 'raw',
            'contentOptions' => ['class' => 'text-left font-weight-bold'],
            //'vAlign' => 'middle',
            'hAlign' => 'left',
            'group' => true, // enable grouping,
            'groupedRow' => true,
            'visible' => 1,
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'font-weight-bold'],
            'label' => 'รายการประชุม',
            'attribute' => 'subject',
            'format' => 'raw',
            'width' => '35%',
            'vAlign' => 'top',
            'visible' => 1,
            'value' => function ($model) {
                return $model->subject . ($model->detail == '' ?'': '<br>' . Html::tag('div', $model->detail, ['class' => 'small']))
                . ($model->employee_dep_id == '' ? '' : '' . Html::tag('div', '(' . $model->dep->employee_dep_label . ')', ['class' => 'small font-weight-bold']) );
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ช่วงเวลา',
            'attribute' => 'date_event_timein',
            'hAlign' => 'center',
            'vAlign' => 'top',
            'format' => 'raw',
            'noWrap' => TRUE,
            //'contentOptions' => ['class' => 'small'],
            //'vAlign' => 'middle',
            'visible' => 1,
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->date_event_timein, 'F', true) . ' <br> ' . Ccomponent::getThaiDate($model->date_event_timeout, 'F', true);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ผู้เข้าร่วม',
            'attribute' => 'bk_number_attendee',
            'hAlign' => 'center',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'visible' => 1,
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ผู้จองห้อง',
            'attribute' => 'employee_id',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'visible' => 1,
            'value' => function ($model) {
                return @$model->emp->employee_fullname;
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เวลาทำรายการ',
            'attribute' => 'create_at',
            'format' => 'raw',
            'noWrap' => TRUE,
            // 'width' => '5%',
            'hAlign' => 'right',
            'vAlign' => 'top',
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->create_at, 'S', 1);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => '#',
            // 'attribute' => '',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'value' => function ($model) {
                $html = '<div class="btn-group">';
                $html .= '<button class="btn btn-primary btn-xxs bookingRoomUpdate" data-id="' . $model->id . '"> ' . Html::a('<i class="fa-solid fa-pen-to-square"></i> แก้ไขรายการ', 'javascript:;', ['class' => 'text-white text-decoration-none']) . '</button>';
                $html .= '<button class="btn btn-danger btn-xxs pjax-delete-link" data-id="' . $model->id . '">' . Html::a('<i class="fa-solid fa-ban"></i> ยกเลิกการจอง', false, [
                            'class' => 'text-white text-decoration-none btnUpdate1']) . '</button>';
                if ((\Yii::$app->user->can('SuperAdmin')) || (\Yii::$app->user->can('MeetingRoom')) || ($model->employee_id == Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id))
                    return $html . '</div>';
            }
        ],
    ],
]);
?>
<?php Pjax::end(); ?>