<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

$js1 = <<<JS
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
    <form id="frmIndex" class="form-inline1" data-pjax="true">
<?php
/*
  $form = ActiveForm::begin([
  'id' => 'frmIndex',
  'action' => ['index'],
  'method' => 'get',
  'options' => [
  'data-pjax' => true,
  'class' => 'form-inline'
  ],
  ]);
 *
 */
?>

        <div class="input-group input-group-md mr-2">
            <!--            <div class="input-group-prepend d-none">
                            <div class="btn d-none d-xl-block">{summary}</div>
                        </div>-->
            <input type="text" class="form-control form-control-lg" id="inputSearch"  name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >

<?PHP
/*
  DateRangePicker::widget([
  'id' => 'search_date',
  'name' => 'search_date',
  'attribute' => 'search_date',
  'value' => ((isset($_GET['date_between_a']) && isset($_GET['date_between_b'])) && !empty($_GET['date_between_a']) ? $_GET['date_between_a'] . ' - ' . $_GET['date_between_b'] : ''),
  'convertFormat' => true,
  'startAttribute' => 'date_between_a',
  'endAttribute' => 'date_between_b',
  'startInputOptions' => ['value' => (!isset($_GET['date_between_a']) ? '' : $_GET['date_between_a'])],
  'endInputOptions' => ['value' => (!isset($_GET['date_between_b']) ? '' : $_GET['date_between_b'])],
  'language' => 'th',
  'options' => ['placeholder' => 'เลือกวันที่ดำเนินการ', 'class' => 'form-control form-control-lg'],
  'pluginOptions' => [
  'timePicker' => false,
  'timePickerIncrement' => 30,
  'disableTouchKeyboard' => true,
  'locale' => [
  'format' => 'Y-m-d'
  ]
  ]
  ]);
 */
echo Html::dropDownList('dep', (isset($_GET['dep']) ? $_GET['dep'] : ''), ArrayHelper::map(app\modules\survay\models\Cdepartment::find()->orderBy(['department_code' => 'ASC'])->asArray()->all(), 'department_code', 'department_name'),
        [
            'class' => 'form-control form-control-lg ', //d-none d-xl-block
            'prompt' => '---เลือกหน่วยงานทั้งหมด---',
            'width' => '10',
        ]);
?>
            <div class="input-group-append">
            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> <b>แสดงรายงาน</b>', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<i class="fa-regular fa-rectangle-list"></i> <b>ทะเบียนผู้บริหาร</b>', 'javascript:;', ['class' => 'btn btn-dark btnPopup']) ?>
                <?= Html::a('<i class="fa-regular fa-rectangle-list"></i> <b>ทะเบียนหัวหน้าฝ่าย</b>', 'javascript:;', ['class' => 'btn btn-dark btnPopup']) ?>
                <?= Html::a('<i class="fa-regular fa-rectangle-list"></i> <b>ทะเบียนหัวหน้ากลุ่มงาน</b>', 'javascript:;', ['class' => 'btn btn-dark btnPopup']) ?>
                <?PHP Html::a('<i class="fa-regular fa-rectangle-list"></i> <b>เขียนบันทึกข้อความ</b>', 'javascript:;', ['class' => 'btn btn-dark btnPopup']) ?>
                {export}
            </div>
        </div>
    </form>
<?php #ActiveForm::end();      ?>
</div>
