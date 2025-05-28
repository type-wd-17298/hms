<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use app\modules\inventories\models\ItemsCondition;
use yii\helpers\ArrayHelper;
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
                /*
                  'enableClientValidation' => false,
                  'layout' => 'horizontal',
                  'fieldConfig' => [
                  //'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                  'horizontalCssClasses' => [
                  'label' => 'col-sm-12',
                  'offset' => 'offset-sm-1',
                  'wrapper' => 'col-sm-12',
                  'error' => '',
                  'hint' => 'col-sm-3',
                  ],
                  ],
                 *
                 */
        ]);
print_r($form->errorSummary($model2));
?>
<?= Html::hiddenInput('rid', $model2->purchase_order_id) ?>
<?= Html::hiddenInput('pid', $model2->purchase_order_detail_id) ?>
<?= Html::hiddenInput('sn', @$_POST['sn']) ?>
<div class="row">
    <div class="col-md-12">
        <?php
        /*
          $addon = [

          'prepend' => [
          'content' => Html::button('<i class="feather icon-search"></i>', [
          'class' => 'btn btn-sm btn-secondary',
          ]),
          'asButton' => true
          ],

          'append' => [
          'content' => Html::submitButton('<i class="feather icon-plus"></i> เพิ่มรายการสินค้า', [
          'class' => 'btn btn-sm btn-secondary',
          ]),
          'asButton' => true
          ]
          ];
         *
         */
        ?>

        <?php
        echo $form->field($model2, 'items_id')->widget(Select2::classname(), [
            //'initValueText' => @$_GET['StockMasterList_99_drug_detail'], // set the initial display text
            'initValueText' => @(isset($model2->items_id) ? app\modules\inventories\models\Items::findOne($model2->items_id)->items_name : ''), // set the initial display text
            'options' => ['placeholder' => 'Click เพื่อพิมพ์ชื่อหรือรหัสสินค้า',],
            //'size' => Select2::MEDIUM,
            //'addon' => $addon,
            'pluginOptions' => [
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
    <div class="col-md-12 text-danger d-none">สินค้าที่แสดงให้เลือกนี้เป็นรายการที่กำหนดสีไว้แล้ว สามารถเพิ่มเติมได้ที่หน้าจัดการสินค้า</div>
    <br><br>
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
    <div class="col-md-12"><?= $form->field($model2, 'engine_no')->label()->textInput() ?> </div>
    <div class="col-md-12"><?= $form->field($model2, 'vin_no')->label()->textInput() ?> </div>
    <div class="col-md-12"><?= $form->field($model2, 'regis_no')->label()->textInput() ?> </div>
    <div class="col-md-12">
        <?php
        echo $form->field($model2, 'items_color_id')->dropDownList(
                ArrayHelper::map(app\modules\inventories\models\ItemsColor::find()
                                ->orderBy(['items_color_name' => SORT_ASC])
                                ->all(), 'items_color_id', 'items_color_name')
                , ['prompt' => 'เลือกสีตัวรถ...']);
        ?>
    </div>
    <div class="col-md-12">
        <?php
        echo $form->field($model2, 'items_condition_id')->dropDownList(
                ArrayHelper::map(ItemsCondition::find()
                                ->orderBy(['items_condition_id' => SORT_ASC])
                                ->all(), 'items_condition_id', 'items_condition_name')
                , ['prompt' => 'เลือกสภาพตัวรถ...']);
        ?>
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