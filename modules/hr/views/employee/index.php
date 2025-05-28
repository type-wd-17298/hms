<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\widgets\SwitchInput;
use app\components\Cdata;
use app\components\Ccomponent;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);

Pjax::begin(['id' => 'gridView01', 'timeout' => false, 'enablePushState' => false]); //
$url = Url::to(['default/executive']);
$url2 = Url::to(['default/executive']);
$urlDisplay = Url::to(['set-active']);
$js = <<<JS
function setDisplay(id,d){
        $.get("{$urlDisplay}",{id:id,status:d}, function(data) {
               $.pjax.reload({container:"#gridView01"});  //Reload GridView
        });
};
JS;
$this->registerJs($js, $this::POS_READY);

$js = <<<JS
$(".btnPopup").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       //event.preventDefault();
       $.get("{$url}",{id:$(this).data("id")},  function(data) {
           $("#modalContents").html(data);
       });
});
/*
$(".btnFilters").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       event.preventDefault();
       $.get("{$url2}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
});
*/
$(".widget-stat").hover(
   function () {
    $(this).addClass('bg-primary-light');
  },
  function () {
    $(this).removeClass('bg-primary-light');
  }
);

JS;
$this->registerJs($js, $this::POS_READY);
$this->title = 'ระบบบริหารงานบุคคล : จัดการข้อมูลบุคลากร';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCss('.popover-x {display:none}');
?>

<h3 class="text-primary font-weight-bold"><i class="fas fa-solid fa-users-cog"></i> <?= $this->title ?></h3>
<div class="row d-none1">
<?PHP
foreach ($empType as $key => $value) {
    ?>
        <div class="col-xl-3 col-xxl-4 col-lg-4 col-sm-4">
            <div class="widget-stat card" onclick='$("#type").val("<?= $value['employee_type_id'] ?>");$("#frmIndex").submit();'>
                <a href="javascript:;">
                    <div class="card-body p-4" >
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            <div class="media-body">
                                <p class="mb-1 font-weight-bold text-primary"><?= $value['employee_type_name'] ?></p>
                                <h4 class="mb-0"><?= @number_format($empData['cc'][$value['employee_type_id']]) ?></h4>
                                <span class="badge badge-primary"><?= @number_format(($empData['cc'][$value['employee_type_id']] * 100) / $empData['sum']) ?>%</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
<?PHP } ?>
    <div class="col-xl-3 col-xxl-4 col-lg-4 col-sm-4">
        <div class="widget-stat card" onclick='$("#type").val("");$("#frmIndex").submit();'>
            <a href="javascript:;">
                <div class="card-body p-4" >
                    <div class="media ai-icon">
                        <span class="me-3 bgl-primary text-primary">
                            <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <div class="media-body">
                            <p class="mb-1 font-weight-bold text-primary">ทั้งหมด</p>
                            <h4 class="mb-0"><?= @number_format((float) $empData['sum'], 0) ?></h4>
                            <span class="badge badge-primary">100%</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
