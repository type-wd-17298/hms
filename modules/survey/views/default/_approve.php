<?php

use kartik\form\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap4\Html;

$form = ActiveForm::begin([
  'id' => 'approve-form',
  'type' => ActiveForm::TYPE_HORIZONTAL,
  'options' => ['enctype' => 'multipart/form-data'],
]);

$requestedQty = (int) $model->survey_list_reuest;
$approveMainUrl = Url::to(['default/approve-main']);

function calcUsageAge($receiveDate)
{
  if (!$receiveDate || $receiveDate === '-') {
    return '-';
  }

  try {
    $start = new DateTime($receiveDate);
  } catch (Exception $e) {
    return '-';
  }
  $end = new DateTime();
  $diff = $start->diff($end);
  return "{$diff->y} ปี {$diff->m} เดือน {$diff->d} วัน";
}
$js = <<<JS
$(document).off('beforeSubmit', '#approve-form').on('beforeSubmit', '#approve-form', function (e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);

    $.ajax({
        url: form.action,
        type: 'POST',
        data: data,
        processData: false,
        contentType: false,
        success: function(res) {
            if (res.success) {
                $('#modalForm').modal('hide');
                $.pjax.reload({container:'#gServiceView', async:false});
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: JSON.stringify(res.errors || 'ไม่ทราบสาเหตุ')
                });
            }
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

    <div class="col-md-4">
      <strong>หน่วยงาน:</strong>
      <span id="department"> <?= Html::encode($model->dep->employee_dep_label) ?> </span>
    </div>

    <div class="col-md-4">
      <strong>หน่วยงานที่ติดตั้ง:</strong>
      <span id="department"> <?= Html::encode($model->subdep->employee_subDept_label) ?> </span>
    </div>

    <div class="col-md-12">
      <strong>ชื่อครุภัณฑ์:</strong>
      <span id="itemName" style="<?= $model->item_id == 0 ? 'color:red;' : '' ?>">
        <?= $model->item_id == 0
          ? 'รายการนอกเกณฑ์ราคากลาง'
          : Html::encode($model->item->item ?? '-') ?>
      </span>
    </div>

    <div class="col-md-4">
      <strong>จำนวนที่ร้องขอ:</strong>
      <span id="itemRequestedQty">
        <?= Html::encode($model->survey_list_reuest) ?>
      </span>
    </div>

    <div class="col-md-4">
      <strong>ราคาต่อหน่วย:</strong>
      <span id="itemPrice">
        <?= Html::encode($model->item_id == 0
          ? $model->requested_price
          : ($model->item->price ?? 0)) ?> บาท
      </span>
    </div>

    <div class="col-md-4">
      <strong>ราคารวม:</strong>
      <span id="totalPrice">
        <?php
        $unitPrice = $model->item_id == 0
          ? $model->requested_price
          : ($model->item->price ?? 0);

        $totalPrice = $unitPrice * $model->survey_list_reuest;

        echo Html::encode($totalPrice) . ' บาท';
        ?>
      </span>
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

    <?php if ($model->item_id == 0): ?>
      <div class="col-md-12">
        <strong>รายละเอียด:</strong>
        <span><?= Html::encode($model->detail ?? '-') ?></span>
      </div>
    <?php endif; ?>

    <div class="col-md-6">
      <strong>ลักษณะงาน:</strong>
      <span id="itemJobType"><?= Html::encode($model->survey_list_desc ?? '-') ?> </span>
    </div>

    <div class="col-md-6">
      <strong>เปรียบเทียบประมาณงาน:</strong>
      <span id="itemWorkload"><?= Html::encode($model->survey_list_compare ?? '-') ?></span>
    </div>

    <div class="col-md-2">
      <strong>ทดแทน/เพิ่มเติม:</strong>
      <span id="itemPurpose"><?= Html::encode($model->survey_type ?? '-') ?></span>
    </div>

    <div class="col-md-10">
      <strong>เลขที่ทดแทน:</strong>
      <!--  <span id="itemReplacement"><?= Html::encode($model->survey_list_partnumber ?? '-') ?></span> -->
      <?php if ($model->survey_type !== 'เพิ่มเติม'): ?>
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th style="width: 15%;">เลขครุภัณฑ์</th>
              <th style="width: 15%;">วันที่รับครุภัณฑ์</th>
              <th style="width: 33%;">รายละเอียด</th>
              <th style="width: 12%;">อายุการใช้งาน</th>
              <th style="width: 10%;" class="text-center">สถานะ</th>
              <th style="width: 15%;" class="text-center">การอนุมัติ</th>

            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
              <tr>
                <td><?= Html::encode($item['asset_number']) ?></td>
                <td><?= $item['receiv_date'] ?? '-' ?></td>
                <td><?= Html::encode($item['description'] ?? '-') ?></td>
                <td><?= isset($item['receiv_date']) ? calcUsageAge($item['receiv_date']) : '-' ?></td>
                <td id="status-<?= $item['id'] ?>" class="text-center">
                  <?= $item['status'] == 1 ? '<span class="badge bg-success">อนุมัติแล้ว</span>' : ($item['status'] == 2 ? '<span class="badge bg-danger">ไม่อนุมัติ</span>' : '-') ?>
                </td>
                <td class="text-center">
                  <?= Html::button('อนุมัติ', [
                    'class' => 'btn btn-success btn-sm approve-item',
                    'data-item-id' => $item['id']
                  ]) ?>
                  <?= Html::button('ปฏิเสธ', [
                    'class' => 'btn btn-danger btn-sm reject-item',
                    'data-item-id' => $item['id']
                  ]) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>

        </table>

      <?php endif; ?>
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
        data-input-id="approveInput<?= $model->survey_list_id ?>"
        value="<?= isset($model->survey_list_approve) ? htmlspecialchars($model->survey_list_approve) : $requestedQty ?>"
        <?= $model->survey_type === 'ทดแทน' ? 'readonly' : '' ?> />

      <span id="approvedBadge<?= $model->survey_list_id ?>"
        class="badge bg-success text-white w-100 text-center py-2"
        style="font-size: 0.85rem;">
        <i class="bi bi-check-circle-fill me-1"></i>
        <?= $model->survey_type === 'ทดแทน' && isset($model->survey_list_approve) ? $model->survey_list_approve : 'ทั้งหมด' ?>
      </span>
    </div>
  </div>

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

<script>
  $(document).on('click', '.approve-item, .reject-item', function() {
    let btn = $(this);
    let itemId = btn.data('item-id');
    let action = btn.hasClass('approve-item') ? 'approve' : 'reject';

    $.ajax({
      url: '<?= Url::to(["approve-item"]) ?>',
      type: 'POST',
      data: {
        id: itemId,
        action: action,
        _csrf: yii.getCsrfToken()
      },
      success: function(res) {
        if (res.success) {
          let statusText = action === 'approve' ?
            '<span class="badge bg-success">อนุมัติแล้ว</span>' :
            '<span class="badge bg-danger">ไม่อนุมัติ</span>';
          $('#status-' + itemId).html(statusText);
        } else {
          alert('เกิดข้อผิดพลาด: ' + (res.message || 'ไม่สามารถอัปเดตได้'));
        }
      },
      error: function() {
        alert('ไม่สามารถเชื่อมต่อ server ได้');
      }
    });
  });

  $(document).off("click", "#btnApproveSubmit").on("click", "#btnApproveSubmit", function(e) {
    e.preventDefault();

    const surveyType = <?= json_encode(trim($model->survey_type)) ?>;
    const surveyId = <?= json_encode($model->survey_list_id) ?>;

    if (surveyType === 'ทดแทน') {
      $.ajax({
        url: '<?= $approveMainUrl ?>',
        type: 'POST',
        data: {
          id: surveyId,
          _csrf: yii.getCsrfToken()
        },
        success: function(res) {
          console.log("ApproveMain Response:", res);
          if (res.success) {
            $("#approvedBadge" + surveyId).text(res.approvedQty + " รายการ");
            $("input[name='SurveyComputerList[survey_list_approve]']").val(res.approvedQty);

            $('#modalForm').modal('hide');
            $.pjax.reload({
              container: '#gServiceView',
              async: false
            });
            Swal.fire({
              icon: 'success',
              title: 'บันทึกสำเร็จ',
              timer: 1500,
              showConfirmButton: false
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'เกิดข้อผิดพลาด',
              text: JSON.stringify(res.errors || 'ไม่ทราบสาเหตุ')
            });
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', status, error);
          console.error('Response Text:', xhr.responseText);
          alert('เกิดข้อผิดพลาด: ' + error);
        }
      });
    } else {
      $("#approve-form").trigger("beforeSubmit");
    }
  });
</script>