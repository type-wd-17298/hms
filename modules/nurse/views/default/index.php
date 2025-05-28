<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;

$this->title = 'รายงานภาระงานพยาบาล';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-index">

    <?php Pjax::begin(); ?>

    <?PHP
    /*
      GridView::widget([
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
      'dataProvider' => @$dataProvider,
      'columns' => [
      ['class' => 'kartik\grid\SerialColumn'],
      [
      'label' => 'รายการสินค้า',
      'attribute' => 'asset_item_id',
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
      ],
      ],
      ]);
     *
     */
    ?>

    <?php Pjax::end(); ?>
</div>