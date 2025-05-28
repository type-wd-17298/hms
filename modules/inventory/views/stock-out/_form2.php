<?php

use app\modules\inventories\models\PurchaseOrderStatus;
use app\components\Ccomponent;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
#use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\DetailView;

$url = Url::to(['upstatus']);
$urlForm = Url::to(['updatedetail']);
$js = <<<JS
function upStatus(id,d){
        if(confirm('ยืนยันการปรับสถานะหรือไม่')){
            $.get("{$url}",{id:id,status:d}, function(data) {
                   $.pjax.reload({container: '#formPO', async: false});
            });
        }
};
JS;
$this->registerJs($js, $this::POS_BEGIN);

$js = <<<JS
    $(".btnLink").click(function(){
        $('#poModal').modal('show');
        $.post("{$urlForm}",{rid:'{$model->asset_stockin_id}',pid:$(this).data("pid"),sn:$(this).data("sn")}, function(data) {
           $("#modalContent").html(data);
        });
    });
    $(".chkStock").click(function(){
        $.get("{$url}",{id:'{$model->asset_stockin_id}',status:3}, function(data) {
           alert(data);
        });
    });


JS;
$this->registerJs($js, $this::POS_READY);
?>
<?php Pjax::begin(['id' => 'formPO']); ?>

<?php
$form = ActiveForm::begin([
            'options' => ['data-pjax' => true],
        ]);
