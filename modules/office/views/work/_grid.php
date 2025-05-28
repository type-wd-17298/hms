<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
//use app\modules\edocument\components\Ccomponent as CC;
use app\components\Ccomponent;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$this->title = 'ระบบบริหารตารางเวร';
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
                            'label' => 'ประเภทการลา/วันที่ลา',
                            'attribute' => 'work_grid_change_date_a',
                            'format' => 'raw',
                            'noWrap' => TRUE,
                            #'width' => '5%',
                            #'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'value' => function ($model) {
                                return ' <div class="rows justify-content-between1">'
                                . '<div class="col-md-6">'
                                . '<div class="label label-primary">' . $model->workType->work_type_name . '</div>'
                                . '</div>'
                                . '<div class="col-md-6">'
                                . Ccomponent::getThaiDate($model->work_grid_change_date_a, 'S') . '- ' . Ccomponent::getThaiDate($model->work_grid_change_date_b, 'S')
                                . '</div></div>';
                            }
                        ],
                        [
                            'headerOptions' => ['class' => 'font-weight-bold'],
                            #'label' => '',
                            'attribute' => 'work_grid_change_date',
                            'format' => 'raw',
                            'noWrap' => TRUE,
                            'width' => '5%',
                            'hAlign' => 'right',
                            'vAlign' => 'middle',
                            'value' => function ($model) {
                                return Ccomponent::getThaiDate($model->work_grid_change_date, 'S', 1);
                            }
                        ],
                        [
                            'headerOptions' => ['class' => 'font-weight-bold'],
                            'label' => 'สถานะ',
                            'attribute' => 'workStatus.work_status_name',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'format' => 'raw',
                            'visible' => 1,
                            'value' => function ($model) {
                                $status = $model->workStatus;
                                return '<div class="badge rounded-pill bg-' . $status->work_status_color . '">' . $status->work_status_name . '</div>';
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
                                $status = $model->workStatus;
                                return $html = '<div class="btn-group btn-block btn-group-toggle btn-group-sm" data-toggle="buttons">
  <label class="btn btn-' . $status->work_status_color . ' btn-sm active">
   ' . Html::a('ดำเนินการ',
                                        'javascript:;',
                                        [
                                            //'onclick' => "embedLink('" . yii\helpers\Url::to(['signature', 'id' => $model->leave_id]) . "');",
                                            //'target' => '_blank',
                                            //'data-toggle' => "modal",
                                            //'data-target' => "#modalSignature",
                                            'data' => ['pjax' => 1, 'pid' => $model->work_grid_change_id],
                                            'class' => ($model->work_status_id < 3 ? 'btnLink text-white' : 'text-white') . ' btnOper',
                                        ]) . '
  </label>
</div>';
                                ;
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
