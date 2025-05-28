<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use miloschuman\highcharts\Highcharts;
use app\components\Ccomponent;
use yii\helpers\ArrayHelper;
?>
<div class="m-2">
    <div class="row">

        <div class="col-md-4">
            <?PHP
            echo Highcharts::widget([
                'options' => [
                    'colors' => ['#f7a35c', '#91e8e1', '#f15c80', '#e4d354', '#2b908f', '#f45b5b'],
                    'chart' => [
                        // 'polar' => 'true',
                        'type' => 'pie',
                        'height' => '350',
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

        <div class="col-xl-6">
            <div class="card ">
                <div class="card-header">
                    <h5 class="card-title">ลาพักผ่อน</h5>
                </div>
                <div class="card-body mb-0">
                    <div class="row">
                        <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 col-sm-12">
                            <div class="alert alert-primary left-icon-big alert-dismissible fade show">
                                <div class="media">
                                    <div class="alert-left-icon-big">
                                        <i class="fa-solid fa-leaf fa-2x m-2"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-1 mb-2 font-weight-bold">วันลาสะสม</h6>
                                        <p class="mb-0 h2 font-weight-bold"><?= @$model->vacation_accrued ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 col-sm-12">
                            <div class="alert alert-danger left-icon-big alert-dismissible fade show">
                                <div class="media">
                                    <div class="alert-left-icon-big">
                                        <i class="fa-solid fa-user-large-slash fa-2x m-2"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-1 mb-2 font-weight-bold">ยกเลิกวันลา</h6>
                                        <p class="mb-0 h2 font-weight-bold"><?= @($model->leave ? $model->leave->accruedCancel : 0) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 col-sm-12">
                            <div class="alert alert-warning left-icon-big alert-dismissible fade show">
                                <div class="media">
                                    <div class="alert-left-icon-big">
                                        <i class="fa-solid fa-user-clock fa-2x m-2"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-1 mb-2 font-weight-bold">ลาพักผ่อน</h6>
                                        <p class="mb-0 h2 font-weight-bold"><?= $model->leave->vacationSS ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 col-sm-12">
                            <div class="alert alert-secondary left-icon-big alert-dismissible fade show">
                                <div class="media">
                                    <div class="alert-left-icon-big">
                                        <i class="fa-solid fa-user-shield fa-2x m-2"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-1 mb-2 font-weight-bold">วันลาเหลือ</h6>
                                        <p class="mb-0 h2 font-weight-bold"><?= @$model->accrued ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card ">
                <div class="card-header">
                    <h5 class="card-title">ลาอื่นๆ</h5>
                </div>
                <div class="card-body mb-0">
                    <div class="row">

                        <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 col-sm-12">
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

                        <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 col-sm-12">
                            <div class="alert alert-danger left-icon-big alert-dismissible fade show">
                                <div class="media">
                                    <div class="alert-left-icon-big">
                                        <i class="fa-solid fa-user-shield fa-2x m-2"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-1 mb-2 font-weight-bold">ลากิจ</h6>
                                        <p class="mb-0 h2 font-weight-bold"><?= @($model->leave ? $model->leave->getLeaveSS('personal_leave') : 0) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 col-sm-12">
                            <div class="alert alert-info left-icon-big alert-dismissible fade show">
                                <div class="media">
                                    <div class="alert-left-icon-big">
                                        <i class="fa-solid fa-user-tag fa-2x m-2"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mt-1 mb-2 font-weight-bold">ลาคลอด</h6>
                                        <p class="mb-0 h2 font-weight-bold"><?= @($model->leave ? $model->leave->getLeaveSS('maternity_leave') : 0) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>


</div>