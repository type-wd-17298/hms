<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\Ccomponent;
use app\modules\hr\models\EmployeeDep;
use app\modules\survey\models\ControlEvent;
use kartik\daterange\DateRangePicker;

$js = <<<JS
$('.btnSubmit').on('click', function(e) {
    e.preventDefault();
    var form = $('#frmSearch');

    var data = {};
    form.find('input, select').each(function() {
        var name = $(this).attr('name');
        var val = $(this).val();
        if(name && val !== ''){
            data[name] = val;
        }
    });

    $.pjax.reload({
        container: '#gServiceView',
        url: form.attr('action'),
        data: data,
        push: false,
        replace: true,
        timeout: 1000
    });
});
JS;
$canShow = \Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin')  || \Yii::$app->user->can('ReviewCommittee');
$control = ControlEvent::findOne(['event_id' => 1, 'event_name' => 'survey']);
$canShowFlatButton = $control ? ($control->active == 1) : false;
$this->registerJs($js, $this::POS_READY);
?>

<div class="text-end my-3">
    <?php if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin')): ?>
        <div class="form-check form-switch d-inline-flex align-items-center">
            <input class="form-check-input me-2" type="checkbox" id="switchFlatButton" <?= $canShowFlatButton ? 'checked' : '' ?>>
            <label class="form-check-label fw-semibold" for="switchFlatButton" style="cursor: pointer;">
                แสดงปุ่มเพิ่มรายการ
            </label>
        </div>
    <?php endif; ?>
</div>

<?php
$toggleFlatUrl = \yii\helpers\Url::to(['/survey/default/toggle-flat-button']);
$jsFlat = <<<JS
$('#switchFlatButton').on('change', function() {
    var value = $(this).is(':checked') ? 1 : 0;
    $.post('{$toggleFlatUrl}', {active: value}, function(data) {
        if(data.success) {
            location.reload();
        } else {
            alert('ไม่สามารถเปลี่ยนสถานะ Flat Action ได้');
        }
    });
});
JS;
$this->registerJs($jsFlat);
?>


<form id="frmSearch" class="form-inline" data-pjax="true" action="<?= \yii\helpers\Url::to(['default/index']) ?>">
    <div class="input-group w-100">
        <?= Html::dropDownList(
            'year',
            $year,
            $yearOptions,
            [
                'class' => 'form-control',
                'style' => 'max-width: 100px;',
                'onchange' => 'this.form.submit()',
            ]
        ) ?>

        <input type="text" class="form-control" id="inputSearch" name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ">

        <?php if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('SecretaryAdmin') || \Yii::$app->user->can('ITAdmin')): ?>
            <?= Html::dropDownList(
                'department',
                Yii::$app->request->get('department', ''),
                ArrayHelper::map(
                    EmployeeDep::find()
                        ->where(['employee_dep_status' => 1])
                        ->joinWith('type')
                        ->orderBy(['category_id' => 'ASC'])
                        ->all(),
                    'employee_dep_id',
                    'employee_dep_label',
                    'type.category_name'
                ),
                [
                    'class' => 'form-control',
                    'prompt' => '---เลือกหน่วยงานทั้งหมด---',
                    'style' => 'max-width: 300px;',
                ]
            ) ?>
        <?php endif; ?>

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


        ?>
        <div class="input-group-append btn-group" role="group">

            <?= Html::button('<i class="fa-solid fa-magnifying-glass"></i> แสดงข้อมูล', [
                'class' => 'btn btn-dark btnSubmit',
                'type' => 'button',
            ]) ?>

            <?php if ($canShowAddButton): ?>
                <?= Html::button('<i class="fa-solid fa-folder-plus"></i> เพิ่มรายการ', [
                    'class' => 'btn btn-primary btnCreate font-weight-bold'
                ]) ?>
            <?php endif; ?>

            <?php if ($canShow): ?>
                <?= Html::button(
                    '<i class="fa-solid fa-database"></i> Master Data',
                    [
                        'class' => 'btn font-weight-bold',
                        'style' => 'background-color: #6f42c1; color: white; border: none;',
                        'id' => 'btnMasterData',
                        'type' => 'button',
                    ]
                ) ?>
                <?php
                $masterDataUrl = \yii\helpers\Url::to(['/survey/default/master-data']);
                $js = <<<JS
                document.getElementById('btnMasterData').addEventListener('click', function() {
                        window.location.href = '{$masterDataUrl}';
                    });
                JS;
                $this->registerJs($js);
                ?>
            <?php endif; ?>

            <?php if ($canShow): ?>
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
            <?php endif; ?>
            <?php if (
                \Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin')
            ): ?>
                <div class="btn-group" role="group">
                    <?= Html::button('<i class="fa-solid fa-chart-column"></i> รายงาน', [
                        'class' => 'btn btn-info dropdown-toggle font-weight-bold',
                        'style' => 'background-color: #17a2b8; border-color: #17a2b8;',
                        'type' => 'button',
                        'data-bs-toggle' => 'dropdown',
                        'aria-expanded' => 'false',
                    ]) ?>
                    <ul class="dropdown-menu">
                        <!-- /<li><?= Html::a('<i class="fa-regular fa-file-lines"></i> รายงานสรุป', ['report/summary'], ['class' => 'dropdown-item']) ?></li> -->
                        <!-- <li><?= Html::a('<i class="fa-solid fa-building"></i> รายงานขอคอมพิวเตอร์', ['report/department'], ['class' => 'dropdown-item']) ?></li> -->
                        <li>
                            <?= Html::a(
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
            <?php endif; ?>
        </div>
    </div>

</form>