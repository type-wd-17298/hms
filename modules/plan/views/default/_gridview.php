<?PHP

use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;
use yii\bootstrap4\Html;
use app\modules\plan\components\Ccomponent as CC;

$urlView = Url::to(['display']);
$url = Url::to(['create']);

$js = <<<JS
$(".btnView").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$urlView}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
       $("#paper-label").html($("#"+$(this).data("id")).html());
});
$(".btnUpdate").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
       $("#paper-label").html('แก้ไขหนังสือเวียน');
});
$(".btnPopup").click(function(event){
       $("#paper-label").html('สร้างหนังสือเวียน');
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url}", function(data) {
           $("#modalContents").html(data);
       });

});
JS;
Pjax::begin(['id' => 'pjGview', 'timeout' => false, 'enablePushState' => false]); //
//$this->registerJs($js, $this::POS_READY);
echo GridView::widget([
    'id' => 'gviewView',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => ' '],
    'containerOptions' => ['class' => ''],
    'pager' => [
        'maxButtonCount' => 5,
    ],
    'panel' => [
        'heading' => '',
        'type' => 'default',
        'before' => $this->render('_search', ['model' => $dataProvider]),
        'footer' => false,
    ],
    'panelTemplate' => '<div class="">
          {panelBefore}
          <div>{items}</div>
          {panelAfter}
          {panelFooter}
          <div class="text-center m-2 small">{summary}</div>
          <div class="text-center m-2 small">{pager}</div>
          </div>',
    'responsive' => false,
    //'responsiveWrap' => false,
    'striped' => FALSE,
    'hover' => TRUE,
    'bordered' => FALSE,
    'condensed' => TRUE,
    //'export' => FALSE,
    //'perfectScrollbar' => TRUE,
    'showPageSummary' => true,
    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'หนังสือวันที่',
            //'attribute' => 'paperless_level',
            'format' => 'raw',
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'visible' => 0,
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            // 'noWrap' => TRUE,
            'attribute' => 'plan_list_title',
            'contentOptions' => ['class' => 'small'],
            'visible' => 1,
            'vAlign' => 'top',
            'format' => 'raw',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            // 'noWrap' => TRUE,
            'attribute' => 'plan_list_objective',
            'contentOptions' => ['class' => 'small'],
            'visible' => 1,
            'vAlign' => 'top',
            'format' => 'raw',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            // 'noWrap' => TRUE,
            'attribute' => 'plan_list_target',
            'contentOptions' => ['class' => 'small'],
            'visible' => 1,
            'vAlign' => 'top',
            'format' => 'raw',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            // 'noWrap' => TRUE,
            'attribute' => 'plan_list_activity',
            'contentOptions' => ['class' => 'small'],
            'visible' => 1,
            'vAlign' => 'top',
            'format' => 'raw',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            //'noWrap' => TRUE,
            'attribute' => 'plan_list_kpi',
            'contentOptions' => ['class' => 'small'],
            'visible' => 1,
            'vAlign' => 'top',
            'format' => 'raw',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            //'noWrap' => TRUE,
            'vAlign' => 'top',
            'attribute' => 'plan_list_period',
            'contentOptions' => ['class' => 'small'],
            'visible' => 1,
            'format' => 'raw',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            // 'noWrap' => TRUE,
            'attribute' => 'plan_list_costdetail',
            'contentOptions' => ['class' => 'small'],
            'visible' => 1,
            'vAlign' => 'top',
            'format' => 'raw',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            // 'noWrap' => TRUE,
            'attribute' => 'plan_list_budget',
            'contentOptions' => ['class' => 'small font-weight-bold'],
            'visible' => 1,
            'vAlign' => 'top',
            'hAlign' => 'right',
            'pageSummary' => true,
            'format' => ['decimal', 2],
        ], [
            'headerOptions' => ['class' => 'font-weight-bold'],
            // 'noWrap' => TRUE,
            'attribute' => 'employee_id',
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'visible' => 1,
            'format' => 'raw',
            'value' => function ($model) {
                return $model->emp->employee_fullname;
            }
        ],
    ],
]);
Pjax::end();
?>
<!-- Modal -->
<div class="modal fade  bg-success-light" id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title font-weight-bold">
                    <div class="clearfix">
                        <div id="paper-label" class="h3">หนังสือเวียน</div>
                    </div>
                </div>
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