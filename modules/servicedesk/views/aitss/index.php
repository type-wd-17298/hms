<?PHP

use kartik\grid\GridView;
use app\components\Ccomponent;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use miloschuman\highcharts\Highcharts;
use app\components\Cdata;

$mode = '';
$url = Url::to(['create']);
$url2 = Url::to(['update']);

$css = '.modal-xl {max-width: 90% !important;}  .blink {  animation: blink-animation 2s steps(5, start) infinite; -webkit-animation: blink-animation 2s steps(5, start) infinite; }';
$this->registerCss($css);
$js = <<<JS

$(".btnCreate").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url}",{}, function(data) {
           $("#modalContents").html(data);
       });
});
$(".btnUpdate").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url2}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
});
JS;
?>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-block">
                <h4 class="card-title mb-2">ระบบการบันทึกกิจกรรมการทำงาน Activity Report</h4>
            </div>
            <div class="card-body pb-0">
                <?PHP
                Pjax::begin(['id' => 'gServiceView', 'timeout' => false, 'enablePushState' => false]); //
                $this->registerJs($js, $this::POS_READY);
                foreach ($data as $key => $value) {
                    @$sum += $value['cc'];
                }
                ?>
                <div class="row d-none1">
                    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
                        <div class="widget-stat card bg-active-light">
                            <div class="card-body1 m-2">
                                <div class="media ai-icon">
                                    <span class="me-3 bgl-danger text-danger">
                                        <i class="fa-regular fa-clipboard fa-lg"></i>
                                    </span>
                                    <div class="media-body">
                                        <p class="mb-1 font-weight-bold">จำนวนกิจกรรมทั้งหมด</p>
                                        <h3 class="mb-0"><?= number_format($sum, 0) ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?PHP foreach ($data as $key => $value) { ?>
                        <?PHP
                        $userProfile = Cdata::getDataUserAccount($value['employee_cid']);
                        ?>
                        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
                            <div class="widget-stat card bg-active-light">
                                <div class="card-body1 m-2">
                                    <div class="media ai-icon">
                                        <span class="mt-0 bgl-success text-success ">
                                            <?PHP echo @Html::img($userProfile['pictureUrl'], ['class' => 'img-thumbnail1 img-responsive', 'width' => 60]) ?>
                                        </span>
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <div class="h5 font-weight-bold"><?= $value['employee_fullname'] ?>
                                                        <br><small><?= $value['employee_position_name'] ?></small>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <p class="h3"><?= $value['cc'] ?></p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-auto">
                                                    <p class="mb-1 font-weight-bold">กิจกรรมวันนี้ <b><?= $value['cc_today'] ?></b></p>
                                                </div>
                                                <div class="col-auto">
                                                    <p class="mb-1 font-weight-bold">กำลังดำเนินการ <b><?= $value['cc_wait'] ?></b></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?PHP } ?>
                </div>
                <?=
                GridView::widget([
                    //'id' => 'gServiceView',
                    'dataProvider' => $dataProvider,
                    'panel' => [
                        'type' => '',
                        'heading' => '',
                        'before' => $this->render('_search', ['model' => $dataProvider]),
                    // 'footer' => FALSE,
                    ],
                    'panelTemplate' => '<div class="">
      {panelBefore}
      {items}
      {panelAfter}
      {panelFooter}
      </div>',
                    'responsiveWrap' => FALSE,
                    'striped' => FALSE,
                    'hover' => FALSE,
                    'bordered' => FALSE,
                    'condensed' => FALSE,
                    'export' => FALSE,
                    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none d-none'],
                    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
                    'columns' => [
                        [
                            'class' => 'kartik\grid\SerialColumn',
                            'vAlign' => 'top',
                        ],
                        [
                            'label' => 'เลขที่งาน',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'font-weight-bold small'],
                            'attribute' => 'staff_worklist_code',
                            'vAlign' => 'top',
                            'visible' => 0,
                        ],
                        [
                            'label' => 'สถานะ',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'width' => '50px',
                            'format' => 'raw',
                            'attribute' => 'service_status_id',
                            'value' => function ($model) {
                                return '<span class="badge light badge-' . $model->serviceStatus->service_status_color . '  btn-block">' . $model->serviceStatus->service_status_name . '</span>';
                            }
                        ],
                        [
                            'label' => 'ประเภทงาน',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'attribute' => 'worklist_group_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '<span class="badge badge-sm badge-outline-warning">' . $model->staffWorklistGroup->worklist_group_name . '</span>';
                            }
                        ],
                        [
                            'label' => 'กิจกรรมที่ดำเนินงาน',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            //'contentOptions' => ['class' => 'font-weight-bold small'],
                            'vAlign' => 'top',
                            'attribute' => 'staff_worklist_issue',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a("<b>{$model->staff_worklist_code} {$model->staff_worklist_issue}</b><br><small>{$model->asset->fullname} {$model->staff_worklist_comment} </small>", 'javascript:;', [
                                    'class' => 'btnUpdate',
                                    'data' => ['id' => $model->staff_worklist_id]
                                ]);
                            }
                        ],
                        [
                            'label' => 'ดำเนินงานเมื่อ',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'attribute' => 'staff_worklist_date',
                            'format' => 'raw',
                            'visible' => 1,
                            'value' => function ($model) {
                                return Ccomponent::getThaiDate($model->staff_worklist_date, 'S');
                            }
                        ],
                        [
                            'label' => 'สำเร็จเมื่อ',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'attribute' => 'staff_worklist_date_finish',
                            'format' => 'raw',
                            'visible' => 1,
                            'value' => function ($model) {
                                return Ccomponent::getThaiDate($model->staff_worklist_date_finish, 'S');
                            }
                        ],
                        [
                            'label' => 'หมายเหตุ',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'attribute' => 'staff_worklist_comment',
                            'format' => 'raw',
                            'visible' => 0,
                        ],
                        [
                            'label' => 'ชื่อผู้ดำเนินการ',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'attribute' => 'employee_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->emp->employee_fullname;
                            }
                        ],
                        [
                            'label' => 'หน่วยงานที่เกี่ยวข้อง',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'attribute' => 'department_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->dep->employee_dep_label;
                            }
                        ],
                        [
                            'label' => 'ผู้บันทึก',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'visible' => 0,
                            'attribute' => 'employee_id_staff',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->empStaff->employee_fullname;
                            }
                        ],
                        [
                            'label' => 'IT',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'attribute' => 'employee_id_operation',
                            'format' => 'raw',
                            'visible' => 0,
                            'value' => function ($model) {
                                return $model->empOper->employee_fullname;
                            }
                        ],
                        [
                            'headerOptions' => ['class' => 'font-weight-bold'],
                            'label' => 'IT ผู้ดำเนินการ',
                            'attribute' => 'employee_id_operation',
                            'format' => 'raw',
                            'width' => '50px',
                            'visible' => 1,
                            'noWrap' => TRUE,
                            'value' => function ($model) {
                                $userProfile = Cdata::getDataUserAccount($model->empOper->employee_cid);
                                if (!empty($model->employee_id_operation))
                                    return @Html::img($userProfile['pictureUrl'], ['class' => 'img-thumbnail1 img-responsive', 'width' => 30]);
                            },
                        ],
                        [
                            'label' => 'วันเวลาที่รับ Case',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'attribute' => 'staff_worklist_date',
                            'format' => 'raw',
                            'visible' => 0,
                            'value' => function ($model) {
                                return Ccomponent::getThaiDate($model->staff_worklist_date, 'F', true);
                            }
                        ],
                    ],
                ]);
                ?>
                <?PHP Pjax::end(); ?>
                <!-- Modal -->


                <div class="d-flex justify-content-between py-4 border-bottom flex-wrap">
                    <span>Job ID: #8976542</span>
                    <span>Posted By <strong>Company</strong>/ 12-01-2023</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade  bg-success-light" id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font-weight-bold">ระบบการบันทึกกิจกรรมการทำงาน</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1">
                <div id="modalContents" class="m-2"></div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>