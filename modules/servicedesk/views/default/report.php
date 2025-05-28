<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;

#use yii\widgets\DetailView;
?>
<div class="card">
    <div class="card-header">
        <div class="card-title">รายงานการดำเนินการตามข้อตกระดับบริการของศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร</div>

    </div>
    <div class="card-body1">
        <?php
        echo GridView::widget([
            'panelTemplate' => '<div class="">
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
            'showPageSummary' => TRUE,
            'summaryOptions' => ['class' => 'small'],
            //'summary' => "Showing {begin} - {end} of {totalCount} items",
            'panel' => [
                'heading' => '',
                'type' => '',
                #'before' => Html::a('<i class="feather icon-plus-square"></i> เพิ่มรายการ', ['create'], ['class' => 'btn btn-outline-success']),
                #'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
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
                    'label' => 'การให้บริการ',
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => 'font-weight-bold'],
                    'attribute' => 'service_problem_name',
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => ''],
                    'label' => 'จำนวนปัญหา(ครั้ง)',
                    'attribute' => 'cc',
                    'hAlign' => 'right',
                    'format' => ['decimal', 0],
                    'pageSummary' => TRUE,
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => ''],
                    'label' => 'จำนวนแก้ปัญหาได้ตามเวลา',
                    'attribute' => 'ff',
                    'format' => ['decimal', 0],
                    'hAlign' => 'right',
                    'pageSummary' => TRUE,
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => ''],
                    'label' => 'ร้อยละความสำเร็จตามข้อตกลง',
                    'attribute' => 'pp',
                    'value' => function ($model) {
                        return $model['pp'] . '%';
                    }
                ],
            ],
        ]);
        ?>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <div class="card-title">รายงานการดำเนินการตามข้อตกระดับบริการ สรุปตามเวลาที่ให้บริการ</div>

    </div>
    <div class="card-body1">
        <?php
        echo GridView::widget([
            'panelTemplate' => '<div class="">
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
            //'showPageSummary' => TRUE,
            'summaryOptions' => ['class' => 'small'],
            //'summary' => "Showing {begin} - {end} of {totalCount} items",
            'panel' => [
                'heading' => '',
                'type' => '',
                #'before' => Html::a('<i class="feather icon-plus-square"></i> เพิ่มรายการ', ['create'], ['class' => 'btn btn-outline-success']),
                #'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
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
                    'label' => 'การให้บริการ',
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => 'font-weight-bold'],
                    'attribute' => 'service_problem_name',
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => ''],
                    'label' => 'Max',
                    'attribute' => 'ccmax',
                    'hAlign' => 'right',
                    'format' => ['decimal', 0],
                    'pageSummary' => TRUE,
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => ''],
                    'label' => 'Min',
                    'attribute' => 'ccmin',
                    'format' => ['decimal', 0],
                    'hAlign' => 'right',
                    'pageSummary' => TRUE,
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => ''],
                    'label' => 'เวลาเฉลี่ย(นาที)',
                    'attribute' => 'ccavg',
                    'format' => ['decimal', 1],
                ],
            ],
        ]);
        ?>
    </div>
</div>
