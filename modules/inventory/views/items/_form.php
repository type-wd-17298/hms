<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
//use kartik\depdrop\DepDrop;
//use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
//use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use app\modules\inventory\models\ItemsCategories;
//use app\modules\inventory\models\ItemsGroup;
use app\modules\inventory\models\AssetItemUnit;
use yii\helpers\FileHelper;
use yii\widgets\Pjax;

$cat = new ItemsCategories;
$elements = $cat->find()->asArray()->orderBy(['categories_id' => 'asc'])->all();
$attribute = $cat->buildTree($elements);

$escape = new JsExpression("function(m) { return m; }");

$url = Url::to(['gen-id']);
$url2 = Url::to(['model']);
$urlForm = Url::to(['uploadphoto']);
//echo $cc = count($modelPropertys);
($model->isNewRecord ? $n = 1 : $n = 0);

$js = <<<JS
$("#btnGen").click(function(){
    $.post("{$url}",{}, function(data) {
        $("#assetitems-asset_item_id").val(data);
    });
 });

if($n){
    $("#items-items_name").click(function(){
        var c = $('#cat-id option:selected').text();
        $.get("{$url2}",{id:$('#subcat-id option:selected').val()}, function(data) {
            $("#items-items_id").val(data.code);
            $("#items-items_name").val(c+' '+data.name);
        });
    });
}

function addItems(){
    var value = $("#assetitems-asset_item_id").val();
    $('.itemsSubID').each(function(index){
        var input = $(this);
        //input.val(value+'-'+(index+1));
        input.attr("name","ItemsProperty["+(index)+"][items_id]");
        $(".itemspName:eq("+(index)+")").attr("name","ItemsProperty["+(index)+"][items_property_name]");
        $(".itemspDetail:eq("+(index)+")").attr("name","ItemsProperty["+(index)+"][items_property_detail]");
    });
}

$(".itemsSubID").click(function(){
    addItems();
});
     /*
$("#btnSubmit").click(function(){

    var c = $('#items-items_group_id option:selected').val();
    var count = 0
    $('.itemsSubID').each(function(index){
        if($(".itemspName:eq("+(index)+")").val() == '' && c == 2){
            alert('กรุณาระบุสีก่อนค่ะ');
            count++;
            //return false;
        }
    });
    if(count == 0){
        $("#frmItems").submit();
    }

});
     */
$("#btnGroup").click(function(){
    if($("#assetitems-asset_item_id").val() == ''){
        alert('กรุณาระบุรหัสสินค้าก่อนค่ะ');
        return false;
    }
    var detail = $(".items_property:eq(0)").html();

    $("#items_property").append('<div class="row items_property">'+detail+'</div>');
    $(".itemspName").last().val('');
    $(".itemspDetail").last().val('');
    addItems();
 });

$("#items-items_id").keyup(function(){
    addItems();
});

$("#items-items_group_id").change(function(){
    var c = $('#items-items_group_id option:selected').val();
    if(c == 2){
        $(".multiAdd").removeClass('d-none');
    }else{
        $(".multiAdd").addClass('d-none');
    }
});


 var c = $('#items-items_group_id option:selected').val();
    if(c == 2){
        $(".multiAdd").removeClass('d-none');
        addItems();
    }else{
        $(".multiAdd").addClass('d-none');
    }

$(".btnUpload").click(function(){
        $.get("{$urlForm}",{id:$(this).data('rid')}, function(data) {
           $("#modalContentUpload").html(data);
        });
        $('#modalUpload').modal('show');
 });

$("#modalUpload").on("hidden.bs.modal", function () {
    $.pjax.reload('#frmItems',{async: false,push: false,replace: false});
});

JS;
//Pjax::begin(['id' => 'frmItems', 'enablePushState' => false]);
$this->registerJs($js, $this::POS_READY);

echo newerton\fancybox3\FancyBox::widget([
    'target' => '[data-fancybox]',
    'config' => [
    ]
]);
?>

<div class="items-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'frmItems',
                //'enableAjaxValidation' => false,
                //'enableClientValidation' => true,
                'options' => ['enctype' => 'multipart/form-data']
    ]);

