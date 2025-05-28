<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
//use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

//use yii\widgets\Pjax;
$attr = @$_GET['view'];
$js = <<<JS
        $('#frmSearch').on('submit', function (e) {
           e.preventDefault();
           var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'get',
                data: form.serialize(),
                success: function(response) {
                    //$('#pjGview').html(response);
                }
            });
        return false;
    });

JS;
//$this->registerJs($js, $this::POS_READY);
?>

<form id="frmSearch" class="form-inline1"  action="<?= Url::to(['index']) ?>" data-pjax='1'>
    <div class="input-group input-group-md mr-2">
        <input type="text" class="form-control form-control-md" id="inputSearch"  name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >
        <?PHP
//        if (\Yii::$app->user->can('SuperAdmin')) {
//            if (!isset($modeSearch) || $modeSearch == true)
//                echo Html::dropDownList('dep', (isset($_GET['dep']) ? $_GET['dep'] : ''), ArrayHelper::map(app\modules\hr\models\EmployeeDep::find()->where(['employee_dep_status' => 1])->joinWith('type')->orderBy(['category_id' => 'ASC'])->asArray()->all(), 'employee_dep_id', 'employee_dep_label', 'type.category_name'),
//                        [
//                            'class' => 'form-control form-control-md', //d-none d-xl-block
//                            'prompt' => '---เลือกหน่วยงานทั้งหมด---',
//                            'width' => '10',
//                ]);
//        }
        ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> แสดงข้อมูล', ['class' => 'btn btn-primary btn-sm font-weight-bold']) ?>
        </div>
        <?= Html::button('<i class="fa-regular fa-plus fa-lg"></i> ลงทะเบียนรถยนต์', ['class' => 'btn btn-secondary active btnPopup btn-sm font-weight-bold']) ?>
    </div>
</form>