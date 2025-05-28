<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use kartik\form\ActiveForm;

$this->title = 'ลงรับสินค้าเข้าคลัง';
$this->params['breadcrumbs'][] = $this->title;
?>
<h3 class="font-weight-bold"><i class="fa-solid fa-warehouse"></i> <?= $this->title ?></h3>
<?php Pjax::begin(); ?>
<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                //'class' => 'form-inline',
                'data-pjax' => 1
            ],
        ]);
?>
<div class="input-group mb-3">
    <?php echo Html::textInput('textSearch', @$_GET['textSearch'], ['class' => 'form-control', 'placeholder' => 'ค้นหา']) ?>
    <?php #echo Html::textInput('textSearch', '', ['class' => 'form-control'])   ?>
    <?php
    echo DateRangePicker::widget([
        'name' => 'dateSearch',
        'value' => ( isset($_GET['dateSearch']) ? $_GET['dateSearch'] : ''),
        'language' => 'th',
        'convertFormat' => true,
        #'useWithAddon' => true,
        'startAttribute' => 'dateStart',
        'endAttribute' => 'dateEnd',
        'pluginOptions' => [
            //'locale' => ['format' => 'Y-m-d'],
            'locale' => [
                'format' => 'd-M-y',
                #'format' => 'Y-m-d',
                'separator' => ' ถึง ',
            #'showDropdowns' => true,
            ],
            'opens' => 'left'
        ]
    ]);
    ?>

    <?= Html::submitButton('แสดงรายการ', ['class' => 'btn btn-outline-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
<div class="m-1"></div>
<?=
GridView::widget([
    //'layout' => "{items}\n{pager}",
    'tableOptions' => [
        'class' => '',
    ],
    'panelTemplate' => '<div class="">
          {panelBefore}
          {items}
          {panelAfter}
          {panelFooter}
          </div>',
    //'export' => FALSE,
    'bordered' => FALSE,
    'responsiveWrap' => FALSE,
    'hover' => TRUE,
    'condensed' => TRUE,
    'striped' => FALSE,
    #'showPageSummary' => TRUE,
    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
    'panel' => [
        'heading' => '',
        'type' => '',
        'before' => Html::a('<i class="fa-solid fa-circle-plus me-1"></i> เพิ่มใบรับของเข้าคลัง', ['create'], ['class' => 'btn btn-outline-primary']) . ' ',
    //Html::a('<i class="feather icon-plus-square"></i> เพิ่มรายการรับสินค้า', ['create'], ['class' => 'btn btn-outline-success']),
    #'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
    //'footer' => false
    ],
    'toolbar' => [
        [
            'content' =>
            Html::button('{summary}', [
                'class' => 'btn btn-outline-dark mr-1 d-none d-sm-block',
            ]),
        ],
        ' {export}',
        '{toggleData}'
    ],
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'showPageSummary' => TRUE,
    'columns' => [
        /*
          [
          //'vAlign' => 'top',
          'width' => '1%',
          'header' =>
          '<div class="form-check custom-checkbox checkbox-success check-lg me-3">
          ' . Html::checkbox('selection_all', false, ['class' => 'select-on-check-all form-check-input', 'value' => 1, 'onclick' => '$(".kv-row-checkbox").prop("checked", $(this).is(":checked"));']) . '
          </div>',
          'contentOptions' => ['class' => 'kv-row-select'],
          'content' => function ($model, $key) {
          return '<div class="form-check custom-checkbox checkbox-success check-lg me-3">
          ' . Html::checkbox('selection[]', false, ['class' => 'kv-row-checkbox form-check-input', 'value' => $key, 'onclick' => '$(this).closest("tr").toggleClass("danger");', 'disabled' => isset($model->stopDelete) && !($model->stopDelete === 1)]) . '
          </div>';
          },
          'hAlign' => 'center',
          'hiddenFromExport' => true,
          'mergeHeader' => true,
          ],
         *
         */
        [
            'class' => 'kartik\grid\SerialColumn',
            'vAlign' => 'top',
            'width' => '1%',
            // 'vAlign' => 'top',
            'visible' => 1,
        ],
        [
            'label' => 'เอกสาร',
            'format' => 'raw',
            'visible' => 0,
            'hAlign' => 'right',
            'width' => '5%',
            //'filter' => ArrayHelper::map(TranferStatus::find()->orderBy(['tranfer_status_id' => 'ASC'])->asArray()->all(), 'tranfer_status_id', 'tranfer_status_name'),
            'attribute' => 'purchase_order_status_id',
            'value' => function ($model) {
                return Html::a('<i class="feather fa-1x icon-printer"></i>', '#', [
                    'class' => 'btn btn-sm btn-',
                    //'style' => ['width' => '30px'],
                    //'target' => 'targetLink',
                    //'onclick' => "embedLink();$('#targetLink').attr('src',function(i,e){ return '" . yii\helpers\Url::to(['report', 'id' => $model->items_tranfer_id]) . "';});",
                    //'onclick' => "embedLink('" . yii\helpers\Url::to(['report', 'id' => $model->items_tranfer_id]) . "');",
                    'data-toggle' => "modal",
                    'data-target' => "#modalDocument",
                ]);
            }
        ],
        [
            'label' => 'วันที่เอกสาร',
            'vAlign' => 'top',
            'noWrap' => TRUE,
            'width' => '3%',
            'attribute' => 'asset_stockin_date',
            'hAlign' => 'right',
            'format' => 'raw',
            'value' => function ($model) {
                return app\components\Ccomponent::getThaiDate($model->asset_stockin_date, 'S');
            }
        ],
        [
            'label' => 'เลขที่เอกสาร',
            'width' => '5%',
            'noWrap' => TRUE,
            'vAlign' => 'top',
            'attribute' => 'employee_id',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a("<b>{$model->asset_stockin_no}</b>", ['update', 'id' => $model->asset_stockin_id], [
                    'class' => '',
                ]);
            }
        ],
//            [
//                'label' => 'ชื่อผู้ขาย',
//                'attribute' => 'asset_supplier_id',
//                //'width' => '10%',
//                'vAlign' => 'top',
//                'value' => function ($model) {
//                    return @$model->supplier->asset_supplier_name;
//                }
//            ],
        [
            'label' => 'หมายเหตุ',
            'attribute' => 'asset_stockin_comment',
            //'width' => '10%',
            'vAlign' => 'top',
        /*
          'value' => function ($model) {
          return @$model->supplier->asset_supplier_name;
          }
         *
         */
        ],
        [
            'label' => 'รับเข้าคลัง',
            'noWrap' => TRUE,
            'attribute' => 'asset_master_type_id',
            'width' => '50%',
            'vAlign' => 'top',
            'value' => function ($model) {
                return @$model->master->asset_master_type_name;
            }
        ],
        [
            'label' => 'จำนวนรายการ',
            'attribute' => 'employee_id',
            'noWrap' => TRUE,
            'vAlign' => 'top',
            //'width' => '10%',
            'format' => ['decimal', 0],
            'pageSummary' => true,
            'hAlign' => 'center',
            'value' => function ($model) {
                return $model->itemsCount;
            }
        ],
        [
            'label' => 'จำนวนรับเข้า',
            'attribute' => 'employee_id',
            'noWrap' => TRUE,
            //'width' => '10%',
            'vAlign' => 'top',
            'width' => '3%',
            'format' => ['decimal', 0],
            'pageSummary' => true,
            'hAlign' => 'center',
            'value' => function ($model) {
                return $model->itemsCountSum;
            }
        ],
        [
            'label' => 'จำนวนเงินทั้งสิน',
            'attribute' => 'asset_stockin_summary',
            'format' => ['decimal', 2],
            'noWrap' => TRUE,
            'hAlign' => 'right',
            'vAlign' => 'top',
            'width' => '3%',
            'visible' => 1,
            'pageSummary' => true,
        ],
        [
            'label' => 'ชื่อรับผิดชอบ',
            'vAlign' => 'top',
            'visible' => 0,
            'attribute' => 'employee_id',
            //'width' => '10%',
            'value' => function ($model) {
                return @$model->emp->fullname;
            }
        ],
        [
            'label' => 'สถานะใบสั่งซื้อ',
            'format' => 'raw',
            'vAlign' => 'top',
            'width' => '1%',
            //'visible' => 0,
            'noWrap' => TRUE,
            'attribute' => 'employee_id',
            'value' => function ($model) {
                return Html::a($model->status->asset_order_status_name, '#', [
                    'class' => 'btn btn-xs btn-block  btn-' . $model->status->asset_order_status_class,
                ]);
            }
        ],
        [
            'label' => 'บันทึกรายการสินค้า',
            'format' => 'raw',
            'vAlign' => 'top',
            'width' => '130px',
            'visible' => 0,
            'attribute' => 'employee_id',
            'value' => function ($model) {
                return Html::a('บันทึกรับสินค้า', ['update', 'id' => $model->asset_stockin_id], [
                    'class' => 'btn btn-xs btn-label-primary ',
                ]);
            }
        ],
        //'purchase_order_refno',
        ['class' => 'kartik\grid\ActionColumn', 'noWrap' => TRUE, 'visible' => 0,],
    ],
]);
?>
<?php Pjax::end(); ?>