print_r($form->errorSummary($model));
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">ข้อมูลการรับพัสดุ/ครุภัณฑ์</div>
                <div class="btn-group">
                    <?PHP
                    /*
                      Html::button('สถานะ->' . $model->assetOrderStatus->asset_order_status_name, [
                      'class' => 'chkStock btn btn-sm btn-outline-' . $model->assetOrderStatus->asset_order_status_class,
                      ]);
                     *
                     */
                    ?>
                    <?php
                    if (!$model->isNewRecord) {
                        /*
                          echo Html::dropDownList('assetStatus', '', ArrayHelper::map(PurchaseOrderStatus::find()->where(['<>', 'asset_order_status_id', $model->asset_order_status_id])
                          ->orderBy(['asset_order_status_id' => SORT_ASC])
                          ->all(), 'asset_order_status_id', 'asset_order_status_name'), ['class' => 'btn btn-sm btn-outline-light',
                          'prompt' => 'ปรับสถานะ',
                          'onchange' => "upStatus('{$model->asset_stockin_id}',$(this).val());",
                          ]);
                         *
                         */
                    }
                    ?>
                </div>
            </div>
            <div class="card-content m-1">
                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'asset_stockin_id',
                        [
                            'attribute' => 'asset_stockin_date',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Ccomponent::getThaiDate($model->asset_stockin_date, 'S', 1);
                            }
                        ],
                        [
                            'attribute' => 'asset_master_type_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                //return $model->branch->branch_name;
                            }
                        ],
                        [
                            'attribute' => 'asset_supplier_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return @$model->supplier->asset_supplier_name;
                            }
                        ],
                        'asset_stockin_comment:ntext',
                    ],
                ])
                ?>

                <div class="form-group">
                    <?php Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-outline-primary']) ?>
                    <?= Html::a('<< กลับหน้าจัดการ', ['updatepo', 'id' => $model->asset_stockin_date], ['class' => 'btn btn-light']) ?>
                    <?= Html::a('กลับหน้าจัดการหลัก', ['index', 'id' => $model->asset_stockin_date], ['class' => 'btn btn-outline-light']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">ข้อมูลการบันทึกรับสินค้า</div>
            </div>
            <div class="card-content">

                <?=
                GridView::widget([
                    'layout' => "{items}\n{pager}",
                    'panelTemplate' => '<div class="">
                                            {panelBefore}
                                            {items}
                                            {panelAfter}
                                            {panelFooter}
                                        </div>',
                    'export' => false,
                    'responsiveWrap' => false,
                    'bordered' => false,
                    'hover' => false,
                    'striped' => false,
                    'condensed' => false,
                    'showPageSummary' => true,
                    'panel' => [
                        'heading' => '',
                        'type' => '',
                        //'before' => Html::submitButton('<i class="feather icon-plus-square"></i> เพิ่มรายการ', ['class' => 'btn btn-outline-success mr-1'])
                        'before' =>
                        ($model->asset_order_status_id < 3 ?
                                '<div class="btn-group">' . Html::button('<i class="fa-solid fa-circle-plus"></i> เพิ่มรายการ', ['class' => 'btnLink btn btn-outline-primary', 'data' => ['sn' => 1, 'target' => '#poModal', 'toggle' => 'modal']])
                                . '</div>' : '')
                        ,
#'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                        'footer' => false,
                    ],
                    'toolbar' => [
                        '{export}',
                        '{toggleData}',
                    ],
                    'dataProvider' => $modelList,
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn', 'width' => '1%'],
                        [
                            'visible' => 0,
                            'width' => '5%',
                            'label' => '',
                            'format' => 'raw',
                            'attribute' => 'items_photo',
                            'value' => function ($model) {
                                return Html::img($model->items->photoViewer, [
                                    'class' => 'img-fluid img-thumbnail',
                                ]);
                            },
                        ],
                        [
                            'label' => 'รหัสสินค้า',
                            'attribute' => 'items_id',
                            'width' => '10%',
                        ],
                        [
                            'attribute' => 'items_id',
                            'width' => '20%',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return @$model->items->items_name;
                            },
                        ],
                        [
                            'attribute' => 'amount',
                        //'width' => '5%',
                        ],
                        [
                            'attribute' => 'price',
                            'format' => ['decimal', 2],
                            'hAlign' => 'right',
                        //'width' => '5%',
                        ],
                        [
                            'attribute' => 'engine_no',
                        //'width' => '5%',
                        ],
                        [
                            'attribute' => 'vin_no',
                        //'width' => '5%',
                        ],
                        [
                            'attribute' => 'items_color_id',
                            'value' => function ($model) {
                                return @($model->itemsColor->items_color_name);
                            },
                        //'width' => '5%',
                        ],
                        [
                            'attribute' => 'comment',
                        //'width' => '5%',
                        ],
                        [
                            'header' => 'รวม',
                            'format' => ['decimal', 2],
                            'hAlign' => 'right',
                            'value' => function ($model) {
                                return ($model->amount * $model->price);
                            },
                            'pageSummary' => true,
                        //'width' => '5%',
                        ],
                        //['class' => 'kartik\grid\ActionColumn', 'noWrap' => TRUE],
                        ['class' => 'kartik\grid\ActionColumn',
                            'contentOptions' => ['style' => 'width:10%;'],
                            'header' => 'ดำเนินการ',
                            'template' => '{all}',
                            'buttons' => [
                                'all' => function ($url, $model, $key) {
                                    if ($model->assetOrder->asset_order_status_id < 3) {
                                        return
                                                kartik\bs4dropdown\ButtonDropdown::widget([
                                                    'encodeLabel' => false,
                                                    'label' => 'ดำเนินการ',
                                                    'direction' => 'left',
                                                    'dropdown' => [
                                                        'encodeLabels' => false,
                                                        'items' => [
                                                            ['label' => '<i class="feather icon-edit"></i> แก้ไขรายการ',
                                                                'url' => '#',
                                                                'linkOptions' => [
                                                                    'class' => 'btnLink',
                                                                    'data' => ['rid' => $model->asset_stockin_date, 'pid' => $model->asset_stockin_list_id]],
                                                            ],
                                                            '<div class="dropdown-divider"></div>',
                                                            ['label' => '<i class="feather icon-trash-2"></i> ลบรายการ',
                                                                'linkOptions' => [
                                                                    'data' => [
                                                                        'method' => 'post',
                                                                        'confirm' => \Yii::t('yii', 'ยืนยันการลบข้อมูลนี้หรือไม่ ?'),
                                                                    ],
                                                                ],
                                                                'url' => ['delete-detail', 'id' => $model->asset_stockin_date, 'id2' => $model->asset_stockin_list_id],
                                                            ],
                                                        ],
                                                    ],
                                                    'buttonOptions' => ['class' => 'btn-default btn-sm  btn-outline-light waves-effect waves-light']]);
                                    }
                                },
                            ],
                        ],
                    ],
                ]);
                ?>


            </div>
        </div>
    </div>
    <!-- Modal -->

</div>


<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
<div class="modal fade text-left" id="poModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">บันทึก/แก้ไข รายการสั่งซื้อ</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent"></div>
        </div>
    </div>
</div>

