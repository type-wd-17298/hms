<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="user-search">
    <?php
    $form = ActiveForm::begin([
                'id' => 'frmSearchStock',
                'action' => ['stockitems'],
                'method' => 'get',
                'options' => ['data-pjax' => true]
    ]);
    ?>
    <div class="input-group">
        <input type="hidden"  value="<?= !isset($_GET['master']) ? @$_POST['master'] : @$_GET['master'] ?>" name="master">
        <input type="hidden"  value="<?= !isset($_GET['rid']) ? @$_POST['rid'] : @$_GET['rid'] ?>" name="rid">
        <input type="text" id="itemsSearch" value="<?= @$_GET['search'] ?>" name="search" class="form-control" placeholder="ค้นหาข้อมูล" aria-describedby="button-addon2">
        <button class="btn btn-primary waves-effect waves-light" type="submit">ค้นหา</button>
    </div>
    <?php ActiveForm::end(); ?>
</div>