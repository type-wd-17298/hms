<?php

//use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\ActionColumn;
//use yii\widgets\Pjax;
//use kartik\grid\GridView;
//use app\components\Ccomponent;
use yii\helpers\ArrayHelper;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$url = Url::to(['create']);
$url2 = Url::to(['update']);
$data = date('s');
$this->params['mqttFuncCheck'] = <<<JS
        //if(topic == 'hms/service/paper/update/BNN'){
            //initTab();
        //}
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

<h3 class="font-weight-bold"><i class="fa-solid fa-plane"></i> <?= $this->title ?></h3>
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
<!-- Nav tabs -->
<div class="card">
    <div class="default-tab">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold active" data-bs-toggle="tab" href="#paper-all" id="tabDefault" data-href="<?= Url::to(['list-view', 'view' => 'list']) ?>"><i class="fa-solid fa-arrow-right-arrow-left me-2"></i> (อยู่ระหว่างดำเนินการ) <span id="cc_items_in" class="text-danger  font-weight-bold"></span></a>
            </li>
            <li class="nav-item">
                <a class="tabLink nav-link font-weight-bold" data-bs-toggle="tab" href="#paper-all" data-href="<?= Url::to(['list-view', 'view' => 'keep']) ?>"><i class="fa-solid fa-route text-dark me-2"></i>  ประวัติการไปราชการ</a>
            </li>
        </ul>
        <div class="tab-content ">
            <div class="tab-paper tab-pane fade show active" id="paper-all" role="tabpanel"></div>
        </div>
    </div>
</div>

