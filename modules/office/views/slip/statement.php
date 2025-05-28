<?PHP

use app\components\Ccomponent;
use yii\bootstrap\ActiveForm;

$profile = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
//
//echo '<pre>';
//print_r($list);
//echo '</pre>';
if (isset($_GET['print']))
    echo '<link href="../custom/bootstrap.css" rel="stylesheet">';

$form = ActiveForm::begin(['id' => 'frmprint',
            //'action' => ['statement', 'print' => 1],
            'method' => 'get',
            'options' => [
                'target' => '_blank',
            ],]);
?>
<!--<input type="hidden" value="<?= @$_GET['pid'] ?>" name="pid">
<input type="hidden" value="<?= @$_GET['yymm'] ?>" name="yymm">-->
<input type="hidden" value="1" name="print">
<div class="">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align:center;font-size: 18pt;">ใบแจ้งเงินเดือนและยอดเงินคงเหลือ ประจำเดือน <?= Ccomponent::getThaiMonth(substr($list['month'], -2, 2), 'L') . ' ' . substr($list['month'], 0, 4) ?> </div>
            <div style="text-align:center;font-size: 16pt;"><?= Yii::$app->params['dep_name'] ?></div>
            <hr>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    ชื่อ-นามสกุล : <b><?= $profile->employee_fullname ?></b>
                </div>
                <div class="col-md-12">
                    โอนเงินเข้าเลขบัญชี : <b><?= @$list['BankBookNo'] ?></b>
                </div>
            </div>
            <hr>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
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
                </div>

                <div class="col-md-6">
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
                </div>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <b>รวมรับทั้งหมด <?= number_format($sum_income, 2) ?></b>
        </div>
        <div class="col-md-6 text-right">
            <b>รวมจ่ายทั้งหมด <?= number_format(abs($sum_outcome), 2) ?></b>
        </div>
        <div class="col-md-12 text-right">
            <hr>
            <div class="h3">รับสุทธิ <?= number_format($sum_income + $sum_outcome, 2) ?> บาท</div>
        </div>
        <div class="col-md-12 text-right">
            <br>(พิมพ์ปวีณ์ ชาวห้วยหมาก)</b>&nbsp;&nbsp;&nbsp;&nbsp;
            <br>นักวิชาการเงินและบัญชีชำนาญการ
            <br><?= Ccomponent::getThaiDate($list['AllowPrintSlipDate'], 'L') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <br>วัน เดือน ปี ที่ออกหนังสือรับรอง
        </div>
    </div>
</div>
<div class="modal-footer hidden-print">
    <button type="button" class="btn btn-secondary active btn-lg" data-bs-dismiss="modal"><< ปิด</button>
    <button type="button" onclick="frmprint.submit();" class="btn btn-primary btn-lg btnPrint">พิมพ์รายงาน</button>
</div>
<?php ActiveForm::end(); ?>