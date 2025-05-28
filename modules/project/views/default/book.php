<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use app\modules\survay\models\Thaiaddress;
use app\modules\survay\components\Cprocess;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);

$url = Url::to(['manage']);
$urlGen = Url::to(['gennumber']);
$this->title = 'ทะเบียนคุมเลขจัดซื้อจัดจ้าง';
$this->params['breadcrumbs'][] = $this->title;

$numberStringPrefix = ''; //'สพ 0033.201.3.';

$js = <<<JS
     $("[data-toggle=tooltip").tooltip();
JS;
$this->registerJs($js, $this::POS_READY);
?>
<div class="person-screen-index">
    <h4 class="text-primary"><i class="fa-solid fa-list"></i> <?= $this->title ?></h4>
    <?php Pjax::begin(['id' => 'pjax-gridview', 'timeout' => false, 'enablePushState' => false]); ?>
    <?PHP
    $js = <<<JS
    $('[data-toggle="tooltip"]').tooltip();
    $(".btnPopup").click(function(){
        $('#modalForm').modal('show');
        $("#htitle").html($(this).data("project"));
        $("#modalContent").html('กำลังเรียกข้อมูล...');
        $.get("{$url}",{id:$(this).data("id")}, function(data) {
           $("#modalContent").html(data);
        });
    });
    $(".btnGenNum").click(function(){
        if(confirm('ยืนยันการออก' +$(this).data("gen")+' '+$(this).data("project")+' หรือไม่')){
         var title = 'ออก' +$(this).data("gen")+' '+$(this).data("project");
            $.post("{$urlGen}",{pid:$(this).data("id"),number:$(this).data("number"),title:title}, function(data) {
               alert('ออกเลขสำเร็จ');
               $.pjax.reload({container: '#pjax-gridview', async: false});
            });
        }
    });
