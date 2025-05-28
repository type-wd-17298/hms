<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\grid\GridView;

//use app\components\Ccomponent;
//echo newerton\fancybox3\FancyBox::widget();

$css = <<<CSS
/*.modal-xl {max-width: 90%!important;}*/

.tree * {
    margin: 0; padding: 0;
     /*overflow: hidden;
   white-space: nowrap;*/
}
.tree2 {
    /*min-width: 1800px;
    width: 1800px;
    white-space: nowrap;
    overflow-x: scroll;*/
  width: 1800px;
  overflow-x: scroll;

}
.tree ul {
   padding-top: 20px;
   position: relative;
    -transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

.tree li {
    float: left;
    text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;
    -transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
    content: '';
    position: absolute; top: 0; right: 50%;
    border-top: 2px solid #696969;
    width: 50%; height: 20px;
}
.tree li::after{
    right: auto;
    left: 50%;
    border-left: 2px solid #696969;
}

/*We need to remove left-right connectors from elements without
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
    display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{
    padding-top: 0;
}

/*Remove left connector from first child and
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
    border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
    border-right: 2px solid #696969;
    border-radius: 0 5px 0 0;
    -webkit-border-radius: 0 5px 0 0;
    -moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
    border-radius: 5px 0 0 0;
    -webkit-border-radius: 5px 0 0 0;
    -moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
    content: '';
    position: absolute; top: 0; left: 50%;
    border-left: 2px solid #696969;
    width: 0; height: 20px;
}

.tree li a{

   max-width: 200px;
   min-width: 200px;
 /*
    height: 100px;*/
    width: auto;
    padding: 5px 10px;
   /*
    text-decoration: none;
    background-color: #cbcbcb;*/
    /*color: #8b8b8b;*/
    font-size: 14px;
    display: inline-block;
    /*
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    -transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
       */
}

/*
.tree li li li a {
    width: auto;
}

.tree > ul > li > a {
    width: auto;
}

.tree > ul > li > ul > li > a {
    width: auto;
}
*/


/*Time for some hover effects*/
/*
.tree li a:hover, .tree li a:hover+ul li a {
    background: #cbcbcb; color: #000;
}
*/
/*Connector styles on hover*/
/*
.tree li a:hover+ul li::after,
.tree li a:hover+ul li::before,
.tree li a:hover+ul::before,
.tree li a:hover+ul ul::before{
    border-color:  #94a0b4;
}
*/
CSS;
$this->registerCss($css);

Pjax::begin(['id' => 'gridView99', 'timeout' => false, 'enablePushState' => false]); //
$url = Url::to(['structure/branch']);
$js = <<<JS
$(".dd").draggable({ revert: true,zIndex: 100});
$(".dd").droppable({
            drop: function(event, ui) {
                Swal.fire(
                    'The Internet?',
                    'That thing is still around?',
                    'question'
                  );
                $.pjax.reload({container: '#gridView99', async: false});
            },
            over: function(event, ui) {
                //$(this).css('background', 'orange');
            },
            out: function(event, ui) {
                //$(this).css('background', 'none');
            }
        });
$(".btnPopup").click(function(event){
       $("#modalContents").html('');
       $('#modalForm').modal('show');
        event.preventDefault();
        $.get("{$url}", {id:$(this).data("id")}, function(data) {
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
$this->registerJs($js, $this::POS_READY);
$this->title = 'ระบบบริหารงานบุคคล : จัดการข้อมูลหน่วยงาน';
$this->params['breadcrumbs'][] = $this->title;
?>
<h3 class="text-primary font-weight-bold"><i class="fas fa-solid fa-users-cog"></i> <?= $this->title ?></h3>
<div class="row">
    <div class="col-md-12 text-center">
        <div class="col-md-12 mt-5 mb-5">
            <div style='overflow-x:scroll;overflow-y:hidden;width:auto;'>
                <div style='min-width:5000px;min-height: 800px;'>
                    <?= Html::tag('div', $tree, ['class' => 'tree']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-5">
        <?PHP
        GridView::widget([
            'id' => 'gview01',
            'dataProvider' => @$dataProvider,
            'tableOptions' => ['class' => 'table verticle-middle table-responsive-md'],
            'panel' => [
                'heading' => '',
                'type' => 'primary',
                //'before' => $this->render('_search', ['model' => @$dataProvider]),
                'footer' => false,
            ],
            'panelBeforeTemplate' => '{before}',
            'panelTemplate' => '<div class="">
  {panelBefore}
  {items}
  {panelAfter}
  {panelFooter}
  <div class="text-center m-2">{summary}</div>
  <div class="text-center m-2">{pager}</div>
  <div class="clearfix"></div>
  </div>',
            'responsiveWrap' => FALSE,
            'striped' => TRUE,
            'hover' => TRUE,
            'bordered' => FALSE,
            'condensed' => TRUE,
            'export' => FALSE,
            //'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
            'exportContainer' => ['class' => ''],
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'กลุ่มงาน/ชื่อหน่วยงาน',
                    'attribute' => 'employee_dep_label',
                    'format' => 'raw',
                    'visible' => 1,
                //'group' => true,
                ],
//                [
//                    'headerOptions' => ['class' => 'font-weight-bold'],
//                    'label' => 'ชื่อหน่วยงาน',
//                    'attribute' => 'label',
//                    'format' => 'raw',
//                    //'group' => true,
//                    'visible' => 1,
//                ],
            ],
        ]);
        ?>
    </div>

</div>
<?php Pjax::end(); ?>
<!-- Modal -->
<div class="modal fade " id="modalForm"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">จัดการหน่วยงาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div id="modalContents" class="m-2"></div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"> Save changes</button>
            </div>
        </div>
    </div>
</div>


