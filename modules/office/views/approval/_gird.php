<?php

use app\modules\office\models\PaperlessApproval;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use app\modules\office\components\Ccomponent as CC;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'การขออนุญาตไปราชการ';
$this->params['breadcrumbs'][] = $this->title;
Pjax::begin(['id' => 'pjGview', 'timeout' => false, 'enablePushState' => false]); //
$url = Url::to(['operate']);
$urlDelete = Url::to(['delete']);
$js = <<<JS
$(".btnOper").click(function(){
       var id = $(this).data('id');
       $("#modalContent").html('');
       $('#modalPapaer').modal('show');
        $.get("{$url}",{id:$(this).data("pid")}, function(data) {
           $("#modalContent").html(data);
        });
    });
$(".btnDelete").click(function(event){
       var id = $(this).data('id');
       Swal.fire({
            icon: 'error',
            title: 'ยืนยันการลบนี้หรือไม่?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'ลบข้อมูล',
            denyButtonText: 'ยกเลิก',
          }).then((result) => {
            if (result.isConfirmed) {
                $.post('{$urlDelete}',{id:id}, function(data) {
                        if(data.status == 'success'){
                            Swal.fire({
                                icon: 'success',
                                html:data.message,
                                title: '<strong>ผลการดำเนินการ</strong>',
                                showConfirmButton: false,
                                timer: 3000
                          });
                                $.pjax.reload({container: '#pjGview', async: false});
                                //$("#frmSearch").submit();
                        }else{
                        Swal.fire({
                                icon: 'error',
                                html:data.message,
                                title: '<strong>ผลการดำเนินการ</strong>',
                                showConfirmButton: false,
                                timer: 3000
                          });
                        }
                      });
        } else if (result.isDenied) {

        }
       });
});
JS;
$this->registerJs($js, $this::POS_READY);
?>

<div class="paperless-approval-index">
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'maxButtonCount' => 5,
        ],
        'rowOptions' => function ($model, $key, $index, $widget) {
            if (\Yii::$app->user->can('HRdAdmin'))
                return ['class' => ''];
            //แสดงรายการที่เกี่ยวข้อง
            if ($model->pcheck == 1)
                return ['class' => 'bg-primary-light'];
        },
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
        //'responsive' => false,
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
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'vAlign' => 'top',
                'class' => 'kartik\grid\SerialColumn',
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'label' => 'สถานะ',
                'noWrap' => TRUE,
                'width' => '1%',
                'vAlign' => 'top',
                'attribute' => 'approval_status_id',
                'format' => 'raw',
                'value' => function ($model) {
                    $status = @$model->approvalStatus;
                    if (!empty($model->lastProcess->receiver->employee_fullname))
                        $who = '<br><small>(' . @$model->lastProcess->receiver->employee_fullname . ')</small>';

                    if (!empty($model->lastProcess->staff->employee_fullname) && $model->approval_status_id == 'A08')
                        $who = '<br><small>(' . @$model->lastProcess->staff->employee_fullname . ')</small>';

                    if (in_array($model->approval_status_id, ['A10'])) {
                        return '<span class="btn-block badge badge-' . @$status->approval_status_color . '"><i class="fa-solid fa-sheet-plastic"></i> ' . @$status->approval_status_name . @$who . '</span>';
                    } else {
                        return '<span class="btn-block badge badge-' . @$status->approval_status_color . '"><i class="fa-solid fa-sheet-plastic"></i> ' . @$status->approval_status_name . @$who . '</span>';
                    }
                }
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ดำเนินการ',
                //'noWrap' => TRUE,
                'vAlign' => 'top',
                'attribute' => 'status_id',
                'visible' => @$_GET['view'] <> 'keep',
                'format' => 'raw',
                'value' => function ($model) {
                    return $html = Html::a('ดำเนินการ', 'javascript:;', ['class' => 'font-weight-bold btnOper', 'data' => ['pid' => $model->approval_id]]);
                }
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'attribute' => 'topic',
                'vAlign' => 'top',
                'format' => 'raw',
                'value' => function ($model) {
                    $vehicle = $model->type->vehicle_type;
                    if ($model->travelby == 1 && $model->driver == 'Y')
                        $vehicle = $vehicle . " (พร้อมพนักงานขับรถ)";
                    if ($model->travelby == 2)
                        $vehicle = $vehicle . " (ทะเบียน {$model->vehicle_personal})";

                    $cc = 0;
                    if ($model->employee_id == '') {
                        $emp = "-";
                    } else {
                        $data = CC::getListStaff($model->employee_id);
                        $emp = @implode(", ", $data);
                        $cc = @count($data);
                    }
                    $html = Html::a($model->topic, 'javascript:;', ['class' => 'font-weight-bold btnOper', 'data' => ['pid' => $model->approval_id]])
                            . Html::tag('div', 'ณ ' . $model->place, ['class' => 'small'])
                            . Html::tag('div', 'โดย ' . $vehicle, ['class' => 'small']);
                    if ($cc > 0)
                        $html = $html . Html::tag('div', "พร้อมเจ้าหน้าที่ {$cc} คน", ['class' => 'small']);
                    return $html;
                }
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'vAlign' => 'top',
                'noWrap' => TRUE,
                'attribute' => 'employee_own_id',
                'format' => 'raw',
                //'hAlign' => 'right',
                'value' => function ($model) {
                    return $model->emps->employee_fullname;
                }
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'vAlign' => 'top',
                'noWrap' => TRUE,
                'attribute' => 'startdate',
                'format' => 'raw',
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model->startdate), 'S', 0) . ' - ' . Ccomponent::getThaiDate(($model->enddate), 'S', 0);
                }
            ],
