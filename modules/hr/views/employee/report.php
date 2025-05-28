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
//$("[data-toggle=tooltip").tooltip();
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
    //  'export' => TRUE,
    //'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
    'exportContainer' => ['class' => ''],
    'rowOptions' => function ($model) {
        $filename = $model->getUploadPath() . '../laysen/' . $model->employee_id . '.jpg';
        if (file_exists($filename)) {
            return ['class' => 'd-none'];
        }
    },
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
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
            'label' => 'ชื่อ-นามสกุล',
            'attribute' => 'employee_fullname',
            'format' => 'raw',
            'width' => '30%',
            //'vAlign' => 'middle',
            'visible' => 1,
            'value' => function ($model) {
                $topic = Html::tag('span', ' ' . $model['employee_fullname'] . ' ' . " ({$model['employee_id']})", ['class' => '']);
                return $topic;
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
    ],
]);
?>

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
<?php Pjax::end(); ?>
