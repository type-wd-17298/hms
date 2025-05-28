<?php

//use yii\helpers\Html;
//use yii\helpers\Url;
use kartik\grid\GridView;
use app\components\Ccomponent;

$columns = [
    ['class' => 'kartik\grid\SerialColumn'],
    [
        'label' => 'วันที่',
        'attribute' => 'dd',
        'format' => 'raw',
        'noWrap' => TRUE,
        'vAlign' => 'middle',
        'value' => function ($model) {
            return Ccomponent::getThaiDate(($model['dd']), 'S');
        }
    ],
    [
        'label' => 'เลขที่ลงรับ',
        'attribute' => 'bnn',
        'format' => 'raw',
        'vAlign' => 'middle',
    ],
    [
        'label' => 'จาก',
        'attribute' => 'ff',
        'format' => 'raw',
        'vAlign' => 'middle',
    ],
    [
        'label' => 'เลขที่หนังสือ',
        'attribute' => 'bn',
        'format' => 'raw',
        'vAlign' => 'middle',
    ],
    [
        'label' => 'เรื่อง',
        'attribute' => 'topic',
        'format' => 'raw',
        'vAlign' => 'middle',
    ],
    [
        'label' => 'หน่วยงาน',
        'attribute' => 'dep',
        'format' => 'raw',
        'vAlign' => 'middle',
        'hAlign' => 'right',
        'format' => 'raw',
    ],
    [
        'label' => 'ลายเซ็นต์',
        'format' => 'raw',
        'vAlign' => 'middle',
        'hAlign' => 'right',
        'format' => 'raw',
        'value' => function () {
            return ' ';
        }
    ],
];
?>
<h4>รายงานทะเบียนเอกสาร</h4>
<hr>
<div class="small">
    <?=
    GridView::widget([
        'panel' => [
            'heading' => '',
            'type' => '',
            'before' => $this->render('_search', ['model' => $dataProvider]),
            'footer' => false,
        ],
        'dataProvider' => $dataProvider,
        //'layout' => '{items}',
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        // set export properties
        //'export' => true,
#'persistResize' => FALSE,
        'bordered' => false,
        'striped' => true,
        'condensed' => true,
        #'responsive' => true,
        'responsiveWrap' => false,
        //'showPageSummary' => true,
        'columns' => $columns,
    ]);
    ?>
</div>