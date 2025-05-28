
<?PHP

use app\modules\hr\components\Ccomponent;
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

<div style="text-align:center;font-size: 18pt;"><b><u>แบบใบลาป่วย ลาคลอดบุตร ลากิจส่วนตัว</u></b></div>
<div style="text-align:right;font-size: 16pt;">สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี</div>
<div style="text-align:right;font-size: 16pt;">วันที่...1....เดือน....พฤษภาคม..... พ.ศ...2564....</div>

<div style="text-align:left;font-size: 16pt;"><b>เรื่อง</b> ขอลาป่วย</div>
<div style="text-align:left;font-size: 16pt;"><b>เรียน</b> นายแพทย์สาธารณสุขจังหวัดสุพรรณบุรี</div>
<div style="text-align:left;font-size: 16pt;margin-top: 10px;" >
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    ข้าพเจ้า <span class="dotted"><?= $model->emps->employee_name ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    ตำแหน่ง <span class="dotted">นักวิชาการคอมพิวเตอร์ชำนาญการ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <br>กลุ่มงาน/งาน <span class="dotted">พัฒนายุทธศาสตร์สาธารณสุข&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    สังกัด <span class="dotted">สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <br>
    ขอลา <span class="dotted">ป่วย ลาคลอดบุตร ลากิจส่วนตัว &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    เนื่องจาก <span class="dotted"<span class="dotted">>หมอนัด FU ที่ รพ.เมตตาประชารักษ์ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>

    <br>ตั้งแต่วันที่ <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->leave_start, 'F') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    ถึงวันที่ <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->leave_end, 'F') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    มีกำหนด <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>วัน
    <br>ข้าพเจ้าได้ลา(ป่วย ลาคลอดบุตร ลากิจส่วนตัว) ครั้งสุดท้ายตั้งแต่วันที่ <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->leave_start, 'F') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    ถึงวันที่ <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Ccomponent::getThaiDate($model->leave_start, 'F') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    มีกำหนด <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>วัน
    ในระหว่างลาจะติดต่อข้าพเจ้าได้ที่ <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;83 หมู่ 2 ต.บางพลับ อ.สองพี่น้อง จ.สุพรรณบุรี 72110&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    หมายเลขโทรศัพท์ <span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;086-8110543&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
    <br>
    <div style="text-align:right;font-size: 16pt;margin-top: 50px;">
        ขอแสดงความนับถือ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <br><br>(ลงชื่อ) ………………………….……..…...…….
        <br>(นายศิลา กลั่นแกล้ว)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
    <br>
    <table border="0" width="100%">
        <tr>
            <td width="50%"  style="vertical-align: top">
                <div style="text-align:right;font-size: 16pt;margin-top: 30px;margin-bottom: 50px;">
                    <b><u>สถิติการลาในปีงบประมาณนี้</u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <table border="1" width="100%" style="font-size: 16pt; border: 1px; border-collapse: collapse;">
                    <tr>
                        <th style="text-align: center;">ประเภทลา</th>
                        <th style="text-align: center;">ลามาแล้ว<br>(วันทำการ)</th>
                        <th style="text-align: center;">ลาครั้งนี้<br>(วันทำการ)</th>
                        <th style="text-align: center;">รวมเป็น<br>(วันทำการ)</th>
                    </tr>
                    <tr>
                        <td>ป่วย</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>กิจส่วนตัว</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ลาคลอด</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <div style="text-align:right;font-size: 16pt;margin-top: 20px;">
                    <br>(ลงชื่อ) ………………………….……..…...…….ผู้ตรวจสอบ
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(นายศิลา กลั่นแกล้ว)
                    <br>(ตำแหน่ง)………………………….……..…...…….
                    <br>วันที่...1....เดือน....พฤษภาคม..... พ.ศ...2564....
                </div>

            </td>
            <td width="50%" align="right" style="vertical-align: top">
                <div style="text-align:right;font-size: 16pt;margin-top: 30px;">
                    <b><u>ความเห็นของผู้บังคับบัญชา</u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <br>...............................................................
                    <br>...............................................................
                    <br>(ลงชื่อ) ………………………….……..…...…….
                    <br>(นายศิลา กลั่นแกล้ว)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <br>(ตำแหน่ง)………………………….……..…...…….
                </div>
                <br><br>
                <div style="text-align:right;font-size: 16pt;margin-top: 30px;">
                    <b><u>คำสั่ง</u></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <br><br> อนุญาต&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ไม่อนุญาต
                    <br>...............................................................
                    <br>(ลงชื่อ) ………………………….……..…...…….
                    <br>(นายศิลา กลั่นแกล้ว)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <br>(ตำแหน่ง)………………………….……..…...…….
                    <br>วันที่...1....เดือน....พฤษภาคม..... พ.ศ...2564....
                </div>
            </td>
        </tr>
    </table>
    <!--

    <div class = "">
        <h1>ระบบลางานออนไลน์</h1>
    </div>
    <div class="rows">
        <div class="col-auto">
            <div class="card text-white bg-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Header</div>
                <div class="card-body">
                    <h4 class="card-title">Primary card title</h4>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-auto">
        <div class="card text-white bg-secondary mb-3" style="max-width: 20rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h4 class="card-title">Secondary card title</h4>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
    </div>
    <div class="col-auto">
        <div class="card text-white bg-success mb-3" style="max-width: 20rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h4 class="card-title">Success card title</h4>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>

    </div>
    <div class="col-auto">
        <div class="card text-white bg-danger mb-3" style="max-width: 20rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h4 class="card-title">Danger card title</h4>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>
    </div>
    </div>-->

