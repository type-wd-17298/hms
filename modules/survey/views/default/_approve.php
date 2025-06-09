<?php

use kartik\form\ActiveForm;
use yii\bootstrap4\Html;

$form = ActiveForm::begin([
  'id' => 'approve-form',
  'type' => ActiveForm::TYPE_HORIZONTAL,
  'options' => ['enctype' => 'multipart/form-data'],
]);


$requestedQty = (int) $model->survey_list_reuest;

$js = <<<JS
$(document).on("click", "#btnApproveSubmit", function(e) {
    e.preventDefault();
    $('#approve-form').submit();
});


$(document).on('beforeSubmit', '#approve-form', function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);
    data.forEach((value, key) => {
      console.log(key, value);
    });

    $.ajax({
        url: form.action,
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        success: function(res) {
            $('#modalForm').modal('hide'); 
            $('#frmSearch').submit(); 
            Swal.fire({
                icon: 'success',
                title: 'บันทึกสำเร็จ',
                showConfirmButton: false,
                timer: 1500
            });
        },
       error: function(xhr, status, error) {
    console.error('AJAX Error:', status, error);
    console.error('Response Text:', xhr.responseText);
    alert('เกิดข้อผิดพลาด: ' + error);
}
    });

    return false;
});
JS;

$this->registerJs($js, \yii\web\View::POS_READY);

?>

<div class="modal-body px-4 py-2">

  <h5 class="text-primary mb-3"><i class="fa fa-info-circle me-1"></i> ข้อมูลพื้นฐาน</h5>
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <strong>ปีงบประมาณ:</strong>
      <span id="fiscalYear"><?= Html::encode($model->survey_budget_year) ?></span>
    </div>

    <div class="col-md-8">
      <strong>หน่วยงาน:</strong>
      <span id="department"> <?= Html::encode($model->dep->employee_dep_label) ?> </span>
    </div>

    <div class="col-md-12">
      <strong>ชื่อครุภัณฑ์:</strong>
      <span id="itemName"> <?= Html::encode($model->item->item) ?></span>
    </div>

    <div class="col-md-4">
      <strong>จำนวนที่ร้องขอ:</strong>
      <span id="itemRequestedQty"> <?= Html::encode($model->survey_list_reuest) ?>
      </span>
    </div>

    <div class="col-md-4">
      <strong>ราคาต่อหน่วย:</strong>
      <span id="itemPrice"> <?= Html::encode($model->item->price ?? 0) ?> บาท</span>
    </div>

    <div class="col-md-4">
      <strong>ราคารวม:</strong>
      <span id="totalPrice"><?= Html::encode(($model->item->price ?? 0) * $model->survey_list_reuest) ?> บาท</span>
    </div>
  </div>

  <h5 class="text-success mb-3"><i class="fa fa-clipboard-list me-1"></i> ความจำเป็นและลักษณะงาน</h5>
  <div class="row g-3 mb-4">
    <div class="col-md-12">
      <strong>ปัญหา:</strong>
      <div class="text-muted" id="itemIssue">
        <?= Html::encode($model->survey_list_problem ?? '-') ?>
      </div>
    </div>

    <div class="col-md-6">
      <strong>ลักษณะงาน:</strong>
      <span id="itemJobType"><?= Html::encode($model->survey_list_desc ?? '-') ?> </span>
    </div>

    <div class="col-md-6">
      <strong>เปรียบเทียบประมาณงาน:</strong>
      <span id="itemWorkload"><?= Html::encode($model->survey_list_compare ?? '-') ?></span>
    </div>

    <div class="col-md-6">
      <strong>ทดแทน/เพิ่มเติม:</strong>
      <span id="itemPurpose"><?= Html::encode($model->survey_type ?? '-') ?></span>
    </div>

    <div class="col-md-6">
      <strong>เลขที่ทดแทน:</strong>
      <span id="itemReplacement"><?= Html::encode($model->survey_list_partnumber ?? '-') ?></span>
    </div>
  </div>

  <h5 class="text-warning mb-3"><i class="fa fa-comments me-1"></i> ความคิดเห็นเพิ่มเติม</h5>
  <div class="row g-3">
    <div class="col-md-6">
      <strong>เหตุผล / หมายเหตุ:</strong>
      <div class="text-muted" id="approvalReason">
        <?= Html::encode($model->survey_list_comment ?? '-') ?>
      </div>
    </div>

    <div class="col-md-6">
      <strong>ความคิดเห็นจาก IT:</strong>
      <div class="text-muted" id="itComment">
        <?= Html::encode($model->it_comment ?? '-') ?>
      </div>
    </div>

    <div class="col-md-6">
      <strong>ชื่อผู้ร้องขอ:</strong> <span id="requesterName"><?= Html::encode($model->emp->employee_fullname) ?></span>
    </div>
  </div>

