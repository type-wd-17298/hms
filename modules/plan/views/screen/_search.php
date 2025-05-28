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
                'action' => ['index'],
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
        echo Html::dropDownList('reptype', (isset($_GET['reptype']) ? $_GET['reptype'] : ''), ArrayHelper::map(app\modules\survay\models\PersonType::find()->orderBy(['person_type_id' => 'ASC'])->asArray()->all(), 'person_type_id', 'person_type_name'),
                [
                    'class' => 'form-control d-none d-xl-block',
                    'prompt' => '---เลือกกลุ่มทั้งหมด---',
        ]);
        ?>
        <?PHP
        echo DateRangePicker::widget([
            'id' => 'screen_date',
            'name' => 'screen_date',
            'attribute' => 'screen_date',
            'value' => ((isset($_GET['date_between_a']) && isset($_GET['date_between_b'])) && !empty($_GET['date_between_a']) ? $_GET['date_between_a'] . ' - ' . $_GET['date_between_b'] : ''),
            'convertFormat' => true,
            'startAttribute' => 'date_between_a',
            'endAttribute' => 'date_between_b',
            'startInputOptions' => ['value' => (!isset($_GET['date_between_a']) ? '' : $_GET['date_between_a'])],
            'endInputOptions' => ['value' => (!isset($_GET['date_between_b']) ? '' : $_GET['date_between_b'])],
            'language' => 'th',
            'options' => ['placeholder' => 'เลือกวันที่สำรวจข้อมูล', 'class' => 'form-control'],
            'pluginOptions' => [
                'timePicker' => false,
                'timePickerIncrement' => 30,
                'disableTouchKeyboard' => true,
                'locale' => [
                    'format' => 'Y-m-d'
                ]
            ]
        ]);


        /*
          echo Html::dropDownList('dep', (isset($_GET['dep']) ? $_GET['dep'] : ''), ArrayHelper::map(app\modules\survay\models\Cdepartment::find()->orderBy(['department_code' => 'ASC'])->asArray()->all(), 'department_code', 'department_name'),
          [
          'class' => 'form-control d-none d-xl-block',
          'prompt' => '---เลือกหน่วยงานทั้งหมด---',
          'width' => '10',
          ]);
         *
         */
        ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> ค้นหา', ['class' => 'btn btn-primary']) ?>
            <?PHP Html::a('<i class="fa-solid fa-user-plus fa-lg"></i> เพิ่มประชากร', ['create'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
