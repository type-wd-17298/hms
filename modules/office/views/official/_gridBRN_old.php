<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;

$mode = 'BRN';
Pjax::begin(['id' => 'pjGview' . $mode, 'timeout' => false, 'enablePushState' => false]); //
$url = Url::to(['create']);
$url2 = Url::to(['update']);
$urlView = Url::to(['display']);
$urlOperate = Url::to(['operate']);
$js = <<<JS
$(".btnOperate").click(function(event){
       $("#modalContents").html('');
       event.preventDefault();
        $.get("{$url}",{id:$(this).data("pid")}, function(data) {
           $("#modalContent").html(data);
        });
       $.get("{$urlOperate}",{id:$(this).data("id")}, function(data) {
            if(data.status=='false'){
                            alert(data.message);
                        }else{
                            $('#modalForm').modal('show');
                            $("#modalContents").html(data);
             }
       });
});
$(".btnView").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
        $.get("{$urlView}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
});
$(".btnCreate").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url}",{mode:'{$mode}'}, function(data) {
           $("#modalContents").html(data);
       });
});

$(".btnUpdate").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url2}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
});
JS;

$this->registerJs($js, $this::POS_READY);
echo GridView::widget([
    'id' => 'gview' . $mode,
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table verticle-middle table-responsive-md '],
    'panel' => [
        'heading' => '',
        'type' => '',
        'before' => $this->render('_search', ['model' => $dataProvider]),
        'footer' => false,
    ],
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
    'bordered' => FALSE,
    'condensed' => TRUE,
    'export' => FALSE,
    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],

        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ความเร่งด่วน',
            'attribute' => 'paperless_level',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            'hAlign' => 'center',
            'visible' => 0,
            'value' => function ($model) {
                return Html::tag('div', '<i class="fa-solid fa-quote-left"></i> ' . @$model->level->paperless_level, ['class' => 'badge  badge-rounded badge-outline-' . @$model->level->paperless_level_color]);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'วันที่ลงรับ',
            'attribute' => 'create_at',
            'format' => 'raw',
            //'vAlign' => 'middle',
            //'width' => '1%',
            //'noWrap' => TRUE,
            'hAlign' => 'center',
            'visible' => 1,
            'value' => function ($model) {
                $date = Ccomponent::getThaiDate(($model['create_at']), 'S', 1);
                return'<span class="badge badge-rounded badge-primary"><i class="fa-solid fa-calendar-days"></i> ' . $date . '</span>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'วันที่หนังสือ',
            'format' => 'raw',
            'value' => function ($model) {
                $date = Ccomponent::getThaiDate(($model['paperless_official_date']), 'S');
                return'<span class="badge badge-rounded badge-outline-primary"><i class="fa-solid fa-calendar-days"></i> ' . $date . '</span>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เลขที่หนังสือลงรับ',
            'format' => 'raw',
            'attribute' => 'paperless_official_number',
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เลขที่หนังสือ',
            'format' => 'raw',
            'attribute' => 'paperless_official_booknumber',
            'visible' => 0,
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เรื่อง',
            'attribute' => 'paperless_topic',
            'format' => 'raw',
            //'vAlign' => 'middle',
            'visible' => 1,
            'value' => function ($model) {
                $html = @$model->level->paperless_level == '' ? '' : Html::tag('div', '<i class="fa-solid fa-quote-left "></i> ' . @$model->level->paperless_level, ['class' => 'badge badge-xs badge-rounded badge-outline-' . @$model->level->paperless_level_color]);
                $topic = Html::tag('span', $model['paperless_topic'], ['class' => 'font-weight-bold']) . ' ' . $html .
                        Html::tag('div', $model['paperless_official_booknumber'], ['class' => '']);

                //$topic .= '<span class="bullet bullet-dot bg-success animation-blink"></span>';

                return Html::a($topic, 'javascript:;', [
                    'class' => 'btnView',
                    'data' => [
                        'id' => @$model['paperless_id'],
                        'toggle' => 'tooltip',
                        'placement' => 'right',
                    ],]);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'จาก',
            'attribute' => 'paperless_official_from',
            'format' => 'raw',
//            'value' => function () {
//                return 'สำนักงานปลัดกระทรวงสาธารณสุข';
//            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            //'contentOptions' => ['class' => 'small'],
            //'noWrap' => TRUE,
            'label' => 'ถึงหน่วยงาน',
            'hAlign' => 'right',
            'visible' => 1,
            'value' => function ($model) {
                return $model->dep->employee_dep_label;
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เอกสารแนบ',
            'attribute' => 'paperless_official_from',
            'hAlign' => 'center',
            'format' => 'raw',
            'value' => function ($model) {
                $cf = @count($model->getUrlPdf($model->paperless_id));
                return Html::a(($cf > 0 ? "{$cf} <i class='fa-regular fa-file-pdf fa-lg text-danger'></i>" : '-'), 'javascript:;', [
                    'class' => 'btnView',
                    'data' => [
                        'id' => @$model['paperless_id'],
                        'toggle' => 'tooltip',
                        'placement' => 'right',
                    ],]);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'visible' => 1,
            'label' => 'ดำเนินการ',
            //'attribute' => 'docs_filename',
            //'width' => '10%',
            'format' => 'raw',
            'vAlign' => 'middle',
            'noWrap' => TRUE,
            'hAlign' => 'right',
            'value' => function ($model) {
                $html = '<div class="btn-group btn-group-toggle btn-group-xs">

                   <label class="btn btn-primary btn-xs">
                  ' . Html::a('<i class="fas fa-file-alt fa-lg"></i> แก้ไข', 'javascript:;',
                                [
                                    'class' => 'text-white text-decoration-none btnUpdate',
                                    'data' => [
                                        'id' => @$model['paperless_id'],
                                        'toggle' => 'tooltip',
                                        'placement' => 'right',
                                    ]
                                ]
                        ) . '
                  </label>
                  <label class="btn btn-dark btn-xs">
                  ' . Html::a('<i class="fas fa-ban fa-lg"></i> ยกเลิก', ['delete', 'id' => $model['paperless_id']], [
                            'class' => 'text-white text-decoration-none',
                            'data' => [
                                'pjax' => 1,
                                'confirm' => 'คุณต้องการลบข้อมูลนี้หรือไม่?',
                                'method' => 'post',
                            ],
                        ]) . '
                  </label>
                  </div>';
                return $html; //Html::a('<i class="fas fa-file-alt fa-lg"></i> เอกสาร', '//', ['class' => 'btn btn-warning btn-sm btn-block']);
            }
        ],
        [
            'visible' => 0,
            'label' => '#',
            'width' => '2%',
            'noWrap' => TRUE,
            'format' => 'raw',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'value' => function ($model) {
                $return = '<div class="input-group input-group-sm">'
                        . '<div class="input-group-prepend">' .
                        Html::a('เอกสาร', 'javascript:;', [
                            'class' => 'btn btn-dark btnFilters',
                            'data' => [
                                'id' => @$model['paperless_id'],
                                'toggle' => 'tooltip',
                                'placement' => 'right',
                            ],
                                //'title' => 'แสดงข้อมูล',
                        ])//
                        . Html::a('แก้ไข', 'javascript:;', ['class' => 'btn  btn-primary'])
                        #. Html::a('', ['update', 'id' => $model['person_id']], ['class' => 'btn  btn-primary active'])
                        . '</div></div>';
                return $return;
            },
        ],
    ],
]);
?>

<!-- Modal -->
<div class="modal fade bg-success-light " id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font-weight-bold">ลงทะเบียนรับหนังสือราชการ</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1">
                <div id="modalContents" class="m-2"></div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<?PHP Pjax::end(); ?>