JS;
    $this->registerJs($js, $this::POS_READY);
    ?>
    <?=
    GridView::widget([
        'panel' => [
            'heading' => '',
            'type' => '',
            'before' => $this->render('_search', ['model' => $dataProvider]),
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
        'condensed' => TRUE,
        'showPageSummary' => true,
        'bordered' => FALSE,
        // 'export' => FALSE,
        'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
        'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label' => 'วันที่',
                'attribute' => 'project_date',
                'format' => 'raw',
                'vAlign' => 'middle',
                'width' => '1%',
                'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_date']), 'S', 1);
                }
            ],
            [
                //'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'อนุมัติหลักการ',
                'attribute' => 'project_book_number01',
                'noWrap' => TRUE,
                'width' => '1%',
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) use ($numberStringPrefix) {
                    return empty(substr($model->project_book_number01, 2)) ? Html::button('ออกเลข', [
                        'data-id' => $model->project_id,
                        'data-project' => $model->project_name,
                        'data-gen' => 'เลขอนุมัติหลักการ',
                        'data-number' => 1,
                        'class' => 'btnGenNum btn btn-sm btn-primary active'
                    ]) : $numberStringPrefix . substr($model->project_book_number01, 2);
                }
            ],
            [
                //'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'แต่งตั้ง คกร.',
                'attribute' => 'project_book_number01',
                'noWrap' => TRUE,
                'width' => '1%',
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) use ($numberStringPrefix) {
                    return empty(substr($model->project_book_number01, 2)) ? Html::button('ออกเลข', [
                        'data-id' => $model->project_id,
                        'data-project' => $model->project_name,
                        'data-gen' => 'เลขแต่งตั้งค.กรรมการ',
                        'data-number' => 1,
                        'class' => 'btnGenNum btn btn-sm btn-primary active'
                    ]) : $numberStringPrefix . substr($model->project_book_number01, 2);
                }
            ],
            [
                //'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'รายงานขอซื้อ',
                'attribute' => 'project_book_number02',
                'noWrap' => TRUE,
                'width' => '1%',
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model)use ($numberStringPrefix) {
                    if (!empty($model->project_book_number01))
                        return empty(substr($model->project_book_number02, 2)) ? Html::button('ออกเลข', [
                                    'data-id' => $model->project_id,
                                    'data-project' => $model->project_name,
                                    'data-number' => 2,
                                    'data-gen' => 'เลขรายงานขอซื้อ',
                                    'class' => 'btnGenNum btn btn-sm btn-primary active'
                                ]) : $numberStringPrefix . substr($model->project_book_number02, 2);
                }
            ],
            [
                //'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'รายงานผล',
                'attribute' => 'project_book_number03',
                'noWrap' => TRUE,
                'width' => '1%',
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) use ($numberStringPrefix) {
                    if (!empty($model->project_book_number02))
                        return empty(substr($model->project_book_number03, 2)) ? Html::button('ออกเลข', [
                                    'data-id' => $model->project_id,
                                    'data-gen' => 'เลขรายงานผล',
                                    'data-project' => $model->project_name,
                                    'data-number' => 3,
                                    'class' => 'btnGenNum btn btn-sm btn-primary active'
                                ]) : $numberStringPrefix . substr($model->project_book_number03, 2);
                }
            ],
            [
                'label' => 'รหัสโครงการ',
                'attribute' => 'project_code',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 0,
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ชื่อโครงการ',
                'attribute' => 'project_name',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) {
                    return '' . Html::a($model->project_code, 'javascript:;') . ' ' . $model->project_name;
                }
            ],
            [
                'label' => 'ประเภท',
                'attribute' => 'project_type_id',
                'noWrap' => TRUE,
                //'width' => '5%',
                'vAlign' => 'middle',
                //'hAlign' => 'center',
                'value' => function ($model) {
                    return @$model['type']['project_type_name'];
                },
            ],
            [
                'label' => 'ประเภทจัดซื้อ',
                'attribute' => 'project_type_order_id',
                'noWrap' => TRUE,
                //'width' => '5%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return @$model['typeOrder']['project_type_order_name'];
                },
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'วงเงินจัดสรร',
                'attribute' => 'project_cost',
                //'noWrap' => TRUE,
                'format' => ['decimal', 2],
                'vAlign' => 'middle',
                'hAlign' => 'right',
                'visible' => 1,
                'pageSummary' => true,
            ],
            [
                'label' => 'ข้อมูลผู้ปฏิบัติ',
                'attribute' => 'whoRecord.fullname',
                #'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
            ],
            [
                'label' => 'หน่วยงาน',
                //'attribute' => 'whoRecord.fullname',
                #'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
            ],
            [
                'label' => '',
                'width' => '1%',
                'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    $return = '<div class="input-group input-group-sm"><div class="input-group-append">' .
                            Html::a('<i class="fa-solid fa-code fa-lg"></i>', 'javascript:;',
                                    [
                                        'class' => 'btn  btn-secondary btnPopup',
                                        'data' => [
                                            'id' => $model['project_id'],
                                            'project' => $model['project_name'],
                                            'toggle' => 'tooltip',
                                            'placement' => 'right',
                                        ],
                                        'title' => 'บันทึกสัญญา/PO',
                                    ]
                            ) .
                            /*
                              Html::a('<i class="fa-solid fa-hashtag fa-lg"></i>', 'javascript:;',
                              ['class' => 'btn  btn-primary btnPopup',
                              'data' => [
                              'id' => $model['project_id'],
                              'project' => $model['project_name'],
                              'toggle' => 'tooltip',
                              'placement' => 'right',
                              ],
                              'title' => 'บันทึก PO',]
                              ) .
                             *
                             */
                            Html::a('<i class="fa-solid fa-file-pen fa-lg"></i>', ['update', 'id' => $model['project_id']],
                                    ['class' => 'btn  btn-primary active',
                                        'data' => [
                                            'toggle' => 'tooltip',
                                            'placement' => 'right',
                                        ],
                                        'title' => 'แก้ไขโครงการ',]
                            )
                            . '</div></div>';
                    return $return;
                },
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>
    <!-- Modal -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="htitle">ข้อมูลโครงการ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalContent" class="m-2"></div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalForm2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่ม/แก้ไขข้อมูลรายการโครงการ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalContent2" class="m-2"></div>
            </div>
        </div>
    </div>
</div>


