<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\inventories\models\PurchaseOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'สต็อกสินค้ารวมทั้งหมด';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-index">

    <?php Pjax::begin(); ?>
    <?php
    $js = <<<JS
        $(".stockStatus").click(function(){
            var v = $(this).data('sid');
            $("#stockStatus").val(v);
            $("#frmStock").submit();
        });
JS;
    $this->registerJs($js, $this::POS_READY);


    $form = ActiveForm::begin([
                'id' => 'frmStock',
                'action' => ['allstock'],
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1
                ],
    ]);
    echo \yii\bootstrap4\Html::hiddenInput('stockStatus', '', ['id' => 'stockStatus']);
    //echo $form->field($searchModel, 'tranfer_status_id')->hiddenInput();
    $stock = [];
    foreach ($data as $key => $model) {
        if ($model['cc'] > 0)
            @$stock['ready'] += 1;
        if ($model['cc'] < 1)
            @$stock['runout'] += 1;
        if ($model['cc'] < 5 && $model['cc'] > 0)
            @$stock['almost_runout'] += 1;
        //@$stock[$model->tranfer_status_id] += 1;
    }
    ?>
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card stockStatus" data-sid="1">
                <div class="card-header d-flex align-items-start pb-0">
                    <div>
                        <h2 class="text-bold-700 mb-0"><?= @number_format($stock['ready']) ?> รายการ</h2>
                        <p>พร้อมจำหน่าย</p>
                    </div>
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-shopping-cart text-primary font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card stockStatus" data-sid="2">
                <div class="card-header d-flex align-items-start pb-0">
                    <div>
                        <h2 class="text-bold-700 mb-0">0 รายการ</h2>
                        <p>ส่วนลด</p>
                    </div>
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-server text-success font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card stockStatus" data-sid="3">
                <div class="card-header d-flex align-items-start pb-0">
                    <div>
                        <h2 class="text-bold-700 mb-0"><?= @number_format($stock['almost_runout']) ?> รายการ</h2>
                        <p>ใกล้หมด</p>
                    </div>
                    <div class="avatar bg-rgba-danger p-50 m-0">
                        <div class="avatar-content">
                            <i class="fa fa-battery-quarter text-danger font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card stockStatus" data-sid="4">
                <div class="card-header d-flex align-items-start pb-0">
                    <div>
                        <h2 class="text-bold-700 mb-0"><?= @number_format($stock['runout']) ?> รายการ</h2>
                        <p>หมด</p>
                    </div>
                    <div class="avatar bg-rgba-warning p-50 m-0">
                        <div class="avatar-content">
                            <i class="fa fa-battery-empty  text-warning font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section id="ecommerce-searchbar">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <div class="btn-group">
                        <input type="text" class="form-control form-control-lg mr-1" placeholder="ค้นหารายการ" name="keySearch" value="<?= @$_GET['keySearch'] ?>">
                        <button class="btn btn-white">
                            <i class="feather fa-2x icon-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php ActiveForm::end(); ?>
    <section class="list-view">
        <?=
        GridView::widget([
            //'layout' => "{items}\n{pager}",
            'panelTemplate' => '<div class="">
          {panelBefore}
          {items}
          {panelAfter}
          {panelFooter}
          </div>',
            'export' => FALSE,
            'responsiveWrap' => FALSE,
            'hover' => TRUE,
            #'showPageSummary' => TRUE,
            'panel' => [
                'heading' => '',
                'type' => '',
                #'before' => Html::a('<i class="feather icon-plus-square"></i> เพิ่มรายการ', ['create'], ['class' => 'btn btn-outline-success']),
                #'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                'footer' => false
            ],
            'toolbar' => [
                [
                    'content' =>
                    Html::button('{summary}', [
                        'class' => 'btn btn-outline-secondary mr-1 d-none d-sm-block',
                    ]),
                ],
                ' {export}',
                '{toggleData}'
            ],
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'label' => 'รายการสินค้า',
                    'attribute' => 'items_name',
                    'width' => '30%',
                    'format' => 'raw',
                ],
                [
                    'label' => 'หมวด',
                    'attribute' => 'categories_name',
                    'format' => 'raw',
                ],
                [
                    'label' => 'คงเหลือ',
                    'attribute' => 'cc',
                    'format' => ['decimal', 0],
                    'hAlign' => 'right',
                //'width' => '5%',
                ],
                ['class' => 'kartik\grid\ActionColumn',
                    'contentOptions' => ['style' => 'width:10%;'],
                    'header' => 'ดำเนินการ',
                    'template' => '{all}',
                    'buttons' => [
                        'all' => function ($url, $model, $key) {
                            return
                                    kartik\bs4dropdown\ButtonDropdown::widget([
                                        'encodeLabel' => FALSE,
                                        'label' => 'ดำเนินการ',
                                        'direction' => 'left',
                                        'dropdown' => [
                                            'encodeLabels' => false,
                                            'items' => [
                                                ['label' => '<i class="feather icon-edit"></i> ประวัติ',
                                                    'url' => ['stockcard', 'id' => $model['items_id'], 'lot' => $model['lot_no']],
                                                    'linkOptions' => [
                                                        'class' => 'btnLink',
                                                    //'data' => ['rid' => $model->purchase_order_id, 'pid' => $model->purchase_order_detail_id],
                                                    ]
                                                ],
//                                                ['label' => '<i class="feather icon-clipboard"></i> รายการสินค้า',
//                                                    'url' => '#',
//                                                    'linkOptions' => [
//                                                        'class' => 'btnLink',
//                                                    //'data' => ['rid' => $model->purchase_order_id, 'pid' => $model->purchase_order_detail_id],
//                                                    ]
//                                                ],
//                                                ['label' => '<i class="feather icon-edit"></i> ปรับสต็อก',
//                                                    'url' => '#',
//                                                    'linkOptions' => [
//                                                        'class' => 'btnLink',
//                                                    //'data' => ['rid' => $model->purchase_order_id, 'pid' => $model->purchase_order_detail_id],
//                                                    ]
//                                                ],
//                                                '<div class="dropdown-divider"></div>',
//                                                ['label' => '<i class="feather icon-trash-2"></i> ลบรายการ',
//                                                    'linkOptions' => [
//                                                        'data' => [
//                                                            'method' => 'post',
//                                                            'confirm' => \Yii::t('yii', 'ยืนยันการลบข้อมูลนี้หรือไม่ ?'),
//                                                        ],
//                                                    ],
//                                                //'url' => ['delete-detail', 'id' => $model->purchase_order_id, 'id2' => $model->purchase_order_detail_id],
//                                                ],
                                            ],
                                        ],
                                        'buttonOptions' => ['class' => 'btn-default btn-sm  btn-outline-light waves-effect waves-light']]);
                        },
                    ],
                ],
            ],
        ]);
        ?>
    </section>
    <?php Pjax::end(); ?>

</div>


