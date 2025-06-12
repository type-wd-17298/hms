<?php

namespace app\modules\survey\controllers;

use Yii;
use yii\web\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\web\Response;
use app\modules\survey\models\SurveyComputerList;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ExportController extends Controller
{
    public function actionExportExcel()
    {
        $spreadsheet = new Spreadsheet();
        // ---------------- Sheet 1 ----------------
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], 
                ],
            ],
            'font' => [
                'name' => 'TH SarabunPSK',
                'size' => 16, 
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'wrapText' => true,
            ],
        ];

        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('รายการทั้งหมด');

        $sheet1->setCellValue('A1', 'หน่วยงาน');
        $sheet1->setCellValue('B1', 'รายการครุภัณฑ์คอมพิวเตอร์');
        $sheet1->setCellValue('C1', 'พ/ท');
        $sheet1->setCellValue('D1', 'จำนวน');
        $sheet1->setCellValue('E1', 'อนุมัติ');
        $sheet1->setCellValue('F1', 'ปัญหา/อุปสรรค');
        $sheet1->setCellValue('G1', 'ลักษณะงาน');
        $sheet1->setCellValue('H1', 'เปรียบเทียบกับปริมาณงาน');
        $sheet1->setCellValue('I1', 'เลขที่ขอทดแทน');
        $sheet1->setCellValue('J1', 'หมายเหตุ');
        $sheet1->setCellValue('K1', 'ชื่อผู้บันทึก');
        $sheet1->setCellValue('L1', 'ราคาต่อหน่วย');
        $sheet1->setCellValue('M1', 'รวมขอ');
        $sheet1->setCellValue('N1', 'ราคาอนุมัติ');
        $sheet1->setCellValue('O1', 'ข้อมูลเพิ่มเติมจาก IT');

        $models = SurveyComputerList::find()->all();

        $row = 2;
        foreach ($models as $model) {
            $sheet1->setCellValue("A{$row}", $model->dep->employee_dep_label ?? '-');
            $sheet1->setCellValue("B{$row}", $model->item->item ?? '-');
            $sheet1->setCellValue("C{$row}", $model->survey_type);
            $sheet1->setCellValue("D{$row}", $model->survey_list_reuest);
            $sheet1->setCellValue("E{$row}", $model->survey_list_approve ? $model->survey_list_approve : '-');
            $sheet1->setCellValue("F{$row}", $model->survey_list_problem ?? '-');
            $sheet1->setCellValue("G{$row}", $model->survey_list_desc ?? '-');
            $sheet1->setCellValue("H{$row}", $model->survey_list_compare ?? '-');
            $sheet1->setCellValue("I{$row}", $model->survey_list_partnumber ?? '-');
            $sheet1->setCellValue("J{$row}", $model->survey_list_comment ?? '-');
            $sheet1->setCellValue("K{$row}", $model->emp->employee_fullname ?? '-');
            $sheet1->setCellValue("L{$row}", $model->item->price ?? '-');
            $sheet1->setCellValue("M{$row}", ($model->item->price ?? 0) * $model->survey_list_reuest);
            $sheet1->setCellValue("N{$row}", $model->survey_list_approve !== null ? $model->survey_list_approve * $model->item->price : '-');
            $sheet1->setCellValue("O{$row}", $model->it_comment ?? '-');

            $row++;
        }

        $lastDataRow = $row - 1;
        $sheet1->setCellValue("L{$row}", 'รวมทั้งหมด:');
        $sheet1->setCellValue("M{$row}", "=SUM(M2:M{$lastDataRow})");
        $sheet1->setCellValue("N{$row}", "=SUM(N2:N{$lastDataRow})");

        $sheet1->getStyle("A1:O{$row}")->applyFromArray($styleArray);
        for ($i = 1; $i <= $row; $i++) {
            $sheet1->getRowDimension($i)->setRowHeight(24);
        }

        // ---------------- Sheet 2 ----------------
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('สรุปตามหน่วยงาน');

        $items = SurveyComputerList::find()
            ->select('item_id')
            ->distinct()
            ->column();

        $sheet2->setCellValue('A1', 'ลำดับ');
        $sheet2->setCellValue('B1', 'หน่วยงาน');
        $sheet2->mergeCells('A1:A2');
        $sheet2->mergeCells('B1:B2');

        $colIndex = 3;
        foreach ($items as $itemId) {
            $colLetterStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $colLetterEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);

            $sheet2->mergeCells("{$colLetterStart}1:{$colLetterEnd}1");
            $sheet2->setCellValue("{$colLetterStart}1", $itemId);
            $sheet2->setCellValue("{$colLetterStart}2", 'ทดแทน');
            $sheet2->setCellValue("{$colLetterEnd}2", 'เพิ่มเติม');

            $colIndex += 2;
        }

        $models = SurveyComputerList::find()->all();
        $data = [];

        foreach ($models as $model) {
            $depLabel = $model->dep->employee_dep_label ?? '-';
            $itemId = $model->item_id;
            $type = $model->survey_type;
            $qty = $model->survey_list_reuest;

            if (!isset($data[$depLabel])) {
                $data[$depLabel] = [];
            }

            if (!isset($data[$depLabel][$itemId])) {
                $data[$depLabel][$itemId] = ['ทดแทน' => 0, 'เพิ่มเติม' => 0];
            }

            if ($type === 'ทดแทน') {
                $data[$depLabel][$itemId]['ทดแทน'] += $qty;
            } elseif ($type === 'เพิ่มเติม') {
                $data[$depLabel][$itemId]['เพิ่มเติม'] += $qty;
            }
        }

        $row = 3;
        $index = 1;

        foreach ($data as $dep => $itemData) {
            $sheet2->setCellValue("A{$row}", $index++);
            $sheet2->setCellValue("B{$row}", $dep);

            $colIndex = 3;
            foreach ($items as $itemId) {
                $replaceQty = $itemData[$itemId]['ทดแทน'] ?? '-';
                $addQty = $itemData[$itemId]['เพิ่มเติม'] ?? '-';

                $sheet2->setCellValueByColumnAndRow($colIndex, $row, $replaceQty);
                $sheet2->setCellValueByColumnAndRow($colIndex + 1, $row, $addQty);

                $colIndex += 2;
            }

            $row++;
        }

        $centerStyle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $lastColIndex = $colIndex - 1;
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColIndex);
        $lastRow = $row - 1;

        $sheet2->getStyle("A1:{$lastColLetter}2")->applyFromArray($centerStyle);
        $sheet2->getStyle("A1:{$lastColLetter}{$lastRow}")->applyFromArray($borderStyle);
        $sheet2->getStyle("A3:{$lastColLetter}{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle("B3:B{$lastRow}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $spreadsheet->setActiveSheetIndex(0);

        // ---------------- Sheet 3 -------------------
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('รอความคิดเห็น IT');
        $sheet3->setCellValue('A1', 'ID');
        $sheet3->setCellValue('B1', 'แผนก');
        $sheet3->setCellValue('C1', 'รายการครุภัณฑ์');

        $row = 2;
        foreach ($models as $model) {
            if (trim($model->it_comment) === '') {
                $sheet3->setCellValue('A' . $row, $model->survey_list_id);
                $sheet3->setCellValue('B' . $row, $model->dep->employee_dep_label ?? '-');
                $sheet3->setCellValue('C' . $row, $model->item->item ?? '-');
                $row++;
            }
        }

        $filename = 'survey_computer_list_' . date('Y-m-d_H-i-s') . '.xlsx';

        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        Yii::$app->response->headers->add('Content-Disposition', "attachment;filename=\"$filename\"");
        Yii::$app->response->headers->add('Cache-Control', 'max-age=0');

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return $excelOutput;
    }
}
