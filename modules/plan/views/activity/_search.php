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
        <input type="text" class="form-control" id="inputSearch" name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >

        <?PHP
        echo DateRangePicker::widget([
            'id' => 'activity_date',
            'name' => 'activity_date',
            'attribute' => 'activity_date',
            'value' => ((isset($_GET['date_between_a']) && isset($_GET['date_between_b'])) && !empty($_GET['date_between_a']) ? $_GET['date_between_a'] . ' - ' . $_GET['date_between_b'] : ''),
            'convertFormat' => true,
            'startAttribute' => 'date_between_a',
            'endAttribute' => 'date_between_b',
            'startInputOptions' => ['value' => (!isset($_GET['date_between_a']) ? '' : $_GET['date_between_a'])],
            'endInputOptions' => ['value' => (!isset($_GET['date_between_b']) ? '' : $_GET['date_between_b'])],
            'language' => 'th',
            'options' => ['placeholder' => 'เลือกวันที่ดำเนินการ', 'class' => 'form-control'],
            'pluginOptions' => [
                'timePicker' => false,
                'timePickerIncrement' => 30,
                'disableTouchKeyboard' => true,
                'locale' => [
                    'format' => 'Y-m-d'
                ]
            ]
        ]);

        echo Html::dropDownList('dep', (isset($_GET['dep']) ? $_GET['dep'] : ''), ArrayHelper::map(app\modules\survay\models\Cdepartment::find()->orderBy(['department_code' => 'ASC'])->asArray()->all(), 'department_code', 'department_name'),
                [
                    'class' => 'form-control d-none d-xl-block',
                    'prompt' => '---เลือกหน่วยงานทั้งหมด---',
                    'width' => '10',
        ]);
        ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> แสดงรายงาน', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fa-solid fa-photo-film fa-lg"></i> เพิ่มข้อมูลกิจกรรม', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