</div>


<div class="row px-4 pt-0">
  <div class="col-md-3">
    <div class="row g-2">
      <label>จำนวนที่อนุมัติ</label>

      <input type="number"
        name="SurveyComputerList[survey_list_approve]"
        class="form-control form-control-lg approve-input"
        data-requested="<?= $requestedQty ?>"
        data-badge-id="approvedBadge<?= $model->survey_list_id ?>"
        value="<?= isset($model->survey_list_approve) ? htmlspecialchars($model->survey_list_approve) : $requestedQty ?>" />

      <span id="approvedBadge<?= $model->survey_list_id ?>"
        class="badge bg-success text-white w-100 text-center py-2"
        style="font-size: 0.85rem;">
        <i class="bi bi-check-circle-fill me-1"></i>ทั้งหมด
      </span>
    </div>
  </div>

  <!-- ช่องแสดงความคิดเห็น -->
  <div class="col-md-8">
    <?= $form->field($model, 'approver_comments')->textarea([
      'rows' => 2,
      'class' => 'form-control form-control-lg shadow-sm w-100',
      'placeholder' => 'ระบุเหตุผลประกอบการอนุมัติ เช่น พิจารณาตามความจำเป็น เป็นต้น...'
    ])->label('ความคิดเห็นของผู้อนุมัติ', ['class' => 'form-label fw-semibold']) ?>
  </div>
</div>



<!-- 
<div class="d-flex justify-content-between align-items-center mb-4 p-4">
  <button type="button" class="btn btn-outline-primary" id="prevItem" disabled>
    <i class="fa fa-arrow-left me-1"></i> ย้อนกลับ
  </button>
  <span id="itemCounter" class="fw-semibold text-secondary">รายการที่ 1 จาก 1</span>
  <button type="button" class="btn btn-outline-primary" id="nextItem">
    ถัดไป <i class="fa fa-arrow-right ms-1"></i>
  </button>
</div> -->

<!-- Footer -->
<div class="modal-footer bg-white px-4 mt-4">
  <button type="button" class="btn btn-outline-secondary px-4 mt-3" data-bs-dismiss="modal">
    <i class="fa fa-times me-1"></i> ยกเลิก
  </button>
  <div class="text-right mt-3">
    <?= Html::button('<i class="fas fa-check"></i> อนุมัติ', ['class' => 'btn btn-success', 'id' => 'btnApproveSubmit']) ?>
  </div>

</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
function updateBadge(input) {
  const requestedQty = parseInt(input.dataset.requested);
  const approvedQty = parseInt(input.value);
  const badgeId = input.dataset.badgeId;
  const badge = document.getElementById(badgeId);

  if (!badge || isNaN(approvedQty)) return;

  if (approvedQty === requestedQty) {
    badge.textContent = "อนุมัติทั้งหมด";
    badge.className = "badge bg-success text-white w-100 text-center py-2";
  } else if (approvedQty === 0) {
    badge.textContent = "ไม่อนุมัติ";
    badge.className = "badge bg-danger text-white w-100 text-center py-2";
  } else if (approvedQty > 0 && approvedQty < requestedQty) {
    badge.textContent = "อนุมัติบางส่วน";
    badge.className = "badge bg-warning text-dark w-100 text-center py-2";
  } else {
    badge.textContent = "จำนวนอนุมัติไม่ถูกต้อง";
    badge.className = "badge bg-secondary text-white w-100 text-center py-2";
  }
}


document.querySelectorAll('.approve-input').forEach(input => {
  input.addEventListener('input', () => updateBadge(input));
  updateBadge(input); 
});
JS);
?>