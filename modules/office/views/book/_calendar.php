
<?PHP

use kriss\calendarSchedule\widgets\FullCalendarWidget;
use kriss\calendarSchedule\widgets\processors\EventProcessor;
use kriss\calendarSchedule\widgets\processors\HeaderToolbarProcessor;
use kriss\calendarSchedule\widgets\processors\LocaleProcessor;
use yii\helpers\Url;

$url = Url::to(['event-detail']);
$renderBefore = <<<JS
calendar.on('dateClick', function (info) {
    $("#modalContents").html('');
    $.get("{$url}",{date:info.dateStr}, function(data) {
         $("#modalContents").html(data);
    });
    $('#modalEvent').modal('show');
})

JS;
echo FullCalendarWidget::widget([
    'clientOptions' => [
        'eventTimeFormat' => [
            'hour' => '2-digit',
            'minute' => '2-digit',
            'hour12' => false
        ],
        // all options from fullCalendar
        //'editable' => true,
        'selectable' => true,
        //'nextDayThreshold' => '00:00:00',
        //'aspectRatio' => 1,
        'height' => 1200,
        // 'dayMaxEventRows' => true,
        //'contentHeight' => 600,
        //'bootstrapVersion' => 'bootstrap4',
        'firstDay' => '0',
    ],
    'calendarRenderBefore' => $renderBefore,
    // 'calendarRenderAfter' => "console.log('after', calendar)",
    'processors' => [
        new LocaleProcessor(['locale' => 'th',]),
        new HeaderToolbarProcessor(),
        new EventProcessor([
            'events' => ['/office/book/event-calendar'],
                ]),
    ],
]);
?>