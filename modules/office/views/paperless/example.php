
<?PHP

use app\components\Ccomponent;
use yii\helpers\Html;

$fontSize = 16;
$txtJustify = @Ccomponent::strip_tags_content($model->paperless_detail);
$text = str_repeat('&nbsp;', 20) . $txtJustify;
//$text = wordwrap($text, 10, '&nbsp;');
$txtJustify = nl2br($text);
?>
<style>
    .textarea{
        /*
        width : 850px;
        white-space: pre;
        white-space: pre-wrap;
        white-space: pre-line;
        white-space: -pre-wrap;
        white-space: -o-pre-wrap;
        white-space: -moz-pre-wrap;
        white-space: -hp-pre-wrap;
        word-wrap: break-word;
        -moz-hyphens:auto;
        -webkit-hyphens:auto;
        -o-hyphens:auto;
        hyphens:auto;
        text-align: justify;
        */
    }
    .fulljustify {
        text-align: justify;
        width:408px;
    }
    .fulljustify:after {
        content: "";
        display: inline-block;
        width: 100%;
    }
</style>
<table class="table" border="0" width="100%">
    <tr>
        <td width="20%">
            <?= Html::img('@app/web/img/k.jpg', ['width' => '42']) ?>
        </td>
        <td width="80%">
            <div style="font-size: 32pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>บันทึกข้อความ</b></div>
        </td>
    </tr>
</table>
<div style="text-align:left;font-size: <?= $fontSize ?>pt;"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">ส่วนราชการ</b>
    <?= Yii::$app->params['dep_name'] ?>
    <?= @$model->depFrom->employee_dep_label ?>
</div>
<table border="0" width="100%" style="text-align:left;font-size: <?= $fontSize ?>pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td width="48%"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">ที่</b><span class="dotted">&nbsp;&nbsp;<?= Yii::$app->params['dep_bookNumberPrefix'] . @substr($model->paperless_number, 8) ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td>
        <td width="52%"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">วันที่</b><span class="dotted">&nbsp;&nbsp;<?= @Ccomponent::getThaiDate($model->paperless_date, 'L') ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td>
    </tr>
</table>
<table class="table" border="0" width="100%" style="font-size: <?= $fontSize ?>pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="text-align:left;" width="30px"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">เรื่อง&nbsp;</b></td>
        <td style="vertical-align:bottom;text-align:left;" width="90%"><div style="text-align:left;font-size: <?= $fontSize ?>pt;"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;"></b><?= @$model->paperless_topic ?></div></td>
    </tr>
</table>
<div style="text-align:left;font-size: <?= $fontSize ?>pt;"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">เรียน</b>&nbsp;<?= @$model->paperless_to ?></div>
<span class="textarea"><?= $txtJustify ?></span>
<p><?= str_repeat('&nbsp;', 20) . @$model->command->paperless_command_label ?></p>
<br><table border="0" width="100%" style="text-align:left;font-size: <?= $fontSize ?>pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td width="25%"></td>
        <td width="75%">
            <div style="text-align:center;font-size: <?= $fontSize ?>pt;margin-left:  550px;" >
                <?PHP // Html::img('https://upload.wikimedia.org/wikipedia/commons/c/c5/Chris_Evans%27_Signature.png', ['width' => '120', 'height' => '50', 'style' => 'border:5px solid #000'])              ?>
                <?PHP echo @Html::img((!empty($model) ? $model->getUploadPath() : '') . '../laysen/' . @$model->owner->employee_id . '.jpg', ['width' => 120, 'height' => 45]); ?>
                <div class="child">(ลงชื่อ) ………………………….……..…...…….</div>
                (<?= @$model->owner->employee_fullname ?>)
                <br><?= @$model->owner->position->employee_position_name ?>
            </div>
        </td>
    </tr>
</table>
<?PHP
//str_repeat('&nbsp;', 3) .
?>




