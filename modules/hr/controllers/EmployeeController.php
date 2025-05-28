<?php

namespace app\modules\hr\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
//use yii\helpers\Json;
//use yii\web\UploadedFile;
//use app\modules\mophic\components\Cmophic;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeType;
use app\modules\hr\models\EmployeePositionHead;
use yii\helpers\ArrayHelper;
use app\modules\hr\models\ExecutiveHasCdepartment;
use app\modules\hr\models\Executive;

class EmployeeController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'addexecutive' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        @$params = \Yii::$app->request->queryParams;
        if (isset($params['view']) && !empty($params['view'])) {
            $group = [];
            if ($params['view'] == 1)
                $group = [1];
            if ($params['view'] == 2)
                $group = [2, 3];
            if ($params['view'] == 3)
                $group = [4];
            if ($params['view'] == 4)
                $group = [5, 6];
            $exeModel = Executive::find()->where(['IN', 'employee_executive_level', $group])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            $headModel = EmployeePositionHead::find()->where(['IN', 'employee_executive_id', ArrayHelper::getColumn($exeModel, 'employee_executive_id')])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
        }

        $data = Employee::find()
                ->select(['cc' => 'count(*)', 'id' => 'employee.employee_type_id'])
                ->joinWith('empType')
                ->joinWith('dep')
                ->groupBy(['employee.employee_type_id'])
                ->where(['employee_status' => 1]);
        if (isset($headModel))
            $data->andFilterWhere(['IN', 'employee.employee_id', ArrayHelper::getColumn($headModel, 'employee_id')]);

        /*
          if (isset($params['type']) && !empty($params['type'])) {
          $group2 = [];
          if ($params['type'] == 1)
          $group2 = [1];
          if ($params['type'] == 2)
          $group2 = [2, 3];
          if ($params['type'] == 3)
          $group2 = [4];
          if ($params['type'] == 4)
          $group2 = [5, 6];
          $data->andFilterWhere(['IN', 'employee.employee_type_id', $group2]);
          }
         */

        $data->andFilterWhere(['OR',
            ['=', 'employee_dep.employee_dep_id', @$params['dep']],
            ['=', 'employee_dep.employee_dep_parent', @$params['dep']]
        ]);
        $data->andFilterWhere(['OR',
            ['like', 'employee_dep.employee_dep_label', @$params['search']],
            ['like', 'employee_fullname', @$params['search']],
            ['like', 'employee_cid', @$params['search']],
        ])->asArray();

        $row = $data->all();
        $data = [];
        foreach ($row as $index => &$value) {
            @$data['cc'][$value['id']] = @$value['cc'];
            @$data['sum'] += @$value['cc'];
        }

        $query = Employee::find()
                ->joinWith('dep')
                ->where(['employee_status' => 1]);
        if (isset($headModel))
            $query->andWhere(['IN', 'employee_id', ArrayHelper::getColumn($headModel, 'employee_id')]);

        if (isset($params['type']) && !empty($params['type'])) {
            $query->andFilterWhere(['employee.employee_type_id' => $params['type']]);
        }

        $query->andFilterWhere(['OR',
                    ['=', 'employee_dep.employee_dep_id', @$params['dep']],
                    ['=', 'employee_dep.employee_dep_parent', @$params['dep']]
                ])
                ->andFilterWhere(['OR',
                    ['like', 'employee_dep.employee_dep_label', @$params['search']],
                    ['like', 'employee_fullname', @$params['search']],
                    ['like', 'employee_cid', @$params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ],
//            'sort' => [
//                'defaultOrder' => [
//                    'memorandum_date' => SORT_DESC,
//                ]
//            ],
        ]);

        $empType = EmployeeType::find()->asArray()->all();

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'empType' => $empType,
                    'empData' => $data,
        ]);
    }

    public function actionReport() {
        @$params = \Yii::$app->request->queryParams;
        if (isset($params['view']) && !empty($params['view'])) {
            $group = [];
            if ($params['view'] == 1)
                $group = [1];
            if ($params['view'] == 2)
                $group = [2, 3];
            if ($params['view'] == 3)
                $group = [4];
            if ($params['view'] == 4)
                $group = [5, 6];
            $exeModel = Executive::find()->where(['IN', 'employee_executive_level', $group])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
            $headModel = EmployeePositionHead::find()->where(['IN', 'employee_executive_id', ArrayHelper::getColumn($exeModel, 'employee_executive_id')])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
        }

        $data = Employee::find()
                ->select(['cc' => 'count(*)', 'id' => 'employee.employee_type_id'])
                ->joinWith('empType')
                ->joinWith('dep')
                ->groupBy(['employee.employee_type_id'])
                ->where(['employee_status' => 1]);
        if (isset($headModel))
            $data->andFilterWhere(['IN', 'employee.employee_id', ArrayHelper::getColumn($headModel, 'employee_id')]);


        $data->andFilterWhere(['OR',
            ['=', 'employee_dep.employee_dep_id', @$params['dep']],
            ['=', 'employee_dep.employee_dep_parent', @$params['dep']]
        ]);
        $data->andFilterWhere(['OR',
            ['like', 'employee_dep.employee_dep_label', @$params['search']],
            ['like', 'employee_fullname', @$params['search']],
            ['like', 'employee_cid', @$params['search']],
        ])->asArray();

        $row = $data->all();
        $data = [];
        foreach ($row as $index => &$value) {
            @$data['cc'][$value['id']] = @$value['cc'];
            @$data['sum'] += @$value['cc'];
        }

        $query = Employee::find()
                ->joinWith('dep')
                ->where(['employee_status' => 1]);
        if (isset($headModel))
            $query->andWhere(['IN', 'employee_id', ArrayHelper::getColumn($headModel, 'employee_id')]);

        if (isset($params['type']) && !empty($params['type'])) {
            $query->andFilterWhere(['employee.employee_type_id' => $params['type']]);
        }

        $query->andFilterWhere(['OR',
                    ['=', 'employee_dep.employee_dep_id', @$params['dep']],
                    ['=', 'employee_dep.employee_dep_parent', @$params['dep']]
                ])
                ->andFilterWhere(['OR',
                    ['like', 'employee_dep.employee_dep_label', @$params['search']],
                    ['like', 'employee_fullname', @$params['search']],
                    ['like', 'employee_cid', @$params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ],
        ]);

        $empType = EmployeeType::find()->asArray()->all();

        return $this->render('report', [
                    'dataProvider' => $dataProvider,
                    'empType' => $empType,
                    'empData' => $data,
        ]);
    }

    public function actionAddexecutive() {
        $ids = Yii::$app->request->post('ids');
        $id = Yii::$app->request->post('id');
        $success = 0;
        $error = 0;

        if (isset($ids) && count($ids) > 0) {
            foreach ($ids as $value) {
                $model = new EmployeePositionHead();
                $model->employee_id = $id;
                $model->create = new \yii\db\Expression('NOW()');
                $model->employee_executive_id = $value;
                try {
                    $model->save();
                    $success++;
                } catch (\Exception $ex) {
                    $error++;
                }
            }
            //ลบรายการที่นอกเนื้อจากที่เลือกมา
            EmployeePositionHead::deleteAll(['AND', ['IN', 'employee_id', $id], ['NOT IN', 'employee_executive_id', $ids]]);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => $success, 'error' => $error,];
        }
    }

    public function actionSetActive($id, $status) {
        $model = Employee::findOne($id);
        if ($model->employee_status == 1) {
            $model->employee_status = 0;
        } else {
            $model->employee_status = 1;
        }
        $model->save();
        print_r($model->errors);
    }

    public function actionSetinfo() {
        $model = Employee::find()->asArray()->all();
        foreach ($model as $value) {
            $sqlQuery = "select birthday from hos.patient where cid = '{$value['employee_cid']}' limit 1";
            $result = \Yii::$app->db_myoffice->createCommand($sqlQuery)->queryScalar();
            $e = Employee::findOne(['employee_cid' => $value['employee_cid']]);
            $e->employee_birthdate = $result;
            if (!empty($result))
                $e->save();
            //echo $result;
        }
    }

}