#print_r($form->errorSummary($modelProperty));
    print_r($form->errorSummary($model));
    ?>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <?php
                    if (!$model->isNewRecord) {

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
                            $html .= Html::a(Html::img($pathImg . '/' . $nameFicheiro, ['class' => 'rounded border mr-1', 'height' => 60]), $pathImg . '/' . $nameFicheiro, ['data-fancybox' => 'gallery']);
                            #echo ' </li>';
                        }
                        echo $html;
                    }
                    ?>
                    <hr>
                    <?PHP
                    if (!$model->isNewRecord)
                        echo Html::button('แนบภาพ', ['class' => 'btn btn-primary btn-sm  btnUpload mr-1', 'data' => ['rid' => $model->asset_item_id]]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    /*
                                      $catList = ArrayHelper::map(Brand::find()->orderBy(['brand_id' => SORT_ASC])->all(), 'brand_id', 'brand_name');
                                      echo $form->field($model, 'brand_id')->dropDownList($catList, ['id' => 'cat-id', 'prompt' => 'เลือกยี่ห้อ...', 'disabled' => $model->isNewRecord ? false : true,]);

                                     *
                                     */
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    /*
                                      echo $form->field($model, 'model_id')->widget(DepDrop::classname(), [
                                      #'initValueText' => $hospcodeDesc, // set the initial display text
                                      'type' => DepDrop::TYPE_SELECT2,
                                      //'data' => ['001' => $model->model_id],
                                      //'disabled' => $model->isNewRecord ? false : true,
                                      //'data' => ArrayHelper::map(Model::find()->orderBy(['model_id' => SORT_ASC])->all(), 'model_id', 'fullname', 'model_name'),
                                      'options' => ['placeholder' => 'เลือกรุ่น...', 'id' => 'subcat-id'],
                                      'select2Options' => [
                                      'pluginOptions' => [
                                      'allowClear' => true,
                                      'escapeMarkup' => $escape,
                                      //'templateSelection' => new JsExpression('function (city) { return city.caption; }'),
                                      ],
                                      'pluginEvents' => [
                                      //"select2:select" => "function(e) { var data = e.params.data; console.log(e); }",
                                      ]
                                      ],
                                      'pluginOptions' => [
                                      'depends' => ['cat-id'],
                                      'initialize' => true,
                                      //'allowClear' => true,
                                      //'minimumInputLength' => 0,
                                      'url' => Url::to(['modellist'])
                                      ],
                                      'pluginEvents' => [
                                      //"depdrop:change" => "function(event, id, value, count) { console.log(id); console.log(value); console.log(count); }",
                                      ]
                                      ]);
                                     *
                                     */
                                    ?>
                                </div>

                                <div class="col-md-12">
                                    <?= $form->field($model, 'asset_item_name')->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-md-9">
                                    <?= $form->field($model, 'asset_item_id')->textInput(['readonly' => !$model->isNewRecord]) ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group validating nowrap">
                                        <label for="items-items_unit_id">รหัสสินค้าอัตโนมัติ</label>
                                        <?= Html::button('GEN-ID', ['disabled' => !$model->isNewRecord, 'id' => 'btnGen', 'class' => 'btn btn-warning form-control']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    echo $form->field($model, 'categories_id')->widget(Select2::classname(), [
                                        //'initValueText' => '037',
                                        'data' => ArrayHelper::map($attribute, 'categories_id', 'categories_title'),
                                        'options' => ['placeholder' => 'เลือกประเภท...'],
                                        'pluginOptions' => [
                                            'escapeMarkup' => $escape,
                                            'allowClear' => true,
                                            'minimumInputLength' => 0,
                                        ],
                                    ]);
                                    ?>

                                </div>
                                <div class="col-md-6">
                                    <?php
                                    echo $form->field($model, 'asset_unit_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(AssetItemUnit::find()->orderBy(['asset_unit_id' => SORT_ASC])->all(), 'asset_unit_id', 'asset_unit_name'),
                                        'options' => ['placeholder' => 'เลือกหน่วยนับ...'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'minimumInputLength' => 0,
                                        ],
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-12">
                                    <?= $form->field($model, 'asset_item_detail')->textInput(['maxlength' => true]) ?>
                                </div>

                            </div>
                            <div class="form-group">
                                <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-primary', 'id' => 'btnSubmit']) ?>
                                <?= Html::a('ยกเลิก', ['index'], ['class' => 'btn btn-outline-danger']) ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="modal fade text-left" id="modalUpload" tabindex="1" role="dialog"  aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeader">Upload ภาพถ่าย</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContentUpload"></div>
            </div>
        </div>
    </div>
</div>
<?php
//Pjax::end();
?>