<?PHP

use app\components\Ccomponent;
use miloschuman\highcharts\Highcharts;
?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <?PHP
                echo Highcharts::widget([
                    'options' => [
                        //  'colors' => ['#f7a35c', '#91e8e1', '#f15c80', '#e4d354', '#2b908f', '#f45b5b'],
                        'colors' => [
                            '#9b20d9', '#9215ac', '#861ec9', '#7a17e6', '#7010f9', '#691af3',
                            '#6225ed', '#5b30e7', '#533be1', '#4c46db', '#4551d5', '#3e5ccf',
                            '#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1', '#0a9eaa',
                            '#03c69b', '#00f194'
                        ],
                        'chart' => [
                            'type' => 'column',
                            'type' => 'spline',
                            'height' => '350',
                        ],
                        'title' => ['text' => 'สถิติรายงานอุบัติการณ์ที่เกิดขึ้นในระบบเทคโนโลยีสารสนเทศ สรุปตามจำนวนครั้งที่เกิดขึ้น '],
                        'xAxis' => [
                            'type' => 'category'
                        ],
                        'yAxis' => [
                            'title' => ['text' => 'จำนวนงาน']
                        ],
                        'plotOptions' => [
                            'column' => [
                                'allowPointSelect' => true,
                                'cursor' => 'pointer',
                                'dataLabels' => [
                                    'enabled' => true,
                                // 'color' => '#FFFFFF',
                                // 'align' => 'right',
                                // 'connectorColor' => '#000000',
                                // 'format' => '{point.y:.0f}',
                                // 'format' => '{point.percentage:.1f} %',
                                ]
                            ]
                        ],
                        'series' => $chart,
                        'credits' => ['enabled' => false],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">

        <div class="card">
            <!--            <div class="card-header d-block">
                            <h4 class="card-title">SKILL BARS </h4>
                            <p class="mb-0 subtitle">add <code>.progress-animated</code> to change the style</p>
                        </div>-->
            <div class="card-body">
                <?PHP
                echo Highcharts::widget([
                    'options' => [
                        //  'colors' => ['#f7a35c', '#91e8e1', '#f15c80', '#e4d354', '#2b908f', '#f45b5b'],
                        'colors' => [
                            '#9b20d9', '#9215ac', '#861ec9', '#7a17e6', '#7010f9', '#691af3',
                            '#6225ed', '#5b30e7', '#533be1', '#4c46db', '#4551d5', '#3e5ccf',
                            '#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1', '#0a9eaa',
                            '#03c69b', '#00f194'
                        ],
                        'chart' => [
                            'type' => 'column',
                            // 'type' => 'spline',
                            'height' => '350',
                        ],
                        'title' => ['text' => 'สถิติรายงานอุบัติการณ์ที่เกิดขึ้นในระบบเทคโนโลยีสารสนเทศ สรุปตามจำนวนครั้งที่เกิดขึ้น'],
                        'xAxis' => [
                            'type' => 'category'
                        ],
                        'yAxis' => [
                            'title' => ['text' => 'จำนวนงาน']
                        ],
                        'plotOptions' => [
                            'column' => [
                                'allowPointSelect' => true,
                                'cursor' => 'pointer',
                                'dataLabels' => [
                                    'enabled' => true,
                                // 'color' => '#FFFFFF',
                                // 'align' => 'right',
                                // 'connectorColor' => '#000000',
                                // 'format' => '{point.y:.0f}',
                                // 'format' => '{point.percentage:.1f} %',
                                ]
                            ]
                        ],
                        'series' => $chart,
                        'credits' => ['enabled' => false],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?PHP
                echo Highcharts::widget([
                    'options' => [
                        //  'colors' => ['#f7a35c', '#91e8e1', '#f15c80', '#e4d354', '#2b908f', '#f45b5b'],
                        'colors' => [
                            '#9b20d9', '#9215ac', '#861ec9', '#7a17e6', '#7010f9', '#691af3',
                            '#6225ed', '#5b30e7', '#533be1', '#4c46db', '#4551d5', '#3e5ccf',
                            '#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1', '#0a9eaa',
                            '#03c69b', '#00f194'
                        ],
                        'chart' => [
                            'type' => 'column',
                            'type' => 'spline',
                            'height' => '350',
                        ],
                        'title' => ['text' => 'สถิติการดำเนินการ '],
                        'xAxis' => [
                            'type' => 'category'
                        ],
                        'yAxis' => [
                            'title' => ['text' => 'จำนวนงาน']
                        ],
                        'plotOptions' => [
                            'column' => [
                                'allowPointSelect' => true,
                                'cursor' => 'pointer',
                                'dataLabels' => [
                                    'enabled' => true,
                                // 'color' => '#FFFFFF',
                                // 'align' => 'right',
                                // 'connectorColor' => '#000000',
                                // 'format' => '{point.y:.0f}',
                                // 'format' => '{point.percentage:.1f} %',
                                ]
                            ]
                        ],
                        'series' => $chart2,
                        'credits' => ['enabled' => false],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>