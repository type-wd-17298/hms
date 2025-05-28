<?php

use yii\helpers\Url;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;

$js = <<<JS
    $("#screen_date").keypress(function (e) {
        alert('keypress');
        //return false;
    });
    $("#screen_date").keydown(function (e) {
        //return false;
         alert('keydown');
    });

JS;
//$this->registerJs($js, $this::POS_READY);
?>

<div class="person-search">

    <?php
    $form = ActiveForm::begin([
                'id' => 'frmIndex',
                //'action' => ['summary'],
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
                    'class' => 'form-inline'
                ],
    ]);
    ?>

    <div class="input-group input-group-md mr-2">
        <div class="input-group-prepend d-none">
            <div class="btn d-none d-xl-block">{summary}</div>
        </div>
        <input type="hidden" id="color" name="color" value="<?= @$_GET['color'] ?>">
        <input type="text" class="form-control" id="inputSearch" name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >

        <?PHP
        echo DateRangePicker::widget([
            'id' => 'select_date',
            'name' => 'select_date',
            'attribute' => 'select_date',
            'value' => ((isset($_GET['date_between_a']) && isset($_GET['date_between_b'])) && !empty($_GET['date_between_a']) ? $_GET['date_between_a'] . ' - ' . $_GET['date_between_b'] : ''),
            'convertFormat' => true,
            'startAttribute' => 'date_between_a',
            'endAttribute' => 'date_between_b',
            'startInputOptions' => ['value' => (!isset($_GET['date_between_a']) ? '' : $_GET['date_between_a'])],
            'endInputOptions' => ['value' => (!isset($_GET['date_between_b']) ? '' : $_GET['date_between_b'])],
            'language' => 'th',
            'options' => ['placeholder' => 'เลือกวันที่', 'class' => 'form-control'],
            'pluginOptions' => [
                'timePicker' => false,
                'timePickerIncrement' => 30,
                'disableTouchKeyboard' => true,
                'locale' => [
                    'format' => 'Y-m-d'
                ]
            ]
        ]);
        ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> แสดงรายงาน', ['class' => 'btn btn-primary']) ?>
            <?PHP Html::a('<i class="fa-solid fa-user-plus fa-lg"></i> เพิ่มประชากร', ['create'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