//            [
//                'vAlign' => 'top',
//                'noWrap' => TRUE,
//                'attribute' => 'travelby',
//                'format' => 'raw',
//                'value' => function ($model) {
//                    $html = $model->type->vehicle_type;
//                    if ($model->travelby == 1 && $model->driver == 'Y')
//                        $html = $html . " (พร้อมพนักงานขับรถ)";
//                    if ($model->travelby == 2)
//                        $html = $html . " (ทะเบียน {$model->vehicle_personal})";
//                    return $html;
//                }
//            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'vAlign' => 'top',
                //'noWrap' => TRUE,
                'attribute' => 'withdraw',
                'visible' => @$_GET['view'] <> 'keep',
                'format' => 'raw',
                'value' => function ($model) {
                    $html = $model->type->vehicle_type;
                    if ($model->withdraw == '4') {
                        $html = "ขอเบิกอื่นๆ จาก" . $model->withdraw_from;
                    } else {
                        $html = $model->budget->budget_type;
                    }
                    return $html;
                }
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'เอกสารแนบ',
                'visible' => @$_GET['view'] <> 'keep',
                'vAlign' => 'top',
                'attribute' => 'approval_costs',
                'hAlign' => 'center',
                'format' => 'raw',
                'value' => function ($model) {
                    $cf = @count($model->getUrlPdf($model->approval_id));
                    return Html::a(($cf > 0 ? "{$cf} <i class='fa-regular fa-file-pdf fa-lg text-danger'></i>" : '-'), 'javascript:;', [
                        'class' => 'btnView',
                        'data' => [
                            'id' => @$model['approval_id'],
                            'toggle' => 'tooltip',
                            'placement' => 'right',
                        ],]);
                }
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'vAlign' => 'top',
                'noWrap' => TRUE,
                'attribute' => 'approval_costs',
                'format' => 'decimal',
                'hAlign' => 'right',
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'vAlign' => 'top',
                'visible' => @$_GET['view'] <> 'keep',
                'noWrap' => TRUE,
                'format' => 'raw',
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, PaperlessApproval $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'approval_id' => $model->approval_id]);
//                },
                'value' => function ($model) {
                    $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
                    $html = '';
                    if ($model->budget->budget_yes == 'Y' && in_array($model->approval_status_id, ['A00', 'A08']))
                        $html .= '<a class="btn btn-primary" data-pjax="0"  href="' . Url::to(['view', 'approval_id' => $model->approval_id]) . '"><i class="fa-solid fa-baht-sign"></i> ค่าใช้จ่าย</a>';

                    if (in_array($model->approval_status_id, ['A00', 'A08'])) {
                        $html .= ' <a class="btn btn-dark" href="' . Url::to(['update', 'approval_id' => $model->approval_id]) . '"><i class="fa-solid fa-square-pen"></i> แก้ไข</a>';
                    } else {
                        //$html .= ' <a class="btn btn-dark" href="' . Url::to(['update', 'approval_id' => $model->approval_id]) . '"><i class="fa-solid fa-square-pen"></i> แก้ไข</a>';
                    }

                    if (in_array($model->approval_status_id, ['A00', 'A08']))
                        $html .= ' <a class="btn btn-danger btnDelete" href="javascript:;" data-id="' . $model->approval_id . '"><i class="fa-solid fa-trash-can"></i> ลบ</a>';

                    if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || $model->employee_own_id == $emp->employee_id)
                        return '<div class="btn-group  btn-group-sm"> ' . $html . ' </div>';
                }
            ],
        ],
    ]);
    ?>

</div>

<?PHP Pjax::end(); ?>

<div class="modal fade text-left" id="modalPapaer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document" id="modalPapaerContent">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel16">การขออนุญาตไปราชการ</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1">
                <div id="modalContent" class="m-2"></div>
            </div>
        </div>
    </div>
</div>