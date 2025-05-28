<?php

namespace app\modules\office\controllers;

use Yii;
use app\modules\office\models\WorkChangeGrid;
use app\modules\office\models\WorkChangeGridSearch;
use app\modules\office\models\WorkChange;
use app\modules\office\models\WorkProcessList;
use app\modules\office\models\WorkChangeStatus;
use app\modules\office\models\WorkGridType;
use app\modules\hr\models\EmployeePositionHead;
use app\models\ExtProfile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\components\mPDFMod;
use app\components\Ccomponent;
use app\modules\hr\models\Employee;
use app\modules\office\models\LeaveSignature;
use xstreamka\mobiledetect\Device;
use app\components\TcPDFMod;
use app\modules\line\components\lineBot;
use app\components\Cmqtt;
//use yii\db\Expression;
use app\modules\office\models\Uploads;
use yii\helpers\BaseFileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\hr\models\ExecutiveHasCdepartment;
use app\modules\office\models\LeaveAccumulate;
use yii\db\Expression;

class WorkController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /*
      public function actionHistory($id) {
      $budgetYear = \Yii::$app->params['budgetYear'];
      $budgetYear_start = ($budgetYear - 1) . "-10-01";
      $budgetYear_end = ($budgetYear) . "-09-30";
      $chart = [];
      $chartData = [];
      $series = [];

      $Query = "SELECT
      a.employee_id
      ,CONCAT(YEAR(leave_start),LPAD(MONTH(leave_start),2,0)) AS dd
      ,LPAD(MONTH(leave_start),2,0) AS mm
      ,a.leave_type_id
      ,leave_type_name
      ,COUNT(*) AS cc
      ,SUM(leave_day) AS ss
      ,vacation_leave
      FROM leave_main a
      LEFT JOIN leave_type b ON a.leave_type_id = b.leave_type_id
      LEFT JOIN leave_accumulate a2 ON a2.employee_id = a.employee_id AND a2.budgetyear = '{($budgetYear + 543)}'
      WHERE leave_start >= '{$budgetYear_start}' AND leave_end <= '{$budgetYear_end}'
      AND leave_status_id NOT IN ('L00','L08','L09')
      AND a.employee_id = '{$id}'
      GROUP BY a.leave_type_id,CONCAT(YEAR(leave_start),LPAD(MONTH(leave_start),2,0))";

      $data = \Yii::$app->db->createCommand($Query)->queryAll(); //->cache(1800)
      $monthTH = Ccomponent::getArrayThaiMonth(1);
      foreach ($data as $value) {
      @$chartData[$value['leave_type_id']]['name'] = $value['leave_type_name'];
      @$chartData[$value['leave_type_id']]['y'] += (double) $value['ss'];
      $i = 0;
      foreach ($monthTH as $index => $month) {
      @$chartData[$value['leave_type_id']]['name'] = $value['leave_type_name'];
      if ($value['mm'] == $index) {
      @$chartData['data'][$value['leave_type_id']]['data'][$i] += (double) $value['ss'];
      } else {
      @$chartData['data'][$value['leave_type_id']]['data'][$i] += 0;
      }
      $i++;
      }
      $dataColumn = $chartData['data'][$value['leave_type_id']]['data'];
      $chart['column']['data'][$value['leave_type_id']]['name'] = $value['leave_type_name'];
      $chart['column']['data'][$value['leave_type_id']]['data'] = $dataColumn;
      }

      if (isset($chartData['001']))
      @$chartData['001']['y'] += (double) $data[0]['vacation_leave']; //ยอดยกมาวันลาพักผ่อน
      if (isset($chartData['009']))
      @$chartData['009']['y'] += (double) $data[0]['cancel_leave']; //ยอดยกมาวันลาพักผ่อน

      @sort($chart['column']['data']);
      @sort($chartData);
      $chart['pie'] = [['name' => 'ประเภทการลา', 'data' => $chartData]]; //ใช้งานได้แล้ว
      //---------------------------------------------------------------------------------------------------------------------------------------------------------------------

      $staff = Employee::find()->where(['employee_id' => $id])->one();
      $model = LeaveAccumulate::findOne(['employee_id' => $id, 'budgetyear' => ($budgetYear + 543)]);
      if (!$model) {
      $model = new LeaveAccumulate();
      $model->employee_id = $staff->employee_id;
      $model->budgetyear = ($budgetYear + 543);
      }
      if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin')) {
      if ($model->load(Yii::$app->request->post()) && $model->save()) {
      \Yii::$app->getSession()->setFlash('alert', [
      'body' => 'บันทึกข้อมูลสำเร็จ..',
      'options' => ['class' => 'alert-danger']
      ]);
      }
      }
      return $this->renderAjax('_form_leave', [
      'user' => $staff,
      'model' => $model,
      'chart' => $chart,
      ]);
      }
     */

    public function actionIndex() {

        $param = Yii::$app->request->get('qsearch');
        $params = \Yii::$app->request->queryParams;
        $paramManage = Yii::$app->request->get('qsearchManage');
        $cid = \yii::$app->user->identity->profile->cid;
        $staff = Employee::find()->where(['employee_cid' => $cid])->one();
        $searchModel = new WorkChangeGridSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $events = [];

        if (isset($params['mode']) && $params['mode'] == 'owner') {

            return $this->renderAjax('_grid', [
                        'user' => $staff,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                            //'events' => (array) $events,
            ]);
        } else {
            if (isset($params['view'])) {
                if ($params['view'] == 'list') {
                    /*
                      $userDep = $staff->employee_dep_id;
                      $depLink = $staff->dep->employee_dep_parent;
                      $depModel = ExecutiveHasCdepartment::find()->where(['employee_dep_id' => $userDep])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
                      $headModel = EmployeePositionHead::find()->where(['OR', ['employee_dep_id' => $userDep], ['employee_id' => $staff->employee_id], ['employee_executive_id' => ArrayHelper::getColumn($depModel, 'employee_executive_id')]])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
                      $depModel2 = ExecutiveHasCdepartment::find()->where(['IN', 'employee_executive_id', ArrayHelper::getColumn($headModel, 'employee_executive_id')])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
                      $depLink = Employee::find()->where(['employee_dep_id' => $depLink])->all(); //เพิ่มรายชื่อหน่วยงานที่เกี่ยวข้อง

                      $emp = Employee::find()
                      //->addSelect('*,(vacation_accrued-(vacation_leave+cancel_leave)) as accrued')
                      ->where(['employee_status' => 1, 'budgetYear' => (Yii::$app->params['budgetYear'] + 543)]);
                      if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin')) {

                      $emp->andFilterWhere(['OR',
                      ['like', 'employee_cid', @$params['qsearchManage']],
                      ['like', 'employee_fullname', @$params['qsearchManage']],
                      ]);
                      $emp->andFilterWhere(['AND',
                      ['like', 'employee.employee_dep_id', @$params['dep']],
                      ]);
                      } else {

                      $emp->andWhere(['OR',
                      ['employee_dep_id' => $userDep],
                      ['IN', 'employee.employee_id', ArrayHelper::getColumn($headModel, 'employee_id')],
                      ['IN', 'employee.employee_dep_id', ArrayHelper::getColumn($headModel, 'employee_dep_id')],
                      ['IN', 'employee.employee_dep_id', ArrayHelper::getColumn($depModel2, 'employee_dep_id')],
                      // ['employee.employee_dep_id' => $params['dep']],
                      ]);
                      }
                      $emp->orderBy(['employee_dep_id' => 'ASC', 'employee_type_id' => 'DESC', 'employee_position_id' => 'DESC', 'employee_fullname' => 'ASC',]);
                      $emp->joinWith(['empLeave']);

                      $dataProvider = new ActiveDataProvider([
                      'query' => $emp,
                      'pagination' => [
                      'pageSize' => 100
                      ],
                      ]);
                      $dataProvider->setSort([
                      'attributes' => [
                      'cumulative' => [
                      'asc' => ['cumulative' => SORT_ASC],
                      'desc' => ['cumulative' => SORT_DESC],
                      'default' => SORT_ASC
                      ], 'claim' => [
                      'asc' => ['claim' => SORT_ASC],
                      'desc' => ['claim' => SORT_DESC],
                      'default' => SORT_ASC
                      ], 'cancel_leave' => [
                      'asc' => ['cancel_leave' => SORT_ASC],
                      'desc' => ['cancel_leave' => SORT_DESC],
                      'default' => SORT_ASC
                      ], 'vacation_leave' => [
                      'asc' => ['vacation_leave' => SORT_ASC],
                      'desc' => ['vacation_leave' => SORT_DESC],
                      'default' => SORT_ASC
                      ], 'personal_leave' => [
                      'asc' => ['personal_leave' => SORT_ASC],
                      'desc' => ['personal_leave' => SORT_DESC],
                      'default' => SORT_ASC
                      ], 'sick_leave' => [
                      'asc' => ['sick_leave' => SORT_ASC],
                      'desc' => ['sick_leave' => SORT_DESC],
                      'default' => SORT_ASC
                      ], 'maternity_leave' => [
                      'asc' => ['maternity_leave' => SORT_ASC],
                      'desc' => ['maternity_leave' => SORT_DESC],
                      'default' => SORT_ASC
                      ], 'accrued' => [
                      'asc' => ['accrued' => SORT_ASC],
                      'desc' => ['accrued' => SORT_DESC],
                      'default' => SORT_ASC
                      ],
                      ]]);
                      return $this->renderAjax('_gridviewEmp', [
                      'user' => $staff,
                      'dataProvider' => $dataProvider,
                      ]);
                     *
                     */
                } elseif ($params['view'] == 'pdf') {
                    //print_r($dataProvider);
                    return $this->renderAjax('_grid_pdf', [
                                'user' => $staff,
                                'dataProvider' => $dataProvider,
                    ]);

                    //----------------------------------------------------------------------------------------------------------------------------------------------
                } elseif ($params['view'] == 'calendar') {
                    if (isset($params['mode']) && $params['mode'] == 'event') {

                        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return $events;
                    }

                    return $this->renderAjax('_calendar', [
                                'user' => $staff,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                    ]);
                } elseif ($params['view'] == 'dashboard') {
                    $budgetYear = \Yii::$app->params['budgetYear'];
                    $budgetYear_start = ($budgetYear - 1) . "-10-01";
                    $budgetYear_end = ($budgetYear) . "-09-30";
                    $LeaveModel = LeaveAccumulate::findOne(['employee_id' => $staff->employee_id, 'budgetyear' => ($budgetYear + 543)]);
                    $chart = [];
                    $chartData = [];
                    $series = [];

                    $Query = "SELECT
                            a.employee_id
                            ,CONCAT(YEAR(leave_start),LPAD(MONTH(leave_start),2,0)) AS dd
                            ,LPAD(MONTH(leave_start),2,0) AS mm
                            ,a.leave_type_id
                            ,leave_type_name
                            ,COUNT(*) AS cc
                            ,SUM(leave_day) AS ss
                            ,vacation_leave
                            FROM leave_main a
                            LEFT JOIN leave_type b ON a.leave_type_id = b.leave_type_id
                            LEFT JOIN leave_accumulate a2 ON a2.employee_id = a.employee_id AND a2.budgetyear = '{($budgetYear + 543)}'
                            WHERE leave_start >= '" . ($budgetYear_start) . "' AND leave_end <= '" . ($budgetYear_end) . "'
                            AND leave_status_id NOT IN ('L00','L08','L09')
                            AND a.employee_id = '{$staff->employee_id}'
                            GROUP BY a.leave_type_id,CONCAT(YEAR(leave_start),LPAD(MONTH(leave_start),2,0))";

                    $data = \Yii::$app->db->createCommand($Query)->queryAll(); //->cache(1800)
                    $monthTH = Ccomponent::getArrayThaiMonth(1);
                    foreach ($data as $value) {
                        @$chartData[$value['leave_type_id']]['name'] = $value['leave_type_name'];
                        @$chartData[$value['leave_type_id']]['y'] += (double) $value['ss'];
                        $i = 0;
                        foreach ($monthTH as $index => $month) {
                            @$chartData[$value['leave_type_id']]['name'] = $value['leave_type_name'];
                            if ($value['mm'] == $index) {
                                @$chartData['data'][$value['leave_type_id']]['data'][$i] += (double) $value['ss'];
                            } else {
                                @$chartData['data'][$value['leave_type_id']]['data'][$i] += 0;
                            }
                            $i++;
                        }
                        $dataColumn = $chartData['data'][$value['leave_type_id']]['data'];
                        $chart['column']['data'][$value['leave_type_id']]['name'] = $value['leave_type_name'];
                        $chart['column']['data'][$value['leave_type_id']]['data'] = $dataColumn;
                    }

                    if (isset($chartData['001']))
                        @$chartData['001']['y'] += (double) $data[0]['vacation_leave']; //ยอดยกมาวันลาพักผ่อน
                    if (isset($chartData['009']))
                        @$chartData['009']['y'] += (double) $data[0]['cancel_leave']; //ยอดยกมาวันลาพักผ่อน

                    @sort($chart['column']['data']);
                    @sort($chartData);
                    $chart['pie'] = [['name' => 'ประเภทการลา', 'data' => $chartData]]; //ใช้งานได้แล้ว
                    return $this->renderAjax('_dashboard', [
                                'user' => $staff,
                                'model' => $LeaveModel,
                                'chart' => $chart,
                    ]);
                } else {
                    return $this->renderAjax('_gridview', [
                                'user' => $staff,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                    ]);
                }
            }
            return $this->render('index', [
                        'user' => $staff,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionView($id = null, $file = null) {
        if (Device::$isPhone || Device::$isTablet) {
            if ($file === NULL) {
                return $this->renderAjax('view', ['id' => $id, 'file' => $file]);
            }
        } else {
            if ($file === NULL) {
                return $this->redirect(['viewdoc', 'id' => $id]);
            } else {
                return Yii::$app->response->sendFile($file, 'เอกสารแนบ', ['inline' => true]);
            }
        }
    }

    //ยกเลิกวันลา
    public function actionCancel($id) {
        $modelLeave = $this->findModel($id);
        $model = new LeaveMain();
        $cid = \yii::$app->user->identity->profile->cid;
        // $staff = Employee::find()->where(['employee_cid' => $cid])->one();
        $model->employee_id = $modelLeave->employee_id;
        $model->leave_cancel_id = $modelLeave->leave_id;
        $model->employee_dep_id = $modelLeave->employee_dep_id;
        $model->leave_type_time = 'F'; //Fulltime
        $model->leave_type_id = 9; //ประเภทยกเลิกวันลา
        $model->leave_status_id = 'L00'; //เอกสารร่าง
        $model->leave_start = $modelLeave->leave_start;
        $model->leave_end = $modelLeave->leave_end;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-danger']
            ]);
            return $this->redirect(['index']);
        }

        return $this->render('_form_cancel', [
                    'model' => $model,
                    'modelLeave' => $modelLeave,
        ]);
    }

    public function actionCreate() {
        $model = new WorkChangeGrid();
        $cid = \yii::$app->user->identity->profile->cid;
        $staff = Employee::find()->where(['employee_cid' => $cid])->one();
        $model->emp_staff_a = $staff->employee_id;
        $model->employee_dep_id = $staff->employee_dep_id;
        //$model->leave_type_time = 'F'; //Fulltime
        $model->work_status_id = 'L00'; //เอกสารร่าง
        //list($initialPreview, $initialPreviewConfig) = $this->getInitialPreview('L' . $model->work_change_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$this->Uploads(false, 'L' . $model->leave_id);
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-danger']
            ]);
            return $this->redirect(['index']);
        }

        return $this->render('create', [
                    'model' => $model,
                        //'initialPreview' => $initialPreview,
                        //'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-danger']
            ]);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete() {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = \Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if ($model && @in_array($model->work_status_id, ['L00', 'L08'])) {
            if ($model->delete()) {
                if ($pd = WorkProcessList::findOne(['work_grid_change_id' => $id])) {
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

    protected function findModel($id) {
        if (($model = WorkChangeGrid::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionViewdoc($id) {
        $model = WorkChangeGrid::find()
                ->where(['work_grid_change_id' => $id])
                ->one();

        $mpdf = mPDFMod::mPDFModInit('L', 'THS9', 'A5');
        $mpdf->SetProtection(['print']);
        $mpdf->SetTitle("เอกสาร");
        $mpdf->SetAuthor('รพ.สมเด็จพระสังฆราชองค์ที่ 17');
        $mpdf->SetWatermarkText('รพ.สมเด็จพระสังฆราชองค์ที่ 17 ');
        $mpdf->showWatermarkText = false;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
#$mpdf->SetDisplayMode('fullpage');
        $mpdf->SetFooter('<div style="text-align:right;font-size:8px;">รพ.สมเด็จพระสังฆราชองค์ที่ 17 วันที่พิมพ์ ' . Ccomponent::getThaiDate(date('Y-m-d H:i:s'), 'L', true) . ' หน้า {PAGENO} / {nb}</div>');
        @$mpdf->WriteHTML($this->renderPartial('frm01', ['model' => @$model, 'data' => @$data]));
        $mpdf->Output();
        //return $this->render('frm01', []);
    }

    public function actionViewdocs($date_between_a, $date_between_b, $dep) { //ดูภาพรวม
        $model = WorkChangeGrid::find()
                ->where(['work_status_id' => 'L10'])
                ->andWhere(['OR',
                    ['between', 'work_grid_change_date_a', $date_between_a, $date_between_b],
                    ['between', 'work_grid_change_date_b', $date_between_a, $date_between_b],
                ])
                //->andWhere(['between', ['work_grid_change_date_a', $startDate, $endDate]])
                ->andFilterWhere(['employee_dep_id' => @$dep])
                ->orderBy(['work_grid_change_date_a' => SORT_ASC])
                ->limit(150)
                ->all();

        $mpdf = mPDFMod::mPDFModInit('L', 'THS9', 'A5');
        $mpdf->SetProtection(['print']);
        $mpdf->SetTitle("เอกสาร");
        $mpdf->SetAuthor('รพ.สมเด็จพระสังฆราชองค์ที่ 17');
        $mpdf->SetWatermarkText('รพ.สมเด็จพระสังฆราชองค์ที่ 17 ');
        $mpdf->showWatermarkText = false;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetFooter('<div style="text-align:right;font-size:8px;">รพ.สมเด็จพระสังฆราชองค์ที่ 17 วันที่พิมพ์ ' . Ccomponent::getThaiDate(date('Y-m-d H:i:s'), 'L', true) . ' หน้า {PAGENO} / {nb}</div>');
        @$mpdf->WriteHTML($this->renderPartial('frms01', ['models' => @$model, 'data' => @$data]));
        $mpdf->Output();
    }

    public function actionViewdoc1($id = null) {
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $model = WorkChangeGrid::find()
                ->where(['leave_main.leave_id' => $id])
//->join('LEFT JOIN', 'leave_process_list p2', 'p2.leave_id = leave_main.leave_id')
                ->one();
        // create new PDF document
        //$pdf = TcPDFMod::TcPDFModInit();
        //$pdf = new TcPDFMod('P', 'mm', 'A4', true, 'UTF-8', false, true);
        $pdf = TcPDFMod::TcPDFModInit();
        // set document information
        //$pdf->setCreator('HMP:Hospital Management Platform');
        //$pdf->setAuthor('Sila Klanklaeo');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('ใบลา');
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

        @$pdf->writeHTML($this->renderPartial($model->leaveType->leave_type_form, ['model' => @$model, 'data' => @$data]), true, false, true, false, '');
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

    public function actionSignature() {

        $LeaveSignature = Yii::$app->request->post('LeaveSignature');
        if (!empty($LeaveSignature)) {
            $id = @$LeaveSignature['leave_id'];
        } else {
            $id = Yii::$app->request->get('id');
        }
        $model2 = LeaveMain::findOne($id);
        $cid = \yii::$app->user->identity->profile->cid;
        $staff = Employee::find()->where(['employee_cid' => $cid])->one();
        $model = LeaveSignature::find()->where(['leave_level' => $model2->leave_status_id, 'leave_id' => $model2->leave_id, 'employee_id' => $staff->employee_id])->one() ?: new LeaveSignature;
        $model->employee_id = $staff->employee_id;
        $model->leave_signature_date = new \yii\db\Expression('NOW()');
        $model->leave_id = $model2->leave_id;
        $model->leave_level = $model2->leave_status_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model2->leave_status_id < 3) {
                $model2->leave_status_id++;
                $model2->rankdate = new \yii\db\Expression('NOW()');
                $model2->save();
            }

            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'ยืนยันการบันทึกลายเซนต์ สำเร็จ..',
                'options' => ['class' => 'alert-success']
            ]);
//return $this->redirect(['index']);
        }

        return $this->renderAjax('signature', [
                    'model' => $model,
        ]);
    }

    public function actionOperate($id) {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = WorkChangeGrid::findOne($id);
        $modelProcess = WorkProcessList::find()
                ->where(['work_grid_change_id' => $model->work_grid_change_id])
                ->orderBy(['processlist_id' => SORT_DESC])
                ->one();

//-----------------------------------------------------------------------------------------------------------------------------------------------
        $header = 0;
        $headerUpLevel = 0;
        $head = EmployeePositionHead::findOne(['employee_dep_id' => $model->employee_dep_id, 'employee_id' => $emp->employee_id]);
//ข้อมูลผลดำเนินการเป็นหัวหน้ากลุ่มงานหรือไม่
        $headOperation = @EmployeePositionHead::findOne(['employee_dep_id' => $model->lastProcess->receiver->employee_dep_id, 'employee_id' => $model->lastProcess->receiver->employee_id]);
        if ($head && $head->executive->employee_executive_level == 4) { //ระดับหัวหน้ากลุ่มงาน
            $header = 1;
        }
        if (($model->emp_staff_a <> $emp->employee_id) && $head && $head->executive->employee_executive_level == 4) { //ระดับหัวหน้ากลุ่มงาน ผ่านเอกสาร
            $header = 0;
        }



//ข้อมูลผลดำเนินการเป็นหัวหน้ากลุ่มงานหรือไม่
//if (($model->employee_id <> $emp->employee_id) && @($model->lastProcess->receiver->employee_id == $emp->employee_id) && $headOperation && $headOperation->executive->employee_executive_level == 4) { //ระดับหัวหน้ากลุ่มงาน ผ่านเอกสาร
//$headerUpLevel = 1;
//}
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        $canVisible = 0; //กำหนดการแสดงผลการดำเนินการ
        if (!empty($modelProcess->status->work_status_auth)) {//ให้ผู้รับผิดชอบหนังสือสามารถดำเนินการเอกสารได้
            $role = Yii::$app->authManager->getRolesByUser($profile->user_id);
            if (in_array($modelProcess->status->work_status_auth, array_keys($role))) {
                $canVisible = 1;
            }
        }

        if (!empty($modelProcess->process_receiver) && $modelProcess->process_receiver == $emp->employee_id) { //ให้ผู้ถูกเสนอหนังสือสามารถดำเนินการเอกสารได้
            $canVisible = 1;
        }

        if ($model->emp_staff_a == $emp->employee_id && in_array($model->work_status_id, ['L00', 'L08'])) { //ให้ผู้ลาสามารถดำเนินการเอกสารได้
            $canVisible = 1;
        }

        if ((\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('HRsAdmin'))) {//จะสามารถดูเอกสารที่ไม่เกี่ยวข้องได้ถ้าเป็นเอกสารที่ผ่านเพื่อดำเนินการต่อ
            $canVisible = 1;
        }

        if ($model->work_status_id == 'L99') { //ระดับ'หัวหน้ากลุ่มงานการเจ้าหน้าที่
            $header = 1;
        }

        $lookupStatus = [];
        $lookupStatus['User'] = ['F00', 'F03']; //ผู้ใช้งานทั่วไป /หัวหน้าฝ่าย
        if ($header == 1)
            $lookupStatus['User'] = ['F00', 'F15']; //ผู้ใช้งานทั่วไป /หัวหน้าฝ่าย

        $lookupStatus['HRsAdmin'] = ['F00', 'F03', 'F15', 'F17']; //HR
        $lookupStatus['FinanceAdmin'] = ['F00', 'F03', 'F15', 'F17']; //การเงิน
        $lookupStatus['OfficeAdmin'] = ['F00', 'F03', 'F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //สารบรรณกลาง
        $lookupStatus['ManagerAdmin'] = ['F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //หน.บริหาร
        $lookupStatus['SecretaryAdmin'] = ['F00', 'F03', 'F15', 'F16', 'F17', 'F18', 'F19', 'FF']; //งานเลขา
        $lookupStatus['ExecutiveUser'] = ['F15', 'F16', 'F17', 'F18', 'F19']; //รองผู้อำนวยการ/ผู้อำนวยการ
        $lookupStatus['CEO'] = ['F15', 'F16', 'F17', 'F18', 'F19']; //รองผู้อำนวยการ/ผู้อำนวยการ
        $lookupStatus['SuperAdmin'] = ['L00', 'L01', 'L02', 'L03', 'L04', 'L05', 'L06', 'L07', 'L08', 'L09', 'L10', 'L99']; //Admin
        //กำหนดการแสดงผลตัวเลือกตามสิทธิ
        $arrayStatus = [];
        $roles = \Yii::$app->authManager->getRolesByUser($profile->user_id);

        foreach ($roles as $role) {
            if (!isset($lookupStatus[$role->name]))
                continue;
            $arrayStatus = array_unique(array_merge($arrayStatus, $lookupStatus[$role->name]));
        }

        sort($arrayStatus);
        $status = WorkChangeStatus::find()->where(['IN', 'work_status_id', $arrayStatus])->all();
        $query = WorkProcessList::find()->select(['*', 'TIMEDIFF(process_acknowledge_datetime,process_create) AS paperless_tt'])->where(['work_grid_change_id' => $model->work_grid_change_id]);
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

//-----------------------------------------------------------------------------------------------------------------------------------------------
        return $this->renderAjax('_operate', [
                    'header' => @$header,
                    'modelProcess' => $model,
                    'dataProvider' => $dataProvider,
                    'canVisible' => @$canVisible,
                    'status' => $status,
                    'headerUpLevel' => $headerUpLevel,
        ]);
    }

    public function actionCreateprocess() {
        $message = '';
        $status = 'error';
        $param = \Yii ::$app->request->post();
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($param['id'])) {
            $model = new WorkProcessList();
            $modelLeave = WorkChangeGrid::findOne($param['id']); //ข้อมูลใบลา
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            $stepUp = 0;
            $stepUpHead = 0;
            $stepUpOperation = 0;
            $stepOwnerUp = 0;
            $headOwnerUp = @EmployeePositionHead::find()->where(['employee_id' => $modelLeave->emp_staff_a])->all();
            //print_r(ArrayHelper::getColumn($headOwnerUp, 'executive.employee_executive_level'));
            if (in_array(2, ArrayHelper::getColumn($headOwnerUp, 'executive.employee_executive_level'))) { //หาตำแหน่งรอง ผอ.
                $stepUpHead = 1;
            }

            $headOwner = @EmployeePositionHead::findOne(['employee_dep_id' => $modelLeave->emp->employee_dep_id, 'employee_id' => $modelLeave->emp_staff_a]);
            if ($headOwner && $headOwner->executive->employee_executive_level == 4 && $modelLeave->emp->dep->employee_dep_parent <> '74') { //ระดับหัวหน้ากลุ่มงาน
                $stepUp = 1;
                $stepOwnerUp = 1;
            }
//---------------------------------------------------------------------------------------
            $now = \DateTime::createFromFormat('U.u', microtime(true));
            $model->processlist_id = '' . (date('Y') + 543) . date('mdHis') . substr($now->format('u'), 0, 2);
            $model->work_grid_change_id = $modelLeave->work_grid_change_id; //ใบลา

            $model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
            $model->process_create = new \yii\db\Expression('NOW()');

            if (isset($param['cancelLeave']) && $param['cancelLeave'] == 'cancel') { //ปฏิเสธใบลาพักผ่อน
                $model->work_status_id = 'L08'; //ปฏิเสธ
                if (empty($param['comment'])) {
                    $message = 'ไม่พบการระบุความเห็น กรุณาระบุเหตุผลด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                $model->process_acknowledge_staff = $model->employee_id;
                $model->process_comment = @$param['comment']; //หมายเหตุ
                $auth = Employee::find()->where(['IN', 'employee_id', [$modelLeave->emp_staff_a]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " คุณถูกปฏิเสธการลาจาก " . Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_fullname . " เนื่องจาก " . $model->process_comment;
                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
            } elseif (in_array($modelLeave->work_status_id, ['L00', 'L08'])) { //ลาพักผ่อน
                $model->work_status_id = 'L01'; //

                if ($stepUpHead == 0) {
                    if (empty($param['receiver'])) {
                        $message = 'ไม่พบการระบุรายชื่อผู้เสนอ กรุณาระบุชื่อผู้เสนอด้วยค่ะ ' . $stepUpHead;
                        return ['status' => 'error', 'message' => $message];
                    }

                    $model->process_acknowledge_staff = $param['receiver']; //เสนอหัวหน้างาน/หัวหน้าฝ่าย;
                }

                $model->process_receiver = $modelLeave->emp_staff_b;
                $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = " คุณถูกเลือกให้รับ{$modelLeave->workChange->work_change_name}จาก " . $modelLeave->emps->employee_fullname;
                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
            } elseif (in_array($modelLeave->work_status_id, ['L00', 'L08']) && $modelLeave->work_grid_type_id <> 1) { //ลาอื่นๆ
                if ($stepUpHead == 1) { //รอง ผอ.
                    $model->work_status_id = 'L10'; //งาน จ
                } else {
                    if (empty($param['receiver'])) {
                        $message = 'ไม่พบการระบุรายชื่อผู้เสนอ กรุณาระบุชื่อผู้เสนอด้วยค่ะ';
                        return ['status' => 'error', 'message' => $message];
                    }
                    //-------------------------------------------------------------------------------------------------
                    /* หัวหน้าฝ่าย */
                    $head = @EmployeePositionHead::findOne(['employee_dep_id' => $modelLeave->emp->employee_dep_id, 'employee_id' => $param['receiver']]);
                    if ($head && @$head->executive->employee_executive_level == 4 && $modelLeave->emp->dep->employee_dep_parent <> '74') {     //ระดับ'หัวหน้ากลุ่มงานการเจ้าหน้าที่     //employee_dep_parent == 74 กลุ่มการพยาบาล
                        $stepUpOperation = 1;
                    }

                    if ($stepUpOperation == 1 || $stepOwnerUp == 1) { //หัวหน้างาน->เป็นหัวหน้ากลุ่มงาน ----> ระบบจะส่งไปดำเนินการในฐานนะหัวหน้ากลุ่มงานแทน
                        $model->work_status_id = 'L03'; //
                    } else {
                        $model->work_status_id = 'L02'; //
                    }

                    $model->process_receiver = $param['receiver']; //เสนอหัวหน้างาน/หัวหน้าฝ่าย;
                    $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                    foreach ($auth as $emp) {
                        $linebot = new lineBot();
                        $message = "คุณมีใบขอ{$modelLeave->workChange->work_change_name} ส่งมาให้ดำเนินการค่ะ " . $modelLeave->emps->employee_fullname;
                        $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                    }
                }
//-------------------------------------------------------------------------------------------------------
            } elseif ($modelLeave->work_status_id == 'L01') { //ผู้รับงานแทน/ลาพักผ่อน
                $model->process_receiver = $modelLeave->LastProcess->process_acknowledge_staff;
                /* หัวหน้าฝ่าย */
                $head = @EmployeePositionHead::findOne(['employee_dep_id' => $modelLeave->emp->employee_dep_id, 'employee_id' => $model->process_receiver]);
                if ($head && @$head->executive->employee_executive_level == 4 && $modelLeave->emp->dep->employee_dep_parent <> '74') {     //ระดับ'หัวหน้ากลุ่มงานการเจ้าหน้าที่     //employee_dep_parent == 74 กลุ่มการพยาบาล
                    $stepUpOperation = 1;
                }
                if ($stepUpOperation == 1 || $stepOwnerUp == 1) { //หัวหน้างาน->เป็นหัวหน้ากลุ่มงาน ----> ระบบจะส่งไปดำเนินการในฐานนะหัวหน้ากลุ่มงานแทน
                    $model->work_status_id = 'L03'; //
                } else {
                    $model->work_status_id = 'L02'; //
                }

                if ($stepUpHead == 1) { //รอง ผอ.
                    $model->work_status_id = 'L10'; //งาน จ
                } else {
                    $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                    foreach ($auth as $emp) {
                        $linebot = new lineBot();
                        $message = "คุณมีใบขอ{$modelLeave->workChange->work_change_name} ส่งมาให้ดำเนินการค่ะ " . $modelLeave->emps->employee_fullname;
                        $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                    }
                }
            } elseif ($modelLeave->work_status_id == 'L02') { //ส่งให้หัวหน้าฝ่ายเห็นชอบ
                $model->work_status_id = 'L03'; //เสนอหัวหน้างาน/หัวหน้าฝ่าย
                if (empty($param['receiver'])) {
                    $message = 'ไม่พบการระบุรายชื่อผู้เสนอ กรุณาระบุชื่อผู้เสนอด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }

                $model->process_receiver = $param['receiver'];
                $auth = Employee::find()->where(['IN', 'employee_id', [$model->process_receiver]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = "คุณมีใบขอ{$modelLeave->workChange->work_change_name} ส่งมาให้ดำเนินการค่ะ " . $modelLeave->emps->employee_fullname;
                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
                //}
            } elseif ($modelLeave->work_status_id == 'L03') { //ส่งงาน จ.
                $model->work_status_id = 'L10'; //ส่งงาน จ.
            } elseif ($modelLeave->work_status_id == 'L99') { //ส่งรอง ผอ.
                if ($stepUp == 0 && $stepUpHead == 0) {
                    $model->work_status_id = 'L10'; //ส่งรอง ผอ.
                } else {
                    $model->work_status_id = 'L05'; //ส่ง ผอ.
                }

                if (empty($param['receiver'])) {
                    $message = 'ไม่พบการระบุรายชื่อผู้เสนอ กรุณาระบุชื่อผู้เสนอด้วยค่ะ';
                    return ['status' => 'error', 'message' => $message];
                }
                $model->process_receiver = $param['receiver']; //เสนอรอง ผอ.
                $model->process_acknowledge_staff = $model->employee_id; //การเจ้าหน้าที่
            } elseif (in_array($modelLeave->work_status_id, ['L04', 'L05'])) { //ส่งงาน จ.
                $model->work_status_id = 'L10'; //ดำเนินการสำเร็จ
                $model->process_receiver = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
                $auth = Employee::find()->where(['IN', 'employee_id', [$modelLeave->employee_id]])->all(); //เป็นรายบุคคล
                foreach ($auth as $emp) {
                    $linebot = new lineBot();
                    $message = "ผู้บริหารดำเนินการอนุมัติวันลา ให้เรียบร้อยแล้วค่ะ";
                    $linebot->send($message, [$emp->employee_linetoken]); //ส่ง line ให้ส่วนตัว
                }
//-------------------------------------------------------------------------------------------------------
            } else {
                /*
                  $model->process_receiver = $param['receiver']; //หัวหน้างาน
                  if (empty($param['receiver'])) {
                  $message = 'ไม่พบการระบุรายชื่อผู้รับ กรุณาระบุชื่อผู้รับด้วยค่ะ';
                  return ['status' => 'error', 'message' => $message];
                  }
                  $message = '';
                 *
                 */
                return ['status' => 'error', 'message' => 'ไม่สามารถดำเนินการได้ กรุณาติดต่อผู้ดูแลระบบ !'];
//-------------------------------------------------------------------------------------------------------
            }
            if (!empty($model->work_status_id)) {
                if ($model->save()) {
                    $status = 'success';
                    $message = 'ดำเนินการสำเร็จ';
                    $modelLeave->work_status_id = $model->work_status_id;
                    $modelLeave->work_lastprocess_id = $model->processlist_id;
                    $modelLeave->update_at = new \yii\db\Expression('NOW()');
                    $modelLeave->rankdate = new \yii\db\Expression('NOW()');

                    if (!$modelLeave->save()) {
                        return ['status' => 'error', 'message' => print_r($modelLeave->errors, 1)];
                    } else {
                        /*
                          Cmqtt::public('hms/service/paper/update/L-' . $model->process_receiver, 'Leave-system'); // Update MQTT ผู้เกี่ยวข้อง
                          Cmqtt::public('hms/service/paper/update/L-' . $modelLeave->emp_staff_b, 'Leave-system'); //Update MQTT ผู้รับมอบ
                          Cmqtt::public('hms/service/paper/update/L-' . $modelLeave->emp_staff_a, 'Leave-system'); //Update MQTT เจ้าของเรื่อง
                          Cmqtt::public('hms/service/operation/' . $model->process_receiver, date('Y-m-d H:i:s')); //Update MQTT เจ้าของเรื่อง
                          Cmqtt::public('hms/service/operation/' . $modelLeave->emp_staff_b, date('Y-m-d H:i:s')); //Update MQTT เจ้าของเรื่อง
                         */
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

    public function actionUploadAjax() {
        $this->Uploads(true);
    }

    private function CreateDir($folderName) {
        if ($folderName != NULL) {
            $basePath = LeaveMain::getUploadPath();
            if (BaseFileHelper::createDirectory($basePath . $folderName, 0777)) {
//BaseFileHelper::createDirectory($basePath . $folderName . '/thumbnail', 0777);
            }
        }
        return;
    }

    private function Uploads($isAjax = false, $ref = '') {
        if (Yii::$app->request->isPost) {
            $images = UploadedFile::getInstancesByName('upload_ajax');
//$images = UploadedFile::getInstancesByName('LeaveMain[leave_file]');
//$images = UploadedFile::getInstancesByName('upload_ajax');
//print_r($images);
//exit;
            if ($images) {
                if ($isAjax === true) {
                    $ref = Yii::$app->request->post('ref');
                } else {
                    $post = Yii::$app->request->post('LeaveMain');
//print_r($_POST);
//exit;
                }
                $this->CreateDir($ref);
                foreach ($images as $file) {
                    $fileName = $file->baseName . '.' . $file->extension;
                    $realFileName = md5($file->baseName . time()) . '.' . $file->extension;
                    $savePath = LeaveMain::UPLOAD_FOLDER . '/' . $ref . '/' . $realFileName;
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
        $filePath = LeaveMain::getUploadUrl() . $model->ref . '/' . $model->real_filename;
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
        $uploadPath = LeaveMain::getUploadPath() . '/' . $folderName . '/';
        $file = $uploadPath . $fileName;
        $image = \Yii::$app->image->load($file);
        $image->resize($width);
        $image->save($uploadPath . 'thumbnail/' . $fileName);
        return;
    }

    public function actionDeletefileAjax() {
        $model = Uploads::findOne(Yii::$app->request->post('key'));
        if ($model !== NULL) {
            $filename = LeaveMain::getUploadPath() . $model->ref . '/' . $model->real_filename;
            $thumbnail = LeaveMain::getUploadPath() . $model->ref . '/thumbnail/' . $model->real_filename;
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

    //ลบสถานะการดำเนินการ
    public function actionProcessUpdate() {
        $id = Yii::$app->request->post('id');
        if (\Yii::$app->user->can('SuperAdmin')) {
            $model = WorkProcessList::findOne($id);
            $lid = $model->work_grid_change_id;
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
        $model = WorkProcessList::find()->where(['work_grid_change_id' => $id])->orderBy(['process_create' => SORT_DESC])->one();
        $modelLeave = WorkChangeGrid::findOne($model->work_grid_change_id);
        $modelLeave->work_status_id = $model->work_status_id;
        $modelLeave->work_lastprocess_id = $model->processlist_id;
        $modelLeave->update_at = new \yii\db\Expression('NOW()');
        //$modelLeave->rankdate = new \yii\db\Expression('NOW()');
        $modelLeave->save();
        //echo json_encode([$model->process_create]);
    }

}
