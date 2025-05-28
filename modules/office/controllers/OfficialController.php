<?php

namespace app\modules\office\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\Html;
//use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
//use yii\helpers\Json;
use yii\web\UploadedFile;
use app\modules\office\models\PaperlessOfficial;
//use app\modules\office\models\PaperlessOperation;
use app\modules\office\models\PaperlessProcessList;
use app\modules\hr\models\ExecutiveHasCdepartment;
use app\modules\hr\models\EmployeePositionHead;
use app\modules\hr\models\Employee;
use app\modules\office\models\Uploads;
//use app\components\mPDFMod;
use app\components\Ccomponent;
use yii\helpers\BaseFileHelper;
use app\components\TcPDFMod;
use xstreamka\mobiledetect\Device;
use yii\helpers\ArrayHelper;
use mdm\autonumber\AutoNumber;
use app\components\Cmqtt;
use app\models\ExtProfile;
use app\modules\office\models\PaperlessStatus;
use app\modules\line\components\lineBot;
use app\modules\office\models\PaperlessView;
use yii\db\Expression;

class OfficialController extends Controller {

    public function actionExecutive() {

        if (strlen(Yii::$app->user->identity->profile->cid) <> 13 || Yii::$app->user->identity->profile->name == '' || Yii::$app->user->identity->profile->lname == '') {
            Yii::$app->response->redirect(['/user/settings/profile']);
            Yii::$app->end();
        }
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
        $sqlWhere = "'" . implode("','", array_keys($role)) . "'";

        $query = PaperlessOfficial::find();
        $query->join('LEFT JOIN', 'uploads', 'paperless_id = ref');
        $query->addSelect(['brn_year' => "COUNT(IF(paperless_official_type = 'BRN' && YEAR(create_at) = YEAR(CURRENT_DATE()),1,NULL)) "]);
        $query->addSelect(['brn_today' => "COUNT(IF(paperless_official_type = 'BRN' && DATE(create_at) = DATE(CURRENT_DATE()),1,NULL)) "]);
        $query->addSelect(['bsn_year' => "COUNT(IF(paperless_official_type = 'BSN' && YEAR(create_at) = YEAR(CURRENT_DATE()),1,NULL)) "]);
        $query->addSelect(['bsn_today' => "COUNT(IF(paperless_official_type = 'BSN' && DATE(create_at) = DATE(CURRENT_DATE()),1,NULL)) "]);
        $query->addSelect(['brn_oper' => "COUNT(IF((paperless_official_type = 'BRN' && paperless_official.paperless_status_id = '') || paperless_status_auth IN ({$sqlWhere}),1,NULL)) "]);
        $query->addSelect(['bsn_oper' => "COUNT(IF(paperless_official_type = 'BSN' && upload_id IS NULL,1,NULL)) "]);
        $query->addSelect(['bon_oper' => "COUNT(IF(paperless_official_type = 'BON' && upload_id IS NULL,1,NULL)) "]);
        $query->andWhere(['YEAR(create_at)' => date('Y')]);

        $query->join('LEFT JOIN', 'paperless_process_list p', 'processlist_id = paperless_lastprocess_id');
        $query->join('LEFT JOIN', 'paperless_status s', 'paperless_official.paperless_status_id = s.paperless_status_id');

        if (strpos($sqlWhere, 'ExecutiveUser') !== false) {
            $query->andWhere(['OR',
                ['process_receiver' => @$emp->employee_id],
            ]);
        } else {
            $query->andWhere(['OR',
                ['process_receiver' => @$emp->employee_id],
                new Expression(" s.paperless_status_auth IN ({$sqlWhere})"),
            ]);
        }

        $query->asArray();
        $data = $query->one();
        //print_r($data);
        //exit;
        return $this->render('executive', [
                    //'dataProvider' => $dataProvider,
                    'data' => $data
        ]);
    }

    public function actionIndex() {

        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
        $sqlWhere = "'" . implode("','", array_keys($role)) . "'";

        $query = PaperlessOfficial::find();
        $query->join('LEFT JOIN', 'uploads', 'paperless_id = ref');
        $query->addSelect(['brn_year' => "COUNT(IF(paperless_official_type = 'BRN' && YEAR(create_at) = YEAR(CURRENT_DATE()),1,NULL)) "]);
        $query->addSelect(['brn_today' => "COUNT(IF(paperless_official_type = 'BRN' && DATE(create_at) = DATE(CURRENT_DATE()),1,NULL)) "]);
        $query->addSelect(['bsn_year' => "COUNT(IF(paperless_official_type = 'BSN' && YEAR(create_at) = YEAR(CURRENT_DATE()),1,NULL)) "]);
        $query->addSelect(['bsn_today' => "COUNT(IF(paperless_official_type = 'BSN' && DATE(create_at) = DATE(CURRENT_DATE()),1,NULL)) "]);
        if (strpos($sqlWhere, 'ExecutiveUser') !== false) {
            $query->addSelect(['brn_oper' => "COUNT(IF((paperless_official_type = 'BRN') && process_receiver = '{$emp->employee_id}',1,NULL)) "]);
        } else {
            $query->addSelect(['brn_oper' => "COUNT(IF((paperless_official_type = 'BRN' && paperless_official.paperless_status_id = '') || paperless_status_auth IN ({$sqlWhere}),1,NULL)) "]);
        }
        $query->addSelect(['bsn_oper' => "COUNT(IF(paperless_official_type = 'BSN' && upload_id IS NULL,1,NULL)) "]);
        $query->addSelect(['bon_oper' => "COUNT(IF(paperless_official_type = 'BON' && upload_id IS NULL,1,NULL)) "]);
        //$query->andWhere(['YEAR(create_at)' => date('Y')]);

        $query->join('LEFT JOIN', 'paperless_process_list p', 'processlist_id = paperless_lastprocess_id');
        $query->join('LEFT JOIN', 'paperless_status s', 'paperless_official.paperless_status_id = s.paperless_status_id');

        $query->asArray();
        $data = $query->one();
        //print_r($data);
        //exit;
        return $this->render('index', [
                    //'dataProvider' => $dataProvider,
                    'data' => $data
        ]);
    }

