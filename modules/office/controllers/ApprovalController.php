<?php

namespace app\modules\office\controllers;

use Yii;
use app\modules\office\models\PaperlessApproval;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ExtProfile;
use yii\web\UploadedFile;
use app\components\mPDFMod;
use app\components\Ccomponent;
use app\modules\hr\models\Employee;
use xstreamka\mobiledetect\Device;
use app\components\TcPDFMod;
use app\modules\line\components\lineBot;
use app\components\Cmqtt;
//use yii\db\Expression;
use app\modules\office\models\Uploads;
use app\modules\office\models\ApprovalProcessList;
use app\modules\office\models\ApprovalStatus;
use app\modules\hr\models\EmployeePositionHead;
use app\modules\office\models\PaperlessApprovalBudgetDetail;
use yii\db\Expression;

/**
 * ApprovalController implements the CRUD actions for PaperlessApproval model.
 */
class ApprovalController extends Controller {

    /**
     * @inheritDoc
     */
    public function behaviors() {
        return array_merge(
                parent::behaviors(),
                [
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ]
        );
    }

    /**
     * Lists all PaperlessApproval models.
     *
     * @return string
     */
    public function actionIndex() {

        return $this->render('index', [
        ]);
    }

    public function actionListView() {
        @$params = \Yii::$app->request->queryParams;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
        $sqlWhere = "'" . implode("','", array_keys($role)) . "'";
        //---------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $query = PaperlessApproval::find();
        $query->addSelect('paperless_approval.*,employee_fullname');
        $query->addSelect(['pcheck' => "IF(p2.process_receiver = '{$emp->employee_id}'  || approval_status_auth IN ({$sqlWhere}) ,1,0)"]);
        $query->orderBy(['pcheck' => SORT_DESC]);
        $query->join('LEFT JOIN', 'paperless_approval_process_list p2', 'p2.processlist_id = paperless_approval.approval_lastprocess_id');
        $query->join('LEFT JOIN', 'paperless_approval_status s', 'paperless_approval.approval_status_id = s.approval_status_id');
        $query->join('LEFT JOIN', 'employee e', 'e.employee_id = paperless_approval.employee_id');
        // add conditions that should always apply here
        $query->groupBy(['paperless_approval.approval_id']);

        if (isset($params['view'])) {
            if ($params['view'] == 'list') {
                $query->andWhere(['AND',
                    new Expression(" (IF(paperless_approval.approval_status_id <> 'A10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                ]);
            } elseif ($params['view'] == 'keep') {
                $query->andWhere(['AND',
                    new Expression(" (IF(paperless_approval.approval_status_id IN ('A10'),1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                ]);
            }
        }

        if (\Yii::$app->user->can('HRdAdmin') || \Yii::$app->user->can('SuperAdmin')) {
//            $query->andWhere(['OR',
//                new Expression(" (IF(p2.process_receiver = '{$emp->employee_id}' && paperless_approval.approval_status_id <> 'A10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
//            ]);
        } else {
            $query->andWhere(['OR',
                new Expression(" (IF(paperless_approval.employee_own_id = '{$emp->employee_id}',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                new Expression(" (IF(paperless_approval.employee_id = '{$emp->employee_id}' ,1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                new Expression(" (IF(p2.process_receiver = '{$emp->employee_id}',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                new Expression("  s.approval_status_auth IN ({$sqlWhere})"),
            ]);
        }
        $query->andFilterWhere(['OR',
            ['LIKE', 'e.employee_fullname', $params['search']],
            ['LIKE', 'topic', $params['search']],
            ['LIKE', 'place', $params['search']],
            ['LIKE', 'vehicle_personal', $params['search']],
            ['LIKE', 'organized', $params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'approval_id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->renderAjax('_gird', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function updatePerson($approval_id) {
        $detail = PaperlessApprovalBudgetDetail::find()->where(['approval_id' => $approval_id])->orderby(['employee_id' => SORT_ASC])->all();
        $emp = [];
        foreach ($detail as $model) {
            if ($model->employee_id <> $model->approval->employee_own_id)
                $emp[] = $model->employee_id;
        }
        $m = PaperlessApproval::find()->where(['approval_id' => $approval_id])->one();
        $m->employee_id = implode(',', $emp);
        $m->save();
    }

    public function actionView($approval_id) {
        $request = Yii::$app->request;
        if ($post = $this->request->post()) {
            if (isset($post['emp'])) {
                foreach ($post['emp'] as $index => $data) {
                    $detail = PaperlessApprovalBudgetDetail::find()->where(['employee_id' => $post['emp'][$index], 'approval_id' => $post['approval_id']])->one();
                    if ($detail) {
                        $detail->budget_detail_costs1 = $post['costs1'][$index];
                        $detail->budget_detail_costs2 = $post['costs2'][$index];
                        $detail->budget_detail_costs3 = $post['costs3'][$index];
                        $detail->budget_detail_costs4 = $post['costs4'][$index];
                        $detail->save();
                    }
                }
                $m = PaperlessApproval::find()->where(['approval_id' => $approval_id])->one();
                $m->approval_costs = $post['approval_costs'];
                $m->save();
            }
        }
        if ($request->isAjax) {
            $this->updatePerson($approval_id);
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-success']
            ]);
            return $this->redirect(['index', 'approval_id' => $approval_id]);
            /*
              return $this->renderAjax('view', [
              'model' => $this->findModel($approval_id),
              'model2' => PaperlessApprovalBudgetDetail::findAll(['approval_id' => $approval_id]),
              ]);
             *
             */
        } else {
            return $this->render('view', [
                        'model' => $this->findModel($approval_id),
                        'model2' => PaperlessApprovalBudgetDetail::findAll(['approval_id' => $approval_id]),
            ]);
        }
    }

    public function saveModel($model) {
        if (is_array($model->employee_id))
            $model->employee_id = @implode(',', $model->employee_id);

        if (is_array($model->develop_id))
            $model->develop_id = @implode(',', $model->develop_id);

        return $model->save();
    }

    /**
     * Creates a new PaperlessApproval model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new PaperlessApproval();
        $model->employee_own_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
        $emp = [];

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $this->saveModel($model)) {
                $emp[] = $model->employee_own_id;
                $emp = array_merge($emp, explode(',', $model->employee_id));
                foreach ($emp as $value) {
                    $detail = new PaperlessApprovalBudgetDetail();
                    $detail->approval_id = $model->approval_id;
                    $detail->employee_id = $value;
                    $detail->budget_detail_costs1 = 0;
                    $detail->budget_detail_costs2 = 0;
                    $detail->budget_detail_costs3 = 0;
                    $detail->budget_detail_costs4 = 0;
                    $detail->save();
                }
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                return $this->redirect(['view', 'approval_id' => $model->approval_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing PaperlessApproval model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $approval_id Approval ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($approval_id) {
        $model = $this->findModel($approval_id);
        $model->employee_id = explode(',', $model->employee_id);
        $model->develop_id = explode(',', $model->develop_id);
        $emp[] = $model->employee_own_id;
        if ($this->request->isPost && $model->load($this->request->post()) && $this->saveModel($model)) {
            $emp = array_merge($emp, explode(',', $model->employee_id));
            foreach ($emp as $value) {
                $detail = PaperlessApprovalBudgetDetail::find()->where(['employee_id' => $value, 'approval_id' => $model->approval_id])->one() ?: new PaperlessApprovalBudgetDetail();
                if ($detail) {
                    if ($detail->isNewRecord) {
                        $detail->approval_id = $model->approval_id;
                        $detail->employee_id = $value;
                        $detail->budget_detail_costs1 = 0;
                        $detail->budget_detail_costs2 = 0;
                        $detail->budget_detail_costs3 = 0;
                        $detail->budget_detail_costs4 = 0;
                        $detail->save();
                    }
                }
            }
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-success']
            ]);
            return $this->redirect(['view', 'approval_id' => $model->approval_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PaperlessApproval model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $approval_id Approval ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteDetail() {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $budget_detail_id = $this->request->post('budget_detail_id');
        if ($budget_detail_id <> '') {
            $model = PaperlessApprovalBudgetDetail::find()->where(['budget_detail_id' => $budget_detail_id])->one();
            $approval_id = $model->approval_id;
            $model->delete();
            $this->updatePerson($approval_id);

            return ['status' => 'success'];
            //return $this->redirect(['index']);
        }
    }

    /**
     * Finds the PaperlessApproval model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $approval_id Approval ID
     * @return PaperlessApproval the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($approval_id) {
        if (($model = PaperlessApproval::findOne(['approval_id' => $approval_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPaperlesslist($q = null, $id = null) {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        //if (!is_null($q)) {
        $query = new \yii\db\Query;
        $query->select(['paperless_id AS id', "CONCAT(IFNULL(paperless_official_booknumber,paperless_official_number) ,' ',paperless_topic) AS `text`  "])
                ->from('paperless_official');

        if (!is_null($q))
            $query->where(['OR',
                        ['LIKE', 'paperless_official_booknumber', $q],
                        ['LIKE', 'paperless_topic', $q],
                        ['LIKE', 'paperless_official_detail', $q],
                        ['LIKE', 'paperless_official_number', $q]
                    ])
                    ->andWhere(['IN', 'paperless_official_type', ['BRN', 'BSN']]);
        /*
          $query = "SELECT * FROM
          (
          SELECT paperless_id AS id,paperless_number AS paperless_number,paperless_status_id,paperless_topic,paperless_detail AS paperless_detail,DATE(paperless_date) AS paperless_date, CONCAT(paperless_topic ,' ',paperless_detail) AS text FROM `paperless`
          UNION ALL
          SELECT paperless_id AS id,paperless_official_booknumber AS paperless_number,'FF' AS paperless_status_id,paperless_topic,paperless_official_detail AS paperless_detail,DATE(paperless_official_date) AS paperless_date, CONCAT(paperless_official_booknumber ,' ',paperless_topic) AS text  FROM `paperless_official` WHERE paperless_official_type IN ('BRN')
          ) z
          WHERE paperless_date > CURRENT_DATE() - 45
          AND paperless_status_id IN ('FF','F100')
          AND (
          paperless_number LIKE '%{$q}%' OR
          paperless_topic LIKE '%{$q}%' OR
          paperless_detail LIKE '%{$q}%'
          )
          ";
         */
        $query->orderBy(['paperless_official_date' => SORT_DESC]);
        $query->limit(20);
        $command = $query->createCommand();
        //$command = \Yii::$app->db->createCommand($query);
        $data = $command->queryAll();
        $out['results'] = array_values($data);

        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => \app\modules\office\models\PaperlessOfficial::find($id)->fullpaper];
        }
        return $out;
    }

    public function actionOperate($id) {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = PaperlessApproval::findOne($id);
        $modelProcess = ApprovalProcessList::find()
                ->where(['approval_id' => $model->approval_id])
                ->orderBy(['processlist_id' => SORT_DESC])
                ->one();
        $header = 0;
        $headerUpLevel = 0;
        $head = @EmployeePositionHead::findOne(['employee_dep_id' => $model->emp->employee_dep_id, 'employee_id' => $model->employee_own_id]);
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        $canVisible = 0; //กำหนดการแสดงผลการดำเนินการ

        if (!empty($modelProcess->status->approval_status_auth)) {//ให้ผู้รับผิดชอบหนังสือสามารถดำเนินการเอกสารได้
            $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
            if (in_array($modelProcess->status->approval_status_auth, array_keys($role))) {
                $canVisible = 1;
            }
        }

        if (!empty($modelProcess->process_receiver) && $modelProcess->process_receiver == $emp->employee_id) { //ให้ผู้ถูกเสนอหนังสือสามารถดำเนินการเอกสารได้
            $canVisible = 1;
        }

        if ($model->employee_own_id == $emp->employee_id && in_array($model->approval_status_id, ['A00', 'A08'])) { //ให้ผู้ลาสามารถดำเนินการเอกสารได้
            $canVisible = 1;
        }

        if ((\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('HRdAdmin'))) {//จะสามารถดูเอกสารที่ไม่เกี่ยวข้องได้ถ้าเป็นเอกสารที่ผ่านเพื่อดำเนินการต่อ
            $canVisible = 1;
        }

        if ($model->approval_status_id == 'A00' && @$head->executive->employee_executive_level == 4 && $model->emp->dep->employee_dep_parent <> '74') { //ระดับหัวหน้ากลุ่มงาน
            $header = 1;
        }


        $lookupStatus = [];
        $lookupStatus['User'] = ['A00', 'A03']; //ผู้ใช้งานทั่วไป /หัวหน้าฝ่าย
        if ($header == 1) {
            $lookupStatus['User'] = ['A00', 'A15']; //ผู้ใช้งานทั่วไป /หัวหน้าฝ่าย
        }

//$lookupStatus['HRsAdmin'] = ['A00', 'A03', 'A15', 'A17']; //HR
        //$lookupStatus['FinanceAdmin'] = ['F00', 'F03', 'F15', 'F17']; //การเงิน
        $lookupStatus['OfficeAdmin'] = ['F00', 'F03', 'F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //สารบรรณกลาง
        //$lookupStatus['ManagerAdmin'] = ['F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //หน.บริหาร
        //$lookupStatus['SecretaryAdmin'] = ['A00', 'A03', 'A15', 'A16', 'A17', 'A18', 'A19', 'FF']; //งานเลขา
        $lookupStatus['ExecutiveUser'] = ['A15', 'A16', 'A17', 'A18', 'A19']; //รองผู้อำนวยการ/ผู้อำนวยการ
        $lookupStatus['SuperAdmin'] = ['A00', 'A01', 'A02', 'A03', 'A04', 'A05', 'A06', 'A07', 'A08', 'A09', 'A10', 'A99']; //Admin
//กำหนดการแสดงผลตัวเลือกตามสิทธิ
        $arrayStatus = [];
        $roles = \Yii::$app->authManager->getRolesByUser($profile->user_id);

        foreach ($roles as $role) {
            if (!isset($lookupStatus[$role->name]))
                continue;
            $arrayStatus = array_unique(array_merge($arrayStatus, $lookupStatus[$role->name]));
        }

        sort($arrayStatus);
        $status = ApprovalStatus::find()->where(['IN', 'approval_status_id', $arrayStatus])->all();
        $query = ApprovalProcessList::find()->select(['*', 'TIMEDIFF(process_acknowledge_datetime,process_create) AS paperless_tt'])->where(['approval_id' => $model->approval_id]);
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

        return $this->renderAjax('_operate', [
                    'header' => @$header,
                    'modelProcess' => $model,
                    'dataProvider' => $dataProvider,
                    'canVisible' => @$canVisible,
                    'status' => @$status,
                    'headerUpLevel' => @$headerUpLevel,
        ]);
    }

    public function actionViewfile($id = null, $file = null) {
        if (Device::$isPhone || Device::$isTablet) {
            if ($file === NULL) {
                return $this->renderAjax('viewfile', ['id' => $id, 'file' => $file]);
            }
        } else {
            if ($file === NULL) {
                return $this->redirect(['viewdoc', 'id' => $id]);
            } else {
                return Yii::$app->response->sendFile($file, 'เอกสารแนบ', ['inline' => true]);
            }
        }
    }

    public function actionViewdoc2($id) {
        $model = PaperlessApproval::find()
                ->where(['approval_id' => $id])
//->join('LEFT JOIN', 'approval_process_list p2', 'p2.approval_id = approval_main.approval_id')
                ->one();
//$model
//$model->one();
        $mpdf = mPDFMod::mPDFModInit();
        $mpdf->SetProtection(['print']);
        $mpdf->SetTitle("แบบใบลาพักผ่อน");
        $mpdf->SetAuthor('สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี');
        $mpdf->SetWatermarkText('สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี ');
        $mpdf->showWatermarkText = false;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
#$mpdf->SetDisplayMode('fullpage');
#$mpdf->SetFooter('<div style="text-align:right;font-size:8px;">สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี วันที่พิมพ์ ' . Ccomponent::getThaiDate(date('Y-m-d'), 'L') . ' หน้า {PAGENO} / {nb}</div>');
        @$mpdf->WriteHTML($this->renderPartial($model->approvalType->approval_type_form, ['model' => @$model, 'data' => @$data]));

        $mpdf->Output();
        return $this->render($model->approvalType->approval_type_form, []);
    }

    public function actionViewdoc($id = null) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $model = PaperlessApproval::find()
                ->where(['approval_id' => $id])
//->join('LEFT JOIN', 'approval_process_list p2', 'p2.approval_id = approval_main.approval_id')
                ->one();

        $data = PaperlessApprovalBudgetDetail::find()->joinWith('emp')
                        ->join('LEFT JOIN', 'employee_position ep', 'ep.employee_position_id = employee.employee_position_id')
                        ->where(['approval_id' => $id])->orderBy([
                    'employee_type_id' => SORT_ASC,
                    'employee_position_sort' => SORT_ASC,
                    'employee_position_name' => SORT_ASC,
                    'employee_fullname' => SORT_ASC,
                ])->all();

        // create new PDF document
        //$pdf = TcPDFMod::TcPDFModInit();
        //$pdf = new TcPDFMod('P', 'mm', 'A4', true, 'UTF-8', false, true);
        $pdf = TcPDFMod::TcPDFModInit();
        // set document information
        //$pdf->setCreator('HMP:Hospital Management Platform');
        //$pdf->setAuthor('Sila Klanklaeo');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('ขออนุญาตเดินทางไปราชการ');
        //$pdf->setPrintFooter(false);
        //$pdf->Footer(true);
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

        $allSum = 0;
        $x = 0;
        $cc = count($data);
        $table = '';
        $table2 = '';
        foreach ($data as $emp) {
            @$sum1 += $emp->budget_detail_costs1;
            @$sum2 += $emp->budget_detail_costs2;
            @$sum3 += $emp->budget_detail_costs3;
            @$sum4 += $emp->budget_detail_costs4;
            @$sum = ($emp->budget_detail_costs1 + $emp->budget_detail_costs2 + $emp->budget_detail_costs3 + $emp->budget_detail_costs4);
            $allSum += $sum;
            ++$x;
            $table2 .= '<tr>
                    <td style="text-align:center;">' . $x . '</td>
                    <td>&nbsp;' . $emp->emp->employee_fullname . '</td>
                    <td>&nbsp;' . $emp->emp->position->employee_position_name . '</td>
                    <td style="text-align:right;">' . number_format($emp->budget_detail_costs1) . '&nbsp;&nbsp;</td>
                    <td style="text-align:right;">' . number_format($emp->budget_detail_costs2) . '&nbsp;&nbsp;</td>
                    <td style="text-align:right;">' . number_format($emp->budget_detail_costs3) . '&nbsp;&nbsp;</td>
                    <td style="text-align:right;">' . number_format($emp->budget_detail_costs4) . '&nbsp;&nbsp;</td>
                    <td style="text-align:right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                </tr>';
            if ($x <= 3) {
                $table .= '<tr>
                    <td style="text-align:center;">' . $x . '</td>
                    <td>&nbsp;' . $emp->emp->employee_fullname . '</td>
                    <td>&nbsp;' . $emp->emp->position->employee_position_name . '</td>
                    <td style="text-align:right;">' . number_format($emp->budget_detail_costs1) . '&nbsp;&nbsp;</td>
                    <td style="text-align:right;">' . number_format($emp->budget_detail_costs2) . '&nbsp;&nbsp;</td>
                    <td style="text-align:right;">' . number_format($emp->budget_detail_costs3) . '&nbsp;&nbsp;</td>
                    <td style="text-align:right;">' . number_format($emp->budget_detail_costs4) . '&nbsp;&nbsp;</td>
                    <td style="text-align:right;">' . number_format($sum) . '&nbsp;&nbsp;</td>
                </tr>';
            } else {
                @$cost1 += $emp->budget_detail_costs1;
                @$cost2 += $emp->budget_detail_costs2;
                @$cost3 += $emp->budget_detail_costs3;
                @$cost4 += $emp->budget_detail_costs4;
                @$cost5 += $sum;
            }
        }

        if ($cc > 3) {
            $table .= '<tr>
          <td style = "text-align:center;">&nbsp;</td>
          <td colspan = "2">&nbsp;รายชื่อผู้เข้าร่วมอบรมท่านอื่นๆ (รายชื่อตามเอกสารแนบท้าย)</td>
          <td style="text-align:right;">' . number_format($cost1) . '&nbsp;&nbsp;</td>
          <td style="text-align:right;">' . number_format($cost2) . '&nbsp;&nbsp;</td>
          <td style="text-align:right;">' . number_format($cost3) . '&nbsp;&nbsp;</td>
          <td style="text-align:right;">' . number_format($cost4) . '&nbsp;&nbsp;</td>
          <td style="text-align:right;">' . number_format($cost5) . '&nbsp;&nbsp;</td>
          </tr>';
        }

        $tableSum = '<tr>
          <td colspan = "3" style = "text-align:right;"><b>รวมทั้งหมด</b>&nbsp;&nbsp;</td>
          <td style="text-align:right;"><b>' . number_format($sum1) . '</b>&nbsp;&nbsp;</td>
          <td style="text-align:right;"><b>' . number_format($sum2) . '</b>&nbsp;&nbsp;</td>
          <td style="text-align:right;"><b>' . number_format($sum3) . '</b>&nbsp;&nbsp;</td>
          <td style="text-align:right;"><b>' . number_format($sum4) . '</b>&nbsp;&nbsp;</td>
          <td style="text-align:right;"><b>' . number_format($allSum) . '</b>&nbsp;&nbsp;</td>
          </tr>';
        $table .= $tableSum;
        $table2 .= $tableSum;

        @$pdf->writeHTML($this->renderPartial('example', ['model' => @$model, 'table' => $table]), true, false, true, false, '');
        @$html = $this->renderPartial('_signature', ['model' => @$model, 'data' => @$data, 'modelProcess' => $modelProcess]);
        @$pdf->writeHTMLCell('', '', 15, 170, $html);
        $pdf->write2DBarcode($model->approval_id, 'QRCODE, H', 170, 5, 50, 50, $style, 'N');
        if ($cc > 3) {
            $pdf->AddPage();
            @$pdf->writeHTML($this->renderPartial('_table', ['model' => @$model, 'table' => $table2]), true, false, true, false, '');
        }
// QRCODE,L : QR-CODE Low error correction
// if (!empty($model->paperless_official_uuid))

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

    public function actionCreateprocess() {
        $message = '';
        $status = 'error';
        $param = \Yii ::$app->request->post();
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($param['id'])) {
            $model = new ApprovalProcessList();
            $modelApproval = PaperlessApproval::findOne($param['id']); //ข้อมูล

            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $model->processlist_id = '' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
            $model->approval_id = $modelApproval->approval_id; //ใบลา

            $model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
            $model->process_create = new \yii\db\Expression('NOW()');

            if (isset($param['cancelLeave']) && $param['cancelLeave'] == 'cancel') { //ปฏิเสธ
                $model->approval_status_id = 'A08'; //ปฏิเสธ
                if (empty($param['comment'])) {
                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                $model->process_acknowledge_staff = $model->employee_id;
                $model->process_comment = @$param['comment']; //หมายเหตุ
                $auth = Employee::find()->where(['IN', 'employee_id', [$modelApproval->employee_own_id]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " คุณถูกปฏิเสธการขออนุญาตไปราชการ จาก " . Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_fullname . " เนื่องจาก " . $model->process_comment . " ({$modelApproval->topic})";
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
            } elseif (in_array($modelApproval->approval_status_id, ['A00', 'A08'])) {
                $ceoOwner = @EmployeePositionHead::findOne(['employee_id' => $modelApproval->employee_own_id]); //ผู้บริหาร
                if (@$ceoOwner && @in_array(2, @$ceoOwner->executives->employee_executive_level)) { //ผู้บริหาร
                    $model->approval_status_id = 'A04'; //
                } else {
                    if (empty($param['receiver'])) {
                        $message = 'ไม่พบการระบุรายชื่อผู้เสนอ กรุณาระบุชื่อผู้เสนอด้วยค่ะ';
                        return ['status' => 'error', 'message' => $message];
                    }

                    $headOwner = @EmployeePositionHead::findOne(['employee_dep_id' => $modelApproval->emp->employee_dep_id, 'employee_id' => $modelApproval->employee_own_id]);

                    if (@$headOwner && @$headOwner->executive->employee_executive_level == 4 && @$modelApproval->emp->dep->employee_dep_parent <> '74') { //ระดับหัวหน้ากลุ่มงาน
                        $model->approval_status_id = 'A02'; //
                    } else {
                        /* หัวหน้าฝ่าย */
                        $head = @EmployeePositionHead::findOne(['employee_dep_id' => $modelApproval->emp->employee_dep_id, 'employee_id' => $param['receiver']]);
                        if ($head && @$head->executive->employee_executive_level == 4 && $modelApproval->emp->dep->employee_dep_parent <> '74') {     //ระดับ'หัวหน้ากลุ่มงานการเจ้าหน้าที่     //employee_dep_parent == 74 กลุ่มการพยาบาล
                            $model->approval_status_id = 'A02'; //
                        } else {
                            $model->approval_status_id = 'A01'; //
                        }
                    }

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

                    $model->process_receiver = $param['receiver']; //เสนอหัวหน้างาน/หัวหน้าฝ่าย;
                    $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                    foreach ($auth as $emp) {
                        $linebot = new lineBot();
                        $message = "คุณมีแฟ้มขออนุญาตไปราชการส่งมาให้ดำเนินการค่ะ จาก" . $modelApproval->emps->employee_fullname . " ";
                        @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                    }
                }//end if
//-------------------------------------------------------------------------------------------------------
            } elseif ($modelApproval->approval_status_id == 'A01') { //
                $model->approval_status_id = 'A02'; //เสนอหัวหน้างาน/หัวหน้าฝ่าย

                if (empty($param['receiver'])) {
                    $message = 'ไม่พบการระบุรายชื่อผู้เสนอ กรุณาระบุชื่อผู้เสนอด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                $model->process_receiver = $param['receiver']; //เสนอหัวหน้างาน/หัวหน้าฝ่าย;
                $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = "คุณมีแฟ้มขออนุญาตไปราชการส่งมาให้ดำเนินการค่ะ จาก" . $modelApproval->emps->employee_fullname . " ";
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
            } elseif ($modelApproval->approval_status_id == 'A02') { //ส่งให้หัวหน้าฝ่ายเห็นชอบ
                $model->approval_status_id = 'A04'; //ส่งเจ้าหน้าที่ HRD ตรวจสอบ
                $model->process_acknowledge_staff = $model->employee_id;
            } elseif ($modelApproval->approval_status_id == 'A04') { //ส่งหัวหน้ากลุ่มงาน พรส. อนุมัติ
                $model->approval_status_id = 'A05';
                $model->process_acknowledge_staff = $model->employee_id; //เจ้าหน้าที่ HRD
            } elseif ($modelApproval->approval_status_id == 'A05') { //ส่งหัวหน้ากลุ่มงาน พรส. อนุมัติ
                $model->approval_status_id = 'A06';
                $model->process_acknowledge_staff = $model->employee_id; //เจ้าหน้าที่ HRD
            } elseif ($modelApproval->approval_status_id == 'A06') { //CEO
                $model->approval_status_id = 'A10'; //ดำเนินการสำเร็จ
                $model->process_receiver = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
                $auth = Employee::find()->where(['IN', 'employee_id', [$modelApproval->employee_own_id]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = "ผู้บริหารดำเนินการอนุมัติไปราชการ ให้เรียบร้อยแล้วค่ะ ({$modelApproval->topic})";
                    @$linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
//-------------------------------------------------------------------------------------------------------
            } else {

                return ['status' => 'error', 'message' => 'ไม่สามารถดำเนินการได้ กรุณาติดต่อผู้ดูแลระบบ !'];
//-------------------------------------------------------------------------------------------------------
            }
            if (!empty($model->approval_status_id)) {
                if ($model->save()) {
                    $status = 'success';
                    $message = 'ดำเนินการสำเร็จ';
                    $modelApproval->approval_status_id = $model->approval_status_id;
                    $modelApproval->approval_lastprocess_id = $model->processlist_id;
                    $modelApproval->update_at = new \yii\db\Expression('NOW()');

                    if (!$modelApproval->save()) {
                        return ['status' => 'error', 'message' => print_r($modelApproval->errors, 1)];
                    } else {
                        @Cmqtt::public('hms/service/paper/update/AP-' . $model->process_receiver, 'approv-system'); // Update MQTT ผู้เกี่ยวข้อง
                        @Cmqtt::public('hms/service/operation/' . $model->process_receiver, date('Y-m-d H:i:s')); //Update MQTT เจ้าของเรื่อง
                    }
                } else {
                    return ['status' => 'error', 'message' => print_r($model->errors, 1)];
                }
            }
        } else {
            $message = 'ไม่พบการระบุ ID';
        }

        return ['status' => $status, 'message' => $message];
    }

//ลบสถานะการดำเนินการ
    public function actionProcessUpdate() {
        $id = Yii::$app->request->post('id');
        if (\Yii::$app->user->can('SuperAdmin')) {
            $model = ApprovalProcessList::findOne($id);
            $lid = $model->approval_id;
            if ($model !== NULL) {
                if ($model->delete()) {
                    $this->actionProcessRefresh($lid);
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

    public function actionProcessRefresh($id) {
        $model = ApprovalProcessList::find()->where(['approval_id' => $id])->orderBy(['process_create' => SORT_DESC])->one();
        if ($model) {
            $modelApproval = PaperlessApproval::findOne($model->approval_id);
            $modelApproval->approval_status_id = $model->approval_status_id;
            $modelApproval->approval_lastprocess_id = $model->processlist_id;
            $modelApproval->update_at = new \yii\db\Expression('NOW()');
            $modelApproval->save();
        } else {
            $modelApproval = PaperlessApproval::findOne($id);
            $modelApproval->approval_status_id = 'A00';
            $modelApproval->approval_lastprocess_id = '';
            $modelApproval->update_at = new \yii\db\Expression('NOW()');
            $modelApproval->save();
        }
    }

    public function actionViewpaper($id = null) {
        /*
          if ($id) {
          return $this->redirect(['/office/paperless/view', 'id' => $id]);
          } else {
         *
         */
        return $this->redirect(['/office/official/view', 'id' => $id]);
        //}
    }

    public function actionDelete() {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = \Yii::$app->request->post('id');
        $model = PaperlessApproval::findOne($id);
        if ($model && @in_array($model->approval_status_id, ['A00', 'A08'])) {
            if ($model->delete()) {
                if ($pd = ApprovalProcessList::findOne(['approval_id' => $id])) {
                    $pd->delete();
//if ($Uploads = Uploads::findOne(['ref' => 'L' . $model->leave_id]))
//$Uploads->delete();
                }
                $status = 'success';
                $message = '';
            } else {
                $status = 'error';
                $message = '';
            }
        } else {
            $status = 'error';
            $message = '';
        }
        return ['status' => $status, 'message' => $message];
    }

}
