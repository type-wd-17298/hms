<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use miloschuman\highcharts\Highcharts;
use app\components\Ccomponent;
use yii\helpers\ArrayHelper;

Pjax::begin(['id' => 'frm01', 'timeout' => false, 'enablePushState' => false]);
?>

<div class="project-company-form">
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
    <div class="row">
        <div class="col-md-4">
            <?PHP
            echo Highcharts::widget([
                'options' => [
                    'colors' => ['#f7a35c', '#91e8e1', '#f15c80', '#e4d354', '#2b908f', '#f45b5b'],
                    'chart' => [
                        'type' => 'pie',
                        'height' => '250',
                        'options3d' => [
                            'enabled' => false,
                            'alpha' => 30,
                            'beta' => 0,
                            'depth' => 80,
                            'viewDistance' => 25
                        ],
                    ],
                    'plotOptions' => [
                        'pie' => [
                            'allowPointSelect' => true,
                            'cursor' => 'pointer',
                            'dataLabels' => [
                                'enabled' => true,
                                'format' => '{point.name}: {point.percentage:.1f} %',
                            ]
                        ]
                    ],
                    'accessibility' => [
                        'point' => [
                            'valueSuffix' => '%'
                        ],
                    ],
                    'title' => ['text' => 'ประเภทการลา'],
//                    'xAxis' => [
//                        'categories' => ['มกราคม', 'มีนาคม', 'เมษายน']
//                    ],
                    'yAxis' => [
                        'title' => ['text' => 'จำนวนวัน']
                    ],
                    'series' => $chart['pie'],
                    'credits' => ['enabled' => false],
                ]
            ]);
            ?>
        </div>

        <div class="col-md-8">
            <?PHP
            echo Highcharts::widget([
                'options' => [
                    'colors' => ['#f7a35c', '#91e8e1', '#f15c80', '#e4d354', '#2b908f', '#f45b5b'],
                    'chart' => [
                        'type' => 'spline',
                        'height' => '250',
                    ],
                    'title' => ['text' => 'สถิติการลา'],
                    'xAxis' => [
                        'categories' => Ccomponent::getArrayThaiMonth()
                    ],
                    'yAxis' => [
                        'title' => ['text' => 'จำนวนวัน']
                    ],
                    'series' => $chart['column']['data'],
                    'credits' => ['enabled' => false],
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="row">
                <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="alert alert-info left-icon-big alert-dismissible fade show">
                        <div class="media">
                            <div class="alert-left-icon-big">
                                <i class="fa-solid fa-leaf fa-2x m-2"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="mt-1 mb-2 font-weight-bold">วันหยุดสะสม</h6>
                                <p class="mb-0 h2 font-weight-bold"><?= @$model->vacation_accrued ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="alert alert-danger left-icon-big alert-dismissible fade show">
                        <div class="media">
                            <div class="alert-left-icon-big">
                                <i class="fa-solid fa-user-injured fa-2x m-2"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="mt-1 mb-2 font-weight-bold">ลาป่วย</h6>
                                <p class="mb-0 h2 font-weight-bold"><?= @($model->leave ? $model->leave->getLeaveSS('sick_leave') : 0) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="alert alert-warning left-icon-big alert-dismissible fade show">
                        <div class="media">
                            <div class="alert-left-icon-big">
                                <i class="fa-solid fa-user-clock fa-2x m-2"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="mt-1 mb-2 font-weight-bold">ลาพักผ่อน</h6>
                                <p class="mb-0 h2 font-weight-bold"><?= @number_format($model->leave->vacationSS, 1) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6">
                    <div class="alert alert-primary left-icon-big alert-dismissible fade show">
                        <div class="media">
                            <div class="alert-left-icon-big">
                                <i class="fa-solid fa-user-shield fa-2x m-2"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="mt-1 mb-2 font-weight-bold">วันหยุดเหลือ</h6>
                                <p class="mb-0 h2 font-weight-bold"><?= @$model->accrued ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="col-md-8">
            <?PHP
            echo Highcharts::widget([
                'options' => [
                    'colors' => ['#f7a35c', '#91e8e1', '#f15c80', '#e4d354', '#2b908f', '#f45b5b'],
                    'chart' => [
                        'type' => 'column',
                        'height' => '250',
                    ],
                    'title' => ['text' => 'สถิติการลาปี ' . @$model->budgetyear],
                    'xAxis' => [
                        'categories' => Ccomponent::getArrayThaiMonth()
                    ],
                    'yAxis' => [
                        'title' => ['text' => 'จำนวนวัน']
                    ],
                    'series' => $chart['column']['data'],
                    'credits' => ['enabled' => false],
                ]
            ]);
            ?>
        </div>


    </div>
    <?php
    $form = ActiveForm::begin([
                'id' => 'frmIndex',
                'action' => ['history', 'id' => $user->employee_id],
                'method' => 'post',
                'layout' => 'horizontal',
                'options' => [
                    'data-pjax' => 1,
                // 'class' => 'form-inline'
                ],
                    // 'enableClientValidation' => false,
    ]);
    ?>
    <div class="card">
        <div class="card-body">
            <?PHP
            echo Html::tag('b', $user->employee_fullname, ['class' => 'text-primary']);
            echo '<br>' . @Html::tag('small', $user->position->employee_position_name, ['class' => '']);
            ?>
            <hr>
            <?= $form->field($model, 'budgetyear')->textInput(['disabled' => '']) ?>
            <?= $form->field($model, 'cumulative')->textInput() ?>
            <?= $form->field($model, 'claim')->textInput() ?>
            <?= $form->field($model, 'vacation_leave')->textInput() ?>
            <?= $form->field($model, 'vacation_accrued')->textInput(['disabled' => '']) ?>
            <?= $form->field($model, 'update_at')->textInput(['disabled' => '']) ?>
            <?= $form->field($model, 'create_at')->textInput(['disabled' => '']) ?>
            <?= $form->field($model, 'staff')->textInput(['disabled' => '']) ?>
            <div class="form-group mt-2">
                <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php Pjax::end() ?>