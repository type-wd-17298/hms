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
                <h4 class="card-title mb-2">ระบบบันทึกแผนปฏิบัติการโรงพยาบาล</h4>
            </div>
            <div class="card-body pb-0">
                <?PHP
                //Pjax::begin(['id' => 'gServiceView', 'timeout' => false, 'enablePushState' => false]); //
                $this->registerJs($js, $this::POS_READY);
                ?>

                <?=
                GridView::widget([
                    //'id' => 'gServiceView',
                    'dataProvider' => $dataProvider,
                    'panel' => [
                        'type' => ' ',
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
                            'attribute' => 'department_id',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'format' => 'raw',
                            // 'hAlign' => 'right',
                            //'group' => true,
                            'value' => function ($model) {
                                return $model->dep->employee_dep_label;
                            }
                        ],
                        [
                            'attribute' => 'plan_list_title',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'font-weight-bold small'],
                            'vAlign' => 'top',
                            //'noWrap' => TRUE,
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a($model->plan_list_title, 'javascript:;', [
                                    'class' => 'btnUpdate',
                                    'data' => ['id' => $model->plan_list_id]
                                ]);
                            }
                        ],
                        'plan_list_objective',
                        'plan_list_target',
                        'plan_list_activity',
                        'plan_list_kpi',
                        'plan_list_period',
                        'plan_list_costdetail',
                        [
                            'attribute' => 'plan_list_budget',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'format' => ['decimal', 2],
                            'hAlign' => 'right',
                            'pageSummary' => true,
                        ],
                        [
                            'attribute' => 'plan_budget_year',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'employee_id',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'format' => 'raw',
                            'hAlign' => 'right',
                            'value' => function ($model) {
                                return $model->emp->employee_fullname;
                            }
                        ],
                    ],
                ]);
                ?>
                <?PHP //Pjax::end(); ?>
                <!-- Modal -->

            </div>
        </div>
    </div>
</div>
<div class="modal fade  bg-success-light" id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font-weight-bold">ระบบการบันทึกแผนปฏิบัติการโรงพยาบาล</h3>
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