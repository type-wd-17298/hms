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
$urlApprove = Url::to(['approve']);

$css = '.modal-xl {max-width: 80% !important;}  .blink {  animation: blink-animation 2s steps(5, start) infinite; -webkit-animation: blink-animation 2s steps(5, start) infinite; }';
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

$(document).on("click", ".btnApprove", function (event) {
  $("#modalContents").html('');
  $('#modalForm').modal('show');
  let id = $(this).data("id");
  console.log("id :",id);
  
  $.get("{$urlApprove}", { id: id }, function (data) {
    $("#modalContents").html(data);
  }).fail(function () {
    console.log("id :",id);
    console.error("โหลดเนื้อหาล้มเหลว");
  });
});

JS;
?>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-block">
                <h4 class="card-title mb-2">ระบบบันทึกสำรวจความต้องการครุภัณฑ์คอมพิวเตอร์ ปี 2568</h4>
            </div>
            <div class="card-body pb-0">
                <?PHP
                Pjax::begin(['id' => 'gServiceView', 'timeout' => false, 'enablePushState' => false]); //
                $this->registerJs($js, $this::POS_READY);
                ?>

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
                    'showPageSummary' => true,
                    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none d-none'],
                    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
                    'columns' => [
                        [
                            'attribute' => 'department_id',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'format' => 'raw',
                            'hAlign' => 'right',
                            'group' => true,
                            'value' => function ($model) {
                                return $model->dep->employee_dep_label;
                            }
                        ],
                        [
                            'class' => 'kartik\grid\SerialColumn',
                            'vAlign' => 'top',
                        ],
                        [
                            'label' => 'สถานะ',
                            'headerOptions' => ['class' => 'font-weight-bold small text-center'],
                            'contentOptions' => ['class' => 'small text-center'],
                            'vAlign' => 'top',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $itComment = trim($model->it_comment);
                                $approve = $model->survey_list_approve;

                                if ($itComment === '') {
                                    return '<span class="badge bg-warning text-dark">รอความคิดเห็น IT</span>';
                                }

                                if ($approve === null || $approve === '') {
                                    return '<span class="badge text-white" style="background-color: #0d6efd;">รออนุมัติ</span>';
                                }


                                if ((int)$approve === 0) {
                                    return '<span class="badge bg-danger">ไม่อนุมัติ</span>';
                                }

                                return '<span class="badge bg-success">อนุมัติแล้ว</span>';
                            }

                        ],

                        [
                            'attribute' => 'item_id',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'font-weight-bold small'],
                            'vAlign' => 'top',
                            //'noWrap' => TRUE,
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a(
                                    $model->item->item,
                                    'javascript:;',
                                    [
                                        'class' => 'btnUpdate text-primary',
                                        'data' => ['id' => $model->survey_list_id]
                                    ]
                                ) . "<br><small>" . nl2br($model->survey_list_problem) . "</small>";
                            }
                        ],
                        [
                            'attribute' => 'survey_list_reuest',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'format' => ['decimal', 0],
                            'hAlign' => 'right',
                            'pageSummary' => true,
                        ],
                        [
                            'label' => 'ราคา',
                            'attribute' => 'item_id',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'font-weight-bold'],
                            'vAlign' => 'top',
                            'format' => ['decimal', 2],
                            'hAlign' => 'right',
                            'pageSummary' => true,
                            'value' => function ($model) {
                                return $model->item->price * $model->survey_list_reuest;
                            }
                        ],
                        [
                            'attribute' => 'survey_budget_year',
                            'headerOptions' => ['class' => 'font-weight-bold small'],
                            'contentOptions' => ['class' => 'small'],
                            'vAlign' => 'top',
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'it_comment',
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
                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'template' => '{approve}',
                            'header' => 'ดำเนินการ',
                            'headerOptions' => ['class' => 'font-weight-bold small text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                            'visible' => Yii::$app->user->can('SuperAdmin') || Yii::$app->user->can('SurveyApprove'),
                            'buttons' => [
                                'approve' => function ($url, $model, $key) {
                                    $disabled = (trim($model->it_comment) === '') ? true : false;
                                    return Html::button('อนุมัติ', [
                                        'class' => 'btnApprove btn btn-sm btn-success',
                                        'data-id' => $model->survey_list_id,
                                        'disabled' => $disabled,
                                        'title' => $disabled ? 'ต้องมีความคิดเห็น IT ก่อนอนุมัติ' : null,
                                    ]);
                                },

                            ],
                        ],


                    ],
                ]);
                ?>
                <?PHP Pjax::end(); ?>
                <!-- Modal -->

            </div>
        </div>
    </div>
</div>

<div class="modal fade  bg-success-light" id="modalForm" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font-weight-bold">ระบบบันทึกสำรวจความต้องการครุภัณฑ์คอมพิวเตอร์</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1">
                <div id="modalContents" class=""></div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>