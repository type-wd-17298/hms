<?php

namespace app\modules\survey\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\modules\plan\models\Plan;
use yii\data\ActiveDataProvider;
use app\components\Ccomponent;
use app\modules\survey\models\AssetList;
use app\modules\survey\models\ControlEvent;
use app\modules\survey\models\SurveyComputer;
use app\modules\survey\models\SurveyComputerList;
use app\modules\survey\models\SurveyComputerListSearch;
use app\modules\survey\models\SurveyRequestItem;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DefaultController extends Controller
{

    public function actionImport()
    {

        $url = "https://data.go.th/api/3/action/datastore_search?resource_id=36b9715f-d434-4833-a4f6-b2984cbca590&limit=100";
        $ch = curl_init();
        // Set the URL
        curl_setopt($ch, CURLOPT_URL, $url);
        // Set the cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Execute the cURL session
        $response = curl_exec($ch);
        curl_close($ch);
        // Decode the JSON data to PHP array
        $data = json_decode($response, true);
        if (isset($data['result']['records'])) {
            foreach ($data['result']['records'] as $record) {
                $model = new SurveyComputer();
                $model->id = $record['ID'];
                $model->item = $record['ITEM'];
                $model->price = $record['PRICE'];
                $model->specification = $record['SPECIFICATION'];
                // Save the model to the database
                if (!$model->save()) {
                    echo '<pre>';
                    print_r($model->errors);
                    echo '</pre>';
                }
            }
        }
    }

    // public function actionIndex()
    // {
    //     $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
    //     @$params = \Yii::$app->request->queryParams;

    //     $query = SurveyComputerList::find();

    //     if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin')) {
    //         //$query->andWhere(['department_id' => $emp->employee_dep_id]);

    //     } else {
    //         $query->andWhere(['department_id' => $emp->employee_dep_id]);
    //     }
    //     $query->andWhere(['survey_budget_year' => 2569]);

    //     $dataProvider = new ActiveDataProvider([
    //         'query' => $query,
    //         'pagination' => [
    //             'pageSize' => 10000
    //         ],
    //         'sort' => [
    //             'defaultOrder' => [
    //                 'department_id' => SORT_ASC,
    //                 'sub_department_id' => SORT_ASC,
    //                 'create_at' => SORT_DESC,
    //             ]
    //         ],
    //     ]);

    //     return $this->render('index', ['dataProvider' => $dataProvider]);
    // }

    public function actionIndex()
    {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $searchModel = new SurveyComputerListSearch();
        $params = Yii::$app->request->queryParams;

        if (isset($params['search'])) {
            $searchModel->survey_list_problem = $params['search'];
        }

        $year = Yii::$app->request->get('year', (int)date('Y') + 544);
        $yearList = SurveyComputerList::find()
            ->select('survey_budget_year')
            ->distinct()
            ->orderBy(['survey_budget_year' => SORT_DESC])
            ->asArray()
            ->all();

        $yearOptions = ArrayHelper::map($yearList, 'survey_budget_year', 'survey_budget_year');
        $dataProvider = $searchModel->search($params);
        if (!(\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin'))) {
            $query = $dataProvider->query;
            $query->andWhere(['department_id' => $emp->employee_dep_id]);
            $dataProvider->query = $query;
        }

        $query = $dataProvider->query;
        $query->andWhere(['survey_budget_year' => $year]);
        $dataProvider->query = $query;

        $dataProvider->pagination = ['pageSize' => 10000];
        $dataProvider->sort->defaultOrder = [
            'department_id' => SORT_ASC,
            'sub_department_id' => SORT_ASC,
            'create_at' => SORT_DESC,
        ];

        $control = ControlEvent::findOne(['event_id' => 1, 'event_name' => 'survey']);
        $canShowAddButton = $control && $control->active == 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'canShowAddButton' => $canShowAddButton,
            'year' => $year,
            'yearOptions' => $yearOptions,
        ]);
    }

    public function actionCreate()
    {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = new SurveyComputerList();
        $model->employee_id = $emp->employee_id;
        $model->department_id = $emp->employee_dep_id;
        $year = Yii::$app->request->get('year', (int)date('Y') + 544);

        $fiveYearsAgo = new Expression('DATE_SUB(CURDATE(), INTERVAL 5 YEAR)');

        $usedAssets = SurveyRequestItem::find()
            ->alias('sri')
            ->innerJoin('survey_computer_list scl', 'scl.survey_list_id = sri.survey_request_id')
            ->where(['scl.survey_budget_year' => $year])
            ->select('sri.asset_number')
            ->column();

        $assetList = AssetList::find()
            ->where(['like', 'asset_number', '7440'])
            ->andWhere(['status_id' => 1])
            ->andWhere(['<=', 'receiv_date', $fiveYearsAgo])
            ->andFilterWhere(['not in', 'asset_number', $usedAssets])
            ->orderBy(['asset_number' => SORT_ASC])
            ->asArray()
            ->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->survey_type === 'ทดแทน') {
                    $requestedCount = (int) $model->survey_list_reuest;
                    $partNumbers = array_filter(explode(',', $model->survey_list_partnumber));

                    if (count($partNumbers) < $requestedCount) {
                        Yii::$app->session->setFlash('error', "กรุณากรอกเลขครุภัณฑ์ให้ครบ $requestedCount รายการ");
                        return $this->renderAjax('_form', [
                            'model' => $model,
                            'assetList' => $assetList,
                        ]);
                    }
                }

                if ($model->save()) {
                    $postData = Yii::$app->request->post('SurveyComputerList', []);
                    $items = $postData['items'] ?? [];
                    foreach ($items as $itemData) {
                        $assetNumber = trim($itemData['asset_number'] ?? '');
                        if ($assetNumber !== '') {
                            $child = new SurveyRequestItem();
                            $child->survey_request_id = $model->survey_list_id;
                            $child->asset_number = $assetNumber;
                            $child->save(false);
                        }
                    }
                    return 'success';
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('_form', [
            'model' => $model,
            'assetList' => $assetList,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = SurveyComputerList::findOne($id);
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $fiveYearsAgo = new Expression('DATE_SUB(CURDATE(), INTERVAL 5 YEAR)');

        $assetList = AssetList::find()
            ->where(['like', 'asset_number', '7440'])
            ->andWhere(['status_id' => 1])
            ->andWhere(['<=', 'receiv_date', $fiveYearsAgo])
            ->orderBy(['asset_number' => SORT_ASC])
            ->asArray()
            ->all();

        $selectedPartnumbers = !empty($model->survey_list_partnumber)
            ? explode(',', $model->survey_list_partnumber)
            : [];

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                if (!empty($model->it_comment)) {
                    $model->it_employee_id = $emp->employee_id;
                }

                if ($model->save()) {
                }
            }
        }

        return $this->renderAjax('_form', [
            'model' => $model,
            'assetList' => $assetList,
            'selectedPartnumbers' => $selectedPartnumbers
        ]);
    }

    public function actionDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        if (!$id) {
            throw new \yii\web\BadRequestHttpException('ID is required.');
        }

        $model = SurveyComputerList::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'ไม่พบข้อมูลที่จะลบ'];
        }

        try {
            if ($model->delete()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'ลบข้อมูลไม่สำเร็จ'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()];
        }
    }


    public function actionApprove($id)
    {
        $model = SurveyComputerList::findOne($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->survey_list_approve_date = date('Y-m-d H:i:s');
            $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
            $model->approver_employee_id = $emp->employee_id;

            if ($model->save()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'errors' => $model->getErrors()];
            }
        }

        $items = SurveyRequestItem::find()
            ->where(['survey_request_id' => $model->survey_list_id])
            ->asArray()
            ->all();

        $assetNumbers = array_column($items, 'asset_number');
        $assetList = AssetList::find()
            ->where(['asset_number' => $assetNumbers])
            ->asArray()
            ->all();

        $assetMap = ArrayHelper::index($assetList, 'asset_number');

        $itemsWithAsset = array_map(function ($item) use ($assetMap) {
            $asset = $assetMap[$item['asset_number']] ?? [];
            return array_merge($item, $asset);
        }, $items);

        return $this->renderAjax('_approve', [
            'model'    => $model,
            'items' => $itemsWithAsset,
        ]);
    }

    public function actionApproveMain()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $id = Yii::$app->request->post('id');

            if (!$id) {
                return ['success' => false, 'errors' => 'ID ไม่ถูกต้อง'];
            }

            $model = SurveyComputerList::findOne($id);

            if (!$model) {
                return ['success' => false, 'errors' => 'ไม่พบรายการใน SurveyComputerList'];
            }

            $approvedQty = SurveyRequestItem::find()
                ->where(['survey_request_id' => $id, 'status' => 1])
                ->count();

            $model->survey_list_approve = $approvedQty;

            if ($model->save(false)) {
                return [
                    'success' => true,
                    'approvedQty' => $approvedQty,
                ];
            } else {
                return [
                    'success' => false,
                    'errors' => $model->getErrors(),
                ];
            }
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), __METHOD__);
            return [
                'success' => false,
                'errors' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
            ];
        }
    }

    public function actionApproveItem()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $action = Yii::$app->request->post('action');

        $item = SurveyRequestItem::findOne($id);
        if (!$item) {
            return ['success' => false, 'message' => 'ไม่พบรายการ'];
        }

        if ($action === 'approve') {
            $item->status = 1;
        } elseif ($action === 'reject') {
            $item->status = 2;
        } else {
            return ['success' => false, 'message' => 'Action ไม่ถูกต้อง'];
        }

        if ($item->save(false)) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'บันทึกไม่สำเร็จ'];
        }
    }


    public function actionDashboard()
    {
        if (!Yii::$app->user->can('SuperAdmin') && !Yii::$app->user->can('ITAdmin') && !Yii::$app->user->can('ReviewCommittee')) {
            throw new \yii\web\ForbiddenHttpException('คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
        return $this->render('dashboard');
    }

    public function actionMasterData()
    {
        if (!Yii::$app->user->can('SuperAdmin') && !Yii::$app->user->can('ITAdmin') && !Yii::$app->user->can('ReviewCommittee')) {
            throw new \yii\web\ForbiddenHttpException('คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $query = SurveyComputer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
            ],
        ]);

        return $this->render('masterData', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateMaster($id)
    {
        $model = SurveyComputer::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'success' => true,
                'message' => 'อัปเดตข้อมูลสำเร็จ',
            ];
        }

        return $this->renderAjax('_updateMasterForm', [
            'model' => $model,
        ]);
    }

    public function actionCreateMaster()
    {
        $model = new SurveyComputer();

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $exists = SurveyComputer::find()
                ->where(['DE_id' => $model->DE_id])
                ->exists();

            if ($exists) {
                return [
                    'success' => false,
                    'message' => 'DE_id นี้มีอยู่แล้ว ไม่สามารถบันทึกซ้ำได้',
                ];
            }

            if ($model->save()) {
                return ['success' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ'];
            } else {
                return ['success' => false, 'message' => json_encode($model->errors)];
            }
        }

        return $this->renderAjax('_createMasterForm', ['model' => $model]);
    }


    public function actionDeleteMaster()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $model = SurveyComputer::findOne($id);

        if (!$model) {
            return ['success' => false, 'message' => 'ไม่พบข้อมูล'];
        }

        if ($model->delete()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'ลบข้อมูลไม่สำเร็จ'];
        }
    }

    public function actionImportExcel()
    {
        $model = new \yii\base\DynamicModel(['excelFile']);
        $model->addRule('excelFile', 'file', ['extensions' => 'xls, xlsx']);

        if (Yii::$app->request->isPost) {
            $model->excelFile = UploadedFile::getInstance($model, 'excelFile');

            if ($model->validate()) {
                $filePath = Yii::getAlias('@app') . '/uploads/' . time() . '.' . $model->excelFile->extension;
                if (!$model->excelFile->saveAs($filePath)) {
                    Yii::$app->session->setFlash('error', 'ไม่สามารถบันทึกไฟล์ได้');
                    return $this->redirect(['index']);
                }

                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray();

                if (count($rows) < 2) {
                    Yii::$app->session->setFlash('error', 'ไฟล์ Excel ไม่มีข้อมูล');
                    return $this->redirect(['index']);
                }

                $header = array_map('trim', $rows[0]);

                for ($i = 1; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    $data = array_combine($header, $row);

                    $id = isset($data['id']) ? intval($data['id']) : null;
                    $_id = isset($data['_id']) ? intval($data['_id']) : null;
                    $item = isset($data['item']) ? trim($data['item']) : null;
                    $price = isset($data['price']) ? floatval($data['price']) : 0;
                    $specification = isset($data['specification']) ? trim($data['specification']) : null;
                    $shortName = isset($data['short_name']) ? trim($data['short_name']) : null;
                    $DE_id = isset($data['DE_id']) ? intval($data['DE_id']) : null;
                    $active = isset($data['active']) ? intval($data['active']) : 0;

                    $computer = SurveyComputer::findOne(['item' => $item]);

                    if ($computer) {
                        $computer->id = $id;
                        $computer->_id = $_id;
                        $computer->price = $price;
                        $computer->specification = $specification;
                        $computer->short_name = $shortName;
                        $computer->DE_id = $DE_id;
                        $computer->active = $active;
                        $computer->save(false);
                    } else {
                        $computer = new SurveyComputer();
                        $computer->id = $id;
                        $computer->_id = $_id;
                        $computer->item = $item;
                        $computer->price = $price;
                        $computer->specification = $specification;
                        $computer->short_name = $shortName;
                        $computer->DE_id = $DE_id;
                        $computer->active = $active;
                        $computer->save(false);
                    }
                }

                Yii::$app->session->setFlash('success', 'Import เสร็จสมบูรณ์');
                return $this->redirect(['index']);
            }
        }
        return $this->render('import', ['model' => $model]);
    }



    public function actionToggleFlatButton()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->user->can('ITAdmin')) {
            return ['success' => false];
        }

        $control = ControlEvent::findOne(['event_id' => 1, 'event_name' => 'survey']);
        if ($control) {
            // รับค่า active จาก POST
            $active = Yii::$app->request->post('active', null);
            if ($active !== null) {
                $control->active = (int)$active;
                if ($control->save()) {
                    return ['success' => true, 'active' => $control->active];
                }
            }
        }

        return ['success' => false];
    }

    public function actionExportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = ['A1' => 'ลำดับ', 'B1' => 'ชื่ออุปกรณ์', 'C1' => 'ราคา', 'D1' => 'รายละเอียด', 'E1' => 'ลำดับในเกณฑ์ราคากลาง'];
        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9EAD3']
            ]
        ]);

        $models = SurveyComputer::find()->all();
        $row = 2;
        $index = 1;

        foreach ($models as $model) {
            $sheet->setCellValue("A$row", $index);
            $sheet->setCellValue("B$row", $model->item);
            $sheet->setCellValue("C$row", $model->price);
            $specText = str_replace(["\r\n", "\r", "\n"], "\n", $model->specification);
            $sheet->setCellValue("D$row", $specText);
            $sheet->getStyle("D$row")
                ->getAlignment()
                ->setWrapText(true)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

            $sheet->setCellValue("E$row", $model->DE_id);

            $row++;
            $index++;
        }

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle("A1:E" . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'masterData_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        $writer->save("php://output");
        exit;
    }
}
