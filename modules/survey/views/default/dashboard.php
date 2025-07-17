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
    $requestQty = $model->survey_list_reuest ?? 0;
    $approveQty = $model->survey_list_approve;
    $unitPrice = $model->item->price ?? 0;
    $requestedPrice = $model->requested_price ?? 0;
    $surveyListId = $model->survey_list_id;
    $itComment = $model->it_comment;

    $requestAmount = $requestQty * $unitPrice;
    $approveAmount = $approveQty * $unitPrice;

    $totalRequestQty += $requestQty;
    $totalApproveQty += $approveQty;
    $totalRequestAmount += $requestAmount;
    $totalApproveAmount += $approveAmount;

    if ($requestQty > 0) {
        $countAllRequests++;
        $totalRequested += $requestQty;
        $byDepartment[$depLabel] = ($byDepartment[$depLabel] ?? 0) + $requestQty;

        if ($model->item_id == 0) {
            // นอกเกณฑ์ราคากลาง: ต้องแสดงแยก
            $key = "รายการนอกเกณฑ์ราคากลาง (ID: {$surveyListId})";
            $byItem[$key] = [
                'price' => null,
                'qty' => $requestQty,
                'approve' => $approveQty,
                'calc_price' => $requestedPrice
            ];
        } else {
            // รายการปกติ: รวมตามชื่อ
            if (!isset($byItem[$itemName])) {
                $byItem[$itemName] = [
                    'price' => $unitPrice,
                    'qty' => 0,
                    'approve' => 0,
                    'calc_price' => $unitPrice
                ];
            }
            $byItem[$itemName]['qty'] += $requestQty;
            $byItem[$itemName]['approve'] += $approveQty;
        }

        // ใช้คำนวณรวมทั้งหมด
        $calculatedPrice = ($model->item_id == 0) ? $requestedPrice : $unitPrice;
        $totalRequestAmount += $requestQty * $calculatedPrice;
        $totalApproveAmount += $approveQty * $calculatedPrice;
    }

    if ($approveQty !== null && $approveQty != 0) {
        $countApproved++;
        $totalApprovedPrice += $approveQty * (($model->item_id == 0) ? $requestedPrice : $unitPrice);
    } elseif((trim((string)$approveQty) === '' || $approveQty === null) &&
          (trim((string)$itComment) === '' || $itComment === null)) {
        $countPending++;
    } elseif ($approveQty === 0 && $requestQty !== 0) {
        $countRejected++;
    }
}

$surveyTypeStats = [
    'เพิ่มเติม' => 0,
    'ทดแทน' => 0,
];

foreach ($allModels as $model) {
    $type = trim($model->survey_type);
    if (isset($surveyTypeStats[$type])) {
        $surveyTypeStats[$type]++;
    }
}

arsort($byDepartment);
$topDepartments = array_slice($byDepartment, 0, 10, true);

$colors = [
    'rgba(255, 99, 132, 0.7)',
    'rgba(54, 162, 235, 0.7)',
    'rgba(255, 206, 86, 0.7)',
    'rgba(75, 192, 192, 0.7)',
    'rgba(153, 102, 255, 0.7)',
    'rgba(255, 159, 64, 0.7)'
];

$borderColors = array_map(function ($c) {
    return str_replace('0.7', '1', $c);
}, $colors);

$this->registerJsVar('depLabels', array_keys($topDepartments));
$this->registerJsVar('depValues', array_values($topDepartments));
$this->registerJsVar('bgColors', array_slice($colors, 0, count($topDepartments)));
$this->registerJsVar('bdColors', array_slice($borderColors, 0, count($topDepartments)));

$this->registerJs("
    var ctx = document.getElementById('depBarChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: depLabels,
            datasets: [{
                label: 'จำนวนร้องขอ',
                data: depValues,
                backgroundColor: bgColors,
                borderColor: bdColors,
                borderWidth: 1,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#333',
                        font: {
                            size: 14,
                            family: 'Kanit, Segoe UI, Tahoma, sans-serif'
                        },
                        padding: 10
                    }
                },
                tooltip: {
                    backgroundColor: '#333',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#ccc',
                    borderWidth: 1
                }
            },
            layout: {
                padding: 10
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
    background-color: #EBEBEB;
    /* transition: all 0.3s ease-in-out;  */
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); 
}
.custom-card-hover.hover-approve:hover {
    background-color: #d4edda;
    transform: scale(1.03);
}

