<?php

namespace app\modules\survey\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\modules\plan\models\Plan;
use yii\data\ActiveDataProvider;
use app\components\Ccomponent;
use app\modules\survey\models\AssetList;
use app\modules\survey\models\SurveyComputer;
use app\modules\survey\models\SurveyComputerList;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

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

    public function actionIndex()
    {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        //$model = new Plan();
        @$params = \Yii::$app->request->queryParams;

        $query = SurveyComputerList::find();

        if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin')) {
            //$query->andWhere(['department_id' => $emp->employee_dep_id]);

        } else {
            $query->andWhere(['department_id' => $emp->employee_dep_id]);
        }
        $query->andWhere(['survey_budget_year' => 2569]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10000
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC,
                ]
            ],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]); //'model' => @$model,
    }

    public function actionCreate()
    {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = new SurveyComputerList();
        $model->employee_id = $emp->employee_id;
        $model->department_id = $emp->employee_dep_id;


        $fiveYearsAgo = new Expression('DATE_SUB(CURDATE(), INTERVAL 5 YEAR)');

        $assetList = AssetList::find()
            ->where(['like', 'asset_number', '7440%', false])
            ->andWhere(['<=', 'receiv_date', $fiveYearsAgo])
            ->andWhere(['IS NOT', 'receiv_date', null])
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
        $assetList = AssetList::find()
            ->where(['like', 'asset_number', '7440%', false])
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

    public function actionApprove($id)
    {
        $model = SurveyComputerList::findOne($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->survey_list_approve_date = date('Y-m-d H:i:s');

            $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
            $model->approver_employee_id = $emp->employee_id;

            if ($model->save()) {
                return 'success';
            } else {
                Yii::error($model->errors, __METHOD__);
                return json_encode(['status' => 'error', 'errors' => $model->errors]);
            }
        }

        return $this->renderAjax('_approve', [
            'model' => $model,
            'mode' => 'approve',
        ]);
    }

    public function actionDashboard()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('dashboard');
        }
        return $this->render('dashboard');
    }
}
