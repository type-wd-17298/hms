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
use app\modules\office\models\Paperless;
//use app\modules\office\models\PaperlessOperation;
use app\modules\office\models\PaperlessProcessList;
use app\modules\hr\models\ExecutiveHasCdepartment;
use app\modules\hr\models\EmployeePositionHead;
use app\modules\office\models\PaperlessStatus;
use app\modules\hr\models\Employee;
use app\modules\office\models\Uploads;
//use app\components\mPDFMod;
use app\components\Ccomponent;
use yii\helpers\BaseFileHelper;
use app\components\TcPDFMod;
use xstreamka\mobiledetect\Device;
use yii\helpers\ArrayHelper;
use app\modules\line\components\lineBot;
use app\models\ExtProfile;
use app\components\Cmqtt;
use yii\db\Expression;
use app\modules\office\models\PaperlessView;
use kartik\form\ActiveForm;

class PaperlessController extends Controller {

    public function actionIndex($view = '') {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
        $sqlWhere = "'" . implode("','", array_keys($role)) . "'";

        @$params = \Yii::$app->request->queryParams;
        $query = Paperless::find();
        $query->join('LEFT JOIN', 'paperless_process_list p', 'processlist_id = paperless_lastprocess_id');
        $query->join('LEFT JOIN', 'paperless_process_list p2', 'p2.paperless_id = paperless.paperless_id ');
        $query->join('LEFT JOIN', 'paperless_status s', 'paperless.paperless_status_id = s.paperless_status_id');
        $query->addSelect('paperless.*');
        $query->addSelect(['pcheck' => "IF((paperless.paperless_status_id IN ('F00','F03') && p.process_receiver = '{$emp->employee_id}') || paperless_status_auth IN ({$sqlWhere}) ,1,0)"]);
        $query->groupBy(['paperless.paperless_id']);
        $query->orderBy(['pcheck' => SORT_DESC]);
        //เป็นเอกสารที่เกี่ยวข้อง แต่ยังไม่ได้กำหนดช่วงเวลา และสถานะ
        $involved = PaperlessProcessList::find();

        if (isset($params['mode']) && $params['mode'] == 'owner') {
            if (strpos($sqlWhere, 'ExecutiveUser') !== false) {
                $query->andWhere(['AND',
                    ['process_receiver' => $emp->employee_id],
                ]);
                $query->andWhere(['AND',
                    new Expression(" paperless_direct < 1 "),
                ]);
            } else {
                $query->andWhere(['OR',
                    ['p.process_receiver' => $emp->employee_id],
                    new Expression(" s.paperless_status_auth IN ({$sqlWhere})"),
                ]);
            }
        } else {

            if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('SecretaryAdmin')) {

            } else {

                if (strpos($sqlWhere, 'ExecutiveUser') !== false) {
                    //รายการในบันทึกข้อความให้แสดงเฉพาะที่ผู้บริหารเคยเซนต์
                    $query->andWhere(['AND',
                        ['p2.process_receiver' => $emp->employee_id],
                    ]);
                } else {
                    $involved->where(['OR',
                        ['employee_id' => $emp->employee_id],
                        ['process_receiver' => $emp->employee_id],
                        ['process_acknowledge_staff' => $emp->employee_id],
                    ]);
                    $arrInvolved = $involved->groupBy(['paperless_id'])->all();
                    $query->andWhere(['OR',
                        ['employee_owner_id' => $emp->employee_id],
                        ['paperless.employee_id' => $emp->employee_id],
                        new Expression("  s.paperless_status_auth IN ({$sqlWhere})"),
                        ['IN', 'paperless.paperless_id', ArrayHelper::getColumn($arrInvolved, 'paperless_id')],
                    ]);
                }
            }
        }

        if (isset($params['search_date']) && !empty($params['search_date'])) {
            list($start2, $end2) = explode(' - ', $params['search_date']);
            if (@$start2) {
                $query->andWhere(['>=', "paperless_date", $start2]);
            }
            if (@$end2) {
                $query->andWhere(['<=', "paperless_date", $end2]);
            }
        }

