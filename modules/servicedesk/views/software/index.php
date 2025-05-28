<?PHP

use kartik\grid\GridView;
use app\components\Ccomponent;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use miloschuman\highcharts\Highcharts;

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
                        <i class="fa-solid fa-microchip fa-lg"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">ซอฟต์แวร์</p>
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
//        [
//            'class' => 'kartik\grid\SerialColumn',
//            'vAlign' => 'top',
//        ],
        [
            'label' => 'เลขทะเบียน',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'font-weight-bold small'],
            'attribute' => 'software_list_id',
            'vAlign' => 'top',
        ],
        [
            'label' => 'ประเภทซอฟต์แวร์',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'software_type_id',
            'format' => 'raw',
            'value' => function ($model) {
                return '<span class="badge  badge-outline-danger">' . $model->softwareType->software_type_name . '</span>';
            }
        ],
        [
            'label' => 'ชื่อซอฟต์แวร์',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            // 'contentOptions' => ['class' => 'font-weight-bold small'],
            'vAlign' => 'top',
            'attribute' => 'software_list_name',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a('<b>' . $model->software_list_name . '</b>' . ($model->software_list_vender <> "" ? '<br><small>' . $model->software_list_vender . '</small>' : ''), 'javascript:;', [
                    'class' => 'btnUpdate',
                    'data' => ['id' => $model->software_list_id]
                ]);
            }
        ],
        [
            'label' => 'รายละเอียด',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'software_list_detail',
            'format' => 'raw',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'software_list_regisdate',
            'format' => 'raw',
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->software_list_regisdate, 'F', true);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'software_list_date_expire',
            'format' => 'raw',
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->software_list_date_expire, 'F', true);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'hAlign' => 'right',
            'attribute' => 'software_list_license',
            'format' => ['decimal', 2],
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'hAlign' => 'right',
            'attribute' => 'software_list_license_amount',
            'format' => ['decimal', 2],
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'hAlign' => 'right',
            'attribute' => 'software_list_ma',
            'format' => ['decimal', 2],
        ],
        [
            'label' => 'ชื่อประสาน',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'employee_id',
            'format' => 'raw',
            'visible' => 0,
            'value' => function ($model) {
                return $model->emp->employee_fullname;
            }
        ],
        [
            'label' => 'แผนก',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'department_id',
            'format' => 'raw',
            'visible' => 0,
            'value' => function ($model) {
                return $model->dep->employee_dep_label;
            }
        ],
        [
            'label' => 'เจ้าหน้าที่ผู้รับผิดชอบ',
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
            'label' => 'สถานะ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            //'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'format' => 'raw',
            'attribute' => 'software_status_id',
            'value' => function ($model) {
                return '<span class="badge light badge-' . $model->softwareStatus->software_status_color . '  btn-block">' . $model->softwareStatus->software_status_name . '</span>';
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
                <h3 class="modal-title font-weight-bold">ทะเบียนซอฟต์แวร์</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1">
                <div id="modalContents" class="m-3"></div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
