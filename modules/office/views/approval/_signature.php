
<?PHP

use app\modules\office\components\Ccomponent as CC;
use app\components\Ccomponent;
use yii\helpers\Html;

$fontSize = 14;
$txtJustify = @Ccomponent::strip_tags_content('');
$text = str_repeat('&nbsp;', 20);
//$text = wordwrap($text, 10, '&nbsp;');
$txtJustify = nl2br($text);
?>
<style>
    .dotted {
        border-bottom: 1.0px dotted #000;
        display: table-cell;
        width: 100%;
    }
</style>

<table border="0" width="100%" style="text-align:left;font-size: <?= $fontSize ?>pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td width="50%"  valign="top">
            <div style="text-align:center;font-size: <?= $fontSize ?>pt;"><br><br>
                ความเห็นหัวหน้ากลุ่มงาน/ฝ่าย<br>
                <?PHP
                IF (!empty($model->processA2->acknowledge->employee_fullname))
                    echo @Html::img((!empty($model) ? $model->getUploadPath() : '') . '../laysen/' . @$model->processA2->employee_id . '.jpg', ['width' => 100, 'height' => 30]);
                ?>
                <div class="child">(ลงชื่อ) ………………………….……..…...…….</div>
                <?PHP
                IF (!empty($model->processA2->acknowledge->employee_fullname)) {
                    ?>
                    (<?= @$model->processA2->acknowledge->employee_fullname ?>)
                    <br><?= @$model->processA2->acknowledge->position->employee_position_name ?>
                <?PHP } ELSE { ?>
                    (...............................................)
                <?PHP } ?>
            </div>
            <div style="text-align:center;font-size: <?= $fontSize ?>pt;" >
                ความเห็นหัวหน้ากลุ่มภารกิจด้านพัฒนาระบบบริการและ<br>สนับสนุนบริการสุขภาพ<br>
                <?PHP
                IF (!empty($model->processA5->employee_id))
                    echo @Html::img((!empty($model) ? $model->getUploadPath() : '') . '../laysen/' . @$model->processA5->employee_id . '.jpg', ['width' => 100, 'height' => 30]);
                ?>
                <div class="child">(ลงชื่อ) ………………………….……..…...…….</div>
                <?PHP
                IF (!empty($model->processA5->employee_id)) {
                    ?>
                    (<?= @$model->processA5->acknowledge->employee_fullname ?>)
                <?PHP } ELSE { ?>
                    (...............................................)
                <?PHP } ?>
                <br>รองผู้อำนวยการกลุ่มพัฒนาระบบริการและสนับสนุนบริการสุขภาพ
            </div>
        </td>
        <td width="50%" valign="top">
            <div style="text-align:center;font-size: <?= $fontSize ?>pt;" >

                <?PHP
                IF (!empty($model->processA1->employee_id))
                    echo @Html::img((!empty($model) ? $model->getUploadPath() : '') . '../laysen/' . @$model->emps->employee_id . '.jpg', ['width' => 100, 'height' => 30]);
                ?>
                <div class="child">(ลงชื่อ) ………………………….……..…...…….ผู้ขออนุมัติ</div>
                (<?= @$model->emps->employee_fullname ?>)
                <br><?= @$model->emps->position->employee_position_name ?>
            </div>
            <div style="text-align:center;font-size: <?= $fontSize ?>pt;" >
                ความเห็นผู้ว่าราชการจังหวัดสุพรรณบุรี/
                ผู้อำนวยการโรงพยาบาลสมเด็จพระสังฆราช องค์ที่ 17<br>
                [<b>/</b>] อนุมัติเข้าร่วมประชุม/อบรม/สัมมนา และเบิกค่าใช้จ่ายได้<br>
                [ ] ความเห็นอื่น…………………..................................................<br>
                <?PHP
                IF (!empty($model->processA6->employee_id))
                    echo @Html::img((!empty($model) ? $model->getUploadPath() : '') . '../laysen/' . @$model->processA6->employee_id . '.jpg', ['width' => 100, 'height' => 30]);
                ?>
                <div class="child">(ลงชื่อ) ………………………….……..…...…….</div>
                <?PHP
                IF (!empty($model->processA6->employee_id)) {
                    ?>
                    (<?= @$model->processA6->receiver->employee_fullname ?>)
                <?PHP } ELSE { ?>
                    (...............................................)
                <?PHP } ?>
                <br>ผู้อำนวยการโรงพยาบาลสมเด็จพระสังฆราช องค์ที่ 17
                <br>ปฏิบัติราชการแทนผู้ว่าราชการจังหวัดสุพรรณบุรี
            </div>
        </td>
    </tr>
</table>