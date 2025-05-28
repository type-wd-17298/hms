<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
#use app\modules\inventories\models\PurchaseOrderStatus;
#use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\number\NumberControl;
#use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(['id' => 'formDetail']); ?>
<?php
$form = ActiveForm::begin([
            'options' => [
                'data' => ['pjax' => true,],
                'id' => 'dynamic-form-detail',
            ],
        ]);
print_r($form->errorSummary($model2));
?>
<?= Html::hiddenInput('rid', $model2->asset_stockout_id) ?>
<?= Html::hiddenInput('pid', $model2->asset_stockout_list_id) ?>
<?= Html::hiddenInput('sn', @$_POST['sn']) ?>
<div class="row">
    <div class="col-md-12">

        <?php
        echo $form->field($model2, 'asset_item_id')->widget(Select2::classname(), [
            'initValueText' => @(isset($model2->asset_item_id) ? app\modules\inventory\models\AssetItems::findOne($model2->asset_item_id)->asset_item_name : ''), // set the initial display text
            'options' => ['placeholder' => 'Click เพื่อพิมพ์ชื่อหรือรหัสวัสดุ/ครุภัณฑ์',],
            //'size' => Select2::MEDIUM,
            //'addon' => $addon,
            'pluginOptions' => [
                'dropdownParent' => '#poModal',
                'allowClear' => true,
                'minimumInputLength' => 1,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => yii\helpers\Url::to(['autosearch']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
                'templateResult' => new JsExpression('function(data) { return data.text; }'),
                'templateSelection' => new JsExpression('function(data) { return data.text; }'),
            ],
        ]);
        ?>
    </div>

    <div class="col-md-12">
        <?php
        echo $form->field($model2, 'amount')->widget(NumberControl::classname(), [
            'maskedInputOptions' => [
                'prefix' => '',
                'suffix' => '',
                'min' => 1,
                'max' => 1000000,
            ],
            'options' => ['class' => 'touchspin']
        ]);
        ?>
    </div>
    <div class="col-md-12">
        <?php
        echo $form->field($model2, 'price')->widget(NumberControl::classname(), [
            'maskedInputOptions' => [
                'prefix' => '',
                'suffix' => '',
                'min' => 0,
                'max' => 1000000,
            ],
        ]);
        ?>

    </div>
    <div class="col-md-12">
        <?= $form->field($model2, 'lot_no')->textInput() ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model2, 'comment')->textInput() ?>
    </div>
</div>

<div class="modal-footer">
    <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-success']) ?>
    <?= Html::button('ยกเลิก', ['class' => 'btn btn-outline-danger', 'data' => ['dismiss' => 'modal']]) ?>
    <?php #Html::a('กลับหน้าจัดการ', ['index'], ['class' => 'btn btn-outline-light'])    ?>

</div>


<?php ActiveForm::end(); ?>

<?php Pjax::end(); ?>