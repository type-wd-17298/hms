<?php

use kriss\calendarSchedule\widgets\FullCalendarWidget;
use kriss\calendarSchedule\widgets\processors\EventProcessor;
use kriss\calendarSchedule\widgets\processors\HeaderToolbarProcessor;
use kriss\calendarSchedule\widgets\processors\LocaleProcessor;
?>

<div class="row">
    <div class="col-xl-2 col-xxl-3">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-intro-title">ประเภทการลา</h4>
                        <div class="">
                            <div id="external-events" class="my-3">
                                <div class="external-event light" style="background-color: #09e879" data-class="bg-white"><i class="fa fa-move"></i><span>ลาพักผ่อน</span></div>
                                <div class="external-event light" style="background-color: #f7ac0a"  data-class="bg-warning"><i class="fa fa-move"></i>ลากิจส่วนตัว</div>
                                <div class="external-event light" style="background-color: #bf40ff"  data-class="bg-info"><i class="fa fa-move"></i>ลาป่วย</div>
                                <div class="external-event light" style="background-color: #f55195" data-class="bg-info"><i class="fa fa-move"></i>ลาคลอดบุตร</div>
                                <div class="external-event light" style="background-color: #ff1717" data-class="bg-danger"><i class="fa fa-move"></i>ยกเลิกวันลา</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning left-icon-big alert-dismissible fade show m-2 d-none">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"><span><i class="mdi mdi-btn-close"></i></span>
            </button>
            <div class="media">
                <div class="alert-left-icon-big">
                    <span><i class="fa-solid fa-circle-info"></i></span>
                </div>
                <div class="media-body">
                    <h5 class="mt-1 mb-2">แจ้งให้ทราบ</h5>
                    <p class="mb-0">ระบบจะแสดงข้อมูลเฉพาะรายการที่อยู่ในระหว่างดำเนินการ</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-10 col-xxl-9">
        <div class="card">
            <div class="card-body">
                <?PHP
                echo FullCalendarWidget::widget([
                    'clientOptions' => [
                        'eventTimeFormat' => [
                            'hour' => '2-digit',
                            'minute' => '2-digit',
                            'hour12' => false
                        ],
                        // all options from fullCalendar
                        //'editable' => true,
                        //'selectable' => true,
                        //'nextDayThreshold' => '00:00:00',
                        //'aspectRatio' => 1,
                        //'height' => 1000,
                        'dayMaxEventRows' => true,
                    //'contentHeight' => 600,
                    //'bootstrapVersion' => 'bootstrap4',
                    ],
                    'processors' => [
                        // quick solve fullCalendar options
                        new LocaleProcessor(['locale' => 'th']),
                        new HeaderToolbarProcessor(),
                        new EventProcessor([
                            //'events' => $events,
                            'events' => ['index', 'view' => 'calendar', 'mode' => 'event'],
                                ]),
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
