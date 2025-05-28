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
                        <p class="mb-1 font-weight-bold">อุปกรณ์ทั้งหมด</p>
                        <h3 class="mb-0">-</h3>

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
                        <p class="mb-1 font-weight-bold">จำหน่วย</p>
                        <h3 class="mb-0">-</h3>

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
                        <p class="mb-1 font-weight-bolder">พร้อมใช้งาน</p>
                        <h3 class="text-white">-</h3>
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
        [
            'class' => 'kartik\grid\SerialColumn',
            'vAlign' => 'top',
        ],
        [
            'label' => 'วันที่จัดซื้อ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'asset_list_regis',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->asset_list_regis, 'F', true);
            }
        ],
        [
            'label' => 'เลขครุภัณฑ์',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'format' => 'raw',
            'attribute' => 'asset_list_number',
        ],
        [
            'label' => 'รายการ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'format' => 'raw',
            'attribute' => 'asset_list_name',
        ],
        [
            'label' => 'ประเภท',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'format' => 'raw',
            'attribute' => 'asset_type_id',
        ],
        [
            'label' => 'ราคาต่อหน่วย',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'hAlign' => 'right',
            'format' => ['decimal', 2],
            'attribute' => 'asset_list_unitprice',
        ],
        [
            'label' => 'ราคาที่จัดซื้อ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'hAlign' => 'right',
            'format' => ['decimal', 2],
            'attribute' => 'asset_list_buyprice',
        ],
        [
            'label' => 'งบประมาณ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            // 'hAlign' => 'right',
            'format' => 'raw',
            'attribute' => 'asset_budget',
        ],
        [
            'label' => 'แหล่งงบประมาณ',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            // 'hAlign' => 'right',
            'format' => 'raw',
            'attribute' => 'asset_budget_source_id',
        ],
        [
            'label' => 'ใช้งานที่',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            // 'hAlign' => 'right',
            'format' => 'raw',
            'attribute' => 'asset_list_place',
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
                <h3 class="modal-title font-weight-bold">แจ้งปัญหา (IT Contact Center)</h3>
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
