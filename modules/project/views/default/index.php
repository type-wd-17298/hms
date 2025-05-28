<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);

$url = Url::to(['manage']);
$urlGen = Url::to(['gennumber']);
$this->title = 'ทะเบียนจัดซื้อจัดจ้าง ปีงบประมาณ ' . (Yii::$app->params['budgetYear'] + 543);
$this->params['breadcrumbs'][] = $this->title;
$numberStringPrefix = ''; //'สพ 0033.201.3.';
$js = <<<JS
     //$("[data-toggle=tooltip").tooltip();
JS;
//$this->registerJs($js, $this::POS_READY);
?>
<div class="person-screen-index">

    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">ระบบบริหารงานพัสดุ</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $this->title ?></a></li>
        </ol>
    </div>

    <?php Pjax::begin(['id' => 'pjax-gridview', 'timeout' => false, 'enablePushState' => false]); ?>
    <?PHP
    $js = <<<JS
    //$('[data-toggle="tooltip"]').tooltip();
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
        'panelTemplate' => '<div class="small">
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
        'condensed' => FALSE,
        'showPageSummary' => true,
        'bordered' => FALSE,
        //'export' => FALSE,
        'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
        'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label' => 'วันที่',
                'attribute' => 'project_date',
                'format' => 'raw',
                //'vAlign' => 'middle',
                //'width' => '1%',
                'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_date']), 'S', 1);
                }
            ],
            [
                'label' => 'อนุมัติหลักการ',
                'attribute' => 'project_book_number00',
                //'noWrap' => TRUE,
                //'width' => '1%',
                'format' => 'raw',
                'hAlign' => 'center',
                //'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) use ($numberStringPrefix) {
                    if ($model->project_buy_type == 2) {
                        return empty(substr($model->project_book_number00, 2)) ? Html::button('ออกเลข', [
                                    'data-id' => $model->project_id,
                                    'data-project' => $model->project_name,
                                    'data-gen' => 'เลขอนุมัติหลักการ',
                                    'data-number' => 0,
                                    'class' => 'btnGenNum btn btn-xs btn-primary'
                                ]) : $numberStringPrefix . substr($model->project_book_number00, 2);
                    }
                }
            ],
            [
                'label' => 'แต่งตั้ง คกร.',
                'attribute' => 'project_book_number01',
                //'noWrap' => TRUE,
                // 'width' => '1%',
                'format' => 'raw',
                'hAlign' => 'center',
                //'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) use ($numberStringPrefix) {
                    if (($model->project_buy_type == 2 && !empty($model->project_book_number00)) || ($model->project_buy_type <> 2 && empty($model->project_book_number00)))
                        return (empty(substr($model->project_book_number01, 2)) ) ? Html::button('ออกเลข', [
                                    'data-id' => $model->project_id,
                                    'data-project' => $model->project_name,
                                    'data-gen' => 'เลขแต่งตั้งค.กรรมการ',
                                    'data-number' => 1,
                                    'class' => 'btnGenNum btn btn-xs btn-primary'
                                ]) : $numberStringPrefix . substr($model->project_book_number01, 2);
                }
            ],
            [
                'label' => 'รายงานขอซื้อ',
                'attribute' => 'project_book_number02',
                // 'noWrap' => TRUE,
                // 'width' => '1%',
                'format' => 'raw',
                'hAlign' => 'center',
                //'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model)use ($numberStringPrefix) {
                    if (!empty($model->project_book_number01))
                        return empty(substr($model->project_book_number02, 2)) ? Html::button('ออกเลข', [
                                    'data-id' => $model->project_id,
                                    'data-project' => $model->project_name,
                                    'data-number' => 2,
                                    'data-gen' => 'เลขรายงานขอซื้อ',
                                    'class' => 'btnGenNum btn btn-xs btn-primary'
                                ]) : $numberStringPrefix . substr($model->project_book_number02, 2);
                }
            ],
            [
                'label' => 'รายงานผล',
                'attribute' => 'project_book_number03',
                // 'noWrap' => TRUE,
                //'width' => '1%',
                'format' => 'raw',
                'hAlign' => 'center',
                //'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) use ($numberStringPrefix) {
                    if (!empty($model->project_book_number02))
                        return empty(substr($model->project_book_number03, 2)) ? Html::button('ออกเลข', [
                                    'data-id' => $model->project_id,
                                    'data-gen' => 'เลขรายงานผล',
                                    'data-project' => $model->project_name,
                                    'data-number' => 3,
                                    'class' => 'btnGenNum btn btn-xs btn-primary'
                                ]) : $numberStringPrefix . substr($model->project_book_number03, 2);
                }
            ],
            [
                'label' => 'รหัสโครงการ',
                'attribute' => 'project_code',
                //'noWrap' => TRUE,
                'format' => 'raw',
                //'vAlign' => 'middle',
                'visible' => 0,
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ชื่อโครงการ',
                'attribute' => 'project_name',
                //'noWrap' => TRUE,
                'width' => '20%',
                'format' => 'html',
                //'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) {
                    return '' . Html::a($model->project_code, 'javascript:;') . ' ' . $model->project_name;
                    //Html::tag('div', $model->project_comment, ['class' => 'small']);
                }
            ],
            [
                'label' => 'ประเภท',
                //'contentOptions' => ['class' => 'small'],
                'attribute' => 'project_type_id',
                //'noWrap' => TRUE,
                //'width' => '5%',
                //'vAlign' => 'middle',
                //'hAlign' => 'center',
                'value' => function ($model) {
                    return @$model['type']['project_type_name'];
                },
            ],
            [
                'label' => 'ประเภทจัดซื้อ',
                //'contentOptions' => ['class' => 'small'],
                'attribute' => 'project_type_order_id',
                //'noWrap' => TRUE,
                //'width' => '5%',
                // 'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return @$model['typeOrder']['project_type_order_name'];
                },
            ],
            [
                'label' => 'วิธีซื้อ/จ้าง',
                //'contentOptions' => ['class' => 'small'],
                'attribute' => 'project_type_prefer_id',
                //'noWrap' => TRUE,
                //'width' => '5%',
                //'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return @$model['typePrefer']['project_type_prefer_name'];
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
                'label' => 'ทำสัญญา',
                //'attribute' => 'project_code',
                //'noWrap' => TRUE,
                'format' => ['decimal', 2],
                //'vAlign' => 'middle',
                'hAlign' => 'right',
                'visible' => 1,
                'pageSummary' => true,
                'value' => function ($model) {
                    return $model->getSumContract();
                }
            ],
            [
                'label' => 'PO',
                //'attribute' => 'project_code',
                //'noWrap' => TRUE,
                'format' => ['decimal', 2],
                // 'vAlign' => 'middle',
                'hAlign' => 'right',
                'visible' => 1,
                'pageSummary' => true,
                'value' => function ($model) {
                    return $model->getSumpo();
                }
            ],
            [
                'label' => 'ข้อมูลผู้ปฏิบัติ',
                'attribute' => 'whoRecord.fullname',
                //'contentOptions' => ['class' => 'small'],
                //'noWrap' => TRUE,
                'format' => 'raw',
                //'vAlign' => 'middle',
                'value' => function ($model) {
                    return '<b>' . $model->whoRecord->fullname . '</b><br>' . $model->whoRecord->dep->employee_dep_label;
                }
            ],
//            [
//                'label' => 'หน่วยงาน',
//                'attribute' => 'whoRecord.dep.department_name',
//                #'noWrap' => TRUE,
//                'format' => 'raw',
//                'vAlign' => 'middle',
//            ],
            [
                'label' => 'หมายเหตุ',
                'attribute' => 'project_comment',
                //'contentOptions' => ['class' => 'small'],
                #'noWrap' => TRUE,
                'visible' => 1,
                'format' => 'raw',
            //'vAlign' => 'middle',
            ],
            [
                'label' => '',
                //'width' => '1%',
                'noWrap' => TRUE,
                'format' => 'raw',
                // 'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    $return = '<div class="input-group input-group-sm"><div class="input-group-append">' .
                            Html::a('<i class="fa-solid fa-code fa-lg"></i>', 'javascript:;',
                                    [
                                        'class' => 'btn  btn-primary btnPopup',
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
                                    ['class' => 'btn  btn-secondary active',
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
                    <h3 class="modal-title" id="htitle">ข้อมูลโครงการ</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div id="modalContent2" class="m-2"></div>
            </div>
        </div>
    </div>
</div>