    public function actionCreate($mode = 'BRN') {//$mode = 'R,S'
        $model = new PaperlessOfficial();
        $model->scenario = $mode;
        $model->paperless_official_type = $mode;
        switch ($mode) {
            case 'BRN':
                $model->paperless_level_id = 1; //ค่าเริ่มต้น ความเร่งด่วน
                $form = '_form_in';
                break;
            case 'BSN':
                $form = '_form_out';
                //$model->paperless_official_date = date('Y-m-d'); จะใส่ก็ต่อเมื่ออกเลขแล้ว
                break;
            case 'BON'://ออกเลขคำสั่ง
                $form = '_form_out_order';
                //$model->paperless_official_date = date('Y-m-d'); จะใส่ก็ต่อเมื่ออกเลขแล้ว
                break;
            case 'BCN'://ออกเลขหนังสือเวียน
                $form = '_form_out';
                //$model->paperless_official_date = date('Y-m-d'); จะใส่ก็ต่อเมื่ออกเลขแล้ว
                break;
            case 'BAN'://ออกเลขประกาศ
                $form = '_form_out_announce';
                //$model->paperless_official_date = date('Y-m-d'); จะใส่ก็ต่อเมื่ออกเลขแล้ว
                break;
            default:
                break;
        }

        list($initialPreview, $initialPreviewConfig) = $this->getInitialPreview($model->paperless_id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $this->Uploads(false, $model->paperless_id);
                \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $message = "คุณรับทราบหนังสือแล้ว";
                $status = 'success';
                //@Cmqtt::public('hms/service/paper/update/' . $mode, 1);
                return ['status' => $status, 'message' => $message];
            }
        } else {
            //$model->loadDefaultValues();
        }
        return $this->renderAjax($form, [
                    'model' => $model,
                    'initialPreview' => $initialPreview,
                    'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

    public function actionUpdate($id) {
        //----------------------------------------------------------------------------
        $model = PaperlessOfficial::findOne($id);
        //set Scenario
        $model->scenario = $model->paperless_official_type;
        $form = '_form_out';
        if ($model->paperless_official_type == 'BRN') {
            $form = '_form_in';
        }

        if ($model->paperless_official_type == 'BON') {
            $form = '_form_out_order';
        }

        if ($model->paperless_official_type == 'BAN') {
            $form = '_form_out_announce';
        }

        list($initialPreview, $initialPreviewConfig) = $this->getInitialPreview($model->paperless_id);
        //----------------------------------------------------------------------------
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $this->Uploads(false, $model->paperless_id);
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-success']
            ]);
            @Cmqtt::public('hms/service/paper/update/' . $model->paperless_official_type, 1);
        }
        return $this->renderAjax($form, [
                    'model' => $model,
                    'initialPreview' => $initialPreview,
                    'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

    public function actionUploadAjax() {
        $this->Uploads(true);
    }

    private function CreateDir($folderName) {
        if ($folderName != NULL) {
            $basePath = PaperlessOfficial::getUploadPath();
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
                    $post = Yii::$app->request->post('Paperless');
                    //print_r($_POST);
                    //exit;
                }
                $this->CreateDir($ref);
                foreach ($images as $file) {
                    $fileName = $file->baseName . '.' . $file->extension;
                    $realFileName = md5($file->baseName . time()) . '.' . $file->extension;
                    $savePath = PaperlessOfficial::UPLOAD_FOLDER . '/' . $ref . '/' . $realFileName;
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
                            echo json_encode(['success' => 'true']);
                        }
                    } else {
                        if ($isAjax === true) {
                            echo json_encode(['success' => 'false', 'eror' => $file->error]);
                        }
                    }
                }
            }
        }
    }

    private function getInitialPreview($ref) {
        $datas = Uploads::find()->where(['ref' => $ref])->orderBy(['create_date' => SORT_ASC])->all();
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
        $filePath = PaperlessOfficial::getUploadUrl() . $model->ref . '/' . $model->real_filename;
        $isImage = $this->isImage($filePath);
        if ($isImage) {
            $file = Html::img($filePath, ['class' => 'file-preview-image', 'alt' => $model->file_name, 'title' => $model->file_name]);
        } else {
            $file = $filePath;
        }

        return $file;
    }

    private function createThumbnail($folderName, $fileName, $width = 500) {
        $uploadPath = PaperlessOfficial::getUploadPath() . '/' . $folderName . '/';
        $file = $uploadPath . $fileName;
        $image = \Yii::$app->image->load($file);
        $image->resize($width);
        $image->save($uploadPath . 'thumbnail/' . $fileName);
        return;
    }

    public function actionDeletefileAjax() {
        $model = Uploads::findOne(Yii::$app->request->post('key'));
        if ($model !== NULL) {
            $filename = PaperlessOfficial::getUploadPath() . $model->ref . '/' . $model->real_filename;
            $thumbnail = PaperlessOfficial::getUploadPath() . $model->ref . '/thumbnail/' . $model->real_filename;
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

    public function actionView3($id = null) {
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $model = PaperlessOfficial::findOne($id);
        return $this->renderPartial('example', ['model' => @$model, 'data' => @$data]);
    }

    public function actionView($id = null) {

        if (Device::$isPhone || Device::$isTablet) {
            return $this->renderAjax('view', ['id' => $id]);
        } else {
            return $this->redirect(['tcpdf', 'id' => $id]);
        }
    }

    public function actionTcpdf($id = null) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $model = PaperlessOfficial::findOne($id);
        $modelProcess = PaperlessProcessList::find()
                ->where(['paperless_id' => $model->paperless_id])
                ->andWhere(['IN', 'paperless_status_id', ['F15', 'F16', 'F18', 'F19']])
                ->orderBy(['processlist_id' => SORT_DESC])
                ->limit(4)
                //->asArray()
                ->all();
// create new PDF document

        if ($model->paperless_official_type == 'BRN') {
            $pdf = TcPDFMod::TcPDFModInit();
        } else {
            $pdf = new TcPDFMod('P', 'mm', 'A4', true, 'UTF-8', false, true);
            $pdf->setPrintFooter(false);
        }
        //$pdf = new TcPDFMod('P', 'mm', 'A4', true, 'UTF-8', false, true);
// set document information
        //$pdf->setCreator('HMP:Hospital Management Platform');
        //$pdf->setAuthor('Sila Klanklaeo');
        $pdf->setTitle($model->paperless_topic);
        //$pdf->setSubject('Paperless Platform');
        //$pdf->setKeywords('TCPDF, PDF, example, test, guide');
        // set certificate file
        $dn = [
            "countryName" => "TH",
            "stateOrProvinceName" => "Suphanburi",
            "localityName" => "Songpheenong",
            "organizationName" => "Somdej17 Hospital",
            "organizationalUnitName" => "PHP Documentation Team",
            "commonName" => "HMS-Somdej17 Hospital",
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
        $csr = openssl_csr_new($dn, $privkey);
// Generate a self-signed cert, valid for 365 days
        $x509 = openssl_csr_sign($csr, null, $privkey, $days = 365);
// Save your private key, CSR and self-signed cert for later use
        openssl_csr_export($csr, $csrout);
        openssl_x509_export($x509, $certout);
        openssl_pkey_export($privkey, $pkeyout, $privkeypass);
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

        if ($model->paperless_official_type == 'BRN') {

            $pdf->WriteHTML($this->renderPartial('example', ['model' => @$model, 'data' => @$data]));
            @$html = $this->renderPartial('/paperless/_signature', ['model' => @$model, 'data' => @$data, 'modelProcess' => $modelProcess]);
            @$pdf->writeHTMLCell('', '', 0, 220, $html);

            // QRCODE,L : QR-CODE Low error correction
            if (!empty($model->paperless_id))
                $pdf->write2DBarcode($model->paperless_id, 'QRCODE,H', 170, 5, 50, 50, $style, 'N');
        }
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

// set document signature
        if ($model->paperless_official_type == 'BRN') {
            $pdf->setSignature($certout, $pkeyout, $privkeypass, '', 2, $info, 'A');
        }
        $pdf->Output($model->paperless_topic . '.pdf', 'I');

        exit;
        //------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    }

    /**
      VIEW PDF
     */
    /*
      public function actionView2($id = null) {
      $model = Paperless::findOne($id);
      $mpdf = mPDFMod::mPDFModInit();
      //$mpdf->SetCompression(true);
      $mpdf->SetProtection(['print']);
      $mpdf->SetTitle("บันทึกข้อความ");
      $mpdf->SetAuthor(Yii::$app->params['dep_name']);
      $mpdf->SetWatermarkText('ตัวอย่างบันทึกข้อความ');

      $mpdf->showWatermarkText = false;
      $mpdf->watermark_font = 'DejaVuSansCondensed';
      $mpdf->watermarkTextAlpha = 0.1;
      $mpdf->SetDisplayMode('fullpage');
      $mpdf->SetFooter('<div style="text-align:right;font-size:8px;">' . Yii::$app->params['dep_name'] . ' วันที่พิมพ์ ' . Ccomponent::getThaiDate(date('Y-m-d'), 'L') . ' หน้า {PAGENO} / {nb}</div>');

      $mpdf->WriteHTML($this->renderPartial('example', ['model' => @$model, 'data' => @$data]));
      $mpdf->WriteHTML($this->renderPartial('_signature', ['model' => @$model, 'data' => @$data]));
      //$mpdf->WriteHTML($this->renderPartial('_signature', ['model' => @$model, 'data' => @$data]));
      //$url = 'https://upload.wikimedia.org/wikipedia/commons/c/c5/Chris_Evans%27_Signature.png';
      //$mpdf->Image($url, 170, 220, 24, 0, 'png', '', true);
      //$mpdf->Image($model->getUploadPath() . '../laysen/51.jpg', 170, 240, 24, 0, 'jpg');
      //$mpdf->Image($model->getUploadPath() . '../laysen/12.jpg', 170, 200, 24, 0, 'jpg');

      $pdfs = $model->getUrlPdf($id, 'u');
      if (is_array($pdfs)) {
      foreach ($pdfs as $file) {
      $pagecount = $mpdf->SetSourceFile($file);
      for ($i = 1; $i <= ($pagecount); $i++) {
      $mpdf->AddPage();
      $import_page = $mpdf->ImportPage($i);
      $mpdf->UseTemplate($import_page);
      }
      }
      }

      $mpdf->Output();
      }
     */
    /*
      public function actionView($id) {
      $completePath = Url::to(['view', 'id' => $id]);
      return Yii::$app->response->sendFile($completePath, 'name', ['inline' => true, 'mimeType' => 'application/pdf']);
      //return $this->renderAjax('pdf', ['id' => $id]);
      }
     */
    /*
      public function actionView($id) {
      $model = Paperless::findOne($id);
      $completePath = Url::to(['view', 'id' => $model->paperless_id]);
      return Yii::$app->response->sendFile($completePath, 'name', ['inline' => true, 'mimeType' => 'application/pdf']);
      }
     */


    public function actionEmplist($q = null, $id = null, $mode = '') {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $userDep = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        $model = Employee::find()->where(['employee_status' => 1]);
        if (!is_null($q))
            $model->andWhere(['like', 'employee_fullname', $q])
                    ->orWhere(['like', 'employee_cid', $q]);
        if ($mode == 'D') {//กรณีอยู่หน่วยงานเดียวกัน
            $excutiveMan = [];
            $depModel = ExecutiveHasCdepartment::find()->where(['employee_dep_id' => $userDep])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            $headModel = EmployeePositionHead::find()->where(['OR', ['employee_dep_id' => $userDep], ['employee_executive_id' => ArrayHelper::getColumn($depModel, 'employee_executive_id')]])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            $model->where(['employee_dep_id' => $userDep]);
            $model->orWhere(['IN', 'employee_id', ArrayHelper::getColumn($headModel, 'employee_id')]);
        }
        if ($mode == 'A') {//กรณีทุกหน่วยงาน
            $excutiveMan = [];
            $depModel = ExecutiveHasCdepartment::find()->where([])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            $headModel = EmployeePositionHead::find()->where(['OR', ['employee_dep_id' => $userDep], ['employee_executive_id' => ArrayHelper::getColumn($depModel, 'employee_executive_id')]])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            //$model->where(['employee_dep_id' => $userDep]);
            //$model->orWhere(['IN', 'employee_id', ArrayHelper::getColumn($headModel, 'employee_id')]);
        }
        $model->orderBy(['employee_dep_id' => SORT_ASC]);
        $model->limit(100);
        $modelArray = $model->All();
        $data = [];
        foreach ($modelArray as $value) {
            $data[] = ['id' => $value->employee_id, 'text' => $value->employee_fullname, 'dep' => @$value->dep->employee_dep_label, 'position' => @$value->position->employee_position_name, 'excutive' => $value->getHead()];
        }

        //asort($data);
//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';
//        exit;

        $out['results'] = $data;
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Employee::find($id)->employee_fullname];
        }
        return $out;
    }

    public function actionDeplist($q = null, $id = null) {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        //if (!is_null($q)) {
        $query = new \yii\db\Query;
        $query->select(['employee_dep_id as id', "CONCAT(employee_dep_label) AS text"])
                ->from('employee_dep');

        if (!is_null($q))
            $query->where(['like', 'employee_dep_label', $q])->limit(200);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);

        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => \app\modules\hr\models\EmployeeDep::find($id)->employee_dep_label];
        }
        return $out;
    }

