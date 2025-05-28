<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Ccomponent;
use app\modules\office\components\Ccomponent as CC;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = $model->approval_id;
$this->params['breadcrumbs'][] = ['label' => 'Paperless Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$urlDeleteDetail = Url::to(['delete-detail']);
$jsscript = '';
if ($model->budget->budget_yes == 'Y') { //ไม่เบิก
    foreach ($model2 as $data) {
        $a = $data->employee_id;
        $jsscript .= "var c=0;\n";
        $jsscript .= "c =  parseFloat($('#costs1_{$a}').val(),2) + parseFloat($('#costs2_{$a}').val(),2) + parseFloat($('#costs3_{$a}').val(),2) + parseFloat($('#costs4_{$a}').val(),2);\n";
        $jsscript .= "$('#emp_costs_{$a}').html(c);\n";
    }
}
$js = <<<JS

$(".upList").click(function(e){
         Swal.fire({
            icon: 'warning',
            title: 'คุณต้องการลบข้อมูลหรือไม่ ?',
            showCancelButton: true,
            confirmButtonText: 'ลบรายการ',
          }).then((result) => {
            if (result.isConfirmed) {
                        var pid = $(this).data("id");
                        $.post('{$urlDeleteDetail}',{budget_detail_id:pid}, function(data) {
                            $.pjax.reload({container: '#pjGview', async: false});
                            Swal.fire({
                            icon: 'success',
                            title: 'คุณได้ลบรายการดำเนินการสำเร็จ !',
                            showConfirmButton: false,
                            timer: 1500
                            });
                        });
            }
        });
});

$("input").keyup(function(){
        cSummary();
});

function cSummary(){
        var sum_amount = 0;
            var sumCosts1 = 0;
                var sumCosts2 = 0;
                    var sumCosts3 = 0;
                        var sumCosts4 = 0;
  $('.amount').each(function(){
        if($(this).val()==''){
            $(this).val(0);
            $(this).css("background-color", "");
        }
         if($(this).val()>0){
            $(this).css("background-color", "pink");
        }
    sum_amount += +$(this).val();
    $('#approval_costs').html(sum_amount);
    $('#approval_costs_input').val(sum_amount);
  })

  $('.costs1').each(function(){
    sumCosts1 += +$(this).val();
    $('#sumCosts1').html(sumCosts1);
  });
  $('.costs2').each(function(){
    sumCosts2 += +$(this).val();
    $('#sumCosts2').html(sumCosts2);
  });
  $('.costs3').each(function(){
    sumCosts3 += +$(this).val();
    $('#sumCosts3').html(sumCosts3);
  });
  $('.costs4').each(function(){
    sumCosts4 += +$(this).val();
    $('#sumCosts4').html(sumCosts4);
  });
  {$jsscript}
};
//init();
cSummary();
JS;

Pjax::begin(['id' => 'pjGview', 'timeout' => false, 'enablePushState' => false]); //
$this->registerJs($js, $this::POS_READY);
?>
<?php if (Yii::$app->session->hasFlash('alert')): ?>
    <?php
    $op = ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options');
    ?>
    <div class="alert <?= $op['class'] ?> alert-dismissible fade show  mt-2" role="alert">
        <p class="mb-0">
            <?= ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body') ?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>
<?php endif; ?>
<div class="paperless-approval-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="card">
        <div class="card-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'topic',
                    'place',
                    [
                        'label' => 'ระหว่างวันที่',
                        'attribute' => 'startdate',
                        'value' => function ($model) {
                            return Ccomponent::getThaiDate(($model->startdate), 'S', 0) . ' - ' . Ccomponent::getThaiDate(($model->enddate), 'S', 0);
                        }
                    ],
                    'approval_day',
                    'organized',
                    [
                        'attribute' => 'employee_id',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model->employee_id == '') {
                                $html = "-";
                            } else {
                                $data = CC::getListStaff($model->employee_id);
                                $html = @implode(", ", $data);
                            }
                            return $html;
                        }
                    ],
                    [
                        'attribute' => 'employee_own_id',
                        'value' => function ($model) {
                            if ($model->employee_own_id == '') {
                                $html = "-";
                            } else {
                                $data = CC::getListStaff($model->employee_own_id);
                                $html = @implode(", ", $data);
                            }
                            return $html;
                        }
                    ],
                    [
                        'label' => 'เดินทางโดย',
                        'attribute' => 'type.vehicle_type',
                        'value' => function ($model) {

                            $html = $model->type->vehicle_type;
                            if ($model->travelby == 1 && $model->driver == 'Y')
                                $html = $html . " (พร้อมพนักงานขับรถ)";
                            if ($model->travelby == 2)
                                $html = $html . " (ทะเบียน {$model->vehicle_personal})";
                            return $html;
                        }
                    ],
                    [
                        //'noWrap' => TRUE,
                        'attribute' => 'withdraw',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $html = $model->type->vehicle_type;
                            if ($model->withdraw == 4) {
                                $html = "ขอเบิกจาก" . $model->withdraw_from;
                            } else {
                                $html = $model->budget->budget_type;
                            }
                            return $html;
                        }
                    ],
                ],
            ]);
            ?>
            <hr>
            <?php
            $form = ActiveForm::begin([
                        'enableClientValidation' => false,
                        'options' => ['data-pjax' => TRUE,]
            ]);
            ?>
            <table border="1" width="100%" cellspacing="0" cellpadding="0" class="table table-responsive1">
                <tr>
                    <th width="5%" rowspan="2" style="text-align:center;vertical-align: middle">ลำดับ</th>
                    <th width="5%" rowspan="2" style="text-align:center;vertical-align: middle">จัดการ</th>
                    <th width="50%" rowspan="2" style="vertical-align: middle">ชื่อ-สกุล</th>
                    <th width="40%" colspan="5" style="text-align:center;">ประมาณค่าใช้จ่าย</th>
                </tr>
                <tr>
                    <th  style="text-align:center;">ค่าลงทะเบียน</th>
                    <th  style="text-align:center;">ค่าเบี้ยเลี้ยง</th>
                    <th  style="text-align:center;">ค่าที่พัก</th>
                    <th  style="text-align:center;">ค่าพาหนะ</th>
                    <th  style="text-align:center;">รวม</th>
                </tr>
                <?PHP
                $ck = explode(',', $model->employee_id);
                $ck[] = $model->employee_own_id;
                $x = 1;
                foreach ($model2 as $data) {
                    ?>
                    <tr>
                        <td style="text-align:center;"><?= $x++ ?></td>
                        <td>
                            <?PHP
                            if ($model->employee_own_id <> $data->employee_id)
                                echo Html::a('X', 'javascript:void(0)', ['class' => 'badge badge-xs badge-primary upList', 'data-id' => $data->budget_detail_id]);
                            ?>
                        </td>
                        <td>
                            <?= (in_array($data->employee_id, $ck) ? Html::tag('b', $data->emp->employee_fullname, ['class' => 'text-primary']) : Html::tag('u', $data->emp->employee_fullname, ['class' => 'text-danger'])) ?>
                            <br><small><?= $data->emp->position->employee_position_name ?></small>
                            <input type="hidden" id="emp"  name="emp[]" value="<?= $data->employee_id ?>" >
                        </td>
                        <td style="text-align:center;"><input type="number" value="<?= $data->budget_detail_costs1 ?>" class="amount costs1 text-right" id="costs1_<?= $data->employee_id ?>" step="0.1" name="costs1[]" min="0" max="100000"></td>
                        <td style="text-align:center;"><input type="number" value="<?= $data->budget_detail_costs2 ?>" class="amount costs2 text-right" id="costs2_<?= $data->employee_id ?>" step="0.1" name="costs2[]" min="0" max="100000"></td>
                        <td style="text-align:center;"><input type="number" value="<?= $data->budget_detail_costs3 ?>" class="amount costs3 text-right" id="costs3_<?= $data->employee_id ?>" step="0.1" name="costs3[]" min="0" max="100000"></td>
                        <td style="text-align:center;"><input type="number" value="<?= $data->budget_detail_costs4 ?>" class="amount costs4 text-right" id="costs4_<?= $data->employee_id ?>" step="0.1" name="costs4[]" min="0" max="100000"></td>
                        <td style="text-align:right;"><span  id="emp_costs_<?= $data->employee_id ?>"></span></td>
                    </tr>
                <?PHP } ?>
                <tr>
                    <td  colspan="3" style="text-align:right;"><b>รวมทั้งหมด</b></td>
                    <th style="text-align:right;"><span id="sumCosts1">-</span></th>
                    <th style="text-align:right;"><span id="sumCosts2">-</span></th>
                    <th style="text-align:right;"><span id="sumCosts3">-</span></th>
                    <th style="text-align:right;"><span  id="sumCosts4">-</span></th>
                    <th style="text-align:right;"><span  id="approval_costs">-</span></th>
                </tr>

            </table>
            <div class="form-group">
                <div class="row justify-content-between mt-3 mb-5">
                    <div class="col-6">
                        <?= Html::a('<i class="la la-angle-left la-lg"></i> กลับหน้าหลัก', ['index'], ['class' => 'btn btn-light btn-lg btn-dark', 'data-bs-dismiss' => 'modal1', 'data-pjax' => 0]) ?>
                        <?= Html::a('<i class="la la-angle-left la-lg"></i> กลับแก้ไขข้อมูล', ['index'], ['class' => 'btn btn-light btn-lg btn-dark', 'data-bs-dismiss' => 'modal1', 'data-pjax' => 0]) ?>
                    </div>
                    <div class="col-6 text-right">
                        <?= Html::submitButton('<i class="fas fa-plus fa-lg"></i> บันทึกรายการ >>', ['class' => 'btn btn-primary btn-lg']) ?>
                    </div>
                </div>
            </div>
            <input type="hidden"  name="approval_id" value="<?= @$_GET['approval_id'] ?>">
            <input type="hidden" name="approval_costs" id="approval_costs_input" >
            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
<?PHP Pjax::end(); ?>