        if (!empty($view)) {
            if ($view == 'paper') {
                $query->andWhere(['AND',
                    new Expression(" paperless_direct < 1 "),
                ]);
            }

            if ($view == 'wait') {
                $query->andWhere(['AND',
                    new Expression(" p.process_acknowledge_staff IS NULL"),
                    new Expression(" paperless_direct > 0 "),
                    new Expression(" paperless.paperless_status_id NOT IN ('FF') "),
                ]);
            }
            if ($view == 'process') {
                $query->andWhere(['AND',
                    new Expression(" p.process_acknowledge_staff  IS NOT NULL "),
                ]);
            }
        }


        if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('ManagerAdmin')) {//สำหรับ Admin ในการติดตามเอกสาร
            $query->filterWhere(['=', 'employee_dep_id', @$params['dep']]);
            //$query->andWhere(['=', 'employee_dep_id', $emp->employee_dep_id]);
        } else {
            //$query->andWhere(['=', 'employee_dep_id', @$params['dep']]);
        }
        $query->andFilterWhere(['OR',
            ['like', 'paperless_topic', @$params['search']],
            ['like', 'paperless_detail', @$params['search']],
            ['like', 'paperless_number', @$params['search']],
        ]);

        $query->andFilterWhere(['AND',
            ['like', 'p.paperless_status_id', @$params['statusid']],
        ]);
		
		//Limit หนังสือ จะแสดงผล 365 วัน
        $query->andWhere(['AND',
            ['BETWEEN', 'paperless.paperless_date',
                date('Y-m-d', strtotime('-365 days')), // วันที่เริ่มต้น (365 วันที่แล้ว)
                date('Y-m-d') // วันที่สิ้นสุด (วันนี้)
            ],
        ]);
		

        $dataProviderDash = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    //'pcheck' => SORT_DESC,
                    'create_at' => SORT_DESC,
                    'update_at' => SORT_DESC,
                    'paperless_status_id' => SORT_ASC,
                ]
            ],
        ]);
        //$data = [];
        //$data['pStatus'] = PaperlessOperation::find()->asArray()->all();

        $Dash = $dataProviderDash->getModels();

        if (isset($params['mode']) && $params['mode'] == 'owner') {
            return $this->renderAjax('_grid', [
                        'dataProvider' => $dataProvider,
                        'data' => $Dash,
            ]);
        } else {

            if (!empty($view)) {
                return $this->renderAjax('_gridViewCenter', [
                            'dataProvider' => $dataProvider,
                            'data' => $Dash,
                ]);
            }

            return $this->render('index', [
                        'dataProvider' => $dataProvider,
                        'data' => $Dash,
            ]);
        }
    }

    public function actionCreate() {
        $model = new Paperless();
        /*         * ***ออกเลขเพื่อใช้อ้างอิง********************** */
        $now = \DateTime::createFromFormat('U.u', microtime(true));
        $model->paperless_uuid = 'D' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
        /*         * ************************* */
        $model->paperless_from = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        $model->paperless_level_id = 1; //ค่าเริ่มต้น ความเร่งด่วน
        $model->paperless_status_id = 'F01'; //กำหนดค่าเริ่มต้น
        $model->paperless_date = new \yii\db\Expression('NOW()');
        $model->paperless_to = 'ผู้อำนวยการ' . Yii::$app->params['dep_name'];

        list($initialPreview, $initialPreviewConfig) = $this->getInitialPreview($model->paperless_id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $this->Uploads(false, $model->paperless_id);
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                @Cmqtt::public('hms/service/paper/update/BNN', 1);
                return $this->redirect(['index', 'paperless_id' => $model->paperless_id]);
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

    public function actionUpdate($id) {

        $query = PaperlessProcessList::find()->where(['paperless_id' => $id]);
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

        //----------------------------------------------------------------------------
        $model = Paperless::findOne($id);
        list($initialPreview, $initialPreviewConfig) = $this->getInitialPreview($model->paperless_id);
        //print_r($initialPreviewConfig);
        /*         * ***ออกเลขเพื่อใช้อ้างอิง********************** */
        if (strlen($model->paperless_uuid) < 17) {
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $model->paperless_uuid = 'D' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
        }
        /*         * ************************* */
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $this->Uploads(false, $model->paperless_id);
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-success']
            ]);
            @Cmqtt::public('hms/service/paper/update/BNN', 1);
            //return $this->redirect(['update', 'id' => $model->paperless_id]);
            $model->refresh();
            //Yii::$app->response->format = 'json';
            //return ['message' => Yii::t('app', 'Success Update!'), 'id' => $model->paperless_id];
        }

        return $this->renderAjax('_form', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'initialPreview' => $initialPreview,
                    'initialPreviewConfig' => $initialPreviewConfig
        ]);
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
                    $post = Yii::$app->request->post('Paperless');
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
        $filePath = Paperless::getUploadUrl() . $model->ref . '/' . $model->real_filename;
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
        $uploadPath = Paperless::getUploadPath() . '/' . $folderName . '/';
        $file = $uploadPath . $fileName;
        $image = \Yii::$app->image->load($file);
        $image->resize($width);
        $image->save($uploadPath . 'thumbnail/' . $fileName);
        return;
    }

    public function actionDeletefileAjax() {
        $model = Uploads::findOne(Yii::$app->request->post('key'));
        if ($model !== NULL) {
            $filename = Paperless::getUploadPath() . $model->ref . '/' . $model->real_filename;
            $thumbnail = Paperless::getUploadPath() . $model->ref . '/thumbnail/' . $model->real_filename;
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
        $model = Paperless::findOne($id);
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
        $model = Paperless::findOne($id);
        $modelProcess = PaperlessProcessList::find()
                ->where(['paperless_id' => $model->paperless_id])
                ->andWhere(['IN', 'paperless_status_id', ['F15', 'F16', 'F18', 'F19']])
                ->orderBy(['processlist_id' => SORT_DESC])
                ->limit(4)
                //->asArray()
                ->all();
// create new PDF document
        $pdf = TcPDFMod::TcPDFModInit();

// set document information
        $pdf->setCreator('HMP:Hospital Management Platform');
        $pdf->setAuthor('Sila Klanklaeo');
        $pdf->setTitle($model->paperless_topic);
        $pdf->setSubject('Paperless Platform');
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

// set text shadow effect
//$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
// Print text using writeHTMLCell()
        @$pdf->writeHTML($this->renderPartial('example', ['model' => @$model, 'data' => @$data]), true, false, true, false, '');

        //@$pdf->writeHTML($this->renderPartial('example', ['model' => @$model, 'data' => @$data]), 1, 0, 1, true, 'J', true);

        if (empty($model->paperless_direct) || $model->paperless_direct < 1)
            @$html = $this->renderPartial('_signature', ['model' => @$model, 'data' => @$data, 'modelProcess' => $modelProcess]);
        @$pdf->writeHTMLCell('', '', 0, 220, $html);
        //$pdf->writeHTMLCell($html, true, 0, true, 0);
        //$url = 'https://upload.wikimedia.org/wikipedia/commons/c/c5/Chris_Evans%27_Signature.png';
        //$pdf->Image($url, 170, 240, 15, 15, 'PNG');
        // define active area for signature appearance
        //$pdf->setSignatureAppearance(170, 240, 15, 15);
        // *** set an empty signature appearance ***
        //$pdf->addEmptySignatureAppearance(170, 240, 15, 15);
        // set style for barcode
        // new style
        $style = array(
            'border' => false,
            'padding' => 0,
            //'fgcolor' => array(128, 0, 0),
            'bgcolor' => false
        );

// QRCODE,L : QR-CODE Low error correction
        if (!empty($model->paperless_uuid))
            $pdf->write2DBarcode($model->paperless_uuid, 'QRCODE,H', 170, 5, 50, 50, $style, 'N');
        if (!empty($model)) {
            if (in_array($model->paperless_level_id, [3, 4])) {
                $html = '<span color="red" style="font-size: 32pt;"><b>' . @$model->level->paperless_level . '</b></span>';
                $pdf->SetFillColor(255, 255, 0);
                $pdf->writeHTMLCell(0, 0, 32, 16, $html);
            }

            $pdfs = @$model->getUrlPdf($id, 'u');
            if (is_array($pdfs)) {
                foreach ($pdfs as $file) {
                    $pagecount = $pdf->SetSourceFile($file);
                    for ($i = 1; $i <= ($pagecount); $i++) {
                        //$pdf->AddPage();
                        //$import_page = $pdf->ImportPage($i);
                        //$pdf->UseTemplate($import_page);
                        //-----------------------------------------------------------------------------
                        $import_page = $pdf->ImportPage($i);
                        $wh = $pdf->getTemplateSize($import_page);
                        @$pdf->AddPage($wh['orientation']);
                        $pdf->UseTemplate($import_page);
                        //-------------------------------------------------------------------------------
                    }
                }
            }
        }

// set document signature
        $pdf->setSignature($certout, $pkeyout, $privkeypass, '', 2, $info, 'A');
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

    public function actionEmplist($q = null, $id = null, $mode = '', $dep = '', $ac = '') {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $userInfo = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $userDep = (!empty($dep) ? $dep : $userInfo->employee_dep_id);
        $depLink = (!empty($dep) ? Ccomponent::DepParent($userDep) : $userInfo->dep->employee_dep_parent);
        $model = Employee::find()->where(['employee_status' => 1]);
        //if (!is_null($q))
        $model->andFilterWhere(['like', 'employee_fullname', $q]);

        if ($ac == '1' && strlen($q) > 3) {
            $mode = 'A';
        }

        if ($mode == 'D') {//กรณีอยู่หน่วยงานเดียวกัน
            $excutiveMan = [];
            $depModel = ExecutiveHasCdepartment::find()->where(['employee_dep_id' => $userDep])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            $headModel = EmployeePositionHead::find()->where(['OR', ['employee_dep_id' => $userDep], ['employee_executive_id' => ArrayHelper::getColumn($depModel, 'employee_executive_id')]])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            $depLink = Employee::find()->where(['employee_dep_id' => $depLink])->all(); //เพิ่มรายชื่อหน่วยงานที่เกี่ยวข้อง

            $model->andWhere(['OR',
                ['employee.employee_dep_id' => $userDep],
                ['IN', 'employee_id', ArrayHelper::getColumn($headModel, 'employee_id')],
                ['IN', 'employee_id', ArrayHelper::getColumn($depLink, 'employee_id')],
            ]);
        }



        if ($mode == 'A') {//กรณีทุกหน่วยงาน
            $excutiveMan = [];
            $depModel = ExecutiveHasCdepartment::find()->where([])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            $headModel = EmployeePositionHead::find()->where(['OR', ['employee_dep_id' => $userDep], ['employee_executive_id' => ArrayHelper::getColumn($depModel, 'employee_executive_id')]])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            //$model->where(['employee_dep_id' => $userDep]);
            //$model->orWhere(['IN', 'employee_id', ArrayHelper::getColumn($headModel, 'employee_id')]);
        }
        $model->joinWith(['position', 'dep']);
        $model->orderBy(['employee_dep.employee_dep_sort' => SORT_ASC, 'employee_position_name' => SORT_DESC]);
        //$model->orderBy(['employee_fullname' => SORT_ASC]);
        $model->limit(100);
        $modelArray = $model->All();
        $data = [];

        foreach ($modelArray as $value) {
            $head = $value->getHead();
            $data[] = ['id' => $value->employee_id, 'text' => $value->employee_fullname, 'dep' => @$value->dep->employee_dep_label, 'position' => @$value->position->employee_position_name, 'excutive' => $value->getHead(), 'sort' => (isset($head[0]['level']) && (($head[0]['level']) > -1) ? @$head[0]['level'] : 99)];
        }

        usort($data, function ($item1, $item2) {
            if ($item1['sort'] == $item2['sort'])
                return 0;
            return $item1['sort'] < $item2['sort'] ? -1 : 1;
        });

        $out['results'] = $data;
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Employee::find($id)->employee_fullname];
        }
        return $out;
    }

    public function actionEmplist2($q = null, $id = null, $mode = '') {//สำหรับผู้บริหาร
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $userDep = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        $model = Employee::find()->where(['employee_status' => 1]);
        if (!is_null($q))
            $model->andWhere(['like', 'employee_fullname', $q])
                    ->orWhere(['like', 'employee_cid', $q]);
        /*
          if ($mode == 'D') {//กรณีอยู่หน่วยงานเดียวกัน
          $excutiveMan = [];
          $depModel = ExecutiveHasCdepartment::find()->where(['employee_dep_id' => $userDep])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
          $headModel = EmployeePositionHead::find()->where(['OR', ['employee_dep_id' => $userDep], ['employee_executive_id' => ArrayHelper::getColumn($depModel, 'employee_executive_id')]])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
          $model->where(['employee_dep_id' => $userDep]);
          $model->orWhere(['IN', 'employee_id', ArrayHelper::getColumn($headModel, 'employee_id')]);
          }
         *
         */
        $depModel = \app\modules\hr\models\Executive::find()->where(['IN', 'employee_executive_code', ['E0001', 'E0002']])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
        //if ($mode == 'A') {//กรณีทุกหน่วยงาน
        $excutiveMan = [];
        if ($mode == '1' || $mode == '')
            $depModel = \app\modules\hr\models\Executive::find()->where(['IN', 'employee_executive_code', ['E0001', 'E0002']])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
        if ($mode == '2')
            $depModel = \app\modules\hr\models\Executive::find()->where(['IN', 'employee_executive_code', ['E0002']])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ

        $headModel = EmployeePositionHead::find()->where(['employee_executive_id' => ArrayHelper::getColumn($depModel, 'employee_executive_id')])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
        //$model->where(['employee_dep_id' => $userDep]);
        $model->andWhere(['IN', 'employee_id', ArrayHelper::getColumn($headModel, 'employee_id')]);
        //}
        //$model->orderBy(['employee_dep_sort' => SORT_ASC, 'employee_dep_id' => SORT_ASC, 'employee_position_name' => SORT_ASC]);
        $model->limit(100);
        $modelArray = $model->All();
        $data = [];
        foreach ($modelArray as $value) {
            $head = $value->getHead();
            $data[] = ['id' => $value->employee_id, 'text' => $value->employee_fullname, 'dep' => @$value->dep->employee_dep_label, 'position' => @$value->position->employee_position_name, 'excutive' => $head, 'sort' => (($head[0]['level']) > -1 ? $head[0]['level'] : 99)];
        }

        //array_msort($data, 'sort', SORT_ASC);
        usort($data, function ($item1, $item2) {
            if ($item1['sort'] == $item2['sort'])
                return 0;
            return $item1['sort'] < $item2['sort'] ? -1 : 1;
        });
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
            $query->where(['like', 'employee_dep_label', $q])
                    //->andWhere(['employee_dep_status' => 1])
                    ->andWhere(['employee_dep_status' => 1]);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);

        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => \app\modules\hr\models\EmployeeDep::find($id)->employee_dep_label];
        }
        return $out;
    }

    public function actionTest() {

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
                $auth = Employee::find()->where(['IN', 'employee_id', [$model->employee_id, $model->paper->employee_owner_id]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารถูกส่งคืนให้แก้ไขค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F02') { //ส่งงานเลขา
                $model->paperless_status_id = 'F17'; //ส่งงานเลขา
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Ccomponent::getTokenUser('SecretaryAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารถูกส่งให้ดำเนินการต่อค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F11') { //ส่งงานบัญชี
                $model->paperless_status_id = 'F11'; //ส่งงานบัญชี
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Ccomponent::getTokenUser('AccountAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารถูกส่งให้ดำเนินการต่อค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F12') { //ส่งงานพัสดุ
                $model->paperless_status_id = 'F12'; //ส่งงานพัสดุ
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Ccomponent::getTokenUser('AssetAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารถูกส่งให้ดำเนินการต่อค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F13') { //ส่งงานบุคลากร
                $model->paperless_status_id = 'F13'; //ส่งงานบุคลากร
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Ccomponent::getTokenUser('HRsAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารถูกส่งให้ดำเนินการต่อค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F14') { //ส่งงานการเงิน
                $model->paperless_status_id = 'F14'; //ส่งงานเลขา
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Ccomponent::getTokenUser('FinanceAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารถูกส่งให้ดำเนินการต่อค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
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
                $auth = Ccomponent::getTokenUser('OfficeAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารถูกส่งเข้ามาค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F16') { //ส่งหน.บริหาร
                $model->paperless_status_id = 'F16'; //รอพิจารณา
                if (empty($param['comment'])) {
                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Ccomponent::getTokenUser('ManagerAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารถูกส่งให้ดำเนินการต่อค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F17') { //ส่งงานเลขา
                $model->paperless_status_id = 'F17'; //รอพิจารณา
//                if (empty($param['comment'])) {
//                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
//                    return ['status' => 'error', 'message' => $message];
//                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Ccomponent::getTokenUser('SecretaryAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารถูกส่งให้ดำเนินการต่อค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F18') { //ส่งรอง ผอ.
                $model->paperless_status_id = 'F18'; //รอพิจารณา
                if (empty($param['receiver'])) {
                    $message = 'ไม่พบการระบุรายชื่อรองผู้อำนวยการ กรุณาระบุชื่อด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                $model->process_receiver = $param['receiver']; //ผู้รับ
//                if (empty($param['comment'])) {
//                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
//                    return ['status' => 'error', 'message' => $message];
//                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                //$auth = Ccomponent::getTokenUser('ManagerAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารเข้ามาให้ดำเนินการพิจารณาค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F19') { //ส่งผอ.
                $model->paperless_status_id = 'F19'; //รอพิจารณา
                if (empty($param['receiver'])) {
                    $message = 'ไม่พบการระบุรายชื่อผู้อำนวยการ กรุณาระบุชื่อด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                $model->process_receiver = $param['receiver']; //ผู้รับ
//                if (empty($param['comment'])) {
//                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
//                    return ['status' => 'error', 'message' => $message];
//                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                //$auth = Ccomponent::getTokenUser('ManagerAdmin'); //ตามสิทธิ
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารเข้ามาให้ดำเนินพิจารณาค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'FF') { //สำเร็จ
                $model->paperless_status_id = 'FF'; //สำเร็จ
                if (isset($param['emps']) || isset($param['deps'])) {
                    if (@!is_array($param['emps']) && @!is_array($param['deps'])) {
                        $message = 'ไม่พบการมอบหมายงาน กรุณาระบุด้วยค่ะ';
                        return ['status' => 'error', 'message' => $message];
                    }
                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                //$auth = Ccomponent::getTokenUser('SuperAdmin'); //ตามสิทธิ
                $auth = Employee::find()->where(['IN', 'employee_id', [$model->paper->employee_owner_id]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = "เอกสารพิจารณาและดำเนินการเสร็จแล้วค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }

                //-------------------------------------------------------------------------------------------------------
            } elseif (isset($param['frmStatus']) && $param['frmStatus'] == 'F100') { //จัดเก็บ
                $model->paperless_status_id = 'F100'; //จัดเก็บ
            } else {
                $model->process_receiver = $param['receiver']; //ผู้รับ
                if (empty($param['receiver'])) {
                    $message = 'ไม่พบการระบุรายชื่อผู้รับ กรุณาระบุชื่อผู้รับด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                //--------แจ้งเตือนการให้แก้ไขเอกสาร-------------------------------------------------------------
                //$auth = Ccomponent::getTokenUser('SuperAdmin'); //ตามสิทธิ
                $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " มีเอกสารให้พิจารณาและดำเนินการค่ะ เรื่อง " . $model->paper->paperless_topic;
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                $message = '';
                //-------------------------------------------------------------------------------------------------------
            }
            if (isset($param['emps']) && is_array($param['emps']))
                $model->process_staffs = @implode(",", $param['emps']);
            if (isset($param['deps']) && is_array($param['deps']))
                $model->process_deps = @implode(",", $param['deps']);
            //genComment
            $model->process_comment = $this->genComment($model);
            //--------------------------------------------------------------------------------------------------------------------------------------------------
            if ($model->save()) {
                $status = 'success';
                $message = 'ดำเนินการสำเร็จ';
                $papaer = Paperless::findOne(['paperless_id' => $param['id']]);
                if (in_array($param['frmStatus'], ['F02']))
                    $papaer->paperless_direct = 1;
                if (in_array($param['frmStatus'], ['F14']))//เสนอหนังสือผ่านงานการเงิน(Finance)
                    $papaer->paperless_direct = 2;
                if (in_array($param['frmStatus'], ['F13']))//เสนอหนังสือผ่านงานบุคลากร(HR)
                    $papaer->paperless_direct = 3;
                if (in_array($param['frmStatus'], ['F11']))//เสนอหนังสือผ่านงานบัญชี
                    $papaer->paperless_direct = 4;
                if (in_array($param['frmStatus'], ['F12']))//เสนอหนังสือผ่านงานพัสดุ
                    $papaer->paperless_direct = 5;

                $papaer->paperless_status_id = $model->paperless_status_id;
                $papaer->paperless_lastprocess_id = $model->processlist_id;
                $papaer->update_at = new \yii\db\Expression('NOW()');
                $papaer->save();

                if (isset($param['frmStatus']) && $param['frmStatus'] == 'FF' && $papaer->paperless_direct <> 1) {
                    //เวียนหนังสือต่อเลย
                    $announce = PaperlessView::findOne(['paperless_paper_ref' => $param['id']]) ?: new PaperlessView();

                    if (isset($param['deps']))
                        $announce->paperless_view_deps = @implode(",", $param['deps']);
                    if (isset($param['emps']))
                        $announce->paperless_view_emps = @implode(",", $param['emps']);

                    $announce->create_at = new \yii\db\Expression('NOW()');
                    $announce->employee_id = $model->employee_id;
                    $announce->paperless_paper_ref = $param['id'];
                    $now = \DateTime::createFromFormat('U.u', microtime(true));
                    $announce->paperless_view_id = 'A' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
                    $announce->paperless_view_startdate = new \yii\db\Expression('NOW()');
                    if (!$announce->save()) {
                        return ['status' => 'error', 'message' => print_r($announce->errors, 1)];
                    }
                }
                //Cmqtt::public('hms/service/paper/update/BNN', 'processlist');
            } else {
                $getErrors = ActiveForm::validate($model);
                return ['status' => 'error', 'message' => print_r($getErrors, 1)];
            }
        } else {
            $message = 'ไม่พบการระบุ ID';
        }

        return ['status' => $status, 'message' => $message];
    }

///บันทึกข้อมูลการรับทราบหนังสือ ที่มีผู้เสนอมาถึง
    public function actionAcknowledge($id) {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $param = \Yii ::$app->request->post();
        $model = PaperlessProcessList::find()->where(['processlist_id' => $id, 'process_receiver' => md5($cid)])->one();
        if (empty($model->process_receiver))
            $model->process_acknowledge_staff = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
        $model->process_acknowledge = 1;
        $model->process_acknowledge_datetime = new \yii\db\Expression('NOW()');
        $model->save();
        $status = 'success';
        //$message = "คุณรับทราบหนังสือเลขที่ {$model->docs->docs_number} แล้ว";
        $message = "คุณรับทราบหนังสือแล้ว";
        @Cmqtt::public('hms/service/paper/update/BNN', 1);
        return ['status' => $status, 'message' => $message];
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
            //Cmqtt::public('hms/service/paper/update/BNN', 1);
            $acknowledge->save();
        }
        //ดึงสถานะที่เกี่ยวข้อง เพื่อเสนอหนังสือ
        $model = Paperless::findOne($where);
        $header = 0;
        $head = EmployeePositionHead::findOne(['employee_dep_id' => $model->employee_dep_id, 'employee_id' => $emp->employee_id]);
        if ($head && $head->executive->employee_executive_level == 4) { //ระดับหัวหน้ากลุ่มงาน
            $header = 1;
        }
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
//->employee_id
        $modelProcess = PaperlessProcessList::find()
                ->where(['paperless_id' => $model->paperless_id])
                ->orderBy(['processlist_id' => SORT_DESC])
                ->one();
        $canVisible = 0; //กำหนดการแสดงผลการดำเนินการ
        if ($modelProcess->process_receiver == $emp->employee_id) { //ให้ผู้ถูกเสนอหนังสือสามารถดำเนินการเอกสารได้
            $canVisible = 1;
        }

        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        if (!empty($modelProcess->status->paperless_status_auth)) {//ให้ผู้รับผิดชอบหนังสือสามารถดำเนินการเอกสารได้
            $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
            if (in_array($modelProcess->status->paperless_status_auth, array_keys($role))) {
                $canVisible = 1;
            }
        }

        if ((\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('SecretaryAdmin'))) {//จะสามารถดูเอกสารที่ไม่เกี่ยวข้องได้ถ้าเป็นเอกสารที่ผ่านเพื่อดำเนินการต่อ
            $canVisible = 1;
        }

        if (!empty($model->paperless_direct) && $model->paperless_direct > 0 && (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('SecretaryAdmin'))) {//งานเลขาจะสามารถดูเอกสารที่ไม่เกี่ยวข้องได้ถ้าเป็นเอกสารที่ผ่านเพื่อดำเนินการต่อ
            $canVisible = 1;
        }

        if ($model->paperless_direct > 0 && \Yii::$app->user->can('ExecutiveUser')) { //ไม่ให้ผู้บริหารดำเนินการ เอกสารที่เซนต์แล้ว
            $canVisible = 0;
        }

        $lookupStatus = [];
        $lookupStatus['User'] = ['F00', 'F03']; //ผู้ใช้งานทั่วไป /หัวหน้าฝ่าย
        if ($header == 1)
            $lookupStatus['User'] = ['F00', 'F15']; //ผู้ใช้งานทั่วไป /หัวหน้าฝ่าย

        $lookupStatus['AssetAdmin'] = ['F00', 'F03', 'F14', 'F15', 'F17']; //AssetAdmin
        $lookupStatus['AccountAdmin'] = ['F00', 'F03', 'F14', 'F15', 'F17']; //AccountAdmin
        $lookupStatus['HRsAdmin'] = ['F00', 'F03', 'F14', 'F15', 'F17']; //HR
        $lookupStatus['FinanceAdmin'] = ['F00', 'F03', 'F15', 'F17']; //การเงิน
        $lookupStatus['OfficeAdmin'] = ['F00', 'F03', 'F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //สารบรรณกลาง
        $lookupStatus['ManagerAdmin'] = ['F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //หน.บริหาร
        $lookupStatus['SecretaryAdmin'] = ['F00', 'F03', 'F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //งานเลขา
        $lookupStatus['ExecutiveUser'] = ['F15', 'F16', 'F17', 'F18', 'F19']; //รองผู้อำนวยการ
        $lookupStatus['CEO'] = ['F15', 'F16', 'F17', 'F18', 'F19']; //ผู้อำนวยการ
        $lookupStatus['SuperAdmin'] = ['F00', 'F03', 'F15', 'F16', 'F17', 'F18', 'F19', 'FF', 'F100']; //Admin

        if ($model->paperless_direct == 2)
            $lookupStatus['FinanceAdmin'] = ['F00', 'F03', 'F15', 'F17', 'FF']; //การเงิน
        if ($model->paperless_direct == 3)
            $lookupStatus['HRsAdmin'] = ['F00', 'F03', 'F14', 'F15', 'F17', 'FF']; //HR
        if ($model->paperless_direct == 4)
            $lookupStatus['AccountAdmin'] = ['F00', 'F03', 'F14', 'F15', 'F17', 'FF']; //AccountAdmin
        if ($model->paperless_direct == 5)
            $lookupStatus['AssetAdmin'] = ['F00', 'F03', 'F14', 'F15', 'F17', 'FF'];


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
                    'header' => $header
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

    public function actionDelete() {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = \Yii::$app->request->post('id');
        $model = Paperless::findOne($id);
        if ($model && @in_array($model->paperless_status_id, ['F01'])) {
            if ($model->delete()) {
                /*
                  if ($pd = ProcessList::findOne(['paperless_id' => $id])) {
                  $pd->delete();
                  //if ($Uploads = Uploads::findOne(['ref' => 'L' . $model->leave_id]))
                  //$Uploads->delete();
                  }
                 *
                 */
                $status = 'success';
                $message = '';
            } else {
                $status = 'error';
                $message = print_r($model->getErrors(), 1);
            }
        } else {
            $status = 'error';
            $message = 'สถานะของหนังสือนี้ ไม่สามารถลบรายการได้ค่ะ';
        }
        return ['status' => $status, 'message' => $message];
    }

}
