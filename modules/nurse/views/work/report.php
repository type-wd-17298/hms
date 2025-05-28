<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;

$this->title = 'รายงานภาระงานพยาบาล';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">
    <?PHP
    $columnField = [
        [
            'headerOptions' => ['class' => 'table-info', 'style' => 'display: none;'],
            'attribute' => 'dep.productivity_dep',
            //'format' => ['decimal', 0],
            'visible' => 0,
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'group' => false,
            'groupedRow' => true, // move grouped column to a single grouped row
            'groupOddCssClass' => 'kv-grouped-row text-left b', // configure odd group cell css class
            'groupEvenCssClass' => 'kv-grouped-row text-left b', // configure even group cell css class
            'pageSummary' => true,
            'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                $groupFooter = [1 => 'รวม (' . $model->dep->productivity_dep . ')'];
                $contentFormats = [];
                $contentOptions = [];
                for ($i = 3; $i < 33; $i++) {
                    $groupFooter[$i] = GridView::F_SUM;
                    $contentFormat[$i] = ['format' => 'number', 'decimals' => 0];
                    $contentOptions[$i] = ['style' => 'text-align:center', 'class' => 'small'];
                }
                return [
            'mergeColumns' => [[1, 2]], // columns to merge in summary
            'content' => $groupFooter,
            'contentFormats' => $contentFormat,
            'contentOptions' => $contentOptions,
            // html attributes for group summary row
            'options' => ['class' => 'info table-info small', 'style' => 'font-weight:bold;']
                ];
            }
        ],
        // ['class' => 'kartik\grid\SerialColumn', 'headerOptions' => ['class' => 'table-info', 'style' => 'display: none;'], 'header' => '',],
        [
            #'label' => '', //Word
            'headerOptions' => ['class' => 'table-info', 'style' => 'display: none;'],
            #'attribute' => 'hname',
            'vAlign' => 'middle',
            'visible' => 1,
            'group' => true,
            'format' => 'raw',
            'value' => function ($model) {
                return Html::tag('B', $model->dep->productivity_dep);
            },
            'pageSummary' => 'รวมทั้งหมด',
        ],
        [
            'label' => 'เวร',
            'headerOptions' => ['class' => 'small', 'style' => 'display: none;'],
            'contentOptions' => ['class' => 'small'],
            //'attribute' => 'report_type_shift_id',
            'format' => 'raw',
            'noWrap' => TRUE,
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return $model->type->report_type_shift_name;
            },
            'pageSummary' => true,
        ],
        [
            'label' => 'ยอดยกมา',
            'headerOptions' => ['class' => '', 'style' => 'display: none;'],
            'contentOptions' => ['class' => 'small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return $model['rps_a4'];
            },
            'pageSummary' => true,
        ],
        [
            'label' => 'รับใหม่',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small'],
            'attribute' => 'rps_a1',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'รับrefer',
            'headerOptions' => ['class' => 'small'],
            'contentOptions' => ['class' => 'small'],
            'attribute' => 'rps_a2',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'รับย้าย',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small', 'style' => 'display: none;'],
            'attribute' => 'rps_a3',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'ยอดผู้ป่วยทั้งหมด',
            'contentOptions' => ['class' => 'small font-weight-bold'],
            'headerOptions' => ['class' => 'small', 'style' => 'display: none;'],
            //'attribute' => 'room_aiir',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return $model['rps_a1'] + $model['rps_a2'] + $model['rps_a3'] + $model['rps_a4'];
            },
            'pageSummary' => true,
        ],
        [
            'label' => 'ทุเลา',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-active small'],
            'attribute' => 'rps_d1',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        //---------------------------2------------------------
        [
            'label' => 'ไม่สมัครอยู่',
            'attribute' => 'rps_d2',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-active small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'ย้ายไป',
            'attribute' => 'rps_d3',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-active small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'ส่งต่อ',
            'attribute' => 'rps_d4',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-active small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'ตาย',
            'attribute' => 'rps_d5',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-active'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'คงพยาบาล',
            'contentOptions' => ['class' => 'small font-weight-bold'],
            'headerOptions' => ['class' => 'small', 'style' => 'display: none;'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'value' => function ($model) {
                return ($model['rps_a1'] + $model['rps_a2'] + $model['rps_a3'] + $model['rps_a4']) - ($model['rps_d1'] + $model['rps_d2'] + $model['rps_d3'] + $model['rps_d4'] + $model['rps_d5']);
            },
            'pageSummary' => true,
        ],
        [
            'label' => 'สามัญ',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-warning small'],
            'attribute' => 'rps_b1',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'พิเศษ',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-warning small'],
            'attribute' => 'rps_b2',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'ICU',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-warning small'],
            'attribute' => 'rps_b3',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'ทารกแรกเกิด',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-warning small'],
            'attribute' => 'rps_b4',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => '1',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-success small'],
            'attribute' => 'rps_p1',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => '2',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-success small'],
            'attribute' => 'rps_p2',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => '3',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-success small'],
            'attribute' => 'rps_p3',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => '4',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-success small'],
            'attribute' => 'rps_p4',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => '5',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-success small'],
            'attribute' => 'rps_p5',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'HN',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small'],
            'attribute' => 'rps_s1',
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
        ],
        [
            'label' => 'RN',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
            'value' => function ($model) {
                return $model['rps_s2'];
            }
        ],
        [
            'label' => 'TN/PN',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
            'value' => function ($model) {
                return $model['rps_s3'];
            }
        ],
        [
            'label' => 'Aid',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
            'value' => function ($model) {
                return $model['rps_s4'];
            }
        ],
        [
            'label' => 'รวม',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-success small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
            'value' => function ($model) {
                return $model->sumNurse;
            },
        ],
        [
            'label' => 'การพยาบาล',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'table-success small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
            'value' => function ($model) {
                return $model->nurse;
            }
        ],
        [
            'label' => 'Productivity %',
            'contentOptions' => ['class' => 'small font-weight-bold'],
            'headerOptions' => ['class' => 'small', 'style' => 'display: none;'],
            'format' => ['decimal', 2],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
            'value' => function ($model) {
                return $model->productivity;
            }
        ],
        [
            'label' => 'Stroke unit',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
            'value' => function ($model) {
                return $model['rps_b7'];
            }
        ],
        [
            'label' => 'Vevtutator',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
            'value' => function ($model) {
                return $model['rps_b5'];
            }
        ],
        [
            'label' => 'on HF',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small'],
            'format' => ['decimal', 0],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true,
            'value' => function ($model) {
                return $model['rps_b6'];
            }
        ],
        [
            'label' => 'หมายเหตุ',
            'contentOptions' => ['class' => 'small'],
            'headerOptions' => ['class' => 'small', 'style' => 'display: none;'],
            'attribute' => 'rps_comment',
            'format' => 'raw',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'visible' => 1,
        ],
    ];

    $columns = $columnField;
    ?>
    แบบรายงานจำนวนเตียงให้บริการ ผู้ป่วย Covid-19 จังหวัดสุพรรณบุรี
    <?=
    GridView::widget([
        'panel' => [
            //  'heading' => 'แบบรายงานจำนวนเตียงให้บริการ ผู้ป่วย Covid-19 จังหวัดสุพรรณบุรี',
            // 'type' => '',
            'emptyCell' => 'N\A',
            'before' => '<form><div class="btn-group">'
            . DatePicker::widget([
                'name' => 'date_between_a',
                'value' => (!isset($_GET['date_between_a']) ? date('Y-m-d') : $_GET['date_between_a']),
                'language' => 'th',
                #'size' => 'sm',
                'type' => DatePicker::TYPE_RANGE,
                'separator' => 'ถึง',
                #
                #'options' => ['class' => 'btn btn-default btn-sm'],
                'name2' => 'date_between_b',
                'value2' => (!isset($_GET['date_between_b']) ? date('Y-m-d') : $_GET['date_between_b']),
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]) . '
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> แสดงข้อมูล</button>
                ' . yii\bootstrap4\Html::a('<<< กลับหน้าจัดการ', ['reportdiary/index'], ['class' => 'btn btn-dark']) . '
</form>',
            'footer' => false,
        ],
        'dataProvider' => $dataProvider,
        #'layout' => '{items}',
        'containerOptions' => ['class' => 'small'],
        'toggleDataContainer' => ['class' => 'btn-group  mr-2'],
        #'export' => false,
        'bordered' => false,
        'striped' => false,
        'condensed' => true,
        'responsive' => true,
        'responsiveWrap' => false,
        'columns' => $columns,
        'showPageSummary' => true,
        'beforeHeader' => [
            [
                'columns' => [
                    // ['content' => 'ลำดับที่', 'options' => ['rowspan' => 2, 'class' => 'text-center  kv-align-middle']],
                    ['content' => 'Ward', 'options' => ['rowspan' => 2, 'class' => 'text-center  kv-align-middle']],
                    ['content' => 'เวร', 'options' => ['rowspan' => 2, 'class' => 'text-center  kv-align-middle']],
                    ['content' => 'ยอดยกมา', 'options' => ['rowspan' => 2, 'class' => 'text-center kv-align-middle']],
                    ['content' => 'Admitted', 'options' => ['colspan' => 2, 'class' => 'text-center kv-align-middle']],
                    ['content' => 'รับย้าย', 'options' => ['rowspan' => 2, 'class' => 'text-center']],
                    ['content' => 'ยอดผู้ป่วย', 'options' => ['rowspan' => 2, 'class' => 'text-center table-success kv-align-middle']],
                    ['content' => 'จำหน่าย', 'options' => ['colspan' => 5, 'class' => 'text-center table-active kv-align-middle']],
                    ['content' => 'คงพยาบาล', 'options' => ['rowspan' => 2, 'class' => 'text-center table-success kv-align-middle']],
                    ['content' => 'จำแนกตามเตียง', 'options' => ['colspan' => 4, 'class' => 'text-center kv-align-middle']],
                    ['content' => 'ประเภทผู้ป่วย', 'options' => ['colspan' => 5, 'class' => 'text-center kv-align-middle']],
                    ['content' => 'เจ้าหน้าที่', 'options' => ['colspan' => 4, 'class' => 'text-center kv-align-middle']],
                    ['content' => 'ผลรวมชั่วโมง', 'options' => ['colspan' => 2, 'class' => 'text-center kv-align-middle']],
                    ['content' => 'Productivity %', 'options' => ['rowspan' => 2, 'class' => 'text-center small table-active kv-align-middle']],
                    ['content' => 'ข้อมูลเพิ่มเติม', 'options' => ['colspan' => 3, 'class' => 'text-center small kv-align-middle']],
                    ['content' => 'หมายเหตุ', 'options' => ['rowspan' => 2, 'class' => 'text-center small kv-align-middle']],
                ],
            #'options' => ['class' => 'skip-export'] // remove this row from export
            ]
        ],
    ]);
    ?>
</div>