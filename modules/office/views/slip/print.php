<?PHP

use app\components\Ccomponent;
use yii\bootstrap\ActiveForm;

$profile = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
//
//echo '<pre>';
//print_r($_GET);
//echo '</pre>';

echo '<link href="../custom/bootstrap.css" rel="stylesheet">';
?>

<div class="col-md-12" style="text-align:center;">
    <b style="font-size: 18pt;">ใบแจ้งเงินเดือนและยอดเงินคงเหลือ ประจำเดือน <?= Ccomponent::getThaiMonth(substr($list['month'], -2, 2), 'L') . ' ' . substr($list['month'], 0, 4) ?> </b>
    <div style="font-size: 16pt;"><?= Yii::$app->params['dep_name'] ?></div>
</div>
<div class="col-md-12" style="font-size: 18px;">
    <div class="row">
        <div class="col-md-12">
            ชื่อ-นามสกุล : <b><?= $profile->employee_fullname ?></b>&nbsp;&nbsp;&nbsp;&nbsp;โอนเงินเข้าเลขบัญชี : <b><?= @$list['BankBookNo'] ?></b>
        </div>

    </div>
</div>
<table class="table" style="font-size: 18px;">
    <tr>
        <td style="vertical-align: top;">
            <table class="table">
                <thead>
                    <tr>
                        <th width="" class="text-left font-weight-bold" colspan="1">รายรับ</th>
                        <th class="text-right font-weight-bold" scope="col">จำนวนเงิน(บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!isset($list['salary'][1]))
                        $list['salary'][1] = [];
                    $sum_income = 0;
                    foreach ((array) $list['salary'][1] as $value) {
                        $sum_income += $value['value'];
                        ?>
                        <tr>
                            <td class="text-left">&nbsp;&nbsp; - <?= @$value['label'] ?></td>
                            <td class="text-right"><?= @number_format($value['value'], 2) ?></td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </td>
        <td style="vertical-align: top;">
            <table class="table">
                <thead>
                    <tr>
                        <th width="" class="text-left font-weight-bold">รายจ่าย</th>
                        <th class="text-right font-weight-bold">จำนวนเงิน(บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!isset($list['salary'][2]))
                        $list['salary'][2] = [];

                    $sum_outcome = 0;
                    foreach ($list['salary'][2] as $key => $value) {
                        $sum_outcome += $value['value'];
                        ?>
                        <tr>
                            <td class="text-left">&nbsp;&nbsp; - <?= @$value['label'] ?></td>
                            <td class="text-right"><?= @number_format(abs($value['value']), 2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: top;text-align:right;">
            <b>รวมรับทั้งหมด <?= number_format($sum_income, 2) ?></b>
        </td>
        <td style="vertical-align: top;text-align:right;">
            <b>รวมจ่ายทั้งหมด <?= number_format(abs($sum_outcome), 2) ?></b>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: top;text-align:right;">

        </td>
        <td style="vertical-align: top;text-align:right;">
            <div class="h3" style="text-align:right;">รับสุทธิ <?= number_format($sum_income + $sum_outcome, 2) ?> บาท</div>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: top;text-align:right;">

        </td>
        <td style="vertical-align: bottom;text-align:right;">
            (พิมพ์ปวีณ์ ชาวห้วยหมาก)</b>&nbsp;&nbsp;&nbsp;&nbsp;
            <br>นักวิชาการเงินและบัญชีชำนาญการ
            <br><?= Ccomponent::getThaiDate($list['AllowPrintSlipDate'], 'L') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
</table>
