<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
#use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use app\modules\survay\models\Thaiaddress;

$this->title = 'ทะเบียนผู้รับการสำรวจ';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::to(['screen/history']);
$js1 = <<<JS
     //$("[data-toggle=tooltip").tooltip();

    $(".btnFilter").click(function(event){
        $("#vcode").val($(this).data('vcode'));
        $("#vmoo").val($(this).data('vmoo'));
        event.preventDefault();
        $("#frmIndex").submit();
    });

JS;
$this->registerJs($js1, $this::POS_READY);
//$this->registerJsFile('//maps.googleapis.com/maps/api/js?key=' . Yii::$app->params['googleMapToken']);
?>

<div class="row">
    <div class="col-md-3 d-none1">
        <div class="text-primary h5">
            <i class="fas fa-solid fa-home"></i>
            ข้อมูลหมู่บ้าน
        </div>
        <div class="card">
            <div class="m-1">
                <?php Pjax::begin(['id' => 'pjax-gridview2', 'timeout' => false, 'enablePushState' => false]); ?>
                <?=
                GridView::widget([
                    /*
                      'panel' => [
                      'heading' => '',
                      'type' => '',
                      'before' => Html::a('<i class="fa-solid fa-plus fa-lg"></i> ', 'javascript:;', ['id' => 'btnVillage', 'class' => 'btn btn-primary']),
                      //'before' => $this->render('_search', ['model' => $dataProvider]),
                      'footer' => false,
                      ],
                     *
                     */
                    'panelTemplate' => '<div class="">
                    {panelBefore}
                    {items}
                    {panelAfter}
                    {panelFooter}
                </div>',
                    'layout' => '<div class="bg-white">{items}{pager}</div>',
                    'responsiveWrap' => FALSE,
                    'striped' => FALSE,
                    'bordered' => FALSE,
                    'export' => FALSE,
                    'hover' => TRUE,
                    'condensed' => TRUE,
                    'showPageSummary' => true,
                    #'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
                    #'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
                    'dataProvider' => $dataProvider_area,
                    'columns' => [
                        [
                            'label' => '#',
                            'attribute' => 'department_name',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'contentOptions' => ['class' => 'font-weight-bold text-right text-end'],
                            'group' => true,
                            'groupedRow' => true,
                        ],
                        [
                            'label' => '#',
                            'attribute' => 'label2',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'contentOptions' => ['class' => 'text-right text-end'],
                            'group' => true,
                            'groupedRow' => true,
                        ],
                        [
                            'label' => 'ชื่อหมู่บ้าน',
                            'attribute' => 'areapath',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'vAlign' => 'middle',
                            'value' => function ($model) {
                                //return '<div class="font-weight-bold">' . $model['areapath'] . ' หมู่ ' . $model['moo'] . '</div>';
                                return '<div class="font-weight-bold">' . $model['areapath'] . '</div>';
                            },
                            'pageSummary' => 'รวมทั้งหมด',
                        ],
                        [
                            'label' => 'จำนวนหลังคาเรือน',
                            'attribute' => 'cc_village',
                            #'width' => '10%',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'format' => ['decimal', 0],
                            'pageSummary' => true,
                        ],
                        [
                            'label' => 'จำนวนคน',
                            'attribute' => 'cc_person',
                            #'width' => '10%',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'format' => ['decimal', 0],
                            'pageSummary' => true,
                        ],
                        [
                            'label' => '#',
                            'width' => '2%',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'value' => function ($model) {

                                $return = '<div class="input-group input-group-sm"><div class="input-group-prepend">' .
                                        Html::a('แสดง', 'javascript:;', [
                                            'class' => 'btn  btn-success btnFilter',
                                            'data' => [
                                                'vcode' => $model['vcode'],
                                                'vmoo' => $model['moo'],
                                                'toggle' => 'tooltip',
                                                'placement' => 'right',
                                            ],
                                            'title' => 'แสดงข้อมูล',
                                        ])//
                                        #. Html::a('', ['update', 'id' => $model['person_id']], ['class' => 'btn  btn-primary'])
                                        #. Html::a('', ['update', 'id' => $model['person_id']], ['class' => 'btn  btn-primary active'])
                                        . '</div></div>';
                                return $return;
                            },
                        ]
                    ],
                ]);
                ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>


    <div class="col-md-9">
        <div class="text-primary h5"><i class="fas fa-solid fa-users-rectangle"></i> ทะเบียนผู้รับการสำรวจ</div>
        <div class="person-indexs">
            <?php Pjax::begin(['id' => 'pjax-gridview', 'timeout' => false, 'enablePushState' => false]); ?>

            <?PHP
            $js = <<<JS
    $('[data-toggle="tooltip"]').tooltip();

    $(".btnPopup").click(function(){
        $('#modalForm').modal('show');
        $.get("{$url}",{id:$(this).data("id")}, function(data) {
           $("#modalContent").html(data);
        });
    });
