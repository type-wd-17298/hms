<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
//use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

//use yii\widgets\Pjax;
$attr = $_GET['attr'];
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

<form id="frmSearch" class="form-inline1"  action="<?= Url::to(['', 'attr' => $attr, 'view' => @$_GET['view']]) ?>">
    <div class="input-group  mr-2">
        <input type="text" class="form-control form-control-lg" id="inputSearch"  name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >
        <?PHP
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
        if (!isset($modeSearch) || $modeSearch == true)
            echo Html::dropDownList('dep', (isset($_GET['dep']) ? $_GET['dep'] : ''), ArrayHelper::map(app\modules\hr\models\EmployeeDep::find()->where(['employee_dep_status' => 1])->joinWith('type')->orderBy(['category_id' => 'ASC'])->asArray()->all(), 'employee_dep_id', 'employee_dep_label', 'type.category_name'),
                    [
                        'class' => 'form-control form-control-lg ', //d-none d-xl-block
                        'prompt' => '---เลือกหน่วยงานทั้งหมด---',
                        'width' => '10',
            ]);
        ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> <b>แสดงข้อมูล</b>', ['class' => 'btn btn-primary']) ?>
            <?PHP
            if (!isset($modeSearch) || $modeSearch == true)
                echo Html::button('<i class="fa-solid fa-folder-plus fa-lg"></i> <b>เพิ่มรายการ</b>', ['class' => 'btn btn-dark btnCreate'])
                ?>
            <?PHP # Html::button('<i class="fa-solid fa-file-import fa-lg"></i> <b>ออกเลขหนังสือส่ง</b>', ['class' => 'btn btn-light btnCbsn'])  ?>
        </div>
    </div>
</form>
<?php #ActiveForm::end();   ?>
