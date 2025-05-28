
<?PHP

use app\components\Ccomponent;
use yii\bootstrap4\Html;
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
<?PHP
foreach ($models as $key => $model) {
    $datetime2 = new Datetime($model->work_grid_change_date_b);
    $datetime1 = new Datetime($model->work_grid_change_date_a);
    ?>
    <div style="text-align:right;font-size: 12pt;"><b>เลขที่ <?= $model->work_grid_change_id ?></b></div>
    <div style="text-align:center;font-size: 18pt;"><b><u>ใบแลกเวร/โอนเวร/แลกวันหยุด</u></b></div>
    <div style="text-align:right;font-size: 15pt;">วันที่ <?= Ccomponent::getThaiDate($model->create_at, 'L') ?></div>
    <div style="text-align:left;font-size: 15pt;"><b>เรียน</b> หัวหน้า<?= $model->emps->dep->employee_dep_label ?></div>
    <div style="text-align:left;font-size: 14pt;margin-top: 10px;" >
        <div class="room-row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ข้าพเจ้า <span class="dotted"><?= $model->emps->employee_fullname ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            ตำแหน่ง <span class="dotted"><?= @$model->emps->position->employee_position_name ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <br>กลุ่มงาน/งาน <span class="dotted"><?= $model->emps->dep->employee_dep_label ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </span>มีความประสงค์จะขอ<?= $model->workChange->work_change_name ?> เนื่องจาก <?= $model->work_grid_change_detail ?>

            <br>ข้าพเจ้าจะต้อง<b>ปฏิบัติงาน/มีวันหยุด</b>ในวันที่ <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->work_grid_change_date_a, 'L') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            ตั้งแต่เวลา <span class="dotted">&nbsp;&nbsp;<?= $model->workType->work_type_time1 ?> น.&nbsp;&nbsp;</span>
            ถึงเวลา <span class="dotted">&nbsp;&nbsp;<?= $model->workType->work_type_time2 ?> น.&nbsp;&nbsp;</span>
            <br>ต้องการ<b><?= $model->workChange->work_change_name ?></b>
            <?PHP IF ($model->work_change_id <> 3) { ?>
                กับ <span class="dotted"><?= $model->emps2->employee_fullname ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                ตำแหน่ง <span class="dotted"><?= @$model->emps2->position->employee_position_name ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <br>และจะใช้คืนในวันที่<span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->work_grid_change_date_b, 'L') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                ตั้งแต่เวลา <span class="dotted">&nbsp;&nbsp;<?= $model->workType2->work_type_time1 ?> น.&nbsp;&nbsp;</span>
                ถึงเวลา <span class="dotted">&nbsp;&nbsp;<?= $model->workType2->work_type_time2 ?> น.&nbsp;&nbsp;</span>

                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้าพเจ้าขอรับรองว่าการ<b><?= $model->workChange->work_change_name ?></b>นี้เป็นกิจจำเป็น ในเดือนนี้ข้าพเจ้า<b><?= $model->workChange->work_change_name ?></b>มาแล้ว
                <br>จำนวน.............ครั้ง รวมครั้งนี้เป็น.............ครั้ง
            <?PHP } else { ?>
                ให้ <span class="dotted"><?= $model->emps2->employee_fullname ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                ตำแหน่ง <span class="dotted"><?= @$model->emps2->position->employee_position_name ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>โดยไม่มีการใช้คืน
            <?PHP } ?>
        </div>
        <table border="0" width="100%" style="font-size: 14pt;">
            <tr>
                <td width="45%" align="center"  style="vertical-align: top">
                    ลงชื่อ&nbsp;&nbsp;<?PHP echo @Html::img((!empty($model->emps->employee_id) ? $model->emps->getUploadPath() : '') . '../laysen/' . @$model->emps->employee_id . '.jpg', ['width' => 80, 'max-height' => 50]); ?>&nbsp;&nbsp;ผู้ขอ<?= $model->workChange->work_change_name ?>
                    <br>(<span class="dotted">&nbsp;<?= $model->emps->employee_fullname ?>&nbsp;</span>)
                </td>
                <td width="10%" align="center" style="vertical-align: top">&nbsp;</td>
                <td width="45%" align="center" style="vertical-align: top">
                    <?PHP IF (!empty($model->processL1->employee_id) && !in_array($model->work_status_id, ['L01', 'L08'])) { ?>
                        ลงชื่อ <?PHP echo @Html::img((!empty($model->processL1->employee_id) ? $model->emps->getUploadPath() : '') . '../laysen/' . @$model->processL1->receiver->employee_id . '.jpg', ['width' => 80, 'max-height' => 50]); ?>&nbsp;&nbsp;ผู้รับ<?= $model->workChange->work_change_name ?>
                        <br>(<span class="dotted">&nbsp;<?= @$model->processL1->receiver->employee_fullname ?>&nbsp;</span>)
                        <!--  <br>(ตำแหน่ง) <?= @$model->processL1->receiver->position->employee_position_name ?> -->
                    <?PHP } ?>
                </td>
            </tr>
            <tr>
                <td align="center"  style="vertical-align: top">
                    <?PHP IF (!empty($model->processL2->employee_id) && !in_array($model->work_status_id, ['L02', 'L08'])) { ?>
                        ลงชื่อ <?PHP echo @Html::img((!empty($model->processL2->employee_id) ? $model->emps->getUploadPath() : '') . '../laysen/' . @$model->processL2->receiver->employee_id . '.jpg', ['width' => 80, 'max-height' => 50]); ?>&nbsp;&nbsp;หัวหน้าหน่วยงาน
                        <br>(<span class="dotted">&nbsp;<?= @$model->processL2->receiver->employee_fullname ?>&nbsp;</span>)
                        <!--  <br>(ตำแหน่ง) <?= @$model->processL2->receiver->position->employee_position_name ?> -->
                    <?PHP } ?>
                </td>
                <td>&nbsp;</td>
                <td align="center" style="vertical-align: top">
                    <?PHP IF (!empty($model->processL3->employee_id) && !in_array($model->work_status_id, ['L01', 'L02'])) { ?>
                        ลงชื่อ <?PHP echo @Html::img((!empty($model->processL3->employee_id) ? $model->emps->getUploadPath() : '') . '../laysen/' . @$model->processL3->receiver->employee_id . '.jpg', ['width' => 80, 'max-height' => 50]); ?>&nbsp;&nbsp;หัวหน้ากลุ่มงาน
                        <br>(<span class="dotted">&nbsp;<?= @$model->processL3->receiver->employee_fullname ?>&nbsp;</span>)
                        <!--   <br>(ตำแหน่ง) <?= @$model->processL3->receiver->position->employee_position_name ?> -->
                    <?PHP } ?>
                </td>
            </tr>
        </table>
        <?PHP
        IF (count($models) <> ($key + 1)) {
            echo "  <pagebreak />";
        }
        ?>
        <?PHP
    }?>