JS;
            $this->registerJs($js, $this::POS_READY);
            ?>
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
                //'bordered' => FALSE,
                #'export' => FALSE,
                'hover' => TRUE,
                //'pjax' => TRUE,
                'condensed' => TRUE,
                'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
                'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'label' => 'ดำเนินการ',
                        'width' => '1%',
                        'noWrap' => TRUE,
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        #'hAlign' => 'center',
                        'value' => function ($model) {
                            $return = '<div class="input-group input-group-sm"><div class="input-group-prepend">' .
                                    Html::a('สำรวจ', ['screen/create', 'id' => $model['person_id']], ['class' => 'btn  btn-secondary', 'data-pjax' => 0])
                                    . Html::a('', 'javascript:;', ['class' => 'btn  btn-primary'])
                                    #. Html::a('', ['update', 'id' => $model['person_id']], ['class' => 'btn  btn-primary active'])
                                    . '</div></div>';
                            return $return;
                        },
                    ],
                    [
                        'label' => 'คัดกรองล่าสุด',
                        'attribute' => 'person_birthdate',
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        #'width' => '1%',
                        'noWrap' => TRUE,
                        #'hAlign' => 'right',
                        'visible' => 0,
                        'value' => function($model) {
                            return Ccomponent::getThaiDate(($model['person_birthdate']), 'S', 1);
                        }
                    ],
                    [
                        'label' => 'กลุ่มสำรวจ',
                        'attribute' => 'person_type_id',
                        'noWrap' => TRUE,
                        #'width' => '5%',
                        'vAlign' => 'middle',
                        'hAlign' => 'center',
                        'value' => function ($model) {
                            return $model['personType']['person_type_name'];
                        },
                    ],
                    [
                        'attribute' => 'person_cid',
                        'noWrap' => TRUE,
                        #'width' => '5%',
                        'vAlign' => 'middle',
                        #'hAlign' => 'center',
                        'value' => function ($model) {
                            return Ccomponent::FnIDX($model['person_cid']);
                        },
                    ],
                    [
                        'attribute' => 'person_fullname',
                        'noWrap' => TRUE,
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        'value' => function ($model) {
                            return '<div class="font-weight-bold">' . Html::a($model['person_fullname'] . '</div>', ['update', 'id' => $model['person_id']], ['data-pjax' => 0]);
                        },
                    ],
                    [
                        'attribute' => 'person_sex',
                        #'width' => '10%',
                        'vAlign' => 'middle',
                        'hAlign' => 'center',
                        'value' => function ($model) {
                            return $model['person_sex'] == 1 ? 'ชาย' : 'หญิง';
                        },
                    ],
                    [
                        'attribute' => 'person_age',
                        #'width' => '10%',
                        'noWrap' => TRUE,
                        'vAlign' => 'middle',
                        'hAlign' => 'center',
                        'value' => function ($model) {

                            if ($model['person_birthdate'] == '0000-00-00') {
                                return '-';
                            } else {
                                return (date('Y') - substr($model['person_birthdate'], 0, 4)) . ' ปี';
                            }
                        },
                    ],
                    [
                        'attribute' => 'person_birthdate',
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        #'width' => '1%',
                        'visible' => 0,
                        'noWrap' => TRUE,
                        #'hAlign' => 'right',
                        'value' => function($model) {
                            return Ccomponent::getThaiDate(($model['person_birthdate']), 'S', 1);
                        }
                    ],
                    [
                        'attribute' => 'person_tel',
                        'noWrap' => TRUE,
                        'vAlign' => 'middle',
                        'visible' => 0,
                        #'hAlign' => 'center',
                        'value' => function ($model) {
                            return Ccomponent::FnMobile($model['person_tel']);
                        },
                    ],
                    [
                        'label' => 'ที่อยู่',
                        'attribute' => 'person_address_code',
                        'vAlign' => 'middle',
                        'noWrap' => TRUE,
                        #'hAlign' => 'center',
                        'value' => function ($model) {
                            return $model['person_address_no'] .
                                    ($model['person_address_moo'] <> '' ? ' ม.' . $model['person_address_moo'] : '')
                                    . @(isset($model['person_address_code']) ? ' ' . Thaiaddress::findOne($model['person_address_code'])->full_name : '');
                        },
                    ],
                    [
                        'label' => 'หน่วยงานผู้รับผิดชอบ',
                        'attribute' => 'dep.department_name',
                        #'noWrap' => TRUE,
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        'visible' => 0,
                    ],
                    [
                        'label' => '',
                        'width' => '1%',
                        'noWrap' => TRUE,
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        'hAlign' => 'center',
                        'value' => function ($model) {
                            $return = '<div class="input-group input-group-sm"><div class="input-group-append">' .
                                    Html::a('<i class="fa fa-solid fa-user-pen fa-lg"></i>', ['update', 'id' => $model['person_id']],
                                            ['class' => 'btn  btn-secondary',
                                                'data' => [
                                                    'toggle' => 'tooltip',
                                                    'placement' => 'right',
                                                    'pjax' => 0,
                                                ]
                                                , 'title' => 'แก้ไขข้อมูล'
                                            ]
                                    )
                                    /*
                                      . Html::a('<i class="fa-solid fa-map-location-dot fa-lg"></i>', ['update', 'id' => $model['person_id']],
                                      ['class' => 'btn  btn-primary']
                                      )

                                      . Html::a('<i class="fa-solid fa-fill fa-lg"></i>', ['update', 'id' => $model['person_id']],
                                      ['class' => 'btn  btn-primary']
                                      )
                                     *
                                     */
                                    . Html::a('<i class="fa fa-solid fa-square-poll-vertical fa-lg"></i>',
                                            'javascript:;',
                                            [
                                                'class' => 'btn  btn-danger active btnPopup',
                                                'data-toggle' => "tooltip",
                                                'data-placement' => "right",
                                                'title' => "แสดงข้อมูล",
                                                'data-id' => $model['person_id']]
                                    )
                                    . '</div></div>';
                            return $return;
                        },
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'width' => '2%',
                        'visible' => 0,
                        'noWrap' => TRUE,
                    ],
                ],
            ]);
            ?>

            <?php Pjax::end(); ?>

        </div>
    </div>
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