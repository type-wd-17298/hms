<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;

#use yii\widgets\DetailView;
?>
<div class="card">
    <div class="card-header">
        <div class="card-title">รายงานอุบัติการณ์ที่เกิดขึ้นในระบบเทคโนโลยีสารสนเทศ สรุปตามจำนวนครั้งที่เกิดขึ้น</div>
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
            ],
        ]);
        ?>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <div class="card-title">รายงานอุบัติการณ์ที่เกิดขึ้นในระบบเทคโนโลยีสารสนเทศ สรุปตามสถานที่เกิดเหตุ</div>
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
            'dataProvider' => $dataProvider2,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'label' => 'สถานที่เกิดเหตุ',
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => 'font-weight-bold'],
                    'attribute' => 'department_name',
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
            ],
        ]);
        ?>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="card-title">รายงานอุบัติการณ์ที่เกิดขึ้นในระบบเทคโนโลยีสารสนเทศ สรุปตามเจ้าหน้าที่ผู้แจ้งเหตุการณ์</div>
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
            'dataProvider' => $dataProvider3,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'label' => 'ชื่อเจ้าหน้าที่แจ้งปัญหา',
                    'headerOptions' => ['class' => 'font-weight-bold small'],
                    'contentOptions' => ['class' => 'font-weight-bold'],
                    'attribute' => 'employee_fullname',
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
            ],
        ]);
        ?>
    </div>
</div>