<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use yii\widgets\DetailView;
?>
<div class="card">
    <div class="card-header">
        <div class="card-title">สรุปยอดวัสดุคงเหลือ</div>

    </div>
    <div class="card-body">

    </div>
</div>
<?php
echo GridView::widget([
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
    'showPageSummary' => TRUE,
    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
    'panel' => [
        'heading' => '',
        'type' => '',
        //'before' => Html::a('<i class="feather icon-plus-square"></i> เพิ่มรายการ', ['create'], ['class' => 'btn btn-outline-success']),
        //'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
        'footer' => false
    ],
    /*
      'toolbar' => [
      [
      'content' =>
      Html::button('{summary}', [
      'class' => 'btn btn-outline-secondary mr-1 d-none d-sm-block',
      ]),
      ],
      '{toggleData}'
      ],
     */
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'label' => 'ชื่อวัสดุ',
            'attribute' => 'asset_item_name',
            'format' => 'raw',
            'value' => function ($model) {
                return $model['asset_item_name']; // . ' [' . $model['asset_item_id'] . ']';
            }
        ],
        [
            'label' => 'หน่วยนับ',
            'attribute' => 'asset_unit_name',
            'format' => 'raw',
            'hAlign' => 'center',
            'width' => '3%',
            'value' => function ($model) {
                return $model['asset_unit_name'];
            }
        ],
        /*
          [
          'label' => 'ราคาต่อหน่วย',
          'attribute' => 'price',
          'format' => ['decimal', 0],
          'width' => '3%',
          'hAlign' => 'right',
          ],
         *
         */
        [
            'label' => 'จำนวนรับ',
            'attribute' => 'quantity_up',
            'format' => ['decimal', 0],
            'width' => '3%',
            'hAlign' => 'right',
        ],
        [
            'label' => 'จำนวนจ่าย',
            'attribute' => 'quantity_down',
            'format' => ['decimal', 0],
            'width' => '3%',
            'hAlign' => 'right',
        ],
        [
            'label' => 'จำนวนคงเหลือ',
            'attribute' => 'balance',
            'width' => '3%',
            'format' => ['decimal', 0],
            'hAlign' => 'right',
        ],
        [
            'label' => 'รวมราคาทั้งหมด',
            'attribute' => 'pp',
            'width' => '3%',
            'format' => ['decimal', 0],
            'hAlign' => 'right',
            'pageSummary' => true,
        ],
        [
            'label' => 'หมายเหตุ',
            'attribute' => 'remark',
            'format' => 'raw',
            'value' => function ($model) {
                return ($model['balance'] <> $model['amount'] ? Html::tag('div', 'กรุณาตรวจสอบรายการคงเหลือใหม่ค่ะ' . "({$model['balance']}/{$model['amount']})", ['class' => 'text-danger']) : '');
            }
        ],
    ],
]);
