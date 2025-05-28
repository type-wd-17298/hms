<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use app\modules\survay\models\Thaiaddress;
use app\modules\survay\components\Cprocess;

$url = Url::to(['']);
$this->title = 'ทะเบียนจัดซื้อจัดจ้าง';
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS
     $("[data-toggle=tooltip").tooltip();
JS;
$this->registerJs($js, $this::POS_READY);
?>
<div class="person-screen-index">

    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">ระบบบริหารงานพัสดุ</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)"><?= $this->title ?></a></li>
        </ol>
    </div>    <?php Pjax::begin(['id' => 'pjax-gridview', 'timeout' => false, 'enablePushState' => false]); ?>
    <?PHP
    $js = <<<JS
    $('[data-toggle="tooltip"]').tooltip();
    $(".btnPopup").click(function(){
        $('#modalForm').modal('show');
        $.get("{$url}",{id:$(this).data("id")}, function(data) {
           //$("#modalContent").html(data);
        });
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
        'bordered' => FALSE,
        'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
        'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label' => 'วันที่ออกเลข',
                'attribute' => 'project_date',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_date']), 'S', 1);
                }
            ],
            [
                //'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ทะเบียนคุม',
                'attribute' => 'project_book_number01',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'hAlign' => 'right',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) {
                    return substr($model->project_book_number01, 2);
                }
            ],
            [
                //'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ทะเบียนคุม',
                'attribute' => 'project_book_number02',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'hAlign' => 'right',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) {
                    return substr($model->project_book_number02, 2);
                }
            ],
            [
                //'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ทะเบียนคุม',
                'attribute' => 'project_book_number03',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'hAlign' => 'right',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) {
                    return substr($model->project_book_number03, 2);
                }
            ],
            [
                'label' => 'รหัสโครงการ',
                'attribute' => 'project_code',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 1,
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ชื่อโครงการ',
                'attribute' => 'project_name',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 1,
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
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'จำนวนเงิน(บาท)',
                'attribute' => 'project_cost',
                //'noWrap' => TRUE,
                'format' => ['decimal', 2],
                'vAlign' => 'middle',
                'hAlign' => 'right',
                'visible' => 1,
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
                                            'toggle' => 'tooltip',
                                            'placement' => 'right',
                                        ],
                                        'title' => 'บันทึกสัญญา',
                                    ]
                            ) .
                            Html::a('<i class="fa-solid fa-hashtag fa-lg"></i>', 'javascript:;',
                                    ['class' => 'btn  btn-primary btnPopup',
                                        'data' => [
                                            'id' => $model['project_id'],
                                            'toggle' => 'tooltip',
                                            'placement' => 'right',
                                        ],
                                        'title' => 'บันทึก PO',]
                            ) .
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
                    <h5 class="modal-title d-none">ข้อมูลโครงการ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalContent" class="m-2"></div>
            </div>
        </div>
    </div>
</div>


