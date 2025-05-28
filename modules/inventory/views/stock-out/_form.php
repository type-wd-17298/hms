<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use app\modules\inventory\models\AssetOrderStatus;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\db\Expression;
use app\components\Ccomponent;

$emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
$url = Url::to(['upstatus']);
$urlForm = Url::to(['default/stockitems']);
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
        $.get("{$urlForm}",{rid:'{$model->asset_stockout_id}',master:'{$model->asset_master_type_id}',pid:$(this).data("pid"),sn:$(this).data("sn")}, function(data) {
           $("#modalContent").html(data);
        });
    });
    $(".chkStock").click(function(){
        //$.get("{$url}",{id:'{$model->asset_stockout_id}',status:3}, function(data) {
           //alert(data);
        //});
    });


JS;
?>
<?php Pjax::begin(['id' => 'formPO']); ?>
<?php $this->registerJs($js, $this::POS_READY); ?>
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
<?php
$form = ActiveForm::begin([
            'options' => ['data-pjax' => true],
            'enableClientValidation' => false,
            /*
              'options' => [
              'data' => ['pjax' => true,],
              'id' => 'dynamic-form',],
             */
            // 'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "<div class='font-weight-bold'>{label}</div>\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
//                'horizontalCssClasses' => [
//                    'label' => 'col-sm-6',
//                    'offset' => 'offset-sm-1',
//                    'wrapper' => 'col-sm-12',
//                    'error' => '',
//                    'hint' => 'col-sm-6',
//                ],
            ],
        ]);
print_r($form->errorSummary($model));
?>

