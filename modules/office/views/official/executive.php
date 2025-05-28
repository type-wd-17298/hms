<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$url = Url::to(['create']);
$url2 = Url::to(['update']);
$js = <<<JS
//setInterval(function(){
//         $.pjax.reload({container: '#pjManageMonitor', async: false});
// }, 10000);
JS;

//ตรวจสอบ topic
$this->params['mqttFuncCheck'] = <<<JS
if(topic = 'hms/service/paper/update'){
      //$.pjax.reload({container: '#pjManageMonitor', async: false});
}
JS;
//ตรวจสอบ topic
$this->params['mqttSubTopics'] = <<<JS
        //sub_topics('hms/service/paper/update');
JS;

$this->registerJs($js, $this::POS_READY);
$urlOperate = Url::to(['/office/paperless/operate']);
$js = <<<JS
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
                if(scanned_barcode.length == 17){
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

JS;

$this->registerJs($js, $this::POS_READY);
$this->title = 'เอกสารรอดำเนินการ';
$this->params['breadcrumbs'][] = $this->title;
?>

<h3 class="text-primary font-weight-bold"><i class="fa-solid fa-clipboard-list"></i> <?= $this->title ?></h3>
<?PHP
Pjax::begin(['id' => 'pjManageMonitor', 'timeout' => false, 'enablePushState' => false]);
$brn = (@$data['brn_oper'] > 0 ? $data['brn_oper'] : '');
$bsn = (@$data['bsn_oper'] > 0 ? $data['bsn_oper'] : '');
$bon = (@$data['bon_oper'] > 0 ? $data['bon_oper'] : '');
$js = <<<JS
        $("#badge-brn").html('{$brn}');
        $("#badge-bsn").html('{$bsn}');
        $("#badge-bon").html('{$bon}');
JS;
$this->registerJs($js, $this::POS_READY);
?>

<div class="row d-none">
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card">
            <div class="card-body p-4">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-regular fa-clipboard fa-2x"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">ทะเบียนรับในปีนี้</p>
                        <h4 class="mb-0"><?= @$data['brn_year'] ?></h4>
                        <span class="badge badge-primary"><?= date('s') ?></span>
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
                        <i class="fa-regular fa-clipboard fa-2x"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">ทะเบียนส่งในปีนี้</p>
                        <h4 class="mb-0"><?= @$data['bsn_year'] ?></h4>
                        <span class="badge badge-primary">+3.5%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card bg-primary-light">
            <div class="card-body p-4">
                <div class="media">
                    <span class="me-3">
                        <i class="fa-solid fa-file-arrow-down fa-2x"></i>
                    </span>
                    <div class="media-body text-white">
                        <p class="mb-1 font-weight-bolder">ทะเบียนรับวันนี้</p>
                        <h3 class="text-white"><?= @$data['brn_today'] ?></h3>
                        <div class="progress mb-2 bg-primary">
                            <div class="progress-bar progress-animated bg-light" style="width: 50%"></div>
                        </div>
                        <small>-</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card bg-primary-light">
            <div class="card-body p-4">
                <div class="media">
                    <span class="me-3">
                        <i class="fa-solid fa-file-arrow-up fa-2x"></i>
                    </span>
                    <div class="media-body text-white">
                        <p class="mb-1 font-weight-bolder">ทะเบียนส่งวันนี้</p>
                        <h3 class="text-white"><?= @$data['bsn_today'] ?></h3>
                        <div class="progress mb-2 bg-primary">
                            <div class="progress-bar progress-animated bg-light" style="width: 50%"></div>
                        </div>
                        <small>-</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php Pjax::end(); ?>
<?PHP
$icon = 'fa-regular fa-file-pdf me-2';
?>
<!-- Nav tabs -->
<div class="card">
    <div class="default-tab">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold active" data-bs-toggle="tab" href="#paper-all" id="tabDefault" data-href="<?= Url::to(['list-bookoperate', 'view' => 'BRN', 'mode' => 'owner']) ?>"><i class="<?= $icon ?>"></i> แฟ้มหนังสือราชการ <span id="badge-brn" class="badge badge-xs text-white bg-danger badge-circle"></span></a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold " data-bs-toggle="tab" href="#paper-bsn" data-href="<?= Url::to(['/office/paperless/index', 'view' => 'grid', 'mode' => 'owner']) ?>"><i class="<?= $icon ?>"></i> แฟ้มบันทึกเสนอ <span id="badge-bsn" class="badge badge-xs text-white bg-danger badge-circle"></span></a>
            </li>

            <!--            <li class="nav-item">
                            <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-bon" data-href="<?= Url::to(['list-bookoperate', 'view' => 'BON', 'mode' => 'owner']) ?>"><i class="<?= $icon ?>"></i> แฟ้มขอไปราชการ <span id="badge-bon" class="badge badge-xs text-white bg-danger badge-circle"><?= (@$data['bon_oper'] > 0 ? '' : '') ?></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-leave" data-href="<?= Url::to(['/office/leavemain/index', 'view' => 'BAN', 'mode' => 'owner']) ?>"><i class="<?= $icon ?>"></i> แฟ้มการลา</a>
                        </li>-->

        </ul>
        <div class="tab-content">
            <div class="tab-paper tab-pane fade show active mt-2" id="paper-all" role="tabpanel"></div>
            <div class="tab-paper tab-pane fade mt-2" id="paper-bsn"></div>
            <div class="tab-paper tab-pane fade mt-2" id="paper-bcn"></div>
            <div class="tab-paper tab-pane fade mt-2" id="paper-leave"></div>
        </div>
    </div>
</div>