///บันทึกข้อมูลการรับทราบหนังสือ ที่มีผู้เสนอมาถึง
    public function actionAcknowledge($id) {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $param = \Yii ::$app->request->post();
        $model = PaperlessProcessList::find()->where(['processlist_id' => $id, 'process_receiver' => md5($cid)])->one();
        $model->process_acknowledge = 1;
        $model->process_acknowledge_datetime = new \yii\db\Expression('NOW()');
        $model->save();
        $status = 'success';
        //$message = "คุณรับทราบหนังสือเลขที่ {$model->docs->docs_number} แล้ว";
        $message = "คุณรับทราบหนังสือแล้ว";
        return ['status' => $status, 'message' => $message];
    }

    /*
      //จัดการการเสนอหนังสือไปยังระบบต่างๆ
      public function actionOperate($id, $ac = '') {

      if (strlen($id) == 17) {
      //รับค่าจาก Scanner
      $where = ['paperless_uuid' => $id];
      } else {
      $where = ['paperless_id' => $id];
      }
      //บันทึกการรับทราบเอกสาร
      if (!empty($ac)) {
      $acknowledge = PaperlessProcessList::find()->where(['paperless_id' => $id, 'processlist_id' => $ac])->one();
      $acknowledge->process_acknowledge = 1;
      $acknowledge->process_acknowledge_datetime = new \yii\db\Expression('NOW()');
      $acknowledge->save();
      }
      //ดึงสถานะที่เกี่ยวข้อง เพื่อเสนอหนังสือ
      $model = Paperless::findOne($where);
      $query = PaperlessProcessList::find()->select(['*', 'TIMEDIFF(process_acknowledge_datetime,process_create) AS paperless_tt'])->where(['paperless_id' => $model->paperless_id]);
      $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
      'pageSize' => 100
      ],
      'sort' => [
      'defaultOrder' => [
      'processlist_id' => SORT_DESC,
      ]
      ],
      ]);

      if ($dataProvider->getTotalCount() < 1) {//ตรวจสอบเอกสารให้ดำเนินการเสนอก่อนยืนพิจารณา
      \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
      return ['status' => 'false', 'message' => 'เอกสารนี้ยังไม่ดำเนินการเสนอเข้าสู่ระบบ กรุณาเสนอหนังสือฉบับนี้ก่อนค่ะ'];
      }
      //----------------------------------------------------------------------------

      $modelProcess = PaperlessProcessList::find()
      ->where(['paperless_id' => $model->paperless_id])
      ->orderBy(['processlist_id' => SORT_DESC])
      ->one();
      return $this->renderAjax('_form_operate', [
      'model' => $model,
      'modelProcess' => $modelProcess,
      'dataProvider' => $dataProvider,
      ]);
      }
     */
    /*
      public function actionListBrn() {//ทะเบียนรับหนังสือ
      @$params = \Yii::$app->request->queryParams;
      $query = PaperlessOfficial::find();
      $query->andWhere(['paperless_official_type' => 'BRN']);
      if (isset($params['search_date']) && !empty($params['search_date'])) {
      list($start2, $end2) = explode(' - ', $params['search_date']);
      if (@$start2) {
      $query->andWhere(['>=', "paperless_official_date", $start2]);
      }
      if (@$end2) {
      $query->andWhere(['<=', "paperless_official_date", $end2]);
      }
      }

      $query->filterWhere(['=', 'employee_dep_id', @$params['dep']])
      ->andFilterWhere(['OR',
      ['like', 'paperless_topic', @$params['search']],
      ['like', 'paperless_official_detail', @$params['search']],
      ['like', 'paperless_official_from', @$params['search']],
      ['like', 'paperless_official_number', @$params['search']],
      ['like', 'paperless_official_booknumber', @$params['search']],
      ]);

      $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
      'pageSize' => 20
      ],
      'sort' => [
      'defaultOrder' => [
      'create_at' => SORT_DESC,
      ]
      ],
      ]);
      $data = [];
      return $this->renderAjax('_gridBRN', [
      'dataProvider' => $dataProvider,
      'var' => $data,
      ]);
      }
      public function actionListBsn() {//ทะเบียนส่งหนังสือ
      @$params = \Yii::$app->request->queryParams;
      $query = PaperlessOfficial::find();
      $query->andWhere(['paperless_official_type' => 'BSN']);
      if (isset($params['search_date']) && !empty($params['search_date'])) {
      list($start2, $end2) = explode(' - ', $params['search_date']);
      if (@$start2) {
      $query->andWhere(['>=', "paperless_official_date", $start2]);
      }
      if (@$end2) {
      $query->andWhere(['<=', "paperless_official_date", $end2]);
      }
      }
      $query->filterWhere(['=', 'employee_dep_id', @$params['dep']])
      ->andFilterWhere(['OR',
      ['like', 'paperless_topic', @$params['search']],
      ['like', 'paperless_official_detail', @$params['search']],
      ['like', 'paperless_official_from', @$params['search']],
      ['like', 'paperless_official_number', @$params['search']],
      ['like', 'paperless_official_booknumber', @$params['search']],
      ]);

      $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
      'pageSize' => 20
      ],
      'sort' => [
      'defaultOrder' => [
      'create_at' => SORT_DESC,
      ]
      ],
      ]);
      $data = [];
      return $this->renderAjax('_gridBSN', [
      //'model' => $model,
      'dataProvider' => $dataProvider,
      'var' => $data,
      ]);
      }
     */

    public function actionListBooknumber($view, $oper = 0) {//ทะเบียน
        @$params = \Yii::$app->request->queryParams;

        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
        $sqlWhere = "'" . implode("','", array_keys($role)) . "'";

        $query = PaperlessOfficial::find();
        $query->andWhere(['paperless_official_type' => $view]);

        if (@$params['upfile'] == 1) {
            $query->join('LEFT JOIN', 'uploads u', 'ref = paperless_official.paperless_id');
            $query->andWhere("ref IS NULL");
        }

        $query->join('LEFT JOIN', 'paperless_process_list p', 'paperless_official.paperless_lastprocess_id = p.paperless_id');
        $query->join('LEFT JOIN', 'paperless_status s', 'paperless_official.paperless_status_id = s.paperless_status_id');

        if ($view == 'BRN' && $oper == 1) {

            $query->addSelect('paperless_official.*');
            $query->addSelect(['pcheck' => "IF((paperless_official.paperless_status_id IN ('F00','F03') && p.process_receiver = '{$emp->employee_id}') || paperless_status_auth IN ({$sqlWhere}) ,1,0)"]);
            $query->groupBy(['paperless_official.paperless_id']);
            $query->orderBy(['pcheck' => SORT_DESC]);

            $query->andWhere(['OR',
                new Expression(" s.paperless_status_auth IN ({$sqlWhere})"),
            ]);
        }

        if (strpos($sqlWhere, 'ExecutiveUser') !== false) {
            if ($view == 'BRN' && $oper == 1) {
                $query->addSelect('paperless_official.*');
                $query->addSelect(['pcheck' => "IF((paperless_official.paperless_status_id IN ('F00','F03') && p.process_receiver = '{$emp->employee_id}') || paperless_status_auth IN ({$sqlWhere}) ,1,0)"]);
                $query->groupBy(['paperless_official.paperless_id']);
                $query->orderBy(['pcheck' => SORT_DESC]);
                $query->andWhere(['OR',
                    ['process_receiver' => $emp->employee_id],
                ]);
            }
        } else {
//            if ($view == 'BRN') {
//                $query->andWhere(['OR',
//                    ['process_receiver' => $emp->employee_id],
//                    new Expression(" s.paperless_status_auth IN ({$sqlWhere})"),
//                ]);
//            }
        }

        if (isset($params['search_date']) && !empty($params['search_date'])) {
            list($start2, $end2) = explode(' - ', $params['search_date']);
            if (@$start2) {
                $query->andWhere(['>=', "paperless_official_date", $start2]);
            }
            if (@$end2) {
                $query->andWhere(['<=', "paperless_official_date", $end2]);
            }
        }
        $query->filterWhere(['=', 'employee_dep_id', @$params['dep']])
                ->andFilterWhere(['OR',
                    ['like', 'paperless_topic', @$params['search']],
                    ['like', 'paperless_official_detail', @$params['search']],
                    ['like', 'paperless_official_from', @$params['search']],
                    ['like', 'paperless_official_number', @$params['search']],
                    ['like', 'paperless_official_booknumber', @$params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC,
                ]
            ],
        ]);
        $data = [];
        return $this->renderAjax('_grid' . $view, [
                    //'model' => $model,
                    'dataProvider' => $dataProvider,
                    'var' => $data,
        ]);
    }

    public function actionListBookoperate($view) {//สำหรับผู้บริหารในการผ่านหนังสือราชการ
        @$params = \Yii::$app->request->queryParams;
        $query = PaperlessOfficial::find();
        $query->andWhere(['paperless_official_type' => $view]);

        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
        $sqlWhere = "'" . implode("','", array_keys($role)) . "'";

        $query->join('LEFT JOIN', 'paperless_process_list p', 'processlist_id = paperless_lastprocess_id');
        $query->join('LEFT JOIN', 'paperless_status s', 'paperless_official.paperless_status_id = s.paperless_status_id');

        //เป็นเอกสารที่เกี่ยวข้อง แต่ยังไม่ได้กำหนดช่วงเวลา และสถานะ
        /*
          $involved = PaperlessProcessList::find();
          $involved->where(['OR',
          // ['employee_id' => $emp->employee_id],
          ['process_receiver' => $emp->employee_id],
          //['process_acknowledge_staff' => $emp->employee_id],
          ]);
          $arrInvolved = $involved->groupBy(['paperless_id'])->all();
         *
         */
        //print_r($arrInvolved);

        if (strpos($sqlWhere, 'ExecutiveUser') !== false) {
            $query->andWhere(['OR',
                ['process_receiver' => $emp->employee_id],
            ]);
        } else {
            $query->andWhere(['OR',
                ['process_receiver' => $emp->employee_id],
                new Expression(" s.paperless_status_auth IN ({$sqlWhere})"),
            ]);
        }

        if (isset($params['search_date']) && !empty($params['search_date'])) {
            list($start2, $end2) = explode(' - ', $params['search_date']);
            if (@$start2) {
                $query->andWhere(['>=', "paperless_official_date", $start2]);
            }
            if (@$end2) {
                $query->andWhere(['<=', "paperless_official_date", $end2]);
            }
        }
        $query->filterWhere(['=', 'employee_dep_id', @$params['dep']])
                ->andFilterWhere(['OR',
                    ['like', 'paperless_topic', @$params['search']],
                    ['like', 'paperless_official_detail', @$params['search']],
                    ['like', 'paperless_official_from', @$params['search']],
                    ['like', 'paperless_official_number', @$params['search']],
                    ['like', 'paperless_official_booknumber', @$params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC,
                ]
            ],
        ]);
        $data = [];
        return $this->renderAjax('_grid' . $view, [
                    'modeSearch' => false,
                    'dataProvider' => $dataProvider,
                    'var' => $data,
        ]);
    }

    public function actionAutoSearch($term) {
        if (Yii::$app->request->isAjax) {
            //sleep(2); // for test
            $results = [['id' => $term, 'label' => $term]];
            $q = addslashes($term);
            $data = PaperlessOfficial::find()->where(['LIKE', 'paperless_official_from', $q])->groupBy('paperless_official_from')->cache(1500)->all();
            $loop = 0;
            foreach ($data as $model) {
                $loop++;
                if ($loop < 10) {
                    $results[] = [
                        'id' => $model['paperless_official_from'],
                        'label' => $model['paperless_official_from'],
                    ];
                }
            }

            echo \yii\helpers\Json::encode($results);
        }
    }

    public function actionGennumber() { //ออกเลขหลังสือส่งออกภายนอก
        $post = \Yii::$app->request->post();
        $pid = $post['pid'];
        $number = $post['number'];
        $title = $post['title'];
        $model = PaperlessOfficial::findOne($pid);
        if (in_array($model->paperless_official_type, ['BSN', 'BCN', 'BON', 'BAN']) && empty($model->paperless_official_number)) {
            $model->paperless_official_date = new \yii\db\Expression('NOW()');
            $model->paperless_official_number = AutoNumber::generate($model->paperless_official_type . (date('Y') + 543) . '-?????');
            if ($model->save()) {
                //เก็บ Log การดำเนินการออกเลข
                @Cmqtt::public('hms/service/paper/update/gennumber', 1);
            }
        }
    }

    public function actionDisplay($id) {
        $model = PaperlessOfficial::findOne($id);
        return $this->renderAjax('_view', [
                    'model' => $model,
                    'dataProvider' => @$dataProvider,
                    'var' => @$data,
        ]);
    }

