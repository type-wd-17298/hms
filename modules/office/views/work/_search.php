<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;

$thaimtL = array('01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม');
?>
<?php
/*
  $form = ActiveForm::begin([
  'id' => 'frmSearch',
  'action' => ['index'],
  'method' => 'get',
  'options' => [
  'data-pjax' => 1,
  //'class' => 'form-inline'
  ],
  ]);
 *
 */
$attr = @$_GET['attr'];
$js = <<<JS
        $('#frmSearch').on('submit', function (e) {
           e.preventDefault();
           var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'get',
                data: form.serialize(),
                success: function(response) {
                    $('{$attr}').html(response);
                }
            });
        return false;
    });

JS;
$this->registerJs($js, $this::POS_READY);
?>
<form id="frmSearch" class="form-inline1"  action="<?= Url::to(['index', 'attr' => @$attr, 'view' => @$_GET['view']]) ?>">
    <div class="input-group input-group-lg1">
        <input type="text" class="form-control form-control search-area" name="qsearchManage" value="<?= @$_GET['qsearchManage'] ?>" placeholder="ค้นหารายการ" >
        <?PHP
//        $yy = [];
//        for ($year = (date('Y') + 542); $year <= (date('Y') + 544); $year++) {
//            $yy[$year - 543] = $year;
//        }
//        echo Html::dropDownList('yy', (isset($_GET['yy']) ? $_GET['yy'] : ''), $yy,
//                [
//                    'class' => 'form-control',
//                    'prompt' => '---เลือกปี---',
//        ]);
        ?>
        <?PHP
//        echo Html::dropDownList('mm', (isset($_GET['mm']) ? $_GET['mm'] : ''), $thaimtL,
//                [
//                    'class' => 'form-control',
//                    'prompt' => '---เลือกเดือน---',
//        ]);

        echo DateRangePicker::widget([
            'id' => 'mm',
            'name' => 'mm',
            'attribute' => 'mm',
            'value' => ((isset($_GET['date_between_a']) && isset($_GET['date_between_b'])) && !empty($_GET['date_between_a']) ? $_GET['date_between_a'] . ' - ' . $_GET['date_between_b'] : date('Y-m-' . '01') . ' - ' . date('Y-m-' . '31')),
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
        <?PHP
        echo Html::dropDownList('dep', (isset($_GET['dep']) ? $_GET['dep'] : ''), ArrayHelper::map(
                        app\modules\hr\models\EmployeeDep::find()
                                ->where("employee_dep_id IN (36,94,93)")
                                ->orderBy(['employee_dep_id' => 'ASC'])->all(), 'employee_dep_id', 'employee_dep_label'),
                [
                    'class' => 'form-control',
                    'prompt' => '---เลือกหน่วยงานทั้งหมด---',
                    'width' => '10',
        ]);
        ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fas fa-search fa-lg"></i> แสดงข้อมูล', ['class' => 'btn btn-secondary active']) ?>
<?PHP echo Html::button('<i class="fa-solid fa-file-circle-plus fa-lg"></i> เขียนใบแลกเวร', ['class' => 'btn btn-primary btnCreateLink ']) ?>
        </div>
    </div>
</form>

<?php
//ActiveForm::end(); ?>