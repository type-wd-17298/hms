<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

$dep = (isset($_GET['dep']) ? $_GET['dep'] : '');
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

$attr = $_GET['attr'];
$js = <<<JS
        $('#frmIndex').on('submit', function (e) {
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

<div class="person-search">
    <form id="frmIndex" class="form-inline1" action="<?= Url::to(['', 'attr' => $attr, 'view' => @$_GET['view']]) ?>">
        <input type="hidden"  id="statusID"  name="statusid" value="<?= @$_GET['statusid'] ?>"  >
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

        <div class="input-group input-group-lg mr-2">

            <!--            <div class="input-group-prepend d-none">
                            <div class="btn d-none d-xl-block">{summary}</div>
                        </div>-->

            <input type="text" class="form-control form-control-md" id="inputSearch"  name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >

            <?PHP
            echo DateRangePicker::widget([
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
                'options' => ['placeholder' => 'เลือกวันที่ดำเนินการ', 'class' => 'form-control form-control-md'],
                'pluginOptions' => [
                    'timePicker' => false,
                    'timePickerIncrement' => 30,
                    'disableTouchKeyboard' => true,
                    'locale' => [
                        'format' => 'Y-m-d'
                    ]
                ]
            ]);
            if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('SecretaryAdmin')) {
                echo Html::dropDownList('dep', $dep, ArrayHelper::map(app\modules\hr\models\EmployeeDep::find()->where(['employee_dep_status' => 1])->joinWith('type')->orderBy(['category_id' => 'ASC'])->asArray()->all(), 'employee_dep_id', 'employee_dep_label', 'type.category_name'),
                        [
                            'class' => 'form-control form-control-md ', //d-none d-xl-block
                            'prompt' => '---เลือกหน่วยงานทั้งหมด---',
                            'width' => '10',
                ]);
            }
            ?>
            <div class="input-group-append">
                <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> <b>แสดงรายงาน</b>', ['class' => 'btn btn-dark']) ?>
                <?= Html::button('<i class="fa-solid fa-file-pen fa-lg"></i> <b>เขียนบันทึกข้อความ</b>', ['class' => 'btn btn-primary btnPopup']) ?>
            </div>
        </div>
    </form>
    <?php #ActiveForm::end();  ?>
</div>
