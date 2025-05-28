
<?PHP

use app\modules\office\components\Ccomponent as CC;
use app\components\Ccomponent;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$data1 = CC::getListStaff($model->employee_id);
$emps = @implode(", ", $data1);
$cc = @count($data1);

$datetime2 = new Datetime($model->startdate);
$datetime1 = new Datetime($model->enddate);
//$interval = $datetime1->diff($datetime2)->days;
//$interval = Ccomponent::getWeekdayDifference($datetime2, $datetime1);

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
<table class="table" border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td width="20%">
            <?= Html::img('@app/web/img/k.jpg', ['width' => '38']) ?>
        </td>
        <td width="80%">
            <div style="font-size: 28pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>บันทึกข้อความ</b></div>
        </td>
    </tr>
</table>
<div style="text-align:left;font-size: <?= $fontSize ?>pt;"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">ส่วนราชการ</b>
    <?= Yii::$app->params['dep_name'] ?>
    <?= @$model->depFrom->employee_dep_label ?> โทร 0-3553-1077 ต่อ 1504
</div>
<table border="0" width="100%" style="text-align:left;font-size: <?= $fontSize ?>pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td width="48%"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">ที่</b><span class="dotted">&nbsp;&nbsp;สพ ๐๐๓๓.202.3/&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td>
        <td width="52%"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">วันที่</b><span class="dotted">&nbsp;&nbsp;<?= @Ccomponent::getThaiDate($model->create_at, 'L') ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td>
    </tr>
</table>
<table class="table" border="0" width="100%" style="font-size: <?= $fontSize ?>pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="text-align:left;" width="30px"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">เรื่อง&nbsp;</b></td>
        <td style="vertical-align:bottom;text-align:left;" width="90%"><div style="text-align:left;font-size: <?= $fontSize ?>pt;"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;"></b>ขออนุญาตเดินทางไปราชการ</div></td>
    </tr>
</table>
<div style="text-align:left;font-size: <?= $fontSize ?>pt;"><b style="text-align:left;font-size: <?= $fontSize + 4 ?>pt;">เรียน</b>&nbsp;ผูวาราชการจังหวัดสุพรรณบุรี (ผู้อำนวยการ<?= Yii::$app->params['dep_name'] ?> ได้รับมอบอำนาจ)</div>
<span class="" style="text-align:left;font-size: <?= $fontSize ?>pt;"><?= $text ?>ด้วยข้าพเจ้า <?= @$model->emps->employee_fullname ?> ตำแหน่ง<?= @$model->emps->position->employee_position_name ?> มีความประสงค์ขออนุญาต
    ไปราชการเพื่อ
    <br><?PHP
    $types = ArrayHelper::map(\app\modules\office\models\PaperlessApprovalTravel::find()
                            ->orderBy(['approval_type_id' => SORT_ASC])
                            ->limit(10)
                            ->all(), 'approval_type_id', 'approval_type_name');
    foreach ($types as $index => $type) {
        if (in_array($index, [$model->approval_type_id])) {
            echo "[ <b>/</b> ] {$type} ";
        } else {
            echo "[  ] {$type} ";
        }
    }
    ?>
    <?= @$model->topic ?>
    <br>ตามหนังสือเลขที่ <?= @$model->paper->fullpaper2 ?>
    ระหว่างวันที่ <span class="dotted">&nbsp;<?= Ccomponent::getThaiDate($model->startdate, 'L') ?>&nbsp;</span>
    ถึงวันที่ <span class="dotted">&nbsp;<?= Ccomponent::getThaiDate($model->enddate, 'L') ?>&nbsp;</span>ณ <?= @$model->place ?>
    มีกำหนด <span class="dotted">&nbsp;<?= $model->approval_day ?>&nbsp;</span>วัน
    จัดโดย<?= $model->organized ?>
    มีผู้เข้าร่วมอบรมทั้งสิ้น  <?= $cc + 1 ?>  คน ดังรายชื่อต่อไปนี้
</span>
<br>
<table border="0.2" width="100%" style="text-align:left;font-size: 12pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td width="5%" rowspan="2" style="text-align:center;vertical-align: middle">ลำดับ</td>
        <td width="20%" rowspan="2"  style="text-align:center;vertical-align: middle">ชื่อ-สกุล</td>
        <td width="25%" rowspan="2"  style="text-align:center;vertical-align: middle">ตำแหน่ง</td>
        <td width="50%" colspan="5" style="text-align:center;">ประมาณค่าใช้จ่าย</td>
    </tr>
    <tr>
        <td  style="text-align:center;">ค่าลงทะเบียน</td>
        <td  style="text-align:center;">ค่าเบี้ยเลี้ยง</td>
        <td  style="text-align:center;">ค่าที่พัก</td>
        <td  style="text-align:center;">ค่าพาหนะ</td>
        <td  style="text-align:center;">รวม</td>
    </tr>
    <?= $table ?>
</table>
<br>
<table border="0" width="100%" style="text-align:left;font-size: <?= $fontSize ?>pt;" cellspacing="0" cellpadding="0">
    <tr>
        <td><b>ค่าใช้จ่ายเบิกจาก</b>&nbsp;&nbsp;[ <?= ($model->withdraw == 1 ? '<b>/</b>' : '') ?> ] หน่วยงานต้นสังกัด  [ <?= ($model->withdraw == 2 ? '<b>/</b>' : '') ?> ] ผู้จัดการประชุม/อบรม/สัมมนา  [ <?= ($model->withdraw == 3 ? '<b>/</b>' : '') ?> ]  ไม่เบิกค่าใช้จ่ายใดๆ   [ <?= ($model->withdraw == 4 ? '<b>/</b>' : '') ?> ] อื่นๆ....<?= @$model->withdraw_from ?>............................</td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*กรณีเบิกค่าพาหนะ คำนวณตามระยะทางจาก https://dohgis.doh.go.th/dohtotravel/ (ตามเอกสารแนบท้าย) </td>
    </tr>
    <tr>
        <td><b>ขออนุมัติเดินทางเข้ารับการประชุม/อบรม/สัมมนา ตามวัน เวลาดังกล่าว โดยใช้พาหนะ</b></td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <?= ($model->travelby == 1 ? '<b>/</b>' : '') ?> ] รถของโรงพยาบาล  [ <?= ($model->travelby == 3 ? '<b>/</b>' : '') ?> ] รถโดยสารประจำทาง  [ <?= ($model->travelby == 2 ? '<b>/</b>' : '') ?> ] รถยนต์ส่วนตัว หมายเลขทะเบียน...<?= ($model->travelby == 2 ? $model->vehicle_personal : '') ?></td>
    </tr>
    <tr>
        <td><b>สมรรถนะที่ได้รับการพัฒนาจากการอบรมครั้งนี้</b> </td>
    </tr>
    <tr>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?PHP
            $develops = ArrayHelper::map(\app\modules\office\models\PaperlessApprovalDevelop::find()
                                    ->orderBy(['develop_id' => SORT_ASC])
                                    ->limit(10)
                                    ->all(), 'develop_id', 'develop_name');
            $ck = explode(',', $model->develop_id);
            foreach ($develops as $index => $develop) {
                if (in_array($index, $ck)) {
                    echo "[ <b>/</b> ] {$develop} ";
                } else {
                    echo "[  ] {$develop} ";
                }
            }
            ?>
        </td>
    </tr>
</table>
<br>
<span class="" style="text-align:left;font-size: <?= $fontSize ?>pt;"><?= $text ?>จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ</span>
