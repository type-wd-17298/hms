<?PHP

use kartik\grid\GridView;
use app\components\Ccomponent;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use miloschuman\highcharts\Highcharts;
use app\components\Cdata;

$mode = '';
$url = Url::to(['create']);
$url2 = Url::to(['update']);

$css = '.modal-xl {max-width: 90% !important;}  .blink {  animation: blink-animation 2s steps(5, start) infinite; -webkit-animation: blink-animation 2s steps(5, start) infinite; }';
$this->registerCss($css);

$js = <<<JS

$(".btnCreate").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url}",{}, function(data) {
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
Pjax::begin(['id' => 'gServiceView', 'timeout' => false, 'enablePushState' => false]); //
$this->registerJs($js, $this::POS_READY);
?>


<?=
GridView::widget([
    //'id' => 'gServiceView',
    'dataProvider' => $dataProvider,
    'panel' => [
        'type' => '',
        'heading' => '',
        'before' => $this->render('_search', ['model' => $dataProvider]),
    // 'footer' => FALSE,
    ],
    'panelTemplate' => '<div class="">
      {panelBefore}
      {items}
      {panelAfter}
      {panelFooter}
      </div>',
    'responsiveWrap' => FALSE,
    'striped' => FALSE,
    'hover' => FALSE,
    'bordered' => FALSE,
    'condensed' => FALSE,
    'export' => false,
    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none d-none'],
    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'vAlign' => 'top',
        ],
        [
            'label' => 'เลขที่หนังสือการตาย',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'font-weight-bold small'],
            'attribute' => 'dead_id_number',
            'vAlign' => 'top',
            'visible' => 1,
            'value' => function ($model) {
                return Yii::$app->params['dep_bookNumberPrefix'] . (int) substr($model->dead_id_number, 10);
            }
        ],
        [
            'label' => 'ลงวันที่',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'dead_create',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->dead_create, 'L');
            }
        ],
        [
            'label' => 'วันที่เสียชีวิต',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'dead_date',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                return Ccomponent::getThaiDate($model->dead_date, 'L');
            }
        ],
        [
            'label' => 'ข้อมูลผู้เสียชีวิต',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'dead_infomation',
            'format' => 'raw',
        ],
        [
            'label' => 'หน่วยงาน',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'department_id',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->dep->employee_dep_label;
            }
        ],
        [
            'label' => 'ชื่อผู้แจ้ง',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'employee_id',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->emp->employee_fullname;
            }
        ],
        [
            'label' => '#',
            'headerOptions' => ['class' => 'font-weight-bold small'],
            'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            'attribute' => 'employee_id_staff',
            'format' => 'raw',
            'value' => function ($model) {
                //return $model->empStaff->employee_fullname;
            }
        ],
    ],
]);
?>
<?PHP Pjax::end(); ?>
<!-- Modal -->
<div class="modal fade  bg-success-light" id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font-weight-bold">ขอเลขหนังสือรับรองการตาย</h3>
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
