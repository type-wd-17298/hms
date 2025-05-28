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
setInterval(function(){
         $.pjax.reload({container: '#pjManageMonitor', async: false});
 }, 10000);
JS;
/*
  //ตรวจสอบ topic
  $this->params['mqttFuncCheck'] = <<<JS
  if(topic = 'hms/service/paper/update'){
  $.pjax.reload({container: '#pjManageMonitor', async: false});
  }
  JS;
  //ตรวจสอบ topic
  $this->params['mqttSubTopics'] = <<<JS
  sub_topics('hms/service/paper/update');
  JS;
 */
//$this->registerJs($js, $this::POS_READY);
$urlOperate = Url::to(['operate']);
$js = <<<JS
//------------------------------tabLink-------------------------------------------
$(".tabLink").click(function(event){
      $('.tab-paper').html('');
       var url = $(this).data('href');
       //var e = $(this).attr('href');
       var e = $(this).data('link');
       $.get(url,{attr:e}, function(data) {
           $(e).html(data);
       });
});

$(".tabLink2").click(function(event){
      $('.tab-paper').html('');
       var url = $(this).data('href');
       var e = $(this).data('link');
       $.get(url,{attr:e,upfile:1}, function(data) {
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

$this->registerJs($js, $this::POS_READY);
$this->title = 'หนังสือราชการ';
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

<div class="row d-none1">
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card">
            <div class="card-body1 m-2">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-regular fa-clipboard fa-lg"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">ทะเบียนรับในปีนี้</p>
                        <h4 class="mb-0"><?= @$data['brn_year'] ?></h4>
                        <span class="badge badge-primary">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card">
            <div class="card-body1 m-2">
                <div class="media ai-icon">
                    <span class="me-3 bgl-primary text-primary">
                        <i class="fa-regular fa-clipboard fa-lg"></i>
                    </span>
                    <div class="media-body">
                        <p class="mb-1 font-weight-bold">ทะเบียนส่งในปีนี้</p>
                        <h4 class="mb-0"><?= @$data['bsn_year'] ?></h4>
                        <span class="badge badge-primary">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card bg-primary-light">
            <div class="card-body1 m-2">
                <div class="media">
                    <span class="me-3">
                        <i class="fa-solid fa-file-arrow-down fa-lg"></i>
                    </span>
                    <div class="media-body text-white">
                        <p class="mb-1 font-weight-bolder">ทะเบียนรับวันนี้</p>
                        <h3 class="text-white"><?= @$data['brn_today'] ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6 col-xs-3">
        <div class="widget-stat card bg-primary-light">
            <div class="card-body1 m-2">
                <div class="media">
                    <span class="me-3">
                        <i class="fa-solid fa-file-arrow-up fa-lg"></i>
                    </span>
                    <div class="media-body text-white">
                        <p class="mb-1 font-weight-bolder">ทะเบียนส่งวันนี้</p>
                        <h3 class="text-white"><?= @$data['bsn_today'] ?></h3>
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
                <a class="tabLink nav-link font-weight-bold active" data-bs-toggle="tab" href="#paper-oper" data-link="#paper-oper" id="tabDefault" data-href="<?= Url::to(['list-booknumber', 'view' => 'BRN', 'oper' => '1']) ?>"><i class="<?= $icon ?>"></i> เอกสารรอดำเนิน <span id="badge-brn" class="badge badge-xs text-white bg-danger badge-circle"><?= (@$data['brn_oper'] > 0 ? $data['brn_oper'] : '') ?> </span></a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-all" data-link="#paper-all"  data-href="<?= Url::to(['list-booknumber', 'view' => 'BRN']) ?>"><i class="<?= $icon ?>"></i> แฟ้มหนังสือรับ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-bsn"  data-link="#paper-bsn">
                    <span  data-link="#paper-bsn" class="tabLink" data-href="<?= Url::to(['list-booknumber', 'view' => 'BSN']) ?>"><i class="<?= $icon ?>"></i> แฟ้มหนังสือส่ง </span>
                    <span  data-href="<?= Url::to(['list-booknumber', 'view' => 'BSN']) ?>" id="badge-bsn" data-link="#paper-bsn"  class="badge badge-xs text-white bg-primary badge-circle tabLink2 float-right"><?= (@$data['bsn_oper'] > 0 ? $data['bsn_oper'] : '') ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-bon" data-link="#paper-bon">
                    <span  data-link="#paper-bon" class="tabLink" data-href="<?= Url::to(['list-booknumber', 'view' => 'BON']) ?>"><i class="<?= $icon ?>"></i> แฟ้มคำสั่ง </span>
                    <span  data-href="<?= Url::to(['list-booknumber', 'view' => 'BON']) ?>" id="badge-bon"  data-link="#paper-bon"  class="badge badge-xs text-white bg-primary badge-circle tabLink2 float-right"><?= (@$data['bon_oper'] > 0 ? $data['bon_oper'] : '') ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-ban" data-link="#paper-ban" data-href="<?= Url::to(['list-booknumber', 'view' => 'BAN']) ?>"><i class="<?= $icon ?>"></i> แฟ้มประกาศ</a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-bcn" data-link="#paper-bcn" data-href="<?= Url::to(['list-booknumber', 'view' => 'BCN']) ?>"><i class="<?= $icon ?>"></i> แฟ้มหนังสือเวียนภายนอก</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-paper tab-pane fade show active mt-2" id="paper-oper" role="tabpanel"></div>
            <div class="tab-paper tab-pane fade mt-2" id="paper-all"></div>
            <div class="tab-paper tab-pane fade mt-2" id="paper-bsn"></div>
            <div class="tab-paper tab-pane fade mt-2" id="paper-bcn"></div>
            <div class="tab-paper tab-pane fade mt-2" id="paper-bon"></div>
            <div class="tab-paper tab-pane fade mt-2" id="paper-ban"></div>
        </div>
    </div>
</div>
