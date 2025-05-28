<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
//use app\modules\inventories\models\Brand;
//use app\modules\inventories\models\Model;
use app\components\Ccomponent;
use app\modules\inventory\models\ItemsCategories;
use yii\helpers\FileHelper;
use Da\QrCode\QrCode;

$cat = new ItemsCategories;
$elements = $cat->find()->asArray()->orderBy(['categories_id' => 'asc'])->all();
$catArray = $cat->buildTreeLabel($elements);

$this->title = 'สินค้า';
$this->params['breadcrumbs'][] = $this->title;
$url = yii\helpers\Url::to(['setdisplay']);

$js = <<<JS
function setDisplay(id,d){
        $.get("{$url}",{id:id,status:d}, function(data) {
            $.pjax.reload({container: '#pjaxItems', async: false});
        });
};

JS;
$this->registerJs($js, $this::POS_BEGIN);
?>

<div class="items-index">

    <?php Pjax::begin(['id' => 'pjaxItems']); ?>
    <?php
    echo newerton\fancybox3\FancyBox::widget([
        'target' => '[data-fancybox]',
        'config' => [
        ]
    ]);
    ?>

    <?=
    GridView::widget([
        'panelTemplate' => '<div class="">
          {panelBefore}
          <div>{items}</div>
          {panelAfter}
          {panelFooter}
          <div class="text-center m-2 small">{summary}</div>
          <div class="text-center m-2 small">{pager}</div>
          </div>',
        'responsiveWrap' => false,
        'striped' => FALSE,
        'hover' => TRUE,
        'bordered' => FALSE,
        'condensed' => TRUE,
        'export' => FALSE,
        #'showPageSummary' => TRUE,
        'panel' => [
            'heading' => '',
            'type' => '',
            'before' => Html::a('<i class="feather icon-plus-square"></i> เพิ่มรายการ', ['create'], ['class' => 'btn btn-outline-success']),
            #'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
            'footer' => false
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}'
        ],
        'dataProvider' => $dataProvider,
        'pager' => [
            'maxButtonCount' => 5,
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn', 'vAlign' => 'top',],
            [
                'label' => 'รูปสินค้า',
                'vAlign' => 'top',
                'width' => '60px',
                'noWrap' => TRUE,
                'visible' => 1,
                'format' => 'raw',
                'attribute' => 'items_photo',
                'value' => function ($model) {
                    $pathImg = $model->getPath() . DIRECTORY_SEPARATOR . $model->asset_item_id;
                    if (!is_dir($pathImg)) {
                        @mkdir($pathImg);
                    }
                    $files = @FileHelper::findFiles($pathImg, ['only' => ['*.jpg', '*.jpeg', '*.png']]);
                    $pathImg = $model->getUrlPath() . '/' . $model->asset_item_id;
                    $html = '';
                    foreach ($files as $index => $file) {
                        $nameFicheiro = substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1);
                        #echo '<li  data-placement="bottom"  class="avatar pull-up">';
                        //echo Html::img($pathImg . '/' . $nameFicheiro, ['data-fancybox' => true, 'class' => 'media-object rounded-circle', 'width' => '32', 'height' => '32']);
                        $html .= Html::a(Html::img($pathImg . '/' . $nameFicheiro, ['class' => 'rounded border mr-1', 'height' => 30]), $pathImg . '/' . $nameFicheiro, ['data-fancybox' => 'gallery']);
                        #echo ' </li>';
                    }
                    return $html;
//                    return Html::img($model->photoViewer, [
//                        'class' => 'img-fluid img-thumbnail',
//                    ]);
                }
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'label' => 'สถานะเปิด',
                'vAlign' => 'top',
                'attribute' => 'asset_item_active',
                'width' => '1%',
                'format' => 'raw',
                // 'visible' => 0,
                'value' => function ($model) {
                    if (!$model->asset_item_active) {
                        return Html::button('ปิด', ['class' => 'btn btn-xs btn-light', 'onclick' => "setDisplay('{$model->asset_item_id}',1)"]);
                    } else {
                        return Html::button('เปิด', ['class' => 'btn btn-xs btn-primary', 'onclick' => "setDisplay('{$model->asset_item_id}',0)"]);
                    }
                },
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'label' => 'SKU',
                'vAlign' => 'top',
                'attribute' => 'sku',
                'width' => '5%',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->sku, ['update', 'id' => $model['asset_item_id']], ['class' => 'font-weight-bold text-primary']);
                }
            ],
            [
                'label' => 'QRC',
                'format' => 'raw',
                //'vAlign' => 'middle',
                'visible' => 1,
                #'noWrap' => TRUE,
                'width' => '1%',
                'vAlign' => 'top',
                'value' => function ($model) {
                    $qrCode = (new QrCode($model->sku))
                            ->setSize(20)
                            ->setMargin(5);
                    //->useForegroundColor(51, 153, 255);
                    return Html::img($qrCode->writeDataUri(), ['class' => 'img-thumbnail1', 'id' => $model->sku, 'data-fancybox1' => 'gallery1']);
                }
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'attribute' => 'asset_item_name',
                'vAlign' => 'top',
                'width' => '30%',
                'format' => 'raw',
                'value' => function ($model) use ($catArray) {
                    $html = $model['asset_item_name'];
                    return Html::a($html, ['update', 'id' => $model['asset_item_id']], ['class' => 'text-dark font-weight-bold']);
                },
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'attribute' => 'asset_unit_id',
                'vAlign' => 'top',
                'visible' => 1,
                'value' => function ($model) {
                    return $model->itemsUnit->asset_unit_name;
                },
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'label' => 'ประเภท',
                'vAlign' => 'top',
                'attribute' => 'categories_id',
                // 'width' => '30%',
                'format' => 'raw',
                'value' => function ($model) use ($catArray) {
                    return @$catArray[$model['categoriesId']]['categories_title'];
                },
            ],
            [
                'label' => 'วันที่สร้าง/วันที่แก้ไข',
                'vAlign' => 'top',
                'attribute' => 'create_at',
                'format' => 'raw',
                'visible' => 0,
                'value' => function ($model) {
                    //return Ccomponent::getThaiDate($model['items_create_at'], 'S', TRUE) . '<br>' . Ccomponent::getThaiDate($model['items_update_at'], 'S', TRUE);
                },
            ],
            [
                'headerOptions' => ['class' => 'font-weight-bold'],
                'vAlign' => 'top',
                'class' => 'kartik\grid\ActionColumn',
                'noWrap' => TRUE,
            ],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>
