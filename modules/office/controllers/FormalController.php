<?php

namespace app\modules\office\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\office\models\PaperlessView;
use app\modules\office\models\PaperlessOfficial;
use app\modules\office\models\Paperless;
use app\modules\office\models\PaperlessViewList;
use xstreamka\mobiledetect\Device;
use app\components\TcPDFMod;
//use app\components\mPDFMod;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\Settings;
use yii\db\Expression;
use app\components\Ccomponent;
use app\modules\office\models\Uploads;
use yii\helpers\BaseFileHelper;

class FormalController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    ///บันทึกข้อมูลการรับทราบหนังสือ ที่มีผู้เสนอมาถึง
    public function actionAcknowledge($id) {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $param = \Yii ::$app->request->post();
        $model = PaperlessViewList::find()->where(['paperless_view_id' => $id, 'employee_id' => $emp->employee_id])->one() ?: new PaperlessViewList();
        $model->employee_id = $emp->employee_id;
        $model->view_acknowledge_staff = $emp->employee_id;
        $model->view_acknowledge = 1;
        $model->paperless_view_id = $id;
        $model->view_acknowledge_datetime = new \yii\db\Expression('NOW()');
        $model->save();
        $status = 'success';
        //$message = "คุณรับทราบหนังสือเลขที่ {$model->docs->docs_number} แล้ว";
        $message = "คุณรับทราบหนังสือแล้ว";
        //Cmqtt::public('hms/service/paper/update/BNN', 1);
        return ['status' => $status, 'message' => $message];
    }

    public function saveModel($model) {
        if (is_array($model->paperless_view_deps))
            $model->paperless_view_deps = @implode(',', $model->paperless_view_deps);
        if (is_array($model->paperless_view_emps))
            $model->paperless_view_emps = @implode(',', $model->paperless_view_emps);
        if (is_array($model->paperless_view_auth))
            $model->paperless_view_auth = @implode(',', $model->paperless_view_auth);

        return $model->save();
    }

    public function actionCreate() {
        $model = new PaperlessView();
        $model->scenario = 'manual';
        $model->paperless_level_id = 1; //ค่าเริ่มต้น ความเร่งด่วน
        $model->paperless_from = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        $model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
        $now = \DateTime::createFromFormat('U.u', microtime(true));
        $model->paperless_view_id = 'A' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
        $model->paperless_view_startdate = new \yii\db\Expression('NOW()');
        if (empty($model->paperless_paper_ref))
            $model->paperless_paper_ref = $model->paperless_view_id;
        list($initialPreview, $initialPreviewConfig) = $this->getInitialPreview($model->paperless_view_id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $this->saveModel($model)) {
                $this->Uploads(false, $model->paperless_view_id);
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                //Cmqtt::public('hms/service/paper/update/BNN', 1);
                return $this->redirect(['index']);
            }
        } else {
            //$model->loadDefaultValues();
        }

        return $this->renderAjax('_form', [
                    'model' => $model,
                    'initialPreview' => $initialPreview,
                    'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

    public function actionFf($id) {
        $model2 = PaperlessView::findOne($id);
        $model = new PaperlessView();
        $model->paperless_view_startdate = $model2->paperless_view_startdate;
        $model->paperless_view_enddate = $model2->paperless_view_enddate;
        $model->paperless_from = $model2->paperless_from;
        $model->paperless_topic = $model2->paperless_topic;
        $model->paperless_detail = $model2->paperless_detail;
        $model->paperless_level_id = $model2->paperless_level_id;
        $model->paperless_from = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        $model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
        $now = \DateTime::createFromFormat('U.u', microtime(true));
        $model->paperless_view_id = 'A' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
        $model->paperless_view_startdate = new \yii\db\Expression('NOW()');
        $model->paperless_paper_ref = $model2->paperless_paper_ref;
        list($initialPreview, $initialPreviewConfig) = $this->getInitialPreview($model->paperless_view_id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $this->saveModel($model)) {
                //$this->Uploads(false, $model->paperless_view_id);
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                return $this->redirect(['index']);
            }
        } else {
            //$model->loadDefaultValues();
        }
        return $this->renderAjax('_form_ff', [
                    'model' => $model,
                    'model2' => $model2,
                    'initialPreview' => $initialPreview,
                    'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

    public function actionUpdate($id) {
        $model = PaperlessView::findOne($id);
        $model->paperless_view_deps = explode(',', $model->paperless_view_deps);
        $model->paperless_view_emps = explode(',', $model->paperless_view_emps);
        $model->paperless_view_auth = explode(',', $model->paperless_view_auth);

        list($initialPreview, $initialPreviewConfig) = $this->getInitialPreview($model->paperless_view_id);
        if ($this->request->isPost && $model->load($this->request->post()) && $this->saveModel($model)) {
            $this->Uploads(false, $model->paperless_view_id);
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-success']
            ]);
            //Cmqtt::public('hms/service/paper/update/BNN', 1);
            //return $this->redirect(['update', 'id' => $model->paperless_view_id]);
            //$model->refresh();
            return $this->redirect(['index']);
        }

        return $this->renderAjax('_form', [
                    'model' => $model,
                    //'dataProvider' => $dataProvider,
                    'initialPreview' => $initialPreview,
                    'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

    public function actionListView() {
        @$params = \Yii::$app->request->queryParams;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = PaperlessView::find();
        $model->join('LEFT JOIN', 'paperless_official o', 'o.paperless_id = paperless_paper_ref');
        $model->join('LEFT JOIN', 'paperless p', 'p.paperless_id = paperless_paper_ref');
//        $model->andWhere(['AND',
//            new Expression('paperless_view_emps IS NOT NULL'),
//            new Expression('paperless_view_deps IS NOT NULL'),
//        ]);
        $model->andWhere(['OR',
            new Expression('paperless_view_emps <> "" '),
            new Expression('paperless_view_deps <> "" '),
        ]);

        switch (@$params['view']) {
            case 'keep':
                $model->andWhere(['AND',
                    new Expression('paperless_view_id IN (SELECT paperless_view_id FROM paperless_view_list WHERE employee_id = :emp_id )'),
                ])->addParams([':emp_id' => $emp->employee_id]);
                break;
            case 'out':
                $model->andWhere(['AND',
                    new Expression('paperless_view.employee_id = :employee_id '),
                ])->addParams([':employee_id' => $emp->employee_id]);
                break;
            default:
                $model->andWhere(['AND',
                    new Expression('paperless_view_id NOT IN (SELECT paperless_view_id FROM paperless_view_list WHERE employee_id = :emp_id )'),
                ])->addParams([':emp_id' => $emp->employee_id]);

                $model->andWhere(['AND',
                    new Expression('paperless_view.employee_id <> :employee_id '),
                ])->addParams([':employee_id' => $emp->employee_id]);

                break;
        }

        if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin')) {

        } else {

            $model->andWhere(['OR',
                        ['paperless_view.employee_id' => $emp->employee_id],
                        new Expression('FIND_IN_SET(:employeeID_to_find, paperless_view_emps)'),
                        new Expression('FIND_IN_SET(:employeeDEP_to_find, paperless_view_deps)'),
                        new Expression(' paperless_view_deps = "86" '), //โรงพยาบาลสมเด็จพระสังฆราชองค์ที่ 17
                    ])
                    ->addParams([':employeeID_to_find' => $emp->employee_id, ':employeeDEP_to_find' => (isset($params['dep']) ? $params['dep'] : $emp->employee_dep_id)]);
        }

        $model->andFilterWhere(['OR',
            ['like', 'o.paperless_topic', @$params['search']],
            ['like', 'p.paperless_topic', @$params['search']],
            ['like', 'p.paperless_detail', @$params['search']],
            ['like', 'p.paperless_from', @$params['search']],
            ['like', 'p.paperless_number', @$params['search']],
            ['like', 'paperless_view.paperless_topic', @$params['search']],
            ['like', 'paperless_view.paperless_detail', @$params['search']],
            ['like', 'paperless_official_detail', @$params['search']],
            ['like', 'paperless_official_from', @$params['search']],
            ['like', 'paperless_official_number', @$params['search']],
            ['like', 'paperless_official_booknumber', @$params['search']],
        ]);

        if (!empty($params['dep']))
            $model->andFilterWhere(['OR',
                new Expression('FIND_IN_SET(:employeeDEP_to_find, paperless_view_deps)'),
            ])->addParams([':employeeDEP_to_find' => $params['dep']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC,
                ]
            ],
        ]);
        return $this->renderAjax('_gridview', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id = null) {
        if (substr($id, 0, 1) == 'A') {
            if (Device::$isPhone || Device::$isTablet) {
                return $this->renderAjax('view', ['id' => $id]);
            } else {
                return $this->redirect(['tcpdf', 'id' => $id]);
            }
        } else if (substr($id, 0, 3) == 'BNN') {
            return $this->redirect(['/office/paperless/view', 'id' => $id]);
        } else {
            return $this->redirect(['/office/official/view', 'id' => $id]);
        }
    }

    public function actionDisplay($id) {
        /*
          if (substr($id, 0, 1) == 'A') {
          $model = PaperlessView::findOne($id);
          } elseif (substr($id, 0, 3) == 'BNN') {
          $model = Paperless::findOne($id);
          } else {
          $model = PaperlessOfficial::findOne($id);
          }
         */
        return $this->renderAjax('_view', [
                    'pid' => $id
        ]);
    }

    public function actionTcpdf($id = null) {
        $model = PaperlessView::findOne($id);
        $file = $model->getUrlPdf($model->paperless_view_id);
        return Yii::$app->response->sendFile($file[0], $model->paperless_topic, ['inline' => true]);
    }

    public function actionTcpdf1($id = null) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $model = PaperlessView::findOne($id);
        // create new PDF document
        //$pdf = TcPDFMod::TcPDFModInit();
        $pdf = new TcPDFMod('P', 'mm', 'A4', true, 'UTF-8', false, true);
        // set document information
        //$pdf->setCreator('HMP:Hospital Management Platform');
        //$pdf->setAuthor('Sila Klanklaeo');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle($model->paperless_topic);
        $pdf->setPrintFooter(false);
        //$pdf->Footer(false);
        //$pdf->setSubject('Paperless Platform');
        //$pdf->setKeywords('TCPDF, PDF, example, test, guide');
        // set certificate file
        $dn = [
            "countryName" => "TH",
            "stateOrProvinceName" => "Suphanburi",
            "localityName" => "Songpheenong",
            "organizationName" => "Somdej17 Hospital",
            "organizationalUnitName" => "PHP Documentation Team",
            "commonName" => "นายศิลา กลั่นแกล้ว",
            "emailAddress" => "sila.k@spo.moph.go.th",
        ];

// Generate a new private (and public) key pair
        $privkey = openssl_pkey_new(array(
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ));
        $privkeypass = 'QW2267er!!';

// Generate a certificate signing request
        //$csr = openssl_csr_new($dn, $privkey);
// Generate a self-signed cert, valid for 365 days
        //$x509 = openssl_csr_sign($csr, null, $privkey, $days = 365);
// Save your private key, CSR and self-signed cert for later use
        //openssl_csr_export($csr, $csrout);
        //openssl_x509_export($x509, $certout);
        //openssl_pkey_export($privkey, $pkeyout, $privkeypass);
// set additional information
        $info = array(
            'Name' => 'Sila Klanklaeo',
            'Location' => 'Office',
            'Reason' => 'Testing TCPDF HMS',
            'ContactInfo' => 'https://somdej17.moph.go.th',
        );

        // set style for barcode
        // new style
        $style = array(
            'border' => false,
            'padding' => 0,
            //'fgcolor' => array(128, 0, 0),
            'bgcolor' => false
        );

        // QRCODE,L : QR-CODE Low error correction
        // if (!empty($model->paperless_official_uuid))
        //$pdf->write2DBarcode($model->paperless_official_uuid, 'QRCODE,H', 170, 5, 50, 50, $style, 'N');
        if (!empty($model)) {
            $pdfs = @$model->getUrlPdf($id, 'u');
            if (is_array($pdfs)) {
                foreach ($pdfs as $file) {
                    $pagecount = $pdf->SetSourceFile($file);
                    for ($i = 1; $i <= ($pagecount); $i++) {
                        $pdf->AddPage();
                        $import_page = $pdf->ImportPage($i);
                        $pdf->UseTemplate($import_page);
                    }
                }
            }
        }
        $pdf->Output($id . '.pdf');
        exit;
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    }

    public function actionTest() {

        Settings::setTempDir(Yii::getAlias('@webroot') . '/msword/'); //กำหนด folder temp สำหรับ windows server ที่ permission denied temp (อย่ายลืมสร้างใน project ล่ะ)
        $templateProcessor = new TemplateProcessor(Yii::getAlias('@webroot') . '/msword/template.docx'); //เลือกไฟล์ template ที่เราสร้างไว้
        $templateProcessor->setValue('doc_dep', 'สำนักเทคโนโลยีสารสนเทศ'); //อัดตัวแปร รายตัว
        $templateProcessor->setValue(
                [
                    'doc_no',
                    'doc_date',
                    'doc_title',
                    'doc_detail',
                ],
                [
                    'สพ 0033.206.2/075',
                    '22 พฤศจิกายน ๒๕65',
                    'ขออนุมัติเช่าเครื่องพิมพ์ จำนวน 2 รายการ',
                    'เอาล่ะครับ หลังจากที่ประสบปัญหากับการใช้งาน HTML to PDF ด้วย mPDF เนื่องจากไฟล์ PDF ไม่สามารถจัดรูปแบบได้อย่างคล่องตัวมากนัก เช่นคำตก จัดรูปแบบต่างๆ เล็กๆ น้อยๆ ทำให้ไม่สะดวกแก่ผู้ใช้งาน และการจัด HTML ไปเป็น PDF ในรูปแบบเอกสารราชการนี่ปวดหัวจริงๆ
ในบทความนี้เราจะมาเขียนโปรแกรมเพื่อให้สร้างไฟล์ MS Word กัน โดยเราจะมีเอกสารต้นฉบับ (แน่นอนว่าการตั้งค่าต่างๆ ได้ถูกกำหนดตามรูปแบบเอกสารราชการแล้ว) เช่นขนาดข้อความ ตราครุฑ เยื้องหน้า เยื้องหลัง การเว้นบรรทัด บลาๆๆ ให้เป็นรูปแบบที่ต้องการไว้เลย แล้วเอา PHP อัดข้อความลงตามตำแหน่งที่ต้องการ']); //อัดตัวแปรแบบชุด
        //$templateProcessor->saveAs(Yii::getAlias('@webroot') . '/msword/ms_word_result.docx'); //สั่งให้บันทึกข้อมูลลงไฟล์ใหม่
        // Saving the document as HTML file...
        Settings::setPdfRendererName(Settings::PDF_RENDERER_MPDF);
        //Settings::setPdfRendererName('mPDFMod');
        Settings::setPdfRendererPath('.');
        $report_file_doc = Yii::getAlias('@webroot') . '/msword/ms_word_result.docx';
        ob_clean();
        $phpWord = IOFactory::load($report_file_doc, 'Word2007');
        //$phpWord->setDefaultFontName('TH SarabunIT๙');
        //$phpWord->setDefaultFontSize(26);
        //$phpWord->addFontStyle('rStyle', array('name' => 'TH SarabunIT๙', 'bold' => true, 'italic' => true, 'size' => 16));
        $objWriter = IOFactory::createWriter($phpWord, 'PDF');
        $objWriter->save('helloWorld2.pdf', 'PDF');
    }

    public function actionUploadAjax() {
        $this->Uploads(true);
    }

    private function CreateDir($folderName) {
        if ($folderName != NULL) {
            $basePath = Paperless::getUploadPath();
            if (BaseFileHelper::createDirectory($basePath . $folderName, 0777)) {
                //BaseFileHelper::createDirectory($basePath . $folderName . '/thumbnail', 0777);
            }
        }
        return;
    }

    private function Uploads($isAjax = false, $ref = '') {
        if (Yii::$app->request->isPost) {
            $images = UploadedFile::getInstancesByName('upload_ajax');
            //print_r($images);
            //exit;
            if ($images) {
                if ($isAjax === true) {
                    $ref = Yii::$app->request->post('ref');
                } else {
                    $post = Yii::$app->request->post('PaperlessView');
                    //print_r($_POST);
                    //exit;
                }
                $this->CreateDir($ref);
                foreach ($images as $file) {
                    $fileName = $file->baseName . '.' . $file->extension;
                    $realFileName = md5($file->baseName . time()) . '.' . $file->extension;
                    $savePath = Paperless::UPLOAD_FOLDER . '/' . $ref . '/' . $realFileName;
                    if ($file->saveAs($savePath)) {
//------------------------------------------------------
                        //$image = \Yii::$app->image->load($savePath);
                        //$image->resize(1000);
                        //$image->save($savePath);
//------------------------------------------------------
#if ($this->isImage(Url::base(true) . '/' . $savePath)) {
                        //$this->createThumbnail($ref, $realFileName);
#}
                        $model = new Uploads;
                        $model->ref = $ref;
                        $model->file_name = $fileName;
                        $model->real_filename = $realFileName;
                        $model->type = $file->extension;
                        if (!$model->save()) {
                            print_r($model->errors);
                            exit;
                        }


                        if ($isAjax === true) {
                            echo json_encode(['success'
                                => 'true']);
                        }
                    } else {
                        if ($isAjax === true) {
                            echo json_encode
                                    (['success' => 'false', 'eror' => $file->error]);
                        }
                    }
                }
            }
        }
    }

    private function getInitialPreview($ref) {
        $datas = Uploads::find()->where(['ref' => $ref])->orderBy([
                    'create_date' => SORT_ASC])->all();
        $initialPreview = [];
        $initialPreviewConfig = [];
        foreach ($datas as $key => $value) {
            array_push($initialPreview, $this->getTemplatePreview($value));
            $config = [
                'caption' => $value->file_name,
                //'width' => '120px',
                'url' => Url::to(['deletefile-ajax']),
                'key' => $value->upload_id,
            ];

            if ($value->type == 'pdf') {
                $config['type'] = "pdf";
            } else {
                $config['width'] = '120px';
            }
            array_push($initialPreviewConfig, $config);
        }
        //print_r($config);
        //exit();
        return [$initialPreview, $initialPreviewConfig];
    }

    public function isImage($filePath) {
        return @is_array(getimagesize($filePath)) ? true : false;
    }

    private function getTemplatePreview(Uploads $model) {
        //$filePath = Paperless::getUploadUrl() . $model->ref . '/thumbnail/' . $model->real_filename;
        $filePath = PaperlessView::getUploadUrl() . $model->ref . '/' . $model->real_filename;
        $isImage = $this->isImage($filePath);
        if ($isImage) {
            $file = Html::img($filePath, ['class' => 'file-preview-image', 'alt' => $model->file_name, 'title' => $model->file_name]);
        } else {
            $file = $filePath;
            /*
              $file = "<div class='file-preview-other'> " .
              "<h2><i class='glyphicon glyphicon-file'></i></h2>" .
              "</div>";
             *
             */
        }

        return $file;
    }

    private function createThumbnail($folderName, $fileName, $width = 500) {
        $uploadPath = PaperlessView::getUploadPath() . '/' . $folderName . '/';
        $file = $uploadPath . $fileName;
        $image = \Yii::$app->image->load($file);
        $image->resize($width);
        $image->save($uploadPath . 'thumbnail/' . $fileName);
        return;
    }

    public function actionDeletefileAjax() {
        $model = Uploads::findOne(Yii::$app->request->post('key'));
        if ($model !== NULL) {
            $filename = PaperlessView::getUploadPath() . $model->ref . '/' . $model->real_filename;
            $thumbnail = PaperlessView::getUploadPath() . $model->ref . '/thumbnail/' . $model->real_filename;
            if ($model->delete()) {
                @unlink($filename);
                @unlink($thumbnail);
                echo json_encode(['success'
                    => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }

}