<div id="reportSmartCard"></div>
<?PHP
echo GridView::widget([
    'id' => 'gview01',
    'dataProvider' => @$dataProvider,
    'tableOptions' => ['class' => 'table verticle-middle table-responsive-md'],
    'panel' => [
        'heading' => '',
        'type' => 'primary',
        'before' => $this->render('_search', ['model' => @$dataProvider]),
        'footer' => false,
    ],
    'panelBeforeTemplate' => '{before}',
    'panelTemplate' => '<div class="">
  {panelBefore}
  {items}
  {panelAfter}
  {panelFooter}
  <div class="text-center m-2">{summary}</div>
  <div class="text-center m-2">{pager}</div>
  <div class="clearfix"></div>
  </div>',
    'responsiveWrap' => FALSE,
    'striped' => TRUE,
    'hover' => TRUE,
    'bordered' => FALSE,
    'condensed' => TRUE,
    'export' => FALSE,
    //'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
    'exportContainer' => ['class' => ''],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เลขบัตรประชาชน',
            'attribute' => 'employee_cid',
            'format' => 'raw',
            //'vAlign' => 'middle',
            //'width' => '1%',
            'noWrap' => TRUE,
            //'hAlign' => 'center',
            'visible' => 0,
            'value' => function ($model) {
                return Html::tag('b', Ccomponent::FnIDX($model['employee_cid']));
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'รูป',
            'format' => 'raw',
            'width' => '5%',
            'noWrap' => TRUE,
            'value' => function ($model) {
                $userProfile = Cdata::getDataUserAccount($model->employee_cid);
                if (!empty($userProfile['pictureUrl']))
                    return @Html::img($userProfile['pictureUrl'], ['class' => 'img-fluid img-thumbnail img-responsive float-left mr-1', 'width' => '60']);
            },
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ชื่อ-นามสกุล',
            'attribute' => 'employee_fullname',
            'format' => 'raw',
            'width' => '30%',
            //'vAlign' => 'middle',
            'visible' => 1,
            'value' => function ($model) {
                $topic = Html::tag('b', Ccomponent::FnIDX($model['employee_cid'])) . '<br>';
                $topic .= Html::tag('span', ' ' . $model['employee_fullname'] . ' ' . " ({$model['employee_id']})", ['class' => '']);
                $head = $model->getHead();

                if (is_array($head)) {
                    $headText = '<br>';
                    foreach ($head as $value) {
                        $headText .= Html::tag('div', $value['executive'] . ' ' . $value['dep'], ['class' => 'badge badge-xs  badge-primary mr-1']);
                    }
                }

                return Html::a($topic . @$headText, 'javascript:;', [
                    'class' => 'btnPopup',
                    'data' => [
                        'id' => @$model['employee_id'],
                        'toggle' => 'tooltip',
                        'placement' => 'right',
                    ],]);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ลายเซนต์',
            'attribute' => 'posit',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'vAlign' => 'middle',
            'visible' => 1,
            'value' => function ($model) {
                $filename = $model->getUploadPath() . '../laysen/' . $model->employee_id . '.jpg';
                $UrlFileName = $model->getUploadUrl() . '../laysen/' . $model->employee_id . '.jpg';
                return (file_exists($filename) ? Html::img($UrlFileName, ['height' => '30',]) : '');
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'อายุ',
            'attribute' => 'posit',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'vAlign' => 'middle',
            'visible' => 1,
            'value' => function ($model) {
                return;
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ตำแหน่ง',
            'attribute' => 'posit',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'vAlign' => 'middle',
            'visible' => 1,
//            'value' => function ($model) {
//                return'<span class="badge badge-rounded badge-primary sweet-message">สำเร็จ</span>';
//            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ประเภท',
            'attribute' => 'empType.employee_type_name',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'vAlign' => 'middle',
            'visible' => 1,
//            'value' => function ($model) {
//                return'<span class="badge badge-rounded badge-primary sweet-message">สำเร็จ</span>';
//            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'หน่วยงาน',
            'attribute' => 'employee_dep_id',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'vAlign' => 'middle',
            'visible' => 1,
            'value' => function ($model) {
                return @$model->dep->employee_dep_label;
            },
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'skip-export'],
            'label' => 'การใช้งาน',
            'attribute' => 'employee_status',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'vAlign' => 'middle',
            'visible' => 1,
            'value' => function ($model) {
                if ($model->employee_status == '1') {
                    return'<span class="badge badge-rounded badge-primary sweet-message">ปกติ</span>';
                } else {
                    return'<span class="badge badge-rounded badge-danger sweet-message">ไม่ใช้งาน</span>';
                }
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'สถานะใช้งาน',
            'attribute' => 'employee_status',
            'width' => '1%',
            'vAlign' => 'middle',
            'format' => 'raw',
            'value' => function ($model) {
                $status = $model->employee_status ? 0 : 1;
                return SwitchInput::widget([
                    'name' => 'status_' . $model->employee_id,
                    'value' => $model->employee_status,
                    'pluginEvents' => [
                        "switchChange.bootstrapSwitch" => "function(){ setDisplay('{$model->employee_id}','{$status}'); }",
                    ],
                    'containerOptions' => [],
                    'pluginOptions' => [
                        'size' => 'mini',
                        #'class' => '',
                        'onColor' => 'primary',
                        'offColor' => 'danger',
                    ]
                        //'tristate' => true
                ]);
                //}
            },
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'skip-export'],
            'label' => 'สถานะ',
            'attribute' => 'budgetyear',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'vAlign' => 'middle',
            'visible' => 0,
            'value' => function ($model) {
                return '<div class="dropdown custom-dropdown mb-0">
      <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown" aria-expanded="false">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g></svg>
      </div>
      <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">
      <a class="dropdown-item btnPopup" data-id="' . $model->employee_id . '" href="javascript:void();">เพิ่มรายการ</a>
      <a class="dropdown-item btnPopup" data-id="' . $model->employee_id . '" href="javascript:void();">จัดการตำแหน่ง</a>
      <a class="dropdown-item btnPopup" data-id="' . $model->employee_id . '" href="javascript:void();">ย้ายแผนก</a>
      <a class="dropdown-item text-danger" data-id="' . $model->employee_id . '" href="javascript:void();">ยกเลิกรายการ</a>
      </div>
      </div>';
            }
        ],
        [
            'contentOptions' => ['class' => 'skip-export'],
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
                                'id' => @$model['memorandum_id'],
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
<?php Pjax::end(); ?>
<!-- Modal -->
<div class="modal fade " id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">ข้อมูลเจ้าหน้าที่</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div id="modalContents" class="m-2"></div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

