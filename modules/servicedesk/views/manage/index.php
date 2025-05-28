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
Pjax::begin(['id' => 'gServiceView', 'timeout' => false, 'enablePushState' => false]); //
$this->registerJs($js, $this::POS_READY);
?>

<div class="row d-none1">
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card bg-danger-light">
            <div class="card-body1 m-2">
                <div class="media ai-icon">
                    <span class="me-3 bgl-danger text-danger">
                        <i class="fa-regular fa-clipboard fa-lg"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">แจ้งปัญหาวันนี้</p>
                        <h3 class="mb-0"><?= @$data['cc'] ?></h3>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card bg-warning-light">
            <div class="card-body1 m-2">
                <div class="media ai-icon">
                    <span class="me-3 bgl-warning text-warning">
                        <i class="fa-regular fa-clipboard fa-lg"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">รอดำเนินการ</p>
                        <h3 class="mb-0"><?= @$data['cc_wait'] ?></h3>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card bg-primary-light">
            <div class="card-body1 m-2">
                <div class="media">
                    <span class="me-3">
                        <i class="fa-solid fa-list fa-lg"></i>
                    </span>
                    <div class="media-body text-white">
                        <p class="mb-1 font-weight-bolder">กำลังดำเนินการ</p>
                        <h3 class="text-white"><?= @$data['3'] ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card bg-primary-light">
            <div class="card-body1 m-2">
                <div class="media">
                    <span class="me-3">
                        <i class="fa-solid fa-list fa-lg"></i>
                    </span>
                    <div class="media-body text-white">
                        <p class="mb-1 font-weight-bolder"></p>
                        <h3 class="text-white"><?= @$data['bsn_today'] ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    /*
      'panelTemplate' => '<div class="">
      {panelBefore}
      {items}
      {panelAfter}
      {panelFooter}
      </div>',
     *
     */
    'responsiveWrap' => FALSE,
    'striped' => FALSE,
    'hover' => FALSE,
    'bordered' => FALSE,
    'condensed' => FALSE,
    'export' => false,
    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none d-none'],
    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'vAlign' => 'top',
        ],
        [
            'label' => 'เลขที่แจ้ง',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'font-weight-bold small'],
            'attribute' => 'service_list_code',
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
            'attribute' => 'service_problem_id',
            'format' => 'raw',
            'value' => function ($model) {
                return '<span class="badge badge-sm badge-outline-warning">' . $model->serviceProblem->service_problem_name . '</span>';
            }
        ],
        [
            'label' => 'หัวข้อปัญหา',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            //'contentOptions' => ['class' => 'font-weight-bold small'],
            'vAlign' => 'top',
            'attribute' => 'service_list_issue',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a("<b>{$model->service_list_code} {$model->service_list_issue}</b><br><small>{$model->asset->fullname} {$model->service_list_comment} </small>", 'javascript:;', [
                    'class' => 'btnUpdate',
                    'data' => ['id' => $model->service_list_id]
                ]);
            }
        ],
        [
            'label' => 'ความสำคัญ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'visible' => 0,
            'vAlign' => 'top',
            'format' => 'raw',
            'hAlign' => 'right',
            'attribute' => 'service_urgency_id',
            'value' => function ($model) {
                return '<span class="badge badge-sm badge-' . $model->serviceUrgency->service_urgency_color . '">' . $model->serviceUrgency->service_urgency_name . '</span>';
            }
        ],
        [
            'label' => 'หมายเหตุ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'service_list_comment',
            'format' => 'raw',
            'visible' => 0,
        ],
        [
            'label' => 'วันที่แจ้ง',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'service_list_date',
            'format' => 'raw',
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->service_list_date, 'F', true);
            }
        ],
        [
            'label' => 'ชื่อผู้แจ้ง',
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
            'label' => 'สถานที่เกิดเหตุ',
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
            'label' => 'วันที่เริ่มดำเนินการ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'service_list_date_accept',
            'format' => 'raw',
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->service_list_date_accept, 'F', true);
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
            'label' => 'วันที่ซ่อมเสร็จ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'service_list_date_finish',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->service_list_date_finish, 'F', true);
            }
        ],
    ],
]);
?>
<?PHP Pjax::end(); ?>
<!-- Modal -->
<div class="modal fade  bg-success-light" id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font-weight-bold">แจ้งปัญหา (Service desk)</h3>
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
