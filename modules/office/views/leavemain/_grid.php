<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
//use app\modules\edocument\components\Ccomponent as CC;
use app\components\Ccomponent;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$this->title = 'ระบบการลาออนไลน์';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(['id' => 'mainLeave', 'timeout' => false, 'enablePushState' => false]);
$url = Url::to(['operate']);
$js = <<<JS
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
JS;
$this->registerJs($js, $this::POS_READY);
?>

<div class="card mt-3">
    <div class="card-content m-2">
        <div class="row">
            <div class="col-md-12">
                <?=
                GridView::widget([
                    'id' => 'gLeaveView',
                    'dataProvider' => $dataProvider,
                    #'filterModel' => $searchModel,
                    'panel' => [
                        'type' => 'default',
                        'heading' => 'รายการลา',
                        'before' => '', //$this->render('_search', ['model' => $dataProvider]),
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
                        [
                            'headerOptions' => ['class' => 'font-weight-bold'],
                            'label' => 'ชื่อ-สกุล',
                            'attribute' => 'emps.employee_fullname',
                            'format' => 'raw',
                            'noWrap' => TRUE,
                            #'width' => '5%',
                            #'hAlign' => 'right',
                            'vAlign' => 'middle',
                            'value' => function ($model) {
                                return '<b class="text-primary">' . $model->emps->employee_fullname . '</b><br><small>' .
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
                                return ' <div class="rows justify-content-between1">'
                                . '<div class="col-md-6">'
                                . '<div class="label label-primary">' . $model->leaveType->leave_type_name . '</div>'
                                . '</div>'
                                . '<div class="col-md-6">'
                                . Ccomponent::getThaiDate($model->leave_start, 'S') . '- ' . Ccomponent::getThaiDate($model->leave_end, 'S')
                                . '</div></div>';
                            }
                        ],
                        [
                            'headerOptions' => ['class' => 'font-weight-bold'],
                            'label' => 'จำนวนวัน',
                            'format' => 'raw',
                            'width' => '5%',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'value' => function ($model) {
                                $datetime2 = new Datetime($model->leave_start);
                                $datetime1 = new Datetime($model->leave_end);
                                //$interval = $datetime1->diff($datetime2)->days;
                                $interval = Ccomponent::getWeekdayDifference($datetime2, $datetime1);
                                return '<div class="label label-primary"><b>' . ($interval) . '</b></div>';
                            }
                        ],
                        [
                            'headerOptions' => ['class' => 'font-weight-bold'],
                            #'label' => '',
                            'attribute' => 'leave_create',
                            'format' => 'raw',
                            'noWrap' => TRUE,
                            'width' => '5%',
                            'hAlign' => 'right',
                            'vAlign' => 'middle',
                            'value' => function ($model) {
                                return Ccomponent::getThaiDate($model->leave_create, 'S', 1);
                            }
                        ],
                        [
                            'headerOptions' => ['class' => 'font-weight-bold'],
                            'label' => 'สถานะ',
                            'attribute' => 'leaveStatus.leave_status_name',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'format' => 'raw',
                            'visible' => 1,
                            'value' => function ($model) {
                                $status = $model->leaveStatus;
                                return '<div class="badge rounded-pill bg-' . $status->leave_status_color . '">' . $status->leave_status_name . '</div>';
                            }
                        ],
                        [
                            'label' => 'ดำเนินการ',
                            'format' => 'raw',
                            'noWrap' => TRUE,
                            'width' => '3%',
                            #'hAlign' => 'right',
                            #'vAlign' => 'middle',
                            'value' => function ($model) {
                                $status = $model->leaveStatus;
                                return $html = '<div class="btn-group btn-block btn-group-toggle btn-group-sm" data-toggle="buttons">
  <label class="btn btn-' . $status->leave_status_color . ' btn-sm active">
   ' . Html::a('ดำเนินการ',
                                        'javascript:;',
                                        [
                                            //'onclick' => "embedLink('" . yii\helpers\Url::to(['signature', 'id' => $model->leave_id]) . "');",
                                            //'target' => '_blank',
                                            //'data-toggle' => "modal",
                                            //'data-target' => "#modalSignature",
                                            'data' => ['pjax' => 1, 'pid' => $model->leave_id],
                                            'class' => ($model->leave_status_id < 3 ? 'btnLink text-white' : 'text-white') . ' btnOper',
                                        ]) . '
  </label>
</div>';
                                ;
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
                                $html = '<div class="btn-group">
<button class="btn btn-primary btn-sm active">
   ' . Html::a('<i class="fas fa-file-pdf fa-sm"></i> เอกสาร', ['viewdoc', 'id' => $model->leave_id], ['target' => '_blank', 'data' => ['pjax' => 0], 'class' => 'text-white text-decoration-none']) . '
  </button>
</div>';
                                return $html;
                            }
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modalPapaer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" id="modalPapaerContent">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel16">แบบฟอร์ม ขออนุมัติการลา</h3>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body1">
                <div id="modalContent" class="m-2"></div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
