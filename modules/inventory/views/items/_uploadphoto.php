<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;
use kartik\widgets\FileInput;
use yii\helpers\Url;
?>

<?php Pjax::begin(['id' => 'frmUpload', 'enablePushState' => false]); ?>

<?php
echo FileInput::widget([
    'name' => 'photo_upload[]',
    'options' => ['accept' => 'image/*', 'multiple' => true],
    'pluginOptions' => [
        'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
        'overwriteInitial' => false,
        'initialPreviewShowDelete' => true,
        'uploadUrl' => Url::to(['uploadphoto', 'id' => $model->asset_item_id]),
        'browseLabel' => 'เลือกภาพ',
        'maxFileCount' => 10
    ]
]);
?>
<div class="form-group">
    <?php Html::submitButton('Save', ['class' => 'btn btn-lg btn-success']) ?>
</div>
<?php
Pjax::end();
?>
