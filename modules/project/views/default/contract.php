<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use app\modules\survay\models\Thaiaddress;
use app\modules\survay\components\Cprocess;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);

$url = Url::to(['manage']);
$urlGen = Url::to(['gennumber']);
$this->title = 'ทะเบียนคุมเลขสัญญาจัดซื้อจัดจ้าง';
$this->params['breadcrumbs'][] = $this->title;

$numberStringPrefix = ''; //'สพ 0033.201.3.';

$js = <<<JS
     $("[data-toggle=tooltip").tooltip();
JS;
$this->registerJs($js, $this::POS_READY);
?>
<div class="person-screen-index">
    <h4 class="text-primary"><i class="fa-solid fa-list"></i> <?= $this->title ?></h4>
    <?php Pjax::begin(['id' => 'pjax-gridview', 'timeout' => false, 'enablePushState' => false]); ?>
    <?=
    GridView::widget([
        'panel' => [
            'heading' => '',
            'type' => '',
            'before' => $this->render('_search_po', ['model' => $dataProvider]),
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
        'bordered' => FALSE,
        'showPageSummary' => true,
        // 'export' => FALSE,
        'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
        'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'label' => 'วันที่',
                'attribute' => 'project_contract_date',
                'format' => 'raw',
                'vAlign' => 'middle',
                'width' => '1%',
                'noWrap' => TRUE,
                //'hAlign' => 'right',
                'visible' => 1,
                'value' => function ($model) {
                    return Ccomponent::getThaiDate(($model['project_contract_date']), 'S', 1);
                }
            ],
            /*
              [
              //'contentOptions' => ['class' => 'font-weight-bold'],
              'label' => 'เลขที่สัญญา',
              'attribute' => 'project_contract_book',
              'noWrap' => TRUE,
              'width' => '1%',
              'format' => 'raw',
              'hAlign' => 'center',
              'vAlign' => 'middle',
              'visible' => 1,
              'value' => function ($model) use ($numberStringPrefix) {
              return substr($model->project_contract_book, 3);
              }
              ],
             *
             */
            [
                'label' => 'เลขที่สัญญา',
                //'attribute' => 'project.project_code',
                'noWrap' => TRUE,
                'width' => '1%',
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) use ($numberStringPrefix) {
                    return @substr($model['project']['projectContract']['project_contract_book'], 3);
                },
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ชื่อโครงการ',
                'attribute' => 'project.project_name',
                //'noWrap' => TRUE,
                'format' => 'raw',
                'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($model) {
                    return '' . Html::a($model->project->project_code, 'javascript:;') . ' ' . $model->project->project_name;
                }
            ],
            [
                'label' => 'ประเภท',
                //'attribute' => 'project_type_id',
                //'noWrap' => TRUE,
                //'width' => '5%',
                'vAlign' => 'middle',
                //'hAlign' => 'center',
                'value' => function ($model) {
                    return @$model['project']['type']['project_type_name'];
                },
            ],
            [
                'label' => 'ประเภทจัดซื้อ',
                //'attribute' => 'project_type_order_id',
                'noWrap' => TRUE,
                //'width' => '5%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    return @$model['project']['typeOrder']['project_type_order_name'];
                },
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'จำนวนเงิน',
                'attribute' => 'project_contract_cost',
                //'noWrap' => TRUE,
                'format' => ['decimal', 2],
                'vAlign' => 'middle',
                'hAlign' => 'right',
                'visible' => 1,
                'pageSummary' => true,
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>
