<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use app\modules\survay\models\Thaiaddress;
use app\modules\survay\components\Cprocess;

$this->title = 'ทะเบียนคุมเลขโครงการ e-GP';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-screen-index">
    <h4 class="text-primary"><i class="fa-solid fa-list"></i> <?= $this->title ?></h4>
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'panel' => [
            'heading' => '',
            'type' => '',
            'before' => $this->render('_search', ['model' => $dataProvider]),
            'footer' => false,
        ],
        'panelTemplate' => '<div class="">
          {panelBefore}
          {items}
          {panelAfter}
          {panelFooter}
          <div class="text-center m-2">{summary}</div>
          <div class="text-center m-2">{pager}</div>
          </div>',
        'responsiveWrap' => FALSE,
        'striped' => FALSE,
        'hover' => TRUE,
        'condensed' => TRUE,
        // 'export' => FALSE,
        'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
        'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label' => 'วันที่ออกเลข',
                'attribute' => 'project_orderdate',
                'format' => 'raw',
                'vAlign' => 'middle',
                //'width' => '1%',
                'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_orderdate']), 'S', 1);
                }
            ],
            [
                'label' => 'ทะเบียนคุม',
                'attribute' => 'project_number',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 1,
            ],
            [
                'label' => 'เลขที่ PO',
                'attribute' => 'project_ordernumber',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 1,
            ],
            [
                'label' => 'เลขที่สัญญา',
                'attribute' => 'project_contactnumber',
                #'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 1,
            ],
            [
                'label' => 'ชื่อโครงการ',
                'attribute' => 'project_name',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 1,
            ],
            [
                'label' => 'ประเภท',
                'attribute' => 'project_type_id',
                'noWrap' => TRUE,
                //'width' => '5%',
                'vAlign' => 'middle',
                //'hAlign' => 'center',
                'value' => function ($model) {
                    return @$model['type']['project_type_name'];
                },
            ],
            [
                'label' => 'จำนวนเงิน(บาท)',
                'attribute' => 'project_amount',
                //'noWrap' => TRUE,
                'format' => ['decimal', 2],
                'vAlign' => 'middle',
                'hAlign' => 'right',
                'visible' => 1,
            ],
            [
                'attribute' => 'project_contactname',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'project_contactdetail',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'project_contactmain',
                'vAlign' => 'middle',
            ],
            [
                'label' => 'ข้อมูลผู้ปฏิบัติ',
                'attribute' => 'whoRecord.fullname',
                #'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
            ],
            [
                'label' => '',
                'width' => '1%',
                'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    $return = '<div class="input-group input-group-sm"><div class="input-group-append">' .
                            Html::a('<i class="fa-solid fa-file-pen fa-lg"></i> แก้ไขข้อมูล', ['update', 'id' => $model['project_id']],
                                    ['class' => 'btn  btn-primary active']
                            )
                            . '</div></div>';
                    return $return;
                },
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>


