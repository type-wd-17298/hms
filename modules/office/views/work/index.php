<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
//use app\modules\edocument\components\Ccomponent as CC;
use app\components\Ccomponent;
use app\components\Cdata;
use yii\helpers\ArrayHelper;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$this->title = 'ระบบบริหารตารางเวร';
$this->params['breadcrumbs'][] = $this->title;
$userID = $user->employee_id;

//ตรวจสอบ topic
$this->params['mqttFuncCheck'] = <<<JS
  if(topic == 'hms/service/paper/update/L-{$userID}'){
  //$.pjax.reload({container: '#mainLeave', async: false});
  //$("#modalForm").modal('hide');
    $("#frmSearch").submit();
  }
  JS;
//ตรวจสอบ topic
$this->params['mqttSubTopics'] = <<<JS
  sub_topics('hms/service/paper/update/L-{$userID}');
  JS;

//Pjax::begin(['id' => 'mainLeave', 'timeout' => false, 'enablePushState' => false]);
$url = Url::to(['operate']);
$urlCreate = Url::to(['create']);
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
$(".btnUpdate").click(function(event){
       event.preventDefault();
       $("#modalContent").html('');
       $('#modalPapaer').modal('show');
       var url = $(this).attr("href");
       $.get(url, function(data) {
           $("#modalContent").html(data);
       });
});
$(".btnCreate").click(function(event){
       $("#modalContent").html('');
       $('#modalPapaer').modal('show');
       $.get('{$urlCreate}', function(data) {
           $("#modalContent").html(data);
       });
});

$(".btnDelete").click(function(event){
       var id = $(this).data('id');
       Swal.fire({
            icon: 'error',
            title: 'ยืนยันการลบใบลานี้หรือไม่?',
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
                                $.pjax.reload({container: '#mainLeave', async: false});
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
//$this->registerJs($js, $this::POS_READY);
$js = <<<JS
//------------------------------tabLink-------------------------------------------
$(".tabLink").click(function(event){
      $('.tab-paper').html('');
       var url = $(this).data('href');
       var e = $(this).attr('href');
        //alert(e);
          if($(this).attr('href') != '#paper-pdf1' ){
                $.get(url,{attr:e}, function(data) {
                      $(e).html(data);
                });
           }else{

            }


});
//init-Tab
function initTab(){
    var tab = $('#tabDefault');
    var url = tab.data('href');
    var e = tab.attr('href');
    $.get(url,{attr:e,mode:'oper'}, function(data) {
       $(e).html(data);
    });
}
initTab();
//------------------------------tabLink-------------------------------------------
JS;

$this->registerJs($js, $this::POS_READY);
?>
<?php if (Yii::$app->session->hasFlash('alert')): ?>
    <?php
    $op = ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options');
    ?>
    <div class="alert <?= $op['class'] ?> alert-dismissible fade show  mt-2" role="alert">
        <p class="mb-0">
            <?= ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body') ?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>

<?php endif; ?>
<div class="alert alert-danger left-icon-big alert-dismissible fade show d-none">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"><span><i class="mdi mdi-btn-close"></i></span>
    </button>
    <div class="media">
        <div class="alert-left-icon-big">
            <span><i class="mdi mdi-alert"></i></span>
        </div>
        <div class="media-body">
            <h5 class="mt-1 mb-2">แจ้งให้ทราบ</h5>
            <p class="mb-0">ระบบอยู่ในช่วงการทดสอบระบบ จะเปิดให้ใช้งานได้ในสัปดาห์หน้า ครับ</p>
        </div>
    </div>
</div>
<h3 class="font-weight-bold"><i class="fa-solid fa-database"></i> <?= $this->title ?></h3>
<div class="row d-none">
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-solid fa-mug-hot fa-2x"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">ลาพักผ่อน</p>
                        <h4 class="mb-0"><?= @$data['brn_year'] ?></h4>
                        <div class="badge badge-primary" ><h3 class="text-white">5/15</h3></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-solid fa-umbrella fa-2x"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">ลากิจส่วนตัว</p>
                        <h4 class="mb-0"><?= @$data['bsn_year'] ?></h4>
                        <div class="badge badge-primary" ><h3 class="text-white">0/3</h3></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-solid fa-notes-medical fa-2x"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">ลาป่วย</p>
                        <h4 class="mb-0"><?= @$data['bsn_year'] ?></h4>
                        <div class="badge badge-primary" ><h3 class="text-white">0/3</h3></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-solid fa-person-pregnant  fa-2x"></i>
                    </span>
                    <div class="media-body ">
                        <p class="mb-1 font-weight-bold">ลาคลอด/อื่นๆ</p>
                        <h4 class="mb-0"><?= @$data['bsn_year'] ?></h4>
                        <div class="badge badge-primary" ><h3 class="text-white">0</h3></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nav tabs -->
<div class="card">
    <div class="default-tab">
        <ul class="nav nav-tabs">
            <!--            <li class="nav-item">
                            <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-dashboard" data-href="<?= Url::to(['index', 'view' => 'dashboard']) ?>">
                                <i class="fa-solid fa-dashboard me-2"></i> รายงานสรุปวันลา
                            </a>
                        </li>-->
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold active" data-bs-toggle="tab"    id="tabDefault"  href="#paper-wait"   data-href="<?= Url::to(['index', 'view' => 'wait']) ?>"><i class="fa-solid fa-arrow-right-arrow-left me-2"></i> (อยู่ระหว่างดำเนินการ)</a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab"  href="#paper-history" data-href="<?= Url::to(['index', 'view' => 'history']) ?>"><i class="fa-solid fa-clock me-2"></i> ประวัติการแลกเวร</a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab"  href="#paper-pdf" data-href="<?= Url::to(['index', 'view' => 'pdf']) ?>"><i class="fa-solid fa-paperclip me-2"></i> เอกสาร</a>
            </li>
            <!--            <li class="nav-item">
                            <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-calendar" data-href="<?= Url::to(['index', 'view' => 'calendar']) ?>"><i class="fa-solid fa-calendar me-2"></i> ปฏิทินการลา</a>
                        </li>-->
        </ul>
        <div class="tab-content">
            <div class="tab-paper tab-pane fade" id="paper-dashboard"></div>
            <div class="tab-paper tab-pane fade active show" id="paper-wait"></div>
            <div class="tab-paper tab-pane fade" id="paper-history"></div>
            <div class="tab-paper tab-pane fade" id="paper-calendar"></div>
            <div class="tab-paper tab-pane fade" id="paper-list"></div>
            <div class="tab-paper tab-pane fade" id="paper-pdf"></div>
        </div>
    </div>
</div>

<?php //Pjax::end();    ?>
