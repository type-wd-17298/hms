<?php

//use yii\helpers\Html;
use yii\helpers\Url;

//use yii\grid\ActionColumn;
//use yii\widgets\Pjax;
//use kartik\grid\GridView;
//use app\components\Ccomponent;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$url = Url::to(['create']);
$url2 = Url::to(['update']);
$data = date('s');
$this->params['mqttFuncCheck'] = <<<JS
        if(topic == 'hms/service/paper/update/BNN'){
            initTab();
        }
JS;

$urlOperate = Url::to(['operate']);
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
    $.get(url,{attr:e}, function(data) {
       $(e).html(data);
    });
}
initTab();
//------------------------------tabLink-------------------------------------------
JS;
$css = <<<CSS
.nav-tabs .nav-item .nav-link.active {
  color: #0080FF;
}
CSS;
//$this->registerCss($css);
$this->registerJs($js, $this::POS_READY);
$this->title = 'หนังสือไปราชการ';
$this->params['breadcrumbs'][] = $this->title;
?>

<h4 class="card-title card-intro-title"><?= $this->title ?></h4>
<!-- Nav tabs -->
<div class="card">
    <div class="default-tab">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold active" data-bs-toggle="tab" href="#paper-all" id="tabDefault" data-href="<?= Url::to(['list-view', 'view' => 'in']) ?>"><i class="fa-solid fa-envelope text-primary me-2"></i> แฟ้มไปราชการ <span id="cc_items_in" class="text-danger  font-weight-bold"></span></a>
            </li>
            <!--            <li class="nav-item">
                            <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-all" id="tabDefault" data-href="<?= Url::to(['list-view', 'view' => 'out']) ?>"><i class="fa-solid fa-note-sticky text-warning me-2"></i> หนังสือเวียนส่งออก</a>
                        </li>-->
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-all" id="tabDefault" data-href="<?= Url::to(['list-view', 'view' => 'keep']) ?>"><i class="fa-solid fa-layer-group text-dark me-2"></i> ประวัติการไปราชการ</a>
            </li>
        </ul>
        <div class="tab-content ">
            <div class="tab-paper tab-pane fade show active" id="paper-all" role="tabpanel"></div>
        </div>
    </div>
</div>

