<?php

namespace app\modules\office\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\modules\epayslip\components\mPDFMod;
use app\modules\epayslip\components\Ccomponent;

/**
 * Default controller for the `edocument` module
 */
class LeaveController extends Controller {

    public function actionViewdoc() {
        $mpdf = mPDFMod::mPDFModInit();
        $mpdf->SetProtection(['print']);
        $mpdf->SetTitle("แบบใบลาพักผ่อน");
        $mpdf->SetAuthor('สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี');
        $mpdf->SetWatermarkText('สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี ');
        $mpdf->showWatermarkText = false;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        //$mpdf->SetDisplayMode('fullpage');
        //$mpdf->SetFooter('<div style="text-align:right;font-size:8px;">สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี วันที่พิมพ์ ' . Ccomponent::getThaiDate(date('Y-m-d'), 'L') . ' หน้า {PAGENO} / {nb}</div>');
        $mpdf->WriteHTML($this->renderPartial('index2', ['model' => @$model, 'data' => @$data]));
        $mpdf->Output();
        return $this->render('index2', [
        ]);
    }

}