//จัดการการเสนอหนังสือไปยังระบบต่างๆ
    public function actionOperate($id, $ac = '') {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        if (strlen($id) == 17) {
            //รับค่าจาก Scanner
            $where = ['paperless_uuid' => $id];
        } else {
            $where = ['paperless_id' => $id];
        }
        //บันทึกการรับทราบเอกสาร
        if (!empty($ac)) {
            $acknowledge = PaperlessProcessList::find()->where(['paperless_id' => $id, 'processlist_id' => $ac])->one();
            $acknowledge->process_acknowledge = 1;
            $acknowledge->process_acknowledge_datetime = new \yii\db\Expression('NOW()');
            if (empty($acknowledge->process_receiver))
                $acknowledge->process_acknowledge_staff = $emp->employee_id;
            //บันทึกผู้กดรับทราบเอกสาร
            //-----------------------------------------
            @Cmqtt::public('hms/service/paper/update/BNN', 1);
            $acknowledge->save();
        }
        //ดึงสถานะที่เกี่ยวข้อง เพื่อเสนอหนังสือ
        $model = PaperlessOfficial::findOne($where);
        $query = PaperlessProcessList::find()->select(['*', 'TIMEDIFF(process_acknowledge_datetime,process_create) AS paperless_tt'])->where(['paperless_id' => $model->paperless_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ],
            'sort' => [
                'defaultOrder' => [
                    'processlist_id' => SORT_DESC,
                ]
            ],
        ]);
        /*
          if ($dataProvider->getTotalCount() < 1) {//ตรวจสอบเอกสารให้ดำเนินการเสนอก่อนยืนพิจารณา
          \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
          return ['status' => 'false', 'message' => 'เอกสารนี้ยังไม่ดำเนินการเสนอเข้าสู่ระบบ กรุณาเสนอหนังสือฉบับนี้ก่อนค่ะ'];
          }
         *
         */
        //----------------------------------------------------------------------------
