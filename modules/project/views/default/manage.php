<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use app\modules\survay\models\Thaiaddress;
use app\modules\survay\components\Cprocess;

$url = Url::to(['po/index']);
$this->title = 'ทะเบียนจัดซื้อจัดจ้าง';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-screen-index">
    <div class="alert alert-primary">
        <div class="mb-0">
            <strong>ชื่อโครงการ</strong><br>
            <?= $model->project_name ?>
            วงเงิน <?= number_format($model->project_cost, 2) ?> บาท
        </div>
    </div>
    <?php Pjax::begin(['id' => 'pjax-gridview2', 'timeout' => false, 'enablePushState' => false]); ?>

    <?PHP
    $js = <<<JS
    //$('[data-toggle="tooltip"]').tooltip();
    $(".btnPopup2").click(function(e){
        e.preventDefault();
        $('#modalForm2').modal('show');
        var href =  $(this).attr("href");
        var pid = $(this).data("id");
        $.get(href,{pid:pid}, function(data) {
               $("#modalContent2").html(data);
        });
    });
JS;
    $this->registerJs($js, $this::POS_READY);
    ?>
    <?=
    GridView::widget([
        //'id' => 'gpjax01',
        'panel' => [
            'heading' => '',
            'type' => '',
            'before' => ''
            . Html::a('<i class="fa-solid fa-plus"></i> เพิ่มรายการ', ['contract/index', 'pid' => $model->project_id], ['class' => 'btn btn-primary btn-sm float-right btnPopup2'])
            . '<h5 class="modal-title font-weight-bold" id="htitle">สัญญาโครงการ</h5>',
            'footer' => false,
        ],
        'panelTemplate' => '<div class="">
    {panelBefore}
    {items}
    {panelAfter}
    </div>',
        'responsiveWrap' => FALSE,
        'striped' => FALSE,
        'hover' => TRUE,
        'condensed' => TRUE,
        'export' => FALSE,
        'bordered' => FALSE,
        'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none '],
        'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
        'dataProvider' => $dataProvider,
        //'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label' => 'วันที่',
                'attribute' => 'project_contract_date',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_contract_date']), 'S', 1);
                }
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'เลขที่สัญญา',
                'attribute' => 'project_contract_book',
                //'noWrap' => TRUE,
                'format' => 'raw',
                //'hAlign' => 'right',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) {
                    return substr($model->project_contract_book, 3);
                }
            ],
            [
                'label' => 'วันที่เริ่มสัญญา',
                'attribute' => 'project_startdate',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                //'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_startdate']), 'S', 1);
                }
            ],
            [
                'label' => 'วันที่สิ้นสุดสัญญา',
                'attribute' => 'project_finishdate',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                //'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_finishdate']), 'S', 1);
                }
            ],
            [
                'label' => 'วันที่ครบกำหนดภาระผูกผัน',
                'attribute' => 'project_finishdate',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                //'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_finishdate']), 'S', 1);
                }
            ],
            [
                'label' => 'ธนาคาร',
                'attribute' => 'project_bank_id',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                //'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return @$model->bank->project_bank_name;
                }
            ],
            [
                'label' => 'เลขที่ LG',
                'attribute' => 'project_finishdate',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                //'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
            ],
            [
                'label' => 'ค้ำประกัน',
                'attribute' => 'project_contract_type_id',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                //'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return $model->type->project_contract_type_name;
                }
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'วงเงินตามสัญญา',
                'attribute' => 'project_contract_cost',
                //'noWrap' => TRUE,
                'format' => ['decimal', 2],
                'vAlign' => 'middle',
                'hAlign' => 'right',
                'visible' => 1,
                'pageSummary' => true,
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ค่าเงินมัดจำ 5%',
                'attribute' => 'project_contract_pay',
                //'noWrap' => TRUE,
                'format' => ['decimal', 2],
                'vAlign' => 'middle',
                'hAlign' => 'right',
                'pageSummary' => true,
                'visible' => 1,
//                'value' => function ($model) {
//                    //return;
//                }
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
                            Html::a('<i class="fa-solid fa-file-pen fa-lg"></i>', ['contract/index', 'pid' => $model['project_id'], 'uid' => $model['project_contract_id']],
                                    ['class' => 'btn  btn-primary active btnPopup2',
                                        'data' => [
                                            'toggle' => 'tooltip',
                                            'placement' => 'right',
                                        ],
                                        'title' => 'แก้ไขรายการ',]
                            )
                            . '</div></div>';
                    return $return;
                },
            ],
        ],
    ]);
    ?>
    <br>
    <?=
    GridView::widget([
        //'id' => 'gpjax02',
        //'pjax' => 1,
        'panel' => [
            'heading' => '',
            'type' => '',
            'before' => ''
            . Html::a('<i class="fa-solid fa-plus"></i> เพิ่มรายการ', ['po/index', 'pid' => $model->project_id], ['class' => 'btn btn-primary btn-sm float-right btnPopup2'])
            . '<h5 class="modal-title font-weight-bold" id="htitle">ใบสั่งซื้อ/สั่งจ้าง</h5>',
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
        'export' => FALSE,
        'bordered' => FALSE,
        'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none'],
        'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
        'dataProvider' => $dataProviderPO,
        'showPageSummary' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label' => 'วันที่',
                'attribute' => 'project_po_date',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_po_date']), 'S', 1);
                }
            ],
            [
                //'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'เลขที่ใบสั่งซื้อ/สั่งจ้าง',
                'attribute' => 'project_po_book',
                //'noWrap' => TRUE,
                'format' => 'raw',
                //'hAlign' => 'right',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) {
                    return substr($model->project_po_book, 3);
                }
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'จำนวนเงิน(บาท)',
                'attribute' => 'project_po_cost',
                //'noWrap' => TRUE,
                'format' => ['decimal', 2],
                'vAlign' => 'middle',
                'hAlign' => 'right',
                'visible' => 1,
                'pageSummary' => true,
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'หมายเหตุ',
                'attribute' => 'project_po_comment',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                //'hAlign' => 'right',
                'visible' => 1,
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
                            Html::a('<i class="fa-solid fa-file-pen fa-lg"></i>', ['po/index', 'pid' => $model['project_id'], 'uid' => $model['project_po_id']],
                                    ['class' => 'btn  btn-primary active btnPopup2',
                                        'data' => [
                                            'toggle' => 'tooltip',
                                            'placement' => 'right',
                                        ],
                                        'title' => 'แก้ไขรายการ',]
                            )
                            . '</div></div>';
                    return $return;
                },
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>


