<?php

namespace app\modules\survey\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\modules\plan\models\Plan;
use yii\data\ActiveDataProvider;
use app\components\Ccomponent;
use app\modules\survey\models\SurveyComputerList;

class DefaultController extends Controller {

    public function actionImport() {

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

    public function actionIndex() {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        //$model = new Plan();
        @$params = \Yii::$app->request->queryParams;

        $query = SurveyComputerList::find();

        if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin')) {
			//$query->andWhere(['department_id' => $emp->employee_dep_id]);
			
        } else {
            $query->andWhere(['department_id' => $emp->employee_dep_id]);
        }
        $query->andWhere(['survey_budget_year' => 2568]);

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
        return $this->render('index', ['model' => @$model, 'dataProvider' => $dataProvider]);
    }

    public function actionCreate() {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = new SurveyComputerList();
        $model->employee_id = $emp->employee_id;
        $model->department_id = $emp->employee_dep_id;
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->renderAjax('_form', ['model' => $model]);
        //return $this->render('create', ['model' => @$model, 'dataProvider' => $dataProvider]);
    }

    public function actionUpdate($id) {
        $model = SurveyComputerList::findOne($id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

            }
        }
        return $this->renderAjax('_form', ['model' => $model]);
    }

}
