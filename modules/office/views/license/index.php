<?PHP

use yii\helpers\Url;
//use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;
use yii\bootstrap4\Html;
use app\modules\office\components\Ccomponent as CC;

$cc = @number_format($dataProvider->getTotalCount(), 0);
$url = Url::to(['create']);
$url2 = Url::to(['update']);
$urlFF = Url::to(['ff']);
$js = <<<JS
 //$("#cc_items_{$_GET['view']}").html('({$cc})');
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


$(".btnUpdate").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url2}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
          $("#paper-label").html('แก้ไขข้อมูลทะเบียนรถยนต์');
});
$(".btnPopup").click(function(event){
       $("#paper-label").html('เพิ่มทะเบียนรถยนต์');
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       $.get("{$url}", function(data) {
           $("#modalContents").html(data);
       });

});
JS;
?>
<div class="card">
    <div class="card-header d-block">
        <h4 class="card-title">ระบบข้อมูลทะเบียนรถยนต์เจ้าหน้าที่ รพ.</h4>
        <p class="mb-0 subtitle">เข้า-ออกบริเวณบ้านพัก รพ.</p>
    </div>
    <div class="m-2">
        <div class="alert alert-primary alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"><span><i class="mdi mdi-btn-close"></i></span>
            </button>
            <div class="media">
                <div class="media-body">
                    <h5 class="mt-1 mb-1 font-weight-bold">แจ้งให้ทราบ</h5>
                    <p class="mb-0"> ให้ตรวจสอบข้อมูลทะเบียนรถยนต์ของท่าน ที่ลงทะเบียนไว้กับระบบ Online หากพบข้อมูลไม่ถูกต้อง สามารถติดต่อได้ที่กลุ่มงานบริหารทั่วไป โทร. 1417</p>
                </div>
            </div>
        </div>
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?PHP
        Pjax::begin(['id' => 'pjGview', 'timeout' => false, 'enablePushState' => false]); //
        $this->registerJs($js, $this::POS_READY);
        echo GridView::widget([
            'id' => 'gviewBRN',
            'dataProvider' => $dataProvider,
            // 'tableOptions' => ['class' => ' '],
            //'containerOptions' => ['class' => ''],
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
            'bordered' => TRUE,
            'condensed' => FALSE,
            'export' => FALSE,
            //'perfectScrollbar' => TRUE,
            'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
            'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => '#',
                    'noWrap' => TRUE,
                    'attribute' => 'id',
                    'visible' => 1,
                    'format' => 'raw',
                    'value' => function ($model) {
                        return''
                        . ' <span type="button" class="badge badge-warning btnUpdate" data-id="' . $model->id . '">แก้ไข</span> '
                        //. ' <span type="button" class="badge badge-warning">ขอแก้ไข</span> '
                        . ' <span class="badge badge-' . ($model->traffic_status == 1 ? 'success' : 'danger') . ' light">' . ($model->traffic_status == 1 ? 'ใช้งานอยู่' : 'ปิดใช้งาน') . '</span>';
                        //return yii\helpers\Html::button('ใช้งาน', ['class' => 'btn btn-xs btn-primary']);
                    }
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'สถานะล่าสุด',
                    'noWrap' => TRUE,
                    'attribute' => 'traffic_number',
                    'visible' => 0,
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<span class="badge badge-success light">จอดอยู่ รพ.</span> <span class="badge badge-warning light">ออกไปแล้ว</span>';
                        //return '<span class="badge badge-success light">ใช้งานอยู่</span>';
                    }
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'contentOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'ทะเบียนรถ',
                    'noWrap' => TRUE,
                    'attribute' => 'traffic_number',
                    'hAlign' => 'center',
                    'visible' => 1,
                    'format' => 'raw',
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'สถานะเจ้าหน้าที่',
                    //'noWrap' => TRUE,
                    'attribute' => 'traffic_owner',
                    'visible' => 1,
                    'format' => 'raw',
                    'value' => function ($model) {

                        return(@$model->emp->employee_status == 1 ? 'เจ้าหน้าที่ รพ.' : '');
                    }
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'ชื่อเจ้าหน้าที่',
                    // 'noWrap' => TRUE,
                    'attribute' => 'traffic_owner',
                    'visible' => 1,
                    'format' => 'raw',
                    'value' => function ($model) {
                        return @$model->emp->employee_fullname;
                    }
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'หน่วยงาน',
                    // 'noWrap' => TRUE,
                    'attribute' => 'traffic_owner',
                    'visible' => 1,
                    'format' => 'raw',
                    'value' => function ($model) {
                        return @$model->emp->dep->employee_dep_label;
                    }
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'ชื่อที่ลงเบียน',
                    //'noWrap' => TRUE,
                    'attribute' => 'traffic_owner',
                    'visible' => 1,
                    'format' => 'raw',
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'หมายเหตุ',
                    //'noWrap' => TRUE,
                    'attribute' => 'comments',
                    'visible' => 1,
                    'format' => 'raw',
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => '#',
                    'visible' => 1,
                    'format' => 'raw',
//            'value' => function ($model) {
//                return @$model->emp->employee_fullname;
//            }
                ],
            ],
        ]);
        Pjax::end();
        ?>

    </div>
</div>






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