<div class="row">
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-header">
                <div class="btn-group  float-right">
                    <?PHP
                    echo Html::button('<b>สถานะ</b> <i class="fa-solid fa-angle-right fa-sm"></i> ' . $model->status->asset_order_status_name, [
                        'class' => 'chkStock btn btn-sm btn-outline-' . @$model->status->asset_order_status_class,
                    ]);
                    ?>
                    <?php
                    if (!$model->isNewRecord)
                        echo Html::dropDownList('assetStatus', '', ArrayHelper::map(AssetOrderStatus::find()->where(['<>', 'asset_order_status_id', $model->asset_order_status_id])
                                                ->orderBy(['asset_order_status_id' => SORT_ASC])
                                                ->all(), 'asset_order_status_id', 'asset_order_status_name'), ['class' => 'btn btn-sm btn-outline-light',
                            'prompt' => '---ปรับสถานะ---',
                            'onchange' => "upStatus('{$model->asset_stockout_id}',$(this).val());",
                        ]);
                    ?>
                </div>

                <div class="card-title font-weight-bold">
                    ข้อมูลใบเบิกพัสดุ
                    <br><span class="small">ข้อมูลใบเบิกรายการวัสดุ/ครุภัณฑ์</span>
                </div>
            </div>
            <div class="card-content m-3">
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'asset_stockout_no')->textInput(['maxlength' => true, 'disabled' => TRUE,]) ?>
                    </div>
                    <?php
                    /*
                      $form->field($model, 'asset_order_status_id')
                      ->dropDownList(ArrayHelper::map(PurchaseOrderStatus::find()->orderBy([
                      'asset_order_status_id' => SORT_ASC])->all()
                      , 'asset_order_status_id'
                      , 'status_name')
                      , ['prompt' => '---เลือกสถานะ---', 'disabled' => TRUE,]
                      )
                     */
                    ?>
                    <div class="col-md-3">
                        <?= $form->field($model, 'asset_stockout_date')->textInput(['disabled1' => TRUE]) ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo $form->field($model, 'asset_master_type_id')->widget(Select2::classname(), [
                            #'initValueText' => $hospcodeDesc, // set the initial display text
                            'disabled' => !$model->isNewRecord,
                            'data' => ArrayHelper::map(\app\modules\inventory\models\AssetMasterType::find()
                                            ->where(['AND',
                                                new Expression(" FIND_IN_SET(asset_master_type_code , (SELECT asset_master_type_code FROM asset_user WHERE  asset_master_type_active = 1 AND  asset_user.employee_id = '{$emp->employee_id}')) "),
                                            ])
                                            ->orderBy(['asset_master_type_id' => SORT_ASC])->all(), 'asset_master_type_id', 'asset_master_type_name'),
                            'options' => ['placeholder' => 'เลือกคลัง...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 0,
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        echo $form->field($model, 'employee_id')->widget(Select2::classname(), [
                            #'initValueText' => $hospcodeDesc, // set the initial display text
                            //'disabled' => TRUE,
                            'data' => ArrayHelper::map(\app\modules\hr\models\Employee::find()->orderBy(['employee_id' => SORT_ASC])->all(), 'employee_id', 'fullname'),
                            'options' => ['placeholder' => 'เลือกพนักงาน...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 0,
                            ],
                        ]);
                        ?>
                    </div>


                    <div class="col-md-3">
                        <?= $form->field($model, 'asset_stockout_refno')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-9">
                        <?= $form->field($model, 'asset_stockout_comment')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fa-solid fa-floppy-disk me-1"></i>  บันทึกรายการ', ['class' => 'btn btn-primary font-weight-bold']) ?>
                    <?= Html::a('กลับหน้าจัดการ', ['index'], ['class' => 'btn btn-outline-light font-weight-bold']) ?>
                    <?= isset($model->asset_stockout_id) ? Html::a('บันทึกรับสินค้า >>', ['update', 'id' => $model->asset_stockout_id], ['class' => 'btn btn-light']) : '' ?>
                    <!--                    <button type="button" class="btn btn-outline-primary mr-1 waves-effect waves-light">
                                            <i class="feather icon-home"></i>
                                        </button>-->
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 <?= ($model->isNewRecord ? 'd-none' : '') ?>" >
        <div class="card">
            <div class="card-header">
                <div class="card-title font-weight-bold">
                    รายการวัสดุ/ครุภัณฑ์
                    <br><span class="small">บันทึกข้อมูลสำหรับข้อมูลรายการวัสดุ/ครุภัณฑ์</span>
                </div>
            </div>
            <div class="card-content ">

                <?=
                GridView::widget([
                    'layout' => "{items}\n{pager}",
                    'id' => 'gridItems',
                    'panelTemplate' => '<div class="">
                                            {panelBefore}
                                            {items}
                                            {panelAfter}
                                            {panelFooter}
                                        </div>',
                    'export' => FALSE,
                    'responsiveWrap' => FALSE,
                    'bordered' => FALSE,
                    'hover' => FALSE,
                    'striped' => FALSE,
                    'condensed' => FALSE,
                    'showPageSummary' => TRUE,
                    'panel' => [
                        'heading' => '',
                        'type' => '',
                        //'before' => Html::submitButton('<i class="feather icon-plus-square"></i> เพิ่มรายการ', ['class' => 'btn btn-outline-success mr-1'])
                        'before' => '<div class="btn-group">' . Html::button('+เพิ่มรายการ', ['class' => 'btnLink btn btn-outline-primary', 'data' => ['sn' => 0, 'bs-target' => '#poModal', 'bs-toggle' => 'modal']])
                        #. ' ' . Html::button('+เพิ่มอื่นๆ', ['class' => 'btnLink btn btn-outline-success ', 'data' => ['sn' => 0, 'target' => '#poModal', 'toggle' => 'modal']])
                        . '</div>',
#'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                        'footer' => false
                    ],
                    'toolbar' => [
                        '{export}',
                        '{toggleData}'
                    ],
                    'dataProvider' => $modelList,
                    'columns' => [
                        [
                            //'vAlign' => 'top',
                            'width' => '1%',
                            'visible' => 1,
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
                        ['class' => 'kartik\grid\SerialColumn', 'width' => '1%',],
                        [
                            'visible' => 0,
                            'width' => '5%',
                            'label' => '',
                            'format' => 'raw',
                            'attribute' => 'items_photo',
                            'value' => function ($model) {
//                                return Html::img($model->items->photoViewer, [
//                                    'class' => 'img-fluid img-thumbnail',
//                                ]);
                            }
                        ],
                        [
                            'label' => 'รหัสสินค้า',
                            'attribute' => 'asset_item_id',
                        //'width' => '5%',
                        ],
                        [
                            'attribute' => 'asset_item_id',
                            //'width' => '10%',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->items->asset_item_name;
                            },
                        ],
                        [
                            'attribute' => 'amount',
                            'hAlign' => 'right',
                        //'width' => '5%',
                        ],
//                        [
//                            'attribute' => 'price',
//                            'format' => ['decimal', 2],
//                            'hAlign' => 'right',
//                        //'width' => '5%',
//                        ],
                        [
                            'attribute' => 'lot_no',
                        //'width' => '5%',
                        ],
                        [
                            'attribute' => 'comment',
                        //'width' => '5%',
                        ],
//                        [
//                            'header' => 'รวม',
//                            'format' => ['decimal', 2],
//                            'hAlign' => 'right',
//                            'value' => function ($model) {
//                                return ($model->amount * $model->price);
//                            },
//                            'pageSummary' => true,
//                        //'width' => '5%',
//                        ],
                        //['class' => 'kartik\grid\ActionColumn', 'noWrap' => TRUE],
                        ['class' => 'kartik\grid\ActionColumn',
                            // 'contentOptions' => ['style' => 'width:10%;'],
                            'header' => 'ดำเนินการ',
                            'template' => '{all}',
                            'buttons' => [
                                'all' => function ($url, $model, $key) {
                                    return
                                    kartik\bs4dropdown\ButtonDropdown::widget([
                                        'encodeLabel' => FALSE,
                                        'label' => 'ดำเนินการ',
                                        'direction' => 'left',
                                        'dropdown' => [
                                            'encodeLabels' => false,
                                            'items' => [
                                                ['label' => '<i class="feather icon-edit"></i> แก้ไขรายการ',
                                                    'url' => '#',
                                                    'linkOptions' => [
                                                        'class' => 'btnLink',
                                                        'data' => ['rid' => $model->asset_stockout_id, 'pid' => $model->asset_stockout_list_id],]
                                                ],
                                                '<div class="dropdown-divider"></div>',
                                                ['label' => '<i class="feather icon-trash-2"></i> ลบรายการ',
                                                    'linkOptions' => [
                                                        'data' => [
                                                            'method' => 'post',
                                                            'confirm' => \Yii::t('yii', 'ยืนยันการลบข้อมูลนี้หรือไม่ ?'),
                                                        ],
                                                    ],
                                                    'url' => ['delete-detail', 'id' => $model->asset_stockout_id, 'id2' => $model->asset_stockout_list_id],
                                                ],
                                            ],
                                        ],
                                        'buttonOptions' => ['class' => 'btn-default btn-xs btn-outline-light waves-effect waves-light']]);
                                },
                            ],
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
<div class="modal fade text-left" id="poModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">บันทึก/แก้ไข รายการสั่งซื้อ</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent"></div>
        </div>
    </div>
</div>