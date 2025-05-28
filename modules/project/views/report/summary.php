<?php

//use yii\bootstrap4\Html;
use yii\helpers\Url;
#use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;

//use app\modules\survay\models\Thaiaddress;

$this->title = 'แบบสรุปผลการดำเนิการจัดซื้อ';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::to(['screen/history']);
$js1 = <<<JS
     //$("[data-toggle=tooltip").tooltip();
    $(".btnFilter").click(function(event){
        $("#vcode").val($(this).data('vcode'));
        $("#vmoo").val($(this).data('vmoo'));
        event.preventDefault();
        $("#frmIndex").submit();
    });

JS;
//$this->registerJs($js1, $this::POS_READY);
$columns = [
    ['class' => 'kartik\grid\SerialColumn'],
    [
        'label' => 'ผู้ดำเนินการ',
        'attribute' => 'employee_fullname',
        #'width' => '10%',
        'vAlign' => 'middle',
        //'hAlign' => 'center',
        'noWrap' => TRUE,
        'format' => 'raw',
        'value' => function ($model) {
            return '<b>' . $model['employee_fullname'];
        }
    ],
    [
        'label' => 'วันที่',
        //'contentOptions' => ['class' => 'small'],
        'attribute' => 'pdate',
        'format' => 'raw',
        'vAlign' => 'middle',
        'width' => '1%',
        'noWrap' => TRUE,
        //'hAlign' => 'right',
        'visible' => 1,
        'value' => function ($model) {
            return Ccomponent::getThaiDate(($model['pdate']), 'S', 1);
        }
    ],
    [
        'label' => 'งานที่จัดซื้อหรือจัดจ้าง',
        'attribute' => 'project_name',
        #'width' => '10%',
        'vAlign' => 'middle',
        //'hAlign' => 'center',
        'format' => 'raw',
		'value' => function ($model) {
            return $model['project_code']. ' ' . $model['project_name'];
         }
    ],
    [
        'label' => 'วงเงินที่จัดซื้อหรือจัดจ้าง(บาท)',
        'attribute' => 'project_po_cost',
        #'width' => '10%',
        'vAlign' => 'middle',
        'hAlign' => 'right',
        'format' => ['decimal', 2],
    ],
    [
        'label' => 'ราคากลาง(บาท)',
        'attribute' => 'project_po_cost',
        #'width' => '10%',
        'vAlign' => 'middle',
        'hAlign' => 'right',
        'format' => ['decimal', 2],
    ],
    [
        'label' => 'วิธีซื้อหรือจ้าง',
        'attribute' => 'project_type_prefer_name',
        'vAlign' => 'middle',
        //'hAlign' => 'right',
        'format' => 'raw',
    ],
    [
        'label' => 'รายชื่อผู้เสนอราคา',
        'attribute' => 'project_company_name',
        'vAlign' => 'middle',
        //'hAlign' => 'right',
        'format' => 'raw',
    ],
    [
        'label' => 'ผู้ได้รับการคัดเลือก',
        'attribute' => 'project_company_name',
        'vAlign' => 'middle',
        //'hAlign' => 'right',
        'format' => 'raw',
    ],
    [
        'label' => 'เหตุผลที่คัดเลือกโดยสรุป',
        'attribute' => 'project_po_cost',
        'vAlign' => 'middle',
        //'hAlign' => 'right',
        'format' => 'raw',
        'value' => function () {
            return 'ราคาและคุณลักษณะเฉพาะ';
        }
    ],
    [
        'label' => 'เลขที่รายงานขอซื้อ',
        'attribute' => 'project_po_book',
        #'width' => '10%',
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'format' => 'raw',
        'value' => function ($model) {
            return substr($model['project_po_book'], 3);
        }
    ],
];
?>

<div class="row">
    <div class="col-md-12 d-none1">
        <div class="text-primary h5">
            <i class="fas fa-solid fa-paper-plane"></i>
            <?= $this->title ?>
        </div>

        <?php Pjax::begin(['id' => 'pjax-gridview', 'timeout' => false, 'enablePushState' => false]); ?>
        <?PHP
        echo GridView::widget([
            'panel' => [
                'heading' => '',
                'type' => '',
                'before' => $this->render('_search', ['model' => $dataProvider]), 'footer' => false,
            ],
            'panelTemplate' => '<div class="">
                    {panelBefore}
                    {items}
                    {panelAfter}
                    {panelFooter}
                </div>',
            'layout' => '<div class="bg-white">{items}{pager}</div>',
            'responsiveWrap' => FALSE,
            'striped' => FALSE,
            'bordered' => FALSE,
            //'pjax' => TRUE,
            'hover' => TRUE,
            'condensed' => TRUE,
            'showPageSummary' => true,
            'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
            'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>

