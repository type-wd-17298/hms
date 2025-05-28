<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;
use app\modules\office\models\PaperlessProcessList;

//use app\models\ExtProfile;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$urlOperate = Url::to(['operate']);
$url = Url::to(['create']);
$url2 = Url::to(['update']);
$js = <<<JS
var barcode = '';
            var interval;
            document.addEventListener('keydown', function(evt) {
                if (interval)
                    clearInterval(interval);
                if (evt.code == 'Enter') {
                    if (barcode)
                        handleBarcode(barcode);
                    barcode = '';
                    return;
                }
                if (evt.key != 'Shift')
                    barcode += evt.key;
                interval = setInterval(() => barcode = '', 20);
            });
            function handleBarcode(scanned_barcode) {
                //alert(scanned_barcode);
                //document.querySelector('#last-barcode').innerHTML = scanned_barcode;
                if(scanned_barcode.length >= 17){
                    $("#modalContents").html('');
                    event.preventDefault();
                    $.get("{$urlOperate}",{id:scanned_barcode}, function(data) {
                        if(data.status=='false'){
                            alert(data.message);
                        }else{
                            $('#modalForm').modal('show');
                            $("#modalContents").html(data);
                        }
                    });
                }
            }

//$("[data-toggle=tooltip").tooltip();
$(".btnOperate").click(function(event){
       $("#refID").html($(this).data("id"));
       $("#modalContents").html('');
       event.preventDefault();
       $.get("{$urlOperate}",{id:$(this).data("id")}, function(data) {
            if(data.status=='false'){
                            alert(data.message);
                        }else{
                            $('#modalForm').modal('show');
                            $("#modalContents").html(data);
             }
       });
});

$(".btnPopup").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       event.preventDefault();
       $.get("{$url}", function(data) {
           $("#modalContents").html(data);
       });
});

$(".btnFilters").click(function(event){
       $("#refID").html($(this).data("id"));
       $("#modalContents").html('');
       $('#modalForm').modal('show');
       event.preventDefault();
       $.get("{$url2}",{id:$(this).data("id")}, function(data) {
           $("#modalContents").html(data);
       });
});
$(".widget-stat").hover(
   function () {
    $(this).addClass('bg-primary-light');
  },
  function () {
    $(this).removeClass('bg-primary-light');
  }
);
JS;

$jsTab = <<<JS

 //แสดงรายการตามสถานะ

$(".operStatus").click(function(event){
        var id = $(this).data("id");
        $('#statusID').val(id);
        $("#frmIndex").submit();
         /*
        var tab = $('#tabDefault');
        var url = tab.data('href');
        var e = tab.attr('href');
       $.get(url,{attr:e}, function(data) {
           $(e).html(data);
       });
   */
});

//------------------------------tabLink-------------------------------------------
$(".tabLink").click(function(event){
      $('.tab-paper').html('');
       var url = $(this).data('href');
       var e = $(this).attr('href');
       $.get(url,{attr:e}, function(data) {
           $(e).html(data);
       });
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

Pjax::begin(['id' => 'pjPaperMonitor', 'timeout' => false, 'enablePushState' => false]); //
$this->registerJs($jsTab, $this::POS_READY);
$this->title = 'แฟ้มบันทึกข้อความ';
$this->params['breadcrumbs'][] = $this->title;
$dash = 0;
if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('SecretaryAdmin')) {
    $dash = 1;
}

$statusGroup = [];
$sumGroup = 0;
sort($data);
foreach ($data as $row) {
    $sumGroup += 1;
    if ($dash == 0) {
        // if (in_array($row->paperless_status_id, ['F01', 'FF', 'F00'])) {
        @$statusGroup[$row->status->paperless_status]['cc'] += 1;
        @$statusGroup[$row->status->paperless_status]['id'] = $row->paperless_status_id;
        //  } else {
        // @$statusGroup['อยู่ระหว่างดำเนินการ']['cc'] += 1;
        //  @$statusGroup['อยู่ระหว่างดำเนินการ']['id'] = $row->paperless_status_id;
        // }
//        if ($row->paperless_direct == 1)
//            @$statusGroup['แฟ้มผ่านงานเลขา'] += 1;
    } else {
        if (in_array($row->paperless_status_id, ['F03'])) {
            @$statusGroup['รอผู้เกี่ยวข้องลงนาม']['cc'] += 1;
            @$statusGroup['รอผู้เกี่ยวข้องลงนาม']['id'] = 'F03';
        } else {
            @$statusGroup[$row->status->paperless_status]['cc'] += 1;
            @$statusGroup[$row->status->paperless_status]['id'] = $row->paperless_status_id;
        }
//        if ($row->paperless_direct == 1)
//            @$statusGroup['แฟ้มผ่านงานเลขา'] += 1;
    }
}

//echo '<pre>';
//print_r($statusGroup);
//echo '</pre>';
//exit;
?>

<h3 class="text-primary font-weight-bolder"><i class="fas fa-solid fa-file-signature"></i> <?= $this->title ?></h3>
<div class="row d-none1">

    <?PHP
    foreach ($statusGroup as $index => $row) {
        ?>

        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
            <div class="widget-stat card operStatus btn" data-id='<?= $row['id'] ?>'>
                <div class="card-body1 m-2">
                    <div class="media ai-icon media-avatar">
                        <span class="me-3 bgl-primary text-primary">
                            <i class="fa-solid fa-file-signature fa-lg"></i>
                        </span>
                        <div class="media-body">
                            <p class="mb-1 font-weight-bold"><?= $index ?></p>
                            <h3 class="mb-0"><?= @number_format((float) $row['cc'], 0) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?PHP
    }
    ?>

    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card operStatus btn">
            <div class="m-2">
                <div class="media">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-solid fa-folder-tree fa-lg"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bolder">เอกสารทั้งหมด</p>
                        <h3 class="mb-0"><?= @number_format((float) $sumGroup, 0) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?PHP Pjax::end(); ?>
<?PHP
//Pjax::begin(['id' => 'pjPaperGrid', 'timeout' => false, 'enablePushState' => false]); //
$this->registerJs($js, $this::POS_READY);
?>
<!-- Nav tabs -->
<div class="card">
    <div class="default-tab">
        <ul class="nav nav-tabs">

            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold active" data-bs-toggle="tab"  href="#paper-paper"  id="tabDefault" data-href="<?= Url::to(['index', 'view' => 'paper']) ?>"><i class="fa-regular fa-folder me-2"></i> บันทึกข้อความทั่วไป</a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab"  href="#paper-wait" data-href="<?= Url::to(['index', 'view' => 'wait']) ?>"><i class="fa-solid fa-clock me-2"></i> (รอเอกสาร)</a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-process" data-href="<?= Url::to(['index', 'view' => 'process']) ?>"><i class="fa-solid fa-arrow-right-arrow-left me-2"></i> (อยู่ระหว่างดำเนินการ)</a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab"  href="#paper-all" data-href="<?= Url::to(['index', 'view' => 'all']) ?>"><i class="fa-solid fa-folder-tree me-2"></i> เอกสารทั้งหมด</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-paper tab-pane fade" id="paper-all"></div>
            <div class="tab-paper tab-pane fade active show" id="paper-paper"></div>
            <div class="tab-paper tab-pane fade" id="paper-wait"></div>
            <div class="tab-paper tab-pane fade" id="paper-process"></div>
        </div>
    </div>
</div>
<?PHP //Pjax::end();  ?>
<!-- Modal -->
<div class="modal fade" id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">ดำเนินการทางเอกสาร <span id="refID"></span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1 m-3">
                <div id="modalContents"></div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
