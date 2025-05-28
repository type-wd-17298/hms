<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
//use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;
//use app\modules\survay\models\Thaiaddress;
use app\modules\survay\components\Cprocess;

$this->title = 'รายการสำรวจข้อมูลสุขภาพ';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::to(['screen/history']);
$js = <<<JS
    $("[data-toggle=tooltip").tooltip();
    $(".btnPopup").click(function(){
        $('#modalForm').modal('show');
        $.get("{$url}",{id:$(this).data("id")}, function(data) {
           $("#modalContent").html(data);
        });
    });
JS;
$this->registerJs($js, $this::POS_READY);
?>
<div class="person-screen-index">
    <h4 class="text-primary"><i class="fas fa-solid fa-users-rectangle"></i> <?= $this->title ?></h4>
    เกณฑ์แปรผล <b class="text-danger">ระดับความดันโลหิต</b>/<b class="text-danger">ระดับน้ำตาลในเลือด</b>
    <div class="btn-group mr-2 mb-2" role="group" >
        <?PHP foreach ($data as $value) { ?>
            <button type="button" data-toggle="tooltip" title="<?= $value['color_group'] ?>" data-placement="top" class="btn btn-sm <?= $value['color_class'] ?>" style="<?= "width:100px;background-color:{$value['color_rgb']}" ?>"><?= $value['color_name'] ?></button>
            <!--                <br><div class="small"></div>-->
        <?PHP } ?>
    </div>
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'panel' => [
            'heading' => '',
            'type' => '',
            'before' => $this->render('_search', ['model' => $dataProvider]),
            'footer' => false,
        ],
        'panelTemplate' => '<div class="">
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
        'condensed' => TRUE,
        'export' => FALSE,
        'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
        'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => '',
                'width' => '1%',
                'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    $return = '<div class="input-group input-group-sm"><div class="input-group-append">' .
                            Html::a('<i class="fa fa-solid fa-user-pen fa-lg"></i>', ['update', 'id' => $model['person_screen_id']],
                                    ['class' => 'btn  btn-secondary'
                                        , 'data' => ['toggle' => 'tooltip', 'placement' => 'left']
                                        , 'title' => 'แก้ไขข้อมูล'
                                    ]
                            )
                            . Html::a('<i class="fa-solid fa-map-location-dot fa-lg"></i>', 'javascript:;',
                                    ['class' => 'btn  btn-primary']
                            )
                            . Html::a('<i class="fa fa-solid fa-square-poll-vertical fa-lg "></i>', 'javascript:;',
                                    ['class' => 'btn  btn-primary active btnPopup',
                                        'data-toggle' => "tooltip",
                                        'data-placement' => "right",
                                        'title' => "แสดงข้อมูล",
                                        'data-id' => $model['person_id']
                                    ]
                            )
                            . '</div></div>';
                    return $return;
                },
            ],
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label' => 'ผลการคัดกรอง HT',
                'attribute' => 'person_screen_result',
                'noWrap' => TRUE,
                'format' => 'raw',
                'width' => '5%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return Cprocess::calResult($model['color_ht']);
                },
            ],
            [
                'label' => 'ผลการคัดกรอง DM',
                'attribute' => 'person_screen_result',
                'noWrap' => TRUE,
                'format' => 'raw',
                'width' => '5%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return Cprocess::calResult($model['color_dm']);
                },
            ],
            [
                'label' => 'วันที่สำรวจ',
                'attribute' => 'person_screen_date',
                'format' => 'raw',
                'vAlign' => 'middle',
                #'width' => '1%',
                'noWrap' => TRUE,
                #'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['person_screen_date']), 'S', 1);
                }
            ],
            [
                'label' => 'กลุ่มสำรวจ',
                'attribute' => 'person.person_type_id',
                'noWrap' => TRUE,
                #'width' => '5%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return $model['person']['personType']['person_type_name'];
                },
            ],
            [
                'label' => 'ชื่อ-นามสกุล',
                'attribute' => 'person_id',
                'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'value' => function ($model) {
                    #return $model['person']['person_fullname'];
                    return '<div class="font-weight-bold">' . Html::a($model['person']['person_fullname'] . '</div>', ['person/update', 'id' => $model['person']['person_id']]);
                },
            ],
            [
                'attribute' => 'person.person_sex',
                #'width' => '10%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return $model['person']['person_sex'] == 1 ? 'ชาย' : 'หญิง';
                },
            ],
            [
                'attribute' => 'person.person_age',
                #'width' => '10%',
                'noWrap' => TRUE,
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return (substr($model['person_screen_date'], 0, 4) - substr($model['person']['person_birthdate'], 0, 4)) . ' ปี';
                },
            ],
            [
                'attribute' => 'person_screen_bmi',
                'noWrap' => TRUE,
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'value' => function ($model) {
                    return @number_format($model->person_screen_weight / pow(($model->person_screen_height / 100), 2), 2);
                },
            ],
            [
                'attribute' => 'person_screen_weight',
                'noWrap' => TRUE,
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'person_screen_height',
                'noWrap' => TRUE,
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            [
                'label' => 'โรคประจำตัว',
                'attribute' => 'person.person_chronic',
                #'width' => '10%',
                'noWrap' => TRUE,
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return in_array($model['person']['person_chronic'], [1, 2, 3]) ? 'มี' : 'ไม่มี';
                },
            ],
            [
                'label' => 'ความดันโลหิต',
                'attribute' => 'person_screen_sbp',
                'noWrap' => TRUE,
                'hAlign' => 'center',
                'format' => 'raw',
                'vAlign' => 'middle',
                'value' => function ($model) {
                    return $model->person_screen_sbp . '/' . $model->person_screen_dbp;
                },
            ],
            [
                'label' => 'ค่าน้ำตาลในเลือด',
                'attribute' => 'person_screen_fbs',
                'noWrap' => TRUE,
                'hAlign' => 'center',
                'format' => 'raw',
                'vAlign' => 'middle',
                'value' => function ($model) {
                    return $model->person_screen_fbs . ' mg/dL';
                }
            ],
            [
                'attribute' => 'person_screen_pulse',
                'noWrap' => TRUE,
                'visible' => 0,
                'hAlign' => 'center',
                'format' => 'raw',
                'vAlign' => 'middle',
            ],
            [
                'label' => 'ผู้สำรวจ',
                'attribute' => 'whoRecord.fullname',
                #'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 0,
            ],
            [
                'label' => 'หน่วยงานผู้รับผิดชอบ',
                'attribute' => 'person.dep.department_name',
                #'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 0,
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-none">ประวัติการสำรวจ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalContent" class="m-2"></div>
        </div>
    </div>
</div>
