<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

//$url = Url::to(['updatesession']);
//$session = Yii::$app->session;
Pjax::begin(['id' => 'frmStock', 'enablePushState' => false]);
$urlForm = Url::to(['stockitems']);
$url = Url::to(['stock-out/updatedetail']);
$rid = !isset($_GET['rid']) ? @$_POST['rid'] : @$_GET['rid'];
$js = <<<JS
$(".btnSelect").click(function(){
        /*
          var selectedData = [];
            $.each($("input[id='selection']:checked"), function(){
                selectedData.push($(this).val());
            });
        */
            var item = $(this).data('items');
            var lot = $(this).data('lot');
            var quantity = $('#quantity'+item+lot).val();
            $.post("{$url}",{rid:'{$rid}',items:item,lot:lot,quantity:quantity}, function(data) {
                $.pjax.reload({container: '#formPO', async: false});
                $('#frmSearchStock').submit();
                //$("#gridItems").yiiGridView('applyFilter');
            });

        //$('#poModal').modal('hide');
    });
JS;
$this->registerJs($js, $this::POS_READY);
?>

<?php echo $this->render('_search'); ?>
<br>
<div class="card">
    <?=
    GridView::widget([
        'layout' => "{items}\n{pager}",
        'panelTemplate' => '<div class="">
          {panelBefore}
          {items}
          {panelAfter}
          {panelFooter}
          </div>',
        'export' => FALSE,
        'bordered' => FALSE,
        'responsiveWrap' => FALSE,
        'hover' => TRUE,
        'condensed' => TRUE,
        'striped' => FALSE,
        #'pjax' => TRUE,
        #'showPageSummary' => TRUE,
        'toolbar' => [
            [
                'content' =>
                Html::button('{summary}', [
                    'class' => 'btn btn-outline-secondary mr-1 ml-1 d-none d-sm-block',
                ]),
            ],
            ' {export}',
            '{toggleData}'
        ],
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                //'vAlign' => 'top',
                'width' => '1%',
                'visible' => 0,
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
            ['class' => 'kartik\grid\SerialColumn'],
//        [
//            'label' => 'รหัส',
//            'attribute' => 'asset_item_id',
//            'format' => 'raw',
//        ],
            [
                'label' => 'รายการสินค้า',
                'attribute' => 'asset_item_name',
                'width' => '30%',
                'format' => 'raw',
                'vAlign' => 'top',
                'value' => function ($data) {
                    return Html::tag('b', $data['asset_item_id'], ['class' => 'text-dark']) . '<br><small>' . @$data['asset_item_name'] . '</small>';
                }
            ],
            [
                'label' => 'LOT',
                'attribute' => 'lot_no',
                'format' => 'raw',
                'vAlign' => 'top',
            //'width' => '5%',
            ],
            [
                'label' => 'EXP',
                'attribute' => 'exp_date',
                'format' => 'raw',
                'vAlign' => 'top',
            //'width' => '5%',
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'คงเหลือ',
                'attribute' => 'quantity',
                'format' => ['decimal', 0],
                'hAlign' => 'right',
                'vAlign' => 'top',
            //'width' => '5%',
            ],
            [
                'contentOptions' => ['class' => 'font-weight-bold'],
                'label' => 'จำนวนที่ต้องขอเบิก',
                'attribute' => 'quantity',
                'hAlign' => 'right',
                'vAlign' => 'top',
                'visible' => 1,
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::textInput('quantity' . $model['asset_item_id'], 0, ['id' => 'quantity' . $model['asset_item_id'].$model['lot_no'], 'type' => 'number', 'class' => 'form-control', 'maxlength' => $model['quantity']]);
                },
            ],
            [
                'label' => 'เลือกรายการ',
                'vAlign' => 'top',
                'attribute' => 'asset_item_id',
                'format' => 'raw',
                'hAlign' => 'right',
                'value' => function ($model) {
                    return Html::button('เลือกรายการ', [
                        'class' => 'btn btn-sm btn-primary btnSelect',
                        'data-items' => $model['asset_item_id'],
                        'data-lot' => $model['lot_no'],
                    ]);
                }
            ],
        ],
    ]);
    ?>
    <div class="modal-footer">
        <?= Html::button('ปิดหน้าจอ', ['class' => 'btn btn-outline-danger', 'data' => ['bs-dismiss' => 'modal']]) ?>
    </div>
</div>
<?php Pjax::end(); ?>