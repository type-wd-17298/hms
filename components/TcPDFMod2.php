<?php

namespace app\components;

/*
  This may help if you are on windows:
  Click on the START button
  Click on CONTROL PANEL
  Click on SYSTEM AND SECURITY
  Click on SYSTEM
  Click on ADVANCED SYSTEM SETTINGS
  Click on ENVIRONMENT VARIABLES
  Under "System Variables" click on "NEW"
  Enter the "Variable name" OPENSSL_CONF
  Enter the "Variable value". My is - C:\wamp\bin\apache\Apache2.2.17\conf\openssl.cnf
  Click "OK" and close all the windows and RESTART your computer.
  The OPENSSL should be correctly working.
 */

use Yii;
use setasign\Fpdi\Tcpdf\Fpdi;
use app\components\Ccomponent;

class TcPDFMod extends Fpdi {

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
		
        //$pp = Yii::getAlias('@app') . '/custom/fonts/thsaraban';
        //$this->fontDir = $pp;
        //$fontname = \TCPDF_FONTS::addTTFfont($pp . '/THSarabunIT๙.ttf');
        //$this->setFooterFont($fontname, '', 8);
        $this->SetY(-15);
        $this->Cell(0, 10, Yii::$app->name . ' พิมพ์เมื่อ ' . Ccomponent::getThaiDate(date('Y-m-d H:i:s'), 'L', 1), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		
    }

    // Page footer
    public function Header() {

    }

    public static function TcPDFModInit() {
        //$pdf = new Fpdi(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf = new TcPDFMod(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pp = Yii::getAlias('@app') . '/custom/fonts/thsaraban';
        $pdf->fontDir = $pp;
        $fontname = \TCPDF_FONTS::addTTFfont($pp . '/THSarabunIT๙.ttf');
        $pdf->setFont($fontname, '', 16, '', true);
        $pdf->setFooterFont([$fontname, '', 8]);
        //$pdf->SetFooterMargin(5);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set default header data
        $pdf->setPrintHeader(FALSE);
        // set margins
        $pdf->setMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        // set image scale factor
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setCellHeightRatio(1.25);
        // ---------------------------------------------------------
        //$tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
        //$pdf->setHtmlVSpace($tagvs);
        // set default font subsetting mode
        //$pdf->setFontSubsetting(true);
        // Add a page
        // This method has several options, check the source code documentation for more information.
        //$pdf->SetCellPadding(0);
        $pdf->AddPage();
        return $pdf;
    }

    public function genKey() {

    }

}
