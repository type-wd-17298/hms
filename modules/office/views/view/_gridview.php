<?PHP

use yii\helpers\Url;
//use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;
use yii\bootstrap4\Html;
use app\modules\office\components\Ccomponent as CC;

$cc = @number_format($dataProvider->getTotalCount(), 0);
$urlView = Url::to(['display']);
$url = Url::to(['create']);
$url2 = Url::to(['update']);
$urlFF = Url::to(['ff']);
$urlAcl = Url::to(['acknowledge']);

switch (@$_GET['view']) {
    case 'keep':

        break;
    case 'out':

        break;
    default:

        break;
}

$js = <<<JS
 $("#cc_items_{$_GET['view']}").html('({$cc})');
$(".btnAccept").click(function(){
        $.get("{$urlAcl}",{id:$(this).data("id")}, function(data) {
             Swal.fire({
                    icon: 'success',
                    title: 'คุณได้รับทราบตามเอกสารที่ส่งมาแล้ว !',
                    showConfirmButton: false,
                    timer: 1000
                  });
                $("#frmSearch").submit();
        });
    });

$(".btnFF").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$urlFF}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
       $("#paper-label").html($("#"+$(this).data("id")).html());
});

$(".btnView").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$urlView}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
       $("#paper-label").html($("#"+$(this).data("id")).html());
});
$(".btnUpdate").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url2}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
          $("#paper-label").html('แก้ไขหนังสือเวียน');
});
$(".btnPopup").click(function(event){
       $("#paper-label").html('สร้างหนังสือเวียน');
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url}", function(data) {
           $("#modalContents").html(data);

       });

});
JS;
Pjax::begin(['id' => 'pjGview', 'timeout' => false, 'enablePushState' => false]); //
$this->registerJs($js, $this::POS_READY);
echo GridView::widget([
    'id' => 'gviewBRN',
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => ' '],
    'containerOptions' => ['class' => ''],
    'pager' => [
        'maxButtonCount' => 5,
    ],
    'panel' => [
        'heading' => '',
        'type' => 'default',
        'before' => $this->render('_search', ['model' => $dataProvider]),
        'footer' => false,
    ],
    'panelTemplate' => '<div class="">
          {panelBefore}
          <div>{items}</div>
          {panelAfter}
          {panelFooter}
          <div class="text-center m-2 small">{summary}</div>
          <div class="text-center m-2 small">{pager}</div>
          </div>',
    'responsive' => false,
    'responsiveWrap' => false,
    'striped' => FALSE,
    'hover' => TRUE,
    'bordered' => FALSE,
    'condensed' => TRUE,
    'export' => FALSE,
    //'perfectScrollbar' => TRUE,
    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
    'columns' => [
        // ['class' => 'yii\grid\SerialColumn'],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => '',
            //'attribute' => 'paperless_level',
            'format' => 'raw',
            'visible' => 1,
            'noWrap' => TRUE,
            //'width' => '1%',
            //'contentOptions' => ['class' => 'small'],
            'vAlign' => 'top',
            //'visible' => (\Yii::$app->user->can('SuperAdmin')),
            'value' => function ($model) {
                $employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;

                return '<div class="btn-group btn-group-xs text-center">
                    <a class="btn btn-primary btn-xs btnAccept" data-id="' . $model->paperless_view_id . '" href="javascript:;"><i class="fa-solid fa-share-from-square"></i><br>จัดเก็บ</a>
                    <a class="btn btn-primary btn-xs dropdown-item btnFF"  data-id="' . $model->paperless_view_id . '" href="javascript:;"><i class="fa-solid fa-people-arrows"></i><br>เวียนต่อ</a>'
                .
                (($model->employee_id == $employee_id || (\Yii::$app->user->can('SuperAdmin'))) ? ('<a class="btn btn-dark btn-xs btnUpdate" href="javascript:;" data-id="' . $model->paperless_view_id . '"><i class="fa-solid fa-file-pen"></i><br>แก้ไข</a>') : '')
                . '</div>';
                /*
                  return '<div class="btn-group">
                  <a class="btn btnAccept" data-id="' . $model->paperless_view_id . '" href="javascript:;"><i class="fa-solid fa-share-from-square"></i> จัดเก็บ</a>
                  <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa-solid fa-ellipsis-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
                  <a class="dropdown-item btnPopup d-none" href="javascript:;"><i class="fa-solid fa-check-double"></i> มอบหมายงาน</a>
                  <a class="dropdown-item btnFF"  data-id="' . $model->paperless_view_id . '" href="javascript:;"><i class="fa-solid fa-people-arrows"></i> เวียนต่อ</a>
                  <div class="dropdown-divider"></div>' .
                  (($model->employee_id == $employee_id || (\Yii::$app->user->can('SuperAdmin'))) ? ('<a class="dropdown-item btnUpdate" href="javascript:;" data-id="' . $model->paperless_view_id . '"><i class="fa-solid fa-file-pen"></i> แก้ไข</a>') : '')
                  . '</div>
                  </div>';
                 *
                 */
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'หนังสือวันที่',
            //'attribute' => 'paperless_level',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'hAlign' => 'center',
            'visible' => 0,
            'value' => function ($model) {
                $date = Ccomponent::getThaiDate(($model['paperless_view_startdate']), 'S', 0);
                return '<span class="badge badge-rounded badge-primary"><i class="fa-solid fa-calendar-days"></i> ' . $date . '</span>';
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ประเภทหนังสือ',
            'attribute' => 'paperless_paper_ref',
            'format' => 'raw',
            //'contentOptions' => ['class' => 'small'],
            //'hAlign' => 'center',
            'visible' => 0,
            'value' => function ($model) {
                if (substr($model->paperless_paper_ref, 0, 3) == 'BNN') {
                    return '<i class="fa-regular fa-note-sticky"></i> หนังสือภายใน';
                } elseif (substr($model->paperless_paper_ref, 0, 1) == 'A') {
                    return '<i class="fa-regular fa-note-sticky"></i> หนังสือภายใน';
                } else {
                    return '<i class="fa-regular fa-note-sticky"></i> หนังสือภายนอก';
                }
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'text-left'],
            'label' => 'เรื่อง',
            'width' => '50%',
            'vAlign' => 'top',
            'attribute' => 'paperless_paper_ref',
            'format' => 'raw',
            //'group' => true, // enable grouping,
            //'groupedRow' => true,
            //'noWrap' => TRUE,
            //'contentOptions' => ['class' => 'small'],
            //'hAlign' => 'center',
            'visible' => 1,
            'value' => function ($model) {

                if (substr($model->paperless_paper_ref, 0, 1) == 'A') {
                    $cf = @count($model->getUrlPdf($model->paperless_paper_ref));
                } else {
                    $cf = @count($model->paper->getUrlPdf($model->paperless_paper_ref));
                }

                $date = Ccomponent::getThaiDate(($model['paperless_view_startdate']), 'S', 1);
                if (substr($model->paperless_paper_ref, 0, 1) == 'A') {
                    $topic = $model->paperless_topic;
                    $html = @$model->level->paperless_level == '' ? '' : Html::tag('div', @$model->level->paperless_level, ['class' => 'badge badge-xs light badge-' . @$model->level->paperless_level_color]);

                    $html .= Html::tag('span', ' ' . $topic, ['class' => 'font-weight-bold']);
                } else {
                    $topic = @$model->paper->paperless_topic;
                    $html = @$model->paper->level->paperless_level == '' ? '' : Html::tag('div', @$model->paper->level->paperless_level, ['class' => 'badge badge-xs light badge-' . @$model->paper->level->paperless_level_color]);

                    //$date = Ccomponent::getThaiDate(($model['paperless_view_startdate']), 'S', 0);
                    //$date = '<span class="badge badge-rounded badge-primary"><i class="fa-solid fa-calendar-days"></i> ' . $date . '</span>';

                    $html .= Html::tag('span', ' ' . $topic, ['class' => 'font-weight-bold ']) . ' '; //Html::tag('div', $model->paper->bn . ' (' . $model->paper->fm . ')', ['class' => 'small']) . $date;
                }

                $html2 = '<div class="listline-wrapper small">
		<span class="item small"><i class="fa-solid fa-clock"></i>' . $date . '</span>
		<span class="item small"><i class="fa-solid fa-user-tag"></i>' . $model->emp->employee_fullname . '</span>
		<span class="item small"> <i class="fa-solid fa-file-pdf"></i>' . ($cf > 0 ? $cf : 0) . '</span>
		</div>';

                //$html = ' ' . Html::tag('b', '' . $topic, ['class' => 'text-primary']);
                return Html::a($html, 'javascript:;', [
                    'class' => 'btnView ',
                    'id' => $model->paperless_paper_ref,
                    'data' => ['id' => $model->paperless_paper_ref,],]) . $html2;
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'label' => 'ผู้เวียน',
            'noWrap' => TRUE,
            'attribute' => 'employee_id',
            'visible' => 0,
            'format' => 'raw',
            'value' => function ($model) {
                return @$model->emp->employee_fullname;
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'small'],
            'label' => 'หน่วยงานที่เกี่ยวข้อง',
            'attribute' => 'paperless_view_deps',
            'vAlign' => 'top',
            'format' => 'raw',
            'visible' => 1,
            // 'visible' => \Yii::$app->user->can('SuperAdmin'),
            'value' => function ($model) {
                if ($model->paperless_view_deps == '') {
                    $html = "-";
                } else {
                    $data = CC::getListDep($model->paperless_view_deps);
                    $html = @implode(", ", $data);
                }
                return Html::tag('div', $html, ['class' => 'small']);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold text-left'],
            'contentOptions' => ['class' => 'text-left small'],
            'label' => 'เจ้าหน้าที่ที่เกี่ยวข้อง',
            'vAlign' => 'top',
            'visible' => 1,
            'attribute' => 'paperless_view_emps',
            'format' => 'raw',
            //'visible' => \Yii::$app->user->can('SuperAdmin'),
            'value' => function ($model) {
                if ($model->paperless_view_emps == '') {
                    $html = "-";
                } else {
                    $data = CC::getListStaff($model->paperless_view_emps);
                    $html = @implode(", ", $data);
                }
                //return '<span class="d-inline-block text-truncate" style="max-width: 200px;">' . $model->receiver . '</span>';
                return Html::tag('div', $html, ['class' => 'small']);
            }
        ],
        [
            'headerOptions' => ['class' => 'font-weight-bold'],
            'contentOptions' => ['class' => 'font-weight-bold'],
            'label' => 'เอกสารแนบ',
            'vAlign' => 'top',
            //'attribute' => 'paperless_official_from',
            'hAlign' => 'center',
            'format' => 'raw',
            'visible' => 1,
            'value' => function ($model) {
                //$topic = $model->paper->paperless_topic;
                if (substr($model->paperless_paper_ref, 0, 1) == 'A') {
                    $cf = @count($model->getUrlPdf($model->paperless_paper_ref));
                } else {
                    $cf = @count($model->paper->getUrlPdf($model->paperless_paper_ref));
                }
                return Html::a(($cf > 0 ? "{$cf} <i class='fa-regular fa-file-pdf fa-lg text-danger'></i>" : '-'), 'javascript:;', [
                    'class' => 'btnView',
                    'data' => [
                        'id' => @$model['paperless_paper_ref'],
                        'toggle' => 'tooltip',
                        'placement' => 'right',
                    ],]);
            }
        ],
    ],
]);
Pjax::end();
?>
<!-- Modal -->
<div class="modal fade  bg-success-light" id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title font-weight-bold">
                    <div class="clearfix">
<!--                        <a href="javascript:void()" class="btn btn-primary px-3 my-1 light me-2"><i class="fa fa-reply"></i> </a>
                        <a href="javascript:void()" class="btn btn-primary px-3 my-1 light me-2"><i class="fas fa-arrow-right"></i> </a>
                        <a href="javascript:void()" class="btn btn-primary px-3 my-1 light me-2"><i class="fa fa-trash"></i></a>
                        -->
                        <div id="paper-label" class="h3">หนังสือเวียน</div>
                    </div>
                </div>

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