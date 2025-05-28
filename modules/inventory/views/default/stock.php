<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;

#use yii\widgets\DetailView;
?>
<div class="card">
    <div class="card-header">
        <div class="card-title">ข้อมูลสต็อกสินค้า <?= @$model->asset_item_id ?></div>

    </div>
    <div class="card-body">
        <?php
        /*
          DetailView::widget([
          'model' => $model,
          'attributes' => [
          //'items_tranfer_no',
          [
          'attribute' => 'items.asset_item_id',
          ],
          [
          'attribute' => 'items.items_name',
          ],
          [
          //'label'=>'',
          'attribute' => 'lot_no',
          ],
          ],
          ])
         *
         */
        ?>


        <div class="row">

            <div class="col-lg-12">
                <div class="form-group">

                    <?= Html::a('<i class="feather icon-chevron-left"></i> กลับหน้าหลัก', ['index'], ['class' => 'btn btn-outline-light']) ?>

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
//        [
//            'label' => 'สาขา',
//            'attribute' => 'branch_no',
//            'format' => 'raw',
//            'value' => function ($model) {
//                return $model->branch->branch_name;
//            }
//        ],
        [
            'label' => 'รายการสินค้า',
            'attribute' => 'asset_item_id',
            'value' => function ($model) {
                return $model->asset_item_id . ' ' . $model->items->asset_item_name;
            }
        ],
        [
            'label' => 'Lot',
            'attribute' => 'lot_no',
        ],
        [
            'label' => 'คงเหลือ',
            'attribute' => 'quantity',
            'format' => ['decimal', 0],
        ],
        [
            'label' => 'หมายเหตุ',
            'attribute' => 'remark',
        ],
        [
            'label' => 'รายละเอียด',
            'attribute' => 'asset_item_id',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a('รายละเอียด', ['stockcard', 'id' => $model['asset_item_id'], 'lot' => $model['lot_no']], ['class' => 'btn btn-sm btn-danger']);
            }
        ],
    ],
]);
