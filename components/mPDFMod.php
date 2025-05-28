<?php

namespace app\components;

use \Mpdf\Mpdf;

//use kartik\mpdf\Pdf as Mpdf;

class mPDFMod extends Mpdf {
    /*
     * $orientation จัดการการจัดวางหน้ากระดาษ
     *
     * */

    #public $orientation_page; // = 'P'; //orientation P(portrait), any L(landscape)
    #public function setPage($orientation = 'P') {
    #$this->orientation_page = ($orientation == 'L' ? 'A4-L' : 'A4');
    #}

    public static function mPDFModInit($orientation = 'P', $font = 'THS9', $format = 'A4') {
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $pp = __DIR__ . '/../custom/fonts/thsaraban';
        if ($font == 'THS9') {
            return new Mpdf([
                'mode' => 'utf-8',
                'margin_top' => 14,
                'format' => ($orientation == 'L' ? $format . '-L' : $format), //A4-L
                'fontDir' => array_merge($fontDirs, [$pp]),
                'fontdata' => [
                    "thsaraban" => [
                        'R' => "THSarabunIT๙.ttf",
                        'B' => "THSarabunIT๙-Bold.ttf",
                        'I' => "THSarabunIT๙-Italic.ttf",
                        'BI' => "THSarabunIT๙-BoldItalic.ttf"
                    ],
                ],
                'default_font' => 'thsaraban',
            ]);
        } else {
            return new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A5-L',
                'margin_top' => 10,
                //'format' => ($orientation == 'L' ? 'A4-L' : 'A4'), //A4-L
                'fontDir' => array_merge($fontDirs, [$pp]),
                'fontdata' => [
                    "thsaraban" => [
                        'R' => "THSarabunNew.ttf",
                        'B' => "THSarabunNew-Bold.ttf",
                        'I' => "THSarabunNew-Italic.ttf",
                        'BI' => "THSarabunNew-BoldItalic.ttf"
                    ],
                ],
                'default_font' => 'thsaraban',
            ]);
        }
    }

}