//->employee_id
        $modelProcess = PaperlessProcessList::find()
                ->where(['paperless_id' => $model->paperless_id])
                ->orderBy(['processlist_id' => SORT_DESC])
                ->one();
        $canVisible = 0; //กำหนดการแสดงผลการดำเนินการ
        if (@$modelProcess->process_receiver == $emp->employee_id) { //ให้ผู้ถูกเสนอหนังสือสามารถดำเนินการเอกสารได้
            $canVisible = 1;
        }
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        if (!empty($modelProcess->status->paperless_status_auth)) {//ให้ผู้รับผิดชอบหนังสือสามารถดำเนินการเอกสารได้
            $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
            if (in_array($modelProcess->status->paperless_status_auth, array_keys($role))) {
                $canVisible = 1;
            }
        }
        $lookupStatus = [];
        $lookupStatus['User'] = ['F15']; //ผู้ใช้งานทั่วไป /หัวหน้าฝ่าย
        $lookupStatus['OfficeAdmin'] = ['F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //สารบรรณกลาง
        $lookupStatus['ManagerAdmin'] = ['F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //หน.บริหาร
        $lookupStatus['SecretaryAdmin'] = ['F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //งานเลขา
        $lookupStatus['ExecutiveUser'] = ['F15', 'F16', 'F17', 'F18', 'F19']; //รองผู้อำนวยการ/ผู้อำนวยการ
        $lookupStatus['CEO'] = ['F15', 'F16', 'F17', 'F18', 'F19']; //ผู้อำนวยการ
        $lookupStatus['SuperAdmin'] = ['F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //Admin
        //กำหนดการแสดงผลตัวเลือกตามสิทธิ
        $arrayStatus = [];
        $roles = \Yii::$app->authManager->getRolesByUser($profile->user_id);

        foreach ($roles as $role) {
            if (!isset($lookupStatus[$role->name]))
                continue;
            $arrayStatus = array_unique(array_merge($arrayStatus, $lookupStatus[$role->name]));
        }

        sort($arrayStatus);
        $status = PaperlessStatus::find()->where(['IN', 'paperless_status_id', $arrayStatus])->all();

        return $this->renderAjax('_form_operate', [
                    'model' => $model,
                    'status' => $status,
                    'modelProcess' => $modelProcess,
                    'dataProvider' => $dataProvider,
                    'data' => $dataProvider->getModels(),
                    'canVisible' => $canVisible,
        ]);
    }

    public function genComment($model) {
        //$model = PaperlessProcessList::find()->where(['processlist_id' => $id])->one();
        $message = $model->process_comment;
        $deps = \app\modules\hr\models\EmployeeDep::find()->where(['IN', 'employee_dep_id', explode(',', $model->process_deps)])->all();
        $emps = Employee::find()->where(['IN', 'employee_id', explode(',', $model->process_staffs)])->all();
        if ($deps)
            $message .= '<br>มอบให้(หน่วยงาน) ' . @implode(",", ArrayHelper::getColumn($deps, 'employee_dep_label')) . ' ดำเนินการ ';
        if ($emps)
            $message .= '<br>มอบให้(เจ้าหน้าที่) ' . @implode(",", ArrayHelper::getColumn($emps, 'employee_fullname')) . ' ดำเนินการ ';
        return $message;
    }

    public function actionCreateprocess() {

        $message = '';
        $status = 'error';
        $param = \Yii ::$app->request->post();
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($param['id'])) {
            $model = new PaperlessProcessList();
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $model->processlist_id = '' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
            $model->paperless_id = $param['id']; //หนังสือ
            $model->process_comment = $param['comment']; //หมายเหตุ
            $model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
            $model->process_create = new \yii\db\Expression('NOW()');
            $model->paperless_status_id = 'F03'; //รอพิจารณา

            if (isset($param['frmStatus']) && $param['frmStatus'] == 'F00') { //เพื่อแก้ไขหนังสือ
                $model->paperless_status_id = 'F00'; //สถานะแก้ไขเอกสาร F00
                $model->process_receiver = $param['receiver_edit']; //ส่งกลับผู้แก้ไขบันทึกข้อความ;
                //กรณีที่มีการส่งคืนแก้ไข -ระบบจะให้กรอกเหตุผล และส่งคืนผู้เสนอเอกสาร
                if (empty($param['comment'])) {
                    $message = 'ไม่พบการระบุเหตุผลการส่งคืน กรุณาระบุเหตุผลด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }

                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                //$auth = Ccomponent::getTokenUser('SuperAdmin'); //ตามสิทธิ
//                $auth = Employee::find()->where(['IN', 'employee_id', [$model->employee_id, $model->paper->employee_owner_id]])->all(); //เป็นรายบุคคล
//                foreach ($auth as $emp) {
//                    $linebot = new lineBot();
//                    $message = " มีเอกสารถูกส่งคืนให้แก้ไขค่ะ เรื่อง " . $model->paper->paperless_topic;
//                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
//                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F15') { //ส่งสารบรรณกลาง
                $model->paperless_status_id = 'F15'; //รอพิจารณา

                if (isset($param['emps']) || isset($param['deps'])) {
                    if (@!is_array($param['emps']) && @!is_array($param['deps'])) {
                        $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
                        return ['status' => 'error', 'message' => $message];
                    }
                } else {
                    if (empty($param['comment'])) {
                        $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
                        return ['status' => 'error', 'message' => $message];
                    }
                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
//                $auth = Ccomponent::getTokenUser('OfficeAdmin'); //ตามสิทธิ
//                foreach ($auth as $emp) {
//                    $linebot = new lineBot();
//                    $message = " มีเอกสารถูกส่งเข้ามาค่ะ เรื่อง " . $model->paper->paperless_topic;
//                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
//                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F16') { //ส่งหน.บริหาร
                $model->paperless_status_id = 'F16'; //รอพิจารณา
                if (empty($param['comment'])) {
                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
//                $auth = Ccomponent::getTokenUser('ManagerAdmin'); //ตามสิทธิ
//                foreach ($auth as $emp) {
//                    $linebot = new lineBot();
//                    $message = " มีเอกสารถูกส่งให้ดำเนินการต่อค่ะ เรื่อง " . $model->paper->paperless_topic;
//                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
//                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F17') { //ส่งงานเลขา
                $model->paperless_status_id = 'F17'; //รอพิจารณา
//                if (empty($param['comment'])) {
//                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
//                    return ['status' => 'error', 'message' => $message];
//                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Ccomponent::getTokenUser('SecretaryAdmin'); //ตามสิทธิ
//                foreach ($auth as $emp) {
//                    $linebot = new lineBot();
//                    $message = " มีเอกสารถูกส่งให้ดำเนินการต่อค่ะ เรื่อง " . $model->paper->paperless_topic;
//                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
//                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F18') { //ส่งรอง ผอ.
                $model->paperless_status_id = 'F18'; //รอพิจารณา
                $model->process_receiver = $param['receiver'];
                if (empty($param['receiver'])) {
                    $message = 'ไม่พบการระบุรายชื่อรองผู้อำนวยการ กรุณาระบุชื่อผู้รับด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                ////                if (empty($param['comment'])) {
//                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
//                    return ['status' => 'error', 'message' => $message];
//                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                //$auth = Ccomponent::getTokenUser('ManagerAdmin'); //ตามสิทธิ
//                foreach ($auth as $emp) {
//                    $linebot = new lineBot();
//                    $message = " มีเอกสารเข้ามาให้ดำเนินการพิจารณาค่ะ เรื่อง " . $model->paper->paperless_topic;
//                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
//                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F19') { //ส่งผอ.
                if (empty($param['receiver'])) {
                    $message = 'ไม่พบการระบุรายชื่อผู้อำนวยการ กรุณาระบุชื่อผู้รับด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                $model->process_receiver = $param['receiver'];
                $model->paperless_status_id = 'F19'; //รอพิจารณา
//                if (empty($param['comment'])) {
//                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
//                    return ['status' => 'error', 'message' => $message];
//                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
//                $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
//                //$auth = Ccomponent::getTokenUser('ManagerAdmin'); //ตามสิทธิ
//                foreach ($auth as $emp) {
//                    $linebot = new lineBot();
//                    $message = " มีเอกสารเข้ามาให้ดำเนินพิจารณาค่ะ เรื่อง " . $model->paper->paperless_topic;
//                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
//                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'FF') { //สำเร็จ
                $model->paperless_status_id = 'FF'; //สำเร็จ

                if (empty($param['emps']) && empty($param['deps'])) {
                    $message = 'ไม่พบการมอบหมายงาน กรุณาระบุด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                //$auth = Ccomponent::getTokenUser('SuperAdmin'); //ตามสิทธิ
//                $auth = Employee::find()->where(['IN', 'employee_id', [$model->paper->employee_owner_id]])->all(); //เป็นรายบุคคล
//                foreach ($auth as $emp) {
//                    $linebot = new lineBot();
//                    $message = "เอกสารพิจารณาและดำเนินการเสร็จแล้วค่ะ เรื่อง " . $model->paper->paperless_topic;
//                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
//                }
                //-------------------------------------------------------------------------------------------------------
            } else {

                if (empty($param['receiver'])) {
                    $message = 'ไม่พบการระบุรายชื่อผู้รับ กรุณาระบุชื่อผู้รับด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                $model->process_receiver = $param['receiver']; //ผู้รับ
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                //$auth = Ccomponent::getTokenUser('SuperAdmin'); //ตามสิทธิ
//                $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
//                foreach ($auth as $emp) {
//                    $linebot = new lineBot();
//                    $message = " มีเอกสารให้พิจารณาและดำเนินการค่ะ เรื่อง " . $model->paper->paperless_topic;
//                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
//                }
                $message = '';
                //-------------------------------------------------------------------------------------------------------
            }
            if (isset($param['emps']) && is_array($param['emps']))
                $model->process_staffs = @implode(",", $param['emps']);
            if (isset($param['deps']) && is_array($param['deps']))
                $model->process_deps = @implode(",", $param['deps']);
            //genComment
            $model->process_comment = $this->genComment($model);

            if ($model->save()) {
                $status = 'success';
                $message = 'ดำเนินการสำเร็จ';
                $papaer = PaperlessOfficial::findOne(['paperless_id' => $param['id']]);
                $papaer->paperless_status_id = $model->paperless_status_id;
                $papaer->paperless_lastprocess_id = $model->processlist_id;
                $papaer->update_at = new \yii\db\Expression('NOW()');

                if (!$papaer->save()) {
                    return ['status' => 'error', 'message' => print_r($papaer->errors, 1)];
                }

                if (isset($param['frmStatus']) && $param['frmStatus'] == 'FF') {
                    if (isset($param['deps']))
                        $depsArray = @implode(",", $param['deps']);
                    if (isset($param['emps']))
                        $empsArray = @implode(",", $param['emps']);
                    //เวียนหนังสือต่อเลย
                    try {
                        $announce = PaperlessView::findOne(['paperless_paper_ref' => $param['id']]) ?: new PaperlessView();

                        if (!empty($depsArray))
                            $announce->paperless_view_deps = @$depsArray;
                        if (!empty($empsArray))
                            $announce->paperless_view_emps = @$empsArray;

                        $announce->create_at = new \yii\db\Expression('NOW()');
                        $announce->employee_id = $model->employee_id;
                        $announce->paperless_paper_ref = $param['id'];
                        $now = \DateTime::createFromFormat('U.u', microtime(true));
                        $announce->paperless_view_id = 'A' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
                        $announce->paperless_view_startdate = new \yii\db\Expression('NOW()');
                        if (!$announce->save()) {
                            return ['status' => 'error', 'message' => print_r($announce->errors, 1)];
                        }
                    } catch (\Exception $exc) {
                        return ['status' => 'error', 'message' => print_r($exc->getMessage(), 1)];
                    }
                }

                @Cmqtt::public('hms/service/paper/update/BRN', 'processlist');
            }
        } else {
            $message = 'ไม่พบการระบุ ID';
        }
        @Cmqtt::public('hms/service/paper/update', '1');
        return ['status' => $status, 'message' => $message];
    }

    //ดำเนินการเวียนเอกสาร
    public function actionProcessView() {
        $id = Yii::$app->request->post('id');
        if (\Yii::$app->user->can('SuperAdmin')) {
            $model = PaperlessProcessList::findOne($id);
            if ($model !== NULL) {
                $announce = PaperlessView::findOne(['paperless_paper_ref' => $model->paperless_id]) ?: new PaperlessView();
                $announce->paperless_view_deps = $model->process_deps;
                $announce->paperless_view_emps = $model->process_staffs;
                $announce->create_at = new \yii\db\Expression('NOW()');
                $announce->employee_id = $model->employee_id;
                $announce->paperless_paper_ref = $model->paperless_id;
                $now = \DateTime::createFromFormat('U.u', microtime(true));
                $announce->paperless_view_id = 'A' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
                $announce->paperless_view_startdate = new \yii\db\Expression('NOW()');
                if ($announce->save()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }

    //ลบสถานะการดำเนินการ
    public function actionProcessDelete() {
        $id = Yii::$app->request->post('id');
        if (\Yii::$app->user->can('SuperAdmin')) {
            $model = PaperlessProcessList::findOne($id);
            if ($model !== NULL) {
                if ($model->delete()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }

}
