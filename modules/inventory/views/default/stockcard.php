<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use yii\widgets\DetailView;
?>
<div class="card">
    <div class="card-header">
        <div class="card-title">ข้อมูลประวัติสินค้า</div>

    </div>
    <div class="card-body">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'items_tranfer_no',
                [
                    'attribute' => 'items.asset_item_id',
                ],
                [
                    'attribute' => 'items.asset_item_name',
                ],
                [
                    //'label'=>'',
                    'attribute' => 'lot_no',
                ],
            ],
        ])
        ?>


        <div class="row">

            <div class="col-lg-12">
                <div class="form-group">
                    <?= Html::a('<i class="feather icon-chevron-left"></i> กลับหน้าหลัก', ['stock', 'id' => $model->asset_item_id], ['class' => 'btn btn-outline-light']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo GridView::widget([
    'panelTemplate' => '<div class="">
          {panelBefore}
          {items}
          {panelAfter}
          {panelFooter}
          </div>',
    'export' => FALSE,
    'responsiveWrap' => FALSE,
    'hover' => TRUE,
    'bordered' => FALSE,
    'condensed' => TRUE,
    'striped' => FALSE,
    #'showPageSummary' => TRUE,
    'summaryOptions' => ['class' => 'small'],
    //'summary' => "Showing {begin} - {end} of {totalCount} items",
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
        '{toggleData}'
    ],
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'label' => 'เลขที่อ้างอิง',
            'attribute' => 'ref_id',
            'format' => 'raw',
        ],
        [
            'label' => 'วันที่รับจ่าย',
            'attribute' => 'stock_date',
            'format' => 'raw',
            'value' => function ($model) {
                return app\components\Ccomponent::getThaiDate($model->ref->date, 'S', 1);
            }
        ],
        [
            'label' => 'คลัง',
            'attribute' => 'asset_master_type_id',
            'format' => 'raw',
            'value' => function ($model) {
                return @$model->master->asset_master_type_name;
            }
        ],
        [
            'label' => 'ราคาต่อหน่วย',
            'attribute' => 'quantity_down',
            'format' => ['decimal', 0],
        ],
        [
            'label' => 'ดำเนินการ',
            'attribute' => 'stock_type_id',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->stockType->stock_type_name;
            }
        ],
        [
            'label' => 'รับ',
            'attribute' => 'quantity_up',
            'format' => ['decimal', 0],
        ],
        [
            'label' => 'จ่าย',
            'attribute' => 'quantity_down',
            'format' => ['decimal', 0],
        ],
        [
            'label' => 'คงเหลือ',
            'attribute' => 'balance',
            'format' => ['decimal', 0],
        ],
        [
            'label' => 'หมายเหตุ',
            'attribute' => 'remark',
        ],
    ],
]);
