<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\Ccomponent;
use kartik\daterange\DateRangePicker;

$js = <<<JS

        $('.btnSubmit').on('click', function (e) {
           //e.preventDefault();
           var form = $("#frmSearch");

        $.ajax({
            type: "GET",
            url: form.attr('action'),
            data: form.serialize(),
            //dataType: "json",
            encode: true,
          }).done(function (data) {
             $.pjax.reload({container: '#gServiceView', async: false});
          });
/*
            $.ajax({
                url: form.attr('action'),
                type: 'get',
                data: form.serialize(),
                success: function(response) {
                     $.pjax.reload({container: '#gServiceView', async: false});
                }
            });
        */
        return false;
    });
JS;

$this->registerJs($js, $this::POS_READY);
?>
<form id="frmSearch" class="form-inline" data-pjax="true">
    <div class="input-group w-100">
        <input type="text" class="form-control" id="inputSearch" name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ">

        <?php
        /*
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

          if (!isset($modeSearch) || $modeSearch == true)
          echo Html::dropDownList('status', (isset($_GET['status']) ? $_GET['status'] : ''), ArrayHelper::map(app\modules\servicedesk\models\ServiceStatus::find()->orderBy(['service_status_id' => 'ASC'])->asArray()->all(), 'service_status_id', 'service_status_name'),
          [
          'class' => 'form-control', //d-none d-xl-block
          'prompt' => '---เลือกสถานะทั้งหมด---',
          'width' => '10',
          ]);

          echo Html::dropDownList('emp', (isset($_GET['emp']) ? $_GET['emp'] : ''), ArrayHelper::map(app\modules\hr\models\Employee::find()->where(['employee_status' => 1, 'employee_dep_id' => Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id])->orderBy(['employee_id' => 'ASC'])->asArray()->all(), 'employee_id', 'employee_fullname'),
          [
          'class' => 'form-control', //d-none d-xl-block
          'prompt' => '---เลือกเจ้าหน้าที่ทั้งหมด---',
          'width' => '10',
          ]);
         *
         */
        if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('SecretaryAdmin')) {
            echo Html::dropDownList(
                'dep',
                $dep,
                ArrayHelper::map(app\modules\hr\models\EmployeeDep::find()->where(['employee_dep_status' => 1])->joinWith('type')->orderBy(['category_id' => 'ASC'])->asArray()->all(), 'employee_dep_id', 'employee_dep_label', 'type.category_name'),
                [
                    'class' => 'form-control form-control-md',
                    'prompt' => '---เลือกหน่วยงานทั้งหมด---',
                    'style' => 'max-width: 400px;',
                ]
            );
        }
        ?>

        <div class="input-group-append btn-group" role="group">

            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass"></i> แสดงข้อมูล', ['class' => 'btn btn-dark btnSubmit1']) ?>

            <?= Html::button('<i class="fa-solid fa-folder-plus"></i> เพิ่มรายการ', ['class' => 'btn btn-primary btnCreate font-weight-bold']) ?>

            <?= Html::button(
                '<i class="fa-solid fa-gauge-high"></i> Dashboard',
                [
                    'class' => 'btn text-white font-weight-bold',
                    'style' => 'background-color: #fd7e14; border-color: #fd7e14;',
                    'id' => 'btnDashboard',
                    'type' => 'button',
                ]
            ) ?>
            <?php
            $dashboardUrl = \yii\helpers\Url::to(['default/dashboard']);
            $js = <<<JS
    document.getElementById('btnDashboard').addEventListener('click', function() {
        window.location.href = '{$dashboardUrl}';
    });
JS;
            $this->registerJs($js);
            ?>


            <div class="btn-group" role="group">
                <?= Html::button('<i class="fa-solid fa-chart-column"></i> รายงาน', [
                    'class' => 'btn btn-info dropdown-toggle font-weight-bold',
                    'style' => 'background-color: #17a2b8; border-color: #17a2b8;',
                    'type' => 'button',
                    'data-bs-toggle' => 'dropdown',
                    'aria-expanded' => 'false',
                ]) ?>
                <ul class="dropdown-menu">
                    <li><?= Html::a('<i class="fa-regular fa-file-lines"></i> รายงานสรุป', ['report/summary'], ['class' => 'dropdown-item']) ?></li>
                    <li><?= Html::a('<i class="fa-solid fa-building"></i> รายงานขอคอมพิวเตอร์', ['report/department'], ['class' => 'dropdown-item']) ?></li>
                    <li>
                        <?= \yii\helpers\Html::a(
                            '<i class="fa-solid fa-file-excel"></i> รายงานทั้งหมด',
                            ['export/export-excel'],
                            [
                                'class' => 'dropdown-item',
                                'data-pjax' => '0'
                            ]
                        ) ?>
                    </li>

                </ul>
            </div>

        </div>
    </div>
</form>