.custom-card-hover.hover-pending:hover {
    background-color:rgb(252, 243, 214); 
    transform: scale(1.03);
}

.custom-card-hover.hover-reject:hover {
    background-color: #f8d7da; 
    transform: scale(1.03);
}

.custom-card-hover.hover-all:hover {
    background-color: #e2e3e5; 
    transform: scale(1.03);
}
CSS);
?>


<div class="site-dashboard mt-4">
    <div class="row">
        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card shadow-sm custom-card-hover hover-all">
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
            <div class="widget-stat card shadow-sm custom-card-hover hover-approve">

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
            <div class="widget-stat card shadow-sm custom-card-hover hover-pending">

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
            <div class="widget-stat card shadow-sm custom-card-hover hover-reject">
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

    <div class="row">
        <div class="mt-4 col-md-4 d-flex flex-column justify-content-between mb-0" style="height: 100%;">
            <div class="card flex-fill mb-4" style="min-height: 550px;">
                <div class="card-body d-flex flex-column">
                    <h3 class="text-center mb-3">สรุปตามหน่วยงาน</h3>
                    <canvas id="depBarChart" class="flex-fill" style="max-height: 100%;"></canvas>
                </div>
            </div>

            <div class="card flex-fill mb-0" style="min-height: 550px;">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h3 class="text-center mb-3">สรุปตามประเภทคำขอ</h3>
                    <canvas id="surveyTypeChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>


        <div class="col-md-8">
            <div class="card mt-4 flex-fill">
                <div class="card-body">
                    <h3>สรุปตามรายการครุภัณฑ์</h3>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>รายการ</th>
                                <th>ราคากลาง</th>
                                <th>จำนวนร้องขอ</th>
                                <th>จำนวนที่อนุมัติ</th>
                                <th>มูลค่ารวม</th>
                                <th>มูลค่าอนุมัติรวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($byItem as $item => $data): ?>
                                <tr>
                                    <td><?= Html::encode($item) ?></td>
                                    <td class="text-end">
                                        <?= $data['price'] === null ? '-' : number_format($data['price'], 2) ?>
                                    </td>
                                    <td class="text-end"><?= number_format($data['qty']) ?></td>
                                    <td class="text-end"><?= number_format($data['approve']) ?></td>
                                    <td class="text-end"><?= number_format($data['qty'] * $data['calc_price'], 2) ?></td>
                                    <td class="text-end"><?= number_format($data['approve'] * $data['calc_price'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot>
                            <tr class="table-secondary font-weight-bold">
                                <td class="text-end">รวมทั้งหมด</td>
                                <td></td>
                                <td class="text-end"><?= number_format($totalRequestQty) ?></td>
                                <td class="text-end"><?= number_format($totalApproveQty) ?></td>
                                <td class="text-end"><?= number_format($totalRequestAmount, 2) ?></td>
                                <td class="text-end"><?= number_format($totalApproveAmount, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('surveyTypeChart').getContext('2d');

    const data = {
        labels: ['เพิ่มเติม', 'ทดแทน'],
        datasets: [{
            label: 'จำนวนคำขอ',
            data: [<?= $surveyTypeStats['เพิ่มเติม'] ?>, <?= $surveyTypeStats['ทดแทน'] ?>],
            backgroundColor: ['#4BC0C0', '#FF9F40'], // สีสดใส
            borderRadius: 8, // มุมโค้ง
            barPercentage: 0.6, // ความหนาของแท่ง
            categoryPercentage: 0.7,
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 20
            },
            plugins: {
                tooltip: {
                    backgroundColor: '#f4f4f4',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#ccc',
                    borderWidth: 1,
                    cornerRadius: 6
                },
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        font: {
                            family: 'Kanit',
                            size: 14
                        },
                        color: '#333'
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    max: 80,
                    ticks: {
                        stepSize: 10,
                        font: {
                            family: 'Kanit',
                            size: 14
                        },
                        color: '#333'
                    },
                    grid: {
                        color: '#f0f0f0'
                    }
                }
            }
        }
    };

    new Chart(ctx, config);
</script>

<style>
    #surveyTypeChart {
        height: 300px;
        max-height: 550px;
    }
</style>