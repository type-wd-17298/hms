<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;

//use app\modules\edocument\components\Ccomponent as CC;
//use app\components\Ccomponent;
//use app\components\Cdata;
//use yii\helpers\ArrayHelper;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$this->title = 'ระบบการลาออนไลน์';
$this->params['breadcrumbs'][] = $this->title;
$userID = $user->employee_id;
Pjax::begin(['id' => 'mainLeave', 'timeout' => false, 'enablePushState' => false]);
$url = Url::to(['operate']);
$urlCreate = Url::to(['create']);
$urlLeave = Url::to(['history']);
$urlDelete = '';
$js = <<<JS
$(".btnCreateLink").click(function(event){
      window.location.href="{$urlCreate}";
});
$(".btnModal").click(function(){
       var id = $(this).data('id');
       $("#modalContent").html('');
       $('#modalLeave').modal('show');
        $.get("{$urlLeave}",{id:$(this).data("pid")}, function(data) {
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
<div class="alert alert-warning left-icon-big alert-dismissible fade show m-2 d-none">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"><span><i class="mdi mdi-btn-close"></i></span>
    </button>
    <div class="media">
        <div class="alert-left-icon-big">
            <span><i class="fa-solid fa-circle-info"></i></span>
        </div>
        <div class="media-body">
            <h5 class="mt-1 mb-2 font-weight-bold">แจ้งให้ทราบ</h5>
            <p class="mb-0">ข้อมูลการลาที่แสดงผลนี้ถูกนำเข้าจากระบบ MyOffice อาจมีข้อมูลบางส่วนไม่ถูกต้อง ท่านสามารถติดต่อที่งานการเจ้าหน้าที่ของโรงพยาบาล</p>
            <p class="mb-0">ระบบนับข้อมูลการลาจากการนำเข้าข้อมูลระบบ MyOffice เฉพาะข้อมูลที่ได้รับการอนุญาตแล้วเท่านั้น</p>
        </div>
    </div>
</div>
<?=
GridView::widget([
    'id' => 'gLeaveView',
    'dataProvider' => $dataProvider,
    'panel' => [
        //'type' => 'default',
        'heading' => 'รายงานสรุปวันลา',
        'before' => $this->render('_search_list', ['model' => $dataProvider]),
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
    'striped' => true,
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
     *
     */
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'หน่วยงาน',
            'attribute' => 'dep.employee_dep_label',
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
            'label' => 'ชื่อ-นามสกุล',
            'attribute' => 'employee_fullname',
            'vAlign' => 'middle',
            'noWrap' => TRUE,
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                $topic = Html::tag('b', ' ' . $model->employee_fullname, ['class' => 'text-primary'])
                        . '<br>' . Html::tag('small', @$model->position->employee_position_name, ['class' => '']);
                return Html::a($topic, 'javascript:;', [
                    'class' => 'btnModal',
                    'data' => [
                        'pid' => $model->employee_id,
                    ],]);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'small'],
            'label' => 'ประเภท',
            'attribute' => 'empType.employee_type_name',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'vAlign' => 'middle',
            'visible' => 1,
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เหลือสะสม',
            'attribute' => 'empLeave.cumulative',
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
        // 'value' => function ($model) {
        //   return @$model->empLeave->cumulative;
        // }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'วันลาปีนี้',
            //'attribute' => 'claim',
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                return @$model->empLeave->claim;
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ยกเลิกวันลา',
            //'attribute' => 'cancel_leave',
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                return @$model->leave->accruedCancel;
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ลาพักผ่อน',
            //'attribute' => 'vacation_leave',
            'contentOptions' => ['class' => 'font-weight-bold'],
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                return @$model->leave->vacationSS;
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'คงเหลือ',
            //'attribute' => 'accrued',
            'contentOptions' => ['class' => 'table-light font-weight-bold'],
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                $ss = @$model->empLeave->accrued;
                if ($ss < 1) {
                    return @Html::tag('div', $ss, ['class' => 'text-danger']);
                } else {
                    return @$ss;
                }
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ลากิจ',
            //'attribute' => 'personal_leave',
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                if ($model->leave)
                    return $model->leave->getLeaveSS('personal_leave');
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ลาป่วย',
            //'attribute' => 'sick_leave',
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                if ($model->leave)
                    return @$model->leave->getLeaveSS('sick_leave');
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ลาคลอดบุตร',
            //'attribute' => 'maternity_leave',
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                if ($model->leave)
                    return @$model->leave->getLeaveSS('maternity_leave');
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => '#',
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                return Html::tag('div', 'สถิติการลา', ['class' => 'btn btn-danger btn-xs btnModal', 'data-pid' => $model->employee_id]);
            }
        ],
    ],
]);
?>

<?php Pjax::end(); ?>
<div class="modal fade text-left " id="modalLeave" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" id="modalPapaerContent">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel16">สถิติการลา</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1 ">
                <div id="modalContent" class="m-2"></div>
            </div>
        </div>
    </div>
</div>

