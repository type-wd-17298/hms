<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\modules\survey\models\SurveyComputerList as ModelsSurveyComputerList;

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$selectedYear = Yii::$app->request->get('year');

$allModels = ModelsSurveyComputerList::find()->with(['dep', 'item', 'emp'])->all();

$allYears = array_unique(array_filter(ArrayHelper::getColumn($allModels, 'survey_budget_year')));
sort($allYears);

if ($selectedYear) {
    $allModels = array_filter($allModels, function ($model) use ($selectedYear) {
        return $model->survey_budget_year == $selectedYear;
    });
}

$totalRequested = 0;
$totalApprovedPrice = 0;
$byDepartment = [];
$byItem = [];

$countAllRequests = 0;
$countApproved = 0;
$countRejected = 0;
$countPending = 0;

foreach ($allModels as $model) {
    $depLabel = $model->dep->employee_dep_label ?? '-';
    $itemName = $model->item->item ?? '-';
    $requestQty = $model->survey_list_reuest;
    $approveQty = $model->survey_list_approve;
    $unitPrice = $model->item->price ?? 0;
    $itComment = $model->it_comment;

    if ($requestQty > 0) {
        $countAllRequests++;
        $totalRequested += $requestQty;

        $byDepartment[$depLabel] = ($byDepartment[$depLabel] ?? 0) + $requestQty;
        $byItem[$itemName] = ($byItem[$itemName] ?? 0) + $requestQty;
    }

    if ($approveQty !== null && $approveQty != 0) {
        $countApproved++;
        $totalApprovedPrice += $approveQty * $unitPrice;
    } elseif ($approveQty === 0 && $requestQty !== 0) {
        $countRejected++;
    } elseif ($approveQty === null || $itComment === '-') {
        $countPending++;
    }
}

arsort($byDepartment);
$topDepartments = array_slice($byDepartment, 0, 10, true);

$depLabels = json_encode(array_keys($topDepartments), JSON_UNESCAPED_UNICODE);
$depValues = json_encode(array_values($topDepartments));

$this->registerJs("
    var ctx = document.getElementById('depBarChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: $depLabels,
            datasets: [{
                label: 'จำนวนร้องขอ',
                data: $depValues,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
");
?>

<div class="mb-3 row d-flex justify-content-between align-items-center">
    <div class="col-auto">
        <?= Html::button('<i class="fa-solid fa-arrow-left"></i> ย้อนกลับ', [
            'class' => 'btn btn-primary text-white font-weight-bold',
            'id' => 'btnBack',
            'type' => 'button'
        ]) ?>
    </div>

    <div class="col-auto w-25">
        <form method="GET" class="d-flex align-items-center">
            <label for="year" class="me-2 fw-bold w-50">เลือกปีงบประมาณ:</label>
            <select name="year" id="year" class="form-select w-75 " onchange="this.form.submit()">
                <option value="">-- ทุกปี --</option>
                <?php foreach ($allYears as $year): ?>
                    <option value="<?= $year ?>" <?= $year == $selectedYear ? 'selected' : '' ?>>
                        <?= $year ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>


<?php

$homeUrl = Url::to(['/survey/default/index']);
$this->registerJs("document.getElementById('btnBack').addEventListener('click', function() {
    window.location.href = '{$homeUrl}';
});");
?>


<?php
$this->registerCss(<<<CSS
.custom-card-hover {
    transition: transform 0.3s ease, background-color 0.3s ease;
}
.custom-card-hover:hover {
    transform: scale(1.05);
    background-color: #e1e5f5; 
}
CSS);
?>


<div class="site-dashboard mt-4">
    <div class="row">
        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card shadow-sm custom-card-hover">
                <div class="card-body m-2">
                    <div class="media ai-icon">
                        <span class="me-3 bgl-primary text-primary">
                            <i class="fa-solid fa-microchip fa-lg"></i>
                        </span>
                        <div class="media-body">
                            <p class="mb-1 fw-bold text-dark">คำขอทั้งหมด</p>
                            <h3 class="mb-0 text-dark"><?= $countAllRequests ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card shadow-sm custom-card-hover">
                <div class="card-body m-2">
                    <div class="media ai-icon">
                        <span class="me-3 bgl-success text-success">
                            <i class="fa-solid fa-circle-check fa-lg"></i>
                        </span>
                        <div class="media-body">
                            <p class="mb-1 fw-bold text-dark">อนุมัติแล้ว</p>
                            <h3 class="mb-0 text-dark"><?= $countApproved ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card shadow-sm custom-card-hover">
                <div class="card-body m-2">
                    <div class="media ai-icon">
                        <span class="me-3 bgl-warning text-warning">
                            <i class="fa-solid fa-hourglass-half fa-lg"></i>
                        </span>
                        <div class="media-body">
                            <p class="mb-1 fw-bold text-dark">รออนุมัติ</p>
                            <h3 class="mb-0 text-dark"><?= $countPending ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card shadow-sm custom-card-hover">
                <div class="card-body m-2">
                    <div class="media ai-icon">
                        <span class="me-3 bgl-danger text-danger">
                            <i class="fa-solid fa-circle-xmark fa-lg"></i>
                        </span>
                        <div class="media-body">
                            <p class="mb-1 fw-bold text-dark">ไม่อนุมัติ</p>
                            <h3 class="mb-0 text-dark"><?= $countRejected ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h3>สรุปตามหน่วยงาน</h3>
            <canvas id="depBarChart" width="200" height="150"></canvas>
        </div>
        <div class="col-md-6 mt-3">
            <h3>สรุปตามรายการครุภัณฑ์</h3>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>รายการ</th>
                        <th>จำนวนร้องขอ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($byItem as $item => $qty): ?>
                        <tr>
                            <td><?= Html::encode($item) ?></td>
                            <td class="text-right"><?= number_format($qty) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>