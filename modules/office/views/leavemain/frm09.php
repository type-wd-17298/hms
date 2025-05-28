
<?PHP

use app\components\Ccomponent;
use yii\bootstrap4\Html;

$datetime2 = new Datetime($model->leave_start);
$datetime1 = new Datetime($model->leave_end);
//$interval = $datetime1->diff($datetime2)->days;
$interval = Ccomponent::getWeekdayDifference($datetime2, $datetime1);

$textLabel = 'ยกเลิกวัน' . $model->leave->leaveType->leave_type_name;
?>
<style type="text/css">
    html, body, div, span, applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
    a, abbr, acronym, address, big, cite, code,
    del, dfn, em, img, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    /*    table, caption, tbody, tfoot, thead, tr, th, td,*/
    article, aside, canvas, details, embed,
    figure, figcaption, footer, header, hgroup,
    menu, nav, output, ruby, section, summary,
    time, mark, audio, video {
        margin: 0;
        padding: 0;
        border: 0;
        font: inherit;
        font-size: 100%;
        /*        vertical-align: baseline;*/
    }

    html {
        line-height: 1;
    }

    ol, ul {
        list-style: none;
    }

    /*    table {
            border-collapse: collapse;
            border-spacing: 0;
        }*/

    caption, th, td {
        text-align: left;
        font-weight: normal;
        vertical-align: middle;
    }

    q, blockquote {
        quotes: none;
    }
    q:before, q:after, blockquote:before, blockquote:after {
        content: "";
        content: none;
    }

    a img {
        border: none;
    }

    article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
        display: block;
    }

    body {
        /*        font-family: 'Source Sans Pro', sans-serif;*/
        font-weight: 300;
        font-size: 12px;
        margin: 0;
        padding: 0;
        /*        color: #555555;*/
    }
    body a {
        text-decoration: none;
        color: inherit;
    }
    body a:hover {
        color: inherit;
        opacity: 0.7;
    }
    body .container {
        min-width: 460px;
        margin: 0 auto;
        padding: 0 20px;
    }
    body .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    body .left {
        float: left;
    }
    body .right {
        float: right;
    }
    body .helper {
        display: inline-block;
        height: 100%;
        vertical-align: middle;
    }
    body .no-break {
        page-break-inside: avoid;
    }


    .room-row {
        width: 100%;
        display:table;
    }
    .room {
        display:table-cell;
        white-space: nowrap;
        line-height: 0.5;
        padding-right: 5px;
    }
    .dotted {
        border-bottom: 1.0px dotted #000;
        display: table-cell;
        width: 100%;
    }
    .capacity {
        text-align:right;
        white-space: nowrap;
        margin-left: 5px;
    }
</style>

<div style="text-align:center;font-size: 18pt;"><b><u>แบบใบขอยกเลิกวันลา</u></b></div>
<div style="text-align:right;font-size: 16pt;"><?= Yii::$app->params['dep_name'] ?></div>
<div style="text-align:right;font-size: 16pt;">วันที่ <?= Ccomponent::getThaiDate($model->create_at, 'L') ?></div>

<div style="text-align:left;font-size: 16pt;"><b>เรื่อง</b> ขอ<?= $textLabel ?></div>
<div style="text-align:left;font-size: 16pt;"><b>เรียน</b> ผู้อำนวยการ<?= Yii::$app->params['dep_name'] ?></div>
<div style="text-align:left;font-size: 16pt;margin-top: 10px;" >
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    ตามที่ข้าพเจ้า <span class="dotted"><?= @$model->emps->employee_fullname ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    ตำแหน่ง <span class="dotted"><?= @$model->emps->position->employee_position_name ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <br>กลุ่มงาน/งาน <span class="dotted"><?= $model->emps->dep->employee_dep_label ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </span>

    <br>ได้รับอนุญาตให้<span class="dotted"><?= $model->leave->leaveType->leave_type_name ?>&nbsp;&nbsp;</span>
    ตั้งแต่วันที่ <span class="dotted">&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->leave->leave_start, 'L') ?>&nbsp;&nbsp;&nbsp;</span>
    ถึงวันที่ <span class="dotted">&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->leave->leave_end, 'L') ?>&nbsp;&nbsp;&nbsp;</span>
    รวม <span class="dotted">&nbsp;&nbsp;&nbsp;<?= $model->leave->leave_day ?>&nbsp;&nbsp;&nbsp;</span>วัน นั้น
    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    เนื่องจาก<span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $model->leave_detail ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    จึงขอ<?= $textLabel ?><span class="dotted"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>

    <br>ตั้งแต่วันที่ <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->leave_start, 'L') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    ถึงวันที่ <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->leave_end, 'L') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    จำนวน <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $model->leave_day ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>วัน

    <br>
    <div style="text-align:right;font-size: 16pt;margin-top: 50px;">
        ขอแสดงความนับถือ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <br><br>(ลงชื่อ) <?PHP echo @Html::img((!empty($model->emps->employee_id) ? $model->emps->getUploadPath() : '') . '../laysen/' . @$model->emps->employee_id . '.jpg', ['width' => 120, 'height' => 45]); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <br>(<?= $model->emps->employee_fullname ?>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
    <br>

    <div style="text-align:right;font-size: 16pt;margin-top: 30px;">
        <?PHP IF (!empty($model->processL3->employee_id)) { ?>
            <b><u>ความเห็นของผู้บังคับบัญชา</u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <br><span class="dotted">&nbsp;&nbsp;&nbsp;เห็นสมควรอนุญาต&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div style="text-align:right;font-size: 16pt;margin-top: 20px;">
                <br>(ลงชื่อ) <?PHP echo @Html::img((!empty($model->processL3->employee_id) ? $model->emps->getUploadPath() : '') . '../laysen/' . @$model->processL3->receiver->employee_id . '.jpg', ['width' => 120, 'height' => 45]); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <br>(<?= @$model->processL3->receiver->employee_fullname ?>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <br>(ตำแหน่ง) <?= @$model->processL3->receiver->position->employee_position_name ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <br>
        <?PHP } ?>

        <?PHP IF (!empty($model->processExcutive->employee_id)) { ?>
            <b><u>คำสั่ง</u></b>&nbsp;&nbsp;
            <span style="">( <b>/</b> )</span> อนุญาต&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( ) ไม่อนุญาต&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div style="text-align:right;font-size: 16pt;margin-top: 20px;">
                <br>(ลงชื่อ) <?PHP echo @Html::img((!empty($model->processExcutive->employee_id) ? $model->emps->getUploadPath() : '') . '../laysen/' . @$model->processExcutive->employee_id . '.jpg', ['width' => 120, 'height' => 45]); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <br>(<?= @$model->processExcutive->emp->employee_fullname ?>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <br>(ตำแหน่ง) <?= @$model->processExcutive->emp->position->employee_position_name ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <br>
        <?PHP } ?>
    </div>
