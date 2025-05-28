
<?PHP

use app\components\Ccomponent;
use yii\helpers\Html;

$fontSize = 16;
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
    /*table, caption, tbody, tfoot, thead, tr, th, td,*/
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

    .div-text {
        /*
        text-align: justify;
        text-justify: auto;
        */
    }
</style>
<table class="table" border="0" width="100%">
    <tr>
        <td>
            <?= Html::img('@app/web/img/k.jpg', ['width' => '64']) ?>
        </td>
        <td>
            <div style="text-align:center;font-size: 32pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>บันทึกข้อความ</b></div>
        </td>
    </tr>
</table>
<div style="text-align:left;font-size: <?= $fontSize ?>pt;"><b>ส่วนราชการ</b>
    <?= Yii::$app->params['dep_name'] ?>
    <?= @$model->dep->department_name ?>
</div>
<table border="0" width="100%" style="text-align:left;font-size: <?= $fontSize ?>pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <b>ที่</b><span class="dotted">&nbsp;&nbsp;<?= @$model->paperless_number ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td>
        <td>
            <b>วันที่</b><span class="dotted">&nbsp;&nbsp;&nbsp;&nbsp;<?= @Ccomponent::getThaiDate($model->paperless_date, 'L') ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td>
    </tr>
</table>
<table border="0" width="100%" style="text-align:left;font-size: <?= $fontSize ?>pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="vertical-align:top;text-align:left;" width="10"><b>เรื่อง&nbsp;</b></td>
        <td style="text-align:left;"><?= $model->paperless_topic ?></td>
    </tr>
</table>
<div style="text-align:left;font-size: <?= $fontSize ?>pt;"><b>เรียน</b> ผู้อำนวยการ<?= Yii::$app->params['dep_name'] ?></div>
<div style="font-size: <?= $fontSize ?>pt;word-wrap: break-word" >
    <?= @$model->paperless_detail ?>
</div>
<div style="text-align:right;font-size: <?= $fontSize ?>pt;margin-top: 10px;">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <br><br>(ลงชื่อ) ………………………….……..…...…….
    <br>(นายศิลา กลั่นแกล้ว)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>