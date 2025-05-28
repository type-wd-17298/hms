<?php

namespace app\modules\office\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
//use yii\data\ActiveDataProvider;
//use yii\web\UploadedFile;
//use yii\helpers\Json;
//use yii\helpers\Html;
//use yii\helpers\Url;
//use app\modules\office\models\PaperlessView;
//use app\modules\office\models\PaperlessOfficial;
//use app\modules\office\models\Paperless;
//use xstreamka\mobiledetect\Device;
//use app\components\TcPDFMod;
//use app\components\mPDFMod;
//use PhpOffice\PhpWord\IOFactory;
//use PhpOffice\PhpWord\TemplateProcessor;
//use PhpOffice\PhpWord\PhpWord;
//use PhpOffice\PhpWord\Style\Language;
//use PhpOffice\PhpWord\Settings;
//use yii\db\Expression;
use app\components\Ccomponent;
//use app\modules\office\models\Uploads;
//use yii\helpers\BaseFileHelper;
use app\components\mPDFMod;

class SlipController extends Controller {

    public function actionIndex() {
        $cid = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_cid;
        try {
            $query = "SELECT CONCAT( tph.PayrollYear,tph.PayrollMonth) AS salaryMonth,
                tph.ApprovedDateTime, tph.PersonID, SUM(IF(tep.ExpenditureType = 1,tpd.ExpenditureAmount,0)) AS get_salary,
 SUM(IF(tep.ExpenditureType = 2,tpd.ExpenditureAmount,0)) AS put_salary,
 SUM(IF(tep.ExpenditureType = 1,tpd.ExpenditureAmount,0)) + SUM(IF(tep.ExpenditureType = 2,tpd.ExpenditureAmount,0)) AS salary,
 tph.AllowPrintSlipDate as aps,
 tph.PersonID as pid
 FROM tblpayrollhead tph
 LEFT JOIN tblpayrolldetail tpd ON tpd.PayrollHeadID = tph.PayrollHeadID
 LEFT JOIN tblexpenditure tep ON tpd.ExpenditureID = tep.ExpenditureID
 INNER JOIN tblperson p ON p.PersonID = tph.PersonID
WHERE p.PID = '{$cid}' and AllowPrintSlipDate is not null
GROUP BY tph.PayrollYear DESC,tph.PayrollMonth DESC
                ";
            $result = \Yii::$app->db_payroll->createCommand($query)->queryAll();
            $attributes = @count($result[0]) > 0 ? array_keys($result[0]) : array(); //หาชื่อ field ในตาราง
            $dataProvider = new ArrayDataProvider([
                'allModels' => $result,
                'sort' => [
                    'attributes' => $attributes,
                ],
                'pagination' => [
                    'pageSize' => 200,
                ],
            ]);
        } catch (\Exception $e) {
            throw new \yii\web\HttpException(405, 'Error MySQL Query' . $e->getMessage());
            $dataProvider = new ArrayDataProvider();
        }

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStatement() {
        @$params = \Yii::$app->request->get();
        $print = FALSE;
        if (isset($params['print']) && $params['print'] == 1)
            $print = TRUE;
        //print_r($params);
        //$cid = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_cid;
        try {
            $query = "SELECT *,
concat(p.PreName,p.FName,' ',p.LName) as fullname
FROM tblpayrollhead tph
LEFT JOIN tblpayrolldetail tpd ON tpd.PayrollHeadID = tph.PayrollHeadID
LEFT JOIN tblexpenditure tep ON tpd.ExpenditureID = tep.ExpenditureID
LEFT JOIN tblperson p ON p.PersonID = tph.PersonID
LEFT JOIN tbldepartment d ON p.DepartmentID = d.DepartmentID
LEFT JOIN tbluser u ON u.LoginName= tph.ApprovedBy
WHERE tph.PersonID =  '{$params['pid']}'
AND concat(tph.PayrollYear,tph.PayrollMonth) =   '{$params['yymm']}'
                ";
            $result = \Yii::$app->db_payroll->createCommand($query)->queryAll();
            $list = [];
            foreach ($result as $row) {
                @$list['salary'][$row["ExpenditureType"]][] = ['label' => $row["Expenditure"], 'value' => $row["ExpenditureAmount"]];
                //@$list['listSalaryValue'][$row["ExpenditureType"]][] = $row["Expenditure"];
                @$list['fullname'] = $row["fullname"];
                @$list['BankBookNo'] = $row["BankBookNo"];
                @$list['month'] = $params['yymm'];
                @$list['AllowPrintSlipDate'] = $row["AllowPrintSlipDate"];
            }
            //$sum = $sumSalaryValue[1] + $sumSalaryValue[2];
        } catch (\Exception $e) {
            $list = [];
            $result = [$e->getMessage()];
        }

        if ($print) {
            $mpdf = mPDFMod::mPDFModInit('P', 'TH');
            $mpdf->SetProtection(['print']);
            $mpdf->SetTitle("ใบแจ้งเงินเดือนและยอดเงินคงเหลือ");
            $mpdf->SetAuthor(Yii::$app->params['dep_name']);
            $mpdf->SetWatermarkText(Yii::$app->params['dep_name']);
            $mpdf->showWatermarkText = false;
            $mpdf->watermark_font = 'DejaVuSansCondensed';
            $mpdf->watermarkTextAlpha = 0.1;
            #$mpdf->SetDisplayMode('fullpage');

            $mpdf->SetFooter('<div style="text-align:right;font-size:8px;">HMS :: ' . Yii::$app->params['dep_name'] . ' วันที่พิมพ์ ' . Ccomponent::getThaiDate(date('Y-m-d'), 'L') . ' หน้า {PAGENO} / {nb}</div>');
            @$mpdf->WriteHTML($this->renderPartial('print', ['list' => $list,]));
            @$mpdf->Output();
            exit;
        } else {
            return $this->renderAjax('statement', [
                        'data' => $result,
                        'list' => $list,
            ]);
        }
